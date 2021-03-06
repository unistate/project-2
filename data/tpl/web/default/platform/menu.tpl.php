<?php defined('IN_IA') or exit('Access Denied');?><?php  $newUI = true;?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script type="text/javascript">
	require(['angular', 'jquery.ui', 'underscore', 'util'], function(angular, $, _, util){
		angular.module('app', []).controller('menuDesigner', function($scope, $http){
			$scope.menus = <?php  echo json_encode($menus);?>;
			$scope.hmenus = <?php  echo json_encode($hmenus);?>;
			$scope.activeMenu = {};
			
			$scope.addMenu = function() {
				if($scope.menus.length >= 3) {
					return;
				}
				$scope.menus.push({
					title: '',
					type: 'url',
					url: '',
					forward: '',
					subMenus: []
				});
				$('.designer').sortable({handle: '.fa-arrows'});
			};
			$scope.addSubMenu = function(menu, obj) {
				$('.parentmenu').eq(obj.$index).find('a').eq(2).hide();
				if(menu.subMenus.length >= 5) {
					return;
				}
				menu.subMenus.push({
					title: '',
					type: 'url',
					url: '',
					forward: ''
				});
				$('.designer').sortable({handle: '.fa-arrows'});
			};
			$scope.deleteMenu = function(menu, sub, obj) {
				if(sub) {
					if (typeof obj == 'object') {
						var text = $('.sonmenu').eq(obj.$parent.$index).find('input[type="text"]').eq(obj.$index);
						if (text.val() != '') {
							if (confirm('将删除该菜单, 是否继续? ')) {
								if (menu.subMenus.length == 1) {
									$('.parentmenu').eq(obj.$parent.$index).find('a').eq(2).show();
								}
								menu.subMenus = _.without(menu.subMenus, sub);
							}
						} else {
							if (menu.subMenus.length == 1) {
								$('.parentmenu').eq(obj.$parent.$index).find('a').eq(2).show();
							}
							menu.subMenus = _.without(menu.subMenus, sub);
						}
					}
				} else {
					if(menu.subMenus.length > 0 && !confirm('将同时删除所有子菜单, 是否继续? ')) {
						return;
					}
					$scope.menus = _.without($scope.menus, menu);	
				}
			};
			$scope.setAction = function(menu) {
				$scope.activeMenu = menu;
				if(!$scope.activeMenu.url) {
					$scope.activeMenu.url = 'http://';
				}
				var header = "选择菜单 【{{activeMenu.title || '未命名菜单'}}】 要执行的操作";
				var content = $("#url").html();
				var menu = util.dialog(header, content, 'queee');
				
				$('#dialog').modal('show');
			};
			$scope.saveMenuAction = function(){
				$('#dialog').modal('hide');
			};
			$scope.saveMenu = function(version){
				var menus = $scope.menus;
				var hmenus = $scope.hmenus;
				/*如果使用历史记录菜单，则不对表单进行判断*/
				if (version != 'history') {
					if (menus.length < 1) {
						util.message('请您至少输入一个自定义菜单.', '', 'error');
						return ;
					}
					if(menus.length > 3) {
						util.message('不能输入超过 3 个主菜单才能保存.', '', 'error');
						return;
					}
					var error = {empty: false, message: ''};
					angular.forEach(menus, function(val){
						if(val.subMenus.length > 0) {
							angular.forEach(val.subMenus, function(v){
								if($.trim(v.title) == '') {
									this.empty = true;
								}
								if((v.type == 'url' && $.trim(v.url) == '') || (v.type == 'forward' && $.trim(v.forward) == '')) {
									this.message += '菜单【' + val.title + '】的子菜单【' + v.title + '】未设置操作选项. <br />';
								}
							}, error);
						} else {
							if((val.type == 'url' && $.trim(val.url) == '') || (val.type == 'forward' && $.trim(val.forward) == '')) {
								this.message += '菜单【' + val.title + '】不存在子菜单并且未设置操作选项. <br />';
							}
						}
						
						if($.trim(val.title) == '') {
							this.empty = true;
						}
					}, error)
					if(error.empty) {
						util.message('存在未输入名称的菜单.', '', 'error');
						return;
					}
					if(error.message) {
						util.message(error.message, '', 'error');
						return;
					}
				}
				
				var params = {};
				params.menus = _.sortBy($scope.menus, function(i){
					var elm = $(':hidden[data-role="parent"][data-hash="' + i.$$hashKey + '"]');
					return elm.parent().parent().parent().index();
				});
				angular.forEach(params.menus, function(i){
					i.subMenus = _.sortBy(i.subMenus, function(j){
						var e = $(':hidden[data-role="sub"][data-hash="' + j.$$hashKey + '"]');
						return e.parent().index();
					});
				});
				params.menus = angular.copy(params.menus);
				params.method = 'save';

				if (version == 'history') {
					params.hmenus = _.sortBy($scope.hmenus, function(i){
						var elm = $(':hidden[data-role="parent"][data-hash="' + i.$$hashKey + '"]');
						return elm.parent().parent().parent().index();
					});
					angular.forEach(params.hmenus, function(i){
						i.subMenus = _.sortBy(i.subMenus, function(j){
							var e = $(':hidden[data-role="sub"][data-hash="' + j.$$hashKey + '"]');
							return e.parent().index();
						});
					});
					params.type = version;
					params.hmenus = angular.copy(params.hmenus);
					$http.post(location.href, params).success(function(dat, status){
						if(dat != 'success') {
							if (typeof dat == 'string') {
								$('#errorinfo').empty().append(dat);
							} else {
								util.message(dat.message, '', 'error');
							}
						} else {
							util.message('菜单保存成功. ', location.href);
						}
					});
					return;
				}
				$http.post(location.href, params).success(function(dat, status){
					if(dat != 'success') {
						if (typeof dat == 'string') {
							$('#errorinfo').empty().append(dat);
						} else {
							util.message(dat.message, '', 'error');
						}
					} else {
						util.message('菜单保存成功. ', location.href);
					}
				});
				return;
				$('#do').val(ret.data);
				$('#form')[0].submit();
			};
			
			$scope.removeMenu = function(){
				if (false === confirm('确认删除所有菜单吗？')) {
					return false;
				}
				var params = {};
				params.method = 'remove';
				$http.post(location.href, params).success(function(dat, status){
					if(dat != 'success') {
						util.message(dat.message, '', 'error');
					} else {
						util.message('清除自定义菜单成功. ', location.href);
					}

				});
			};
			
			//点击选择【系统连接】事件
			$scope.select_link = function(){
				var ipt = $(this).parent().prev();
				util.linkBrowser(function(href){
					var site_url = "<?php  echo $_W['siteroot'];?>";
					if(href.substring(0, 4) == 'tel:') {
						util.message('自定义菜单不能设置为一键拨号');
						return;
					} else if(href.indexOf("http://") == -1 && href.indexOf("https://") == -1) {
						href = href.replace('./index.php?', '/index.php?');
						href = site_url + 'app' + href;
					}
					$scope.activeMenu.url = href;
					$scope.$digest();
				});
			};
			
			/*选择Emoji表情*/
			$scope.selectEmoji = function(obj, elms) {
				if (elms == 'parentmenu') {
					var elm = $('.' + elms).eq(obj.$index);
					var title = elm.find('input[type="text"]')[0];
				} else {
					var elm = $('.' + elms).eq(obj.$parent.$index);
					var title = elm.find('input[type="text"]').eq(obj.$index)[0];
				}
				title.focus();
				util.emojiBrowser(function(emoji){
					var unshift = '::' + emoji.find("span").text() + '::';
					var newstart = title.selectionStart + unshift.length;
					var insertval = title.value.substr(0,title.selectionStart) + unshift + title.value.substring(title.selectionEnd);
					if (elms == 'parentmenu') {
						obj.menu.title = insertval;
					} else {
						obj.sub.title = insertval;
					}
					$scope.$digest();
					title.selectionStart = newstart;
					title.selectionEnd = newstart;
				});
			};
			
			$scope.search = function(){
				var search_value = $('#ipt-forward').val();
				$.post("<?php  echo url('platform/menu/search_key')?>", {'key_word' : search_value}, function(data){
					var data = $.parseJSON(data);
					var total = data.length;
					var html = '';
					if(total > 0) {
						for(var i = 0; i < total; i++) {
							html += '<li><a href="javascript:;">' + data[i] + '</a></li>';
						}
					} else {
						html += '<li><a href="javascript:;" id="no-result">没有找到您输入的关键字</a></li>';
					}
					$('#key-result ul').html(html);
					$('#key-result ul li a[id!="no-result"]').click(function(){
						$('#ipt-forward').val($(this).html());
						$scope.activeMenu.forward = $(this).html();
						$('#key-result').hide();
					});
					$('#key-result').show();
				});
			}
			
		});
		angular.bootstrap(document, ['app']);
		
		$(function(){
			$('.designer').sortable({handle: '.fa.fa-arrows'});
			$('#dialog').modal({backdrop: 'static', keyboard: false, show: false});

			$('#ipt-forward').keydown(function(event){
				if(event.keyCode == 13){
					$('#search').click();
				}
			});
			$('#dialog').click(function(event){
				var clickid = $(event.target).attr('id');
				if(clickid != 'key-result' && clickid != 'ipt-forward'  && clickid != 'search') {
					$('#key-result').hide();
					return;
				}
			});
			$('.parentmenu').each(function() {
				if ($(this).next().find('input[type="text"]').length > 0) {
					$(this).find('a').eq(2).hide();
				}
			});
		});
	});
	
</script>
<style type="text/css">
	.table-striped td{padding-top: 10px;padding-bottom: 10px}
	a{font-size:14px;}
	a:hover, a:active{text-decoration:none; color:red;}
	.hover td{padding-left:10px;}
	.designer a{border-left:1px #DDD solid; margin-left:10px; padding-left:10px; color:#333;}
	.modal-dialog .radio-inline{width:32.5%; padding:5px 0 5px 20px; margin-left:0;}
</style>
<div class="clearfix">
	<div class="ng-cloak" ng-controller="menuDesigner">
		<div class="panel panel-default">
			<div class="panel-heading">
				菜单设计器 <span class="text-muted">编辑和设置公众号菜单, 必须自定义菜单权限。</span>
			</div>
			<div class="table-responsive panel-body">
				<table class="table table-hover">
					<tbody class="designer">
					<tr class="hover" ng-repeat="menu in menus">
						<td style="border-top:none;">
							<div class="parentmenu">
								<input type="hidden" data-role="parent" data-hash="{{menu.$$hashKey}}"/>
								<input type="text" class="form-control" style="display:inline-block;width:300px;" ng-model="menu.title">
								<a href="javascript:;" title="拖动调整此菜单位置" style="border-left:0;"><i class="fa fa-arrows"></i></a>
								<a href="javascript:;" ng-click="selectEmoji(this, 'parentmenu')" title="添加表情"><i class="fa fa-github-alt"></i> 添加表情</a>
								<a href="javascript:;" ng-click="setAction(menu);" title="设置此菜单动作"><i class="fa fa-pencil"></i> 设置此菜单动作</a>
								<a href="javascript:;" ng-click="deleteMenu(menu)" title="删除此菜单"><i class="fa fa-remove"></i> 删除此菜单</a>
								<a href="javascript:;" ng-click="addSubMenu(menu, this);" title="添加子菜单"><i class="fa fa-plus"></i> 添加子菜单</a>
							</div>
							<div class="designer sonmenu">
								<div ng-repeat="sub in menu.subMenus" style="margin-top:20px;padding-left:80px;background:url('./resource/images/bg_repno.gif') no-repeat -245px -545px;">
									<input type="hidden" data-role="sub" data-hash="{{sub.$$hashKey}}" />
									<input type="text" class="form-control" style="display:inline-block;width:220px;" ng-model="sub.title">
									<a href="javascript:;" title="拖动调整此菜单位置" style="border-left:0;"><i class="fa fa-arrows"></i></a>
									<a href="javascript:;" ng-click="selectEmoji(this, 'sonmenu')"title="添加表情"><i class="fa fa-github-alt"></i> 添加表情</a>
									<a href="javascript:;" ng-click="setAction(sub);" title="设置此菜单动作"><i class="fa fa-pencil"></i> 设置此菜单动作</a>
									<a href="javascript:;" ng-click="deleteMenu(menu, sub, this);" title="删除此菜单"><i class="fa fa-remove"></i> 删除此菜单</a>
								</div>
							</div>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="panel-footer">
				<a href="javascript:;" ng-click="addMenu();">添加菜单 <i class="fa fa-plus-circle" title="添加菜单"></i></a> &nbsp; &nbsp; &nbsp;  <span class="help-inline">可以使用 <i class="fa fa-arrows"></i> 进行拖动排序</span>
			</div>
		</div>


		<div class="panel panel-success">
			<div class="panel-heading">
				历史记录菜单 <span class="text-muted">最后一次编辑时间：<?php echo !empty($createtime) ? date('Y-m-d H:i:s', $createtime['createtime']) : '暂无编辑记录';?> <b><a href="javascript:;" onclick="$('.history-body').fadeToggle();$('.history-foot').fadeToggle();">点击展示</a></b></span>
			</div>
			<div class="table-responsive history-body" style="display:none;">
				<table class="table table-hover">
					<tbody class="designer">
					<tr class="hover" ng-repeat="hmenu in hmenus">
						<td>
							<div>
								<input type="hidden" data-role="parent" data-hash="{{hmenu.$$hashKey}}" />
								<input type="text" class="form-control" readonly  style="display:inline-block;width:300px;" ng-model="hmenu.title">
							</div>
							<div class="designer">
								<div ng-repeat="sub in hmenu.subMenus" style="margin-top:20px;padding-left:80px;background:url('./resource/images/bg_repno.gif') no-repeat -245px -545px;">
									<input type="hidden" data-role="sub" data-hash="{{sub.$$hashKey}}" />
									<input type="text" class="form-control" readonly style="display:inline-block;width:220px;" ng-model="sub.title">
								</div>
							</div>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="panel-footer history-foot" style="display:none;">
				<a href="javascript:;" ng-click="saveMenu('history');" class="btn btn-success">使用历史菜单</a>
			</div>
		</div>

		
		
		<div class="panel panel-default">
			<div class="panel-heading">
				操作 <span class="text-muted">设计好菜单后再进行保存操作</span>
			</div>
			<div class="panel-body">
			<div class="form form-horizontal">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">支持的公众号</label>
					<div class="col-sm-9 col-xs-12">
						<div class="checkbox">
							<?php  if(is_array($accs)) { foreach($accs as $acc) { ?>
							<label>
								<?php  if($acc['level'] > 1) { ?>
								<i class="fa fa-check-square-o"> &nbsp; <?php  echo $acc['name'];?></i>
								<?php  } else { ?>
								<i class="fa fa-square-o"> &nbsp; <?php  echo $acc['name'];?> (权限不足)</i>
								<?php  } ?>
							</label>
							<?php  } } ?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
						<input type="button" value="保存菜单结构" class="btn btn-primary" ng-click="saveMenu();"/>
						<span class="help-block">菜单设计完成将在所有支持的公众号上生效. 成功保存当前菜单结构至公众平台后, 由于缓存可能需要在24小时内生效</span>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
						<input type="button" value="删除" class="btn btn-primary" ng-click="removeMenu();" />
						<div class="help-block">清除自定义菜单</div>
					</div>
				</div>
			</div>
			<div id="dialog" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h3>选择菜单 【{{activeMenu.title || '未命名菜单'}}】 要执行的操作</h3>
						</div>
						<div class="modal-body">
							<label class="radio-inline">
								<input type="radio" name="ipt" ng-model="activeMenu.type" value="url"> 链接
							</label>
							<label class="radio-inline">
								<input type="radio" name="ipt" ng-model="activeMenu.type" value="click"> 触发关键字
							</label>
							<label class="radio-inline">
								<input type="radio" name="ipt" ng-model="activeMenu.type" value="scancode_push"> 扫码
							</label>
							<label class="radio-inline">
								<input type="radio" name="ipt" ng-model="activeMenu.type" value="scancode_waitmsg"> 扫码（等待信息）
							</label>
							<label class="radio-inline">
								<input type="radio" name="ipt" ng-model="activeMenu.type" value="pic_sysphoto"> 系统拍照发图
							</label>
							<label class="radio-inline">
								<input type="radio" name="ipt" ng-model="activeMenu.type" value="pic_photo_or_album"> 拍照或者相册发图
							</label>
							<label class="radio-inline">
								<input type="radio" name="ipt" ng-model="activeMenu.type" value="pic_weixin"> 微信相册发图
							</label>
							<label class="radio-inline">
								<input type="radio" name="ipt" ng-model="activeMenu.type" value="location_select"> 地理位置
							</label>
							<div ng-show="activeMenu.type == 'url';">
								<hr />
								<div class="input-group">
									<input class="form-control" id="ipt-url" type="text" ng-model="activeMenu.url" />
									<div class="input-group-btn">
										<button class="btn btn-primary" id="search" ng-click="select_link()"><i class="fa fa-external-link"></i> 系统链接</button>
									</div>
								</div>
								<span class="help-block">指定点击此菜单时要跳转的链接（注：链接需加http://）</span>
								<span class="help-block"><strong>注意: 由于接口限制. 如果你没有网页oAuth接口权限, 这里输入链接直接进入微站个人中心时将会有缺陷(有可能获得不到当前访问用户的身份信息. 如果没有oAuth接口权限, 建议你使用图文回复的形式来访问个人中心)</strong></span>
							</div>
							<div ng-show="activeMenu.type != 'url';" style="position:relative">
								<hr />
								<div class="input-group">
									<input class="form-control" id="ipt-forward" type="text" ng-model="activeMenu.forward"/>
									<div class="input-group-btn">
										<button class="btn btn-primary" id="search" ng-click="search()"><i class="fa fa-search"></i> 搜索</button>
									</div>
								</div>
								<div id="key-result" style="width:100%;position:absolute;top:55px;left:0px;display:none;z-index:10000">
								  <ul class="dropdown-menu" style="display:block;width:88%;"></ul>
								</div>
								<span class="help-block">指定点击此菜单时要执行的操作, 你可以在这里输入关键字, 那么点击这个菜单时就就相当于发送这个内容至微擎系统</span>
								<span class="help-block"><strong>这个过程是程序模拟的, 比如这里添加关键字: 优惠券, 那么点击这个菜单是, 微擎系统相当于接受了粉丝用户的消息, 内容为"优惠券"</strong></span>
							</div>
						</div>
						<div class="modal-footer">
							<a href="javascript:;" ng-click="saveMenuAction();" class="pull-right btn btn-primary span2" data-dismiss="modal">保存</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>
<!-- 错误信息 -->
<div id="errorinfo"></div>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
