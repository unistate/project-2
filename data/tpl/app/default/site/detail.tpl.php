<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div id="activity-detail">
<style type="text/css">
@charset "utf-8";
html{background:#FFF;color:#000;}
body, div, dl, dt, dd, h1, h2, h3, h4, h5, h6, pre, code, form, fieldset, legend, input, textarea, p, blockquote, th, td{margin:0;padding:0;}
table{border-collapse:collapse;border-spacing:0;}
fieldset, img{border:0;}
address, caption, cite, code, dfn,  th, var{font-style:normal;font-weight:normal;}
ol, ul{list-style:none;}
caption, th{text-align:left;}
h1, h2, h3, h4, h5, h6{font-size:100%;font-weight:normal;}
q:before, q:after{content:'';}
abbr, acronym{border:0;font-variant:normal;}
sup{vertical-align:text-top;}
sub{vertical-align:text-bottom;}
input, textarea, select{font-family:inherit;font-size:inherit;font-weight:inherit;}
input, textarea, select{font-size:100%;}
legend{color:#000;}
body{color:#222;font-family:Helvetica, STHeiti STXihei, Microsoft JhengHei, Microsoft YaHei, Tohoma, Arial;height:100%;position:relative;}
body > .tips{display:none;left:50%;padding:20px;position:fixed;text-align:center;top:50%;width:200px;z-index:100;}
.page{padding:15px;}
.page .page-error, .page .page-loading{line-height:30px;position:relative;text-align:center;}
#activity-detail .page-bizinfo{border-bottom:1px dotted #CCC;}
#activity-detail .page-bizinfo .header{padding:10px 10px 10px;}
#activity-detail .page-bizinfo .header #activity-name{color:#000;font-size:20px;margin-bottom:5px;font-weight:bold;word-break:normal;word-wrap:break-word;}
#activity-detail .page-bizinfo .header #post-date{color:#8c8c8c;font-size:11px;margin:0;}
#activity-detail .page-content{padding:10px;}
#activity-detail .page-content .media{margin-bottom:18px;}
#activity-detail .page-content .media img{width:100%;}
#activity-detail .page-content .text{color:#3e3e3e;font-size:1.5;line-height:1.5;width: 100%;overflow: hidden;zoom:1;}
#activity-detail .page-content .text p{min-height:1.5em;min-height: 1.5em;word-wrap: break-word;word-break:break-all;}
#activity-list .header{font-size:20px;}
#activity-list .page-list{border:1px solid #ccc;border-radius:5px;margin:18px 0;overflow:hidden;}
#activity-list .page-list .line.btn{border-radius:0;margin:0;text-align:left;}
#activity-list .page-list .line.btn .checkbox{height:25px;line-height:25px;padding-left:35px;position:relative;}
#activity-list .page-list .line.btn .checkbox .icons{background-color:#ccc;left:0;position:absolute;top:0;}
#activity-list .page-list .line.btn.off .icons{background-image:none;}
#activity-list #save.btn{background-image:linear-gradient(#22dd22, #009900);background-image:-moz-linear-gradient(#22dd22, #009900);background-image:-ms-linear-gradient(#22dd22, #009900);background-image:-o-linear-gradient(#22dd22, #009900);background-image:-webkit-gradient(linear, left top, left bottom, from(#22dd22), to(#009900));background-image:-webkit-linear-gradient(#22dd22, #009900);}
.vm{vertical-align:middle;}
.tc{text-align:center;}
.db{display:block;}
.dib{display:inline-block;}
.b{font-weight:700;}
.clr{clear:both;}
.text img{max-width:100%;}
.page-url{padding-top:18px;}
.page-url-link{color:#607FA6;font-size:14px;text-decoration:none;text-shadow:0 1px #ffffff;-webkit-text-shadow:0 1px #ffffff;-moz-text-shadow:0 1px #ffffff;}
#mbutton{padding:15px 10px 15px 10px; overflow:hidden; border-bottom:1px #DDD solid;}
/*#mbutton > span{float:right; display:inline-block; background:#666; border:1px #DDD solid; color:#FFF; height:30px; line-height:30px; padding:0 10px; margin-left:1px;}*/
#mbutton >span{float:left;  height:30px; line-height:30px; padding:0 5px; margin-left:0px;}
#mcover{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0, 0, 0, 0.7);display:none;z-index:20000;}
#mcover img{position:fixed;right: 18px;top:5px;width:260px;height:180px;z-index:20001;}
.head{height:40px; line-height:40px; background:#22292C; padding:0 5px; color:#FFF;}
.head .bn{display:inline-block; height:30px; line-height:30px; padding:0 10px; margin-top:4px; font-size:20px; background:transparent; color:#FFF; text-decoration:none;}
.head .bn.pull-left{border-right:1px solid #000;}
.head .bn.pull-right{position:absolute; right:5px; top:0; border-left:1px solid #000;}
.head .title{font-size:14pt;display:block;padding-left:10px;font-weight:bolder;margin-right:49px;text-align:center;height:40px;line-height:40px;text-overflow:ellipsis;white-space:nowrap;overflow: hidden;}
.head .order{background:#F9F9F9; position:absolute; z-index:9999; right:0;}
.head .order li > a{display:block; padding:0 10px; min-width:100px; height:35px; line-height:35px; font-size:16px; color:#333; text-decoration:none; border-top:1px #EEE solid;}
.head .order li.fa-caret-up{font-size:20px;color:#F9F9F9;position:absolute;top:-11px;right:16px;}
</style>
<div class="head">
	<a href="javascript:history.go(-1);" class="bn pull-left"><i class="fa fa-reply"></i></a>
	<span class="title">内容页</span>
	<a href="javascript:;" id="category_show" class="bn pull-right"><i class="fa fa-reorder"></i></a>
	<ul class="list-unstyled order hide">
		<li class="fa fa-caret-up"></li>
		<?php  $site_category = modulefunc('site', 'site_category', array (
  'func' => 'site_category',
  'limit' => 10,
  'index' => 'iteration',
  'multiid' => 0,
  'uniacid' => 1,
  'acid' => 0,
)); if(is_array($site_category)) { $i=0; foreach($site_category as $i => $row) { $i++; $row['iteration'] = $i; ?>
		<li>
			<a href="<?php  echo $row['linkurl'];?>">
				<?php  echo $row['name'];?>
			</a>
		</li>
		<?php  } } ?>
	</ul>
</div>
<div class="page-bizinfo">
	<div class="header">
		<h1 id="activity-name"><?php  echo $detail['title'];?></h1>
		<span id="post-date">
			<span><?php  echo date("Y-m-d", $detail['createtime']);?></span>
			<span><?php  echo $detail['author'];?></span>
			<?php  if(!empty($detail['source'])) { ?><a href="<?php  echo $detail['source'];?>">文章来源</a><?php  } ?>
			<?php  if(!empty($subscribeurl)) { ?><a href="<?php  echo $subscribeurl;?>"><?php  echo $_W['account']['name'];?>&nbsp;<?php  echo $_W['account']['account'];?></a><?php  } ?>
		</span>
	</div>
</div>
<div class="page-content">
	<div class="text">
		<?php  echo $detail['content'];?>
	</div>
</div>
</div>
<div id="mbutton">
	<span class="" ><i class="fa fa-flag-o"></i> 阅读&nbsp;<?php  echo $detail['readcount'];?></span>
	<span class="dianzan" ><i <?php  if($is_or_no_zan) { ?>class=" fa fa-thumbs-up"<?php  } else { ?>class="fa fa-thumbs-o-up"<?php  } ?> ></i> 赞&nbsp;<?php  echo $detail['dianzan'];?></span>
	<span class="" > &nbsp;</span>
	<span class="" onclick="$('#mcover').show()"><i class="fa fa-share-alt"></i> 转发</span>
	<span class="" onclick="$('#mcover').show()"><i class="fa fa-group"></i> 分享</span>
</div>
<div id="mcover"  onclick="$(this).hide()"><img src="./resource/images/guide.png"></div>
<?php  echo register_jssdk(true);?>
<?php  $_share = array('content' => $detail['description'], 'title' => $detail['title'], 'imgUrl' => $detail['thumb']);?>
<script>
	require(['jquery'], function($){
		$('#category_show').click(function(){
			$('.head .order').toggleClass('hide');
		});
                
                //点赞ajax处理
                /*$('.dianzan').click(function(){
                    var url = "<?php  echo url('site/site/dianzan/', array('id' => $detail['id'],'zan' => $detail['dianzan']));?>";
                    $.post(url,function(dat){
                        if(dat == 'error'){
                            dat = $.parseJSON(dat);
                        }else{
                            if('Y' == dat.split("-")[0]){
                                var htmlstr = "<i class='fa fa-thumbs-up'></i> 赞&nbsp;"+dat.split("-")[1];
                                $('.dianzan').html(htmlstr);
                            }else{
                                var htmlstr = "<i class='fa fa-thumbs-o-up'></i> 赞&nbsp;"+dat.split("-")[1];
                                $('.dianzan').html(htmlstr);
                            }
                        }
                    }) 
                
                });*/


 		//阅读文章后,给分享人加积分
		var url = "<?php  echo url('site/site/handsel/', array('id' => $detail['id'], 'action' => 'click', 'u' => $_W['member']['uid']));?>";
		$.post(url, function(dat){
			if(dat != 'success') {
				dat = $.parseJSON(dat);
			} else {
			}
		});

		//转发成功后事件
                wx.ready(function(){
                        //分享到朋友圈
                        wx.onMenuShareTimeline({
                            title: '<?php  echo $_share['title']?>', // 分享标题
                            link: '', // 分享链接，不填表示默认文章连接
                            imgUrl: '<?php  echo $_share['imgUrl']?>', // 分享图标
                            success: function () { 
                            // 用户确认分享后执行的回调函数
                                    var url = "<?php  echo url('site/site/handsel/', array('id' => $detail['id'], 'action' => 'share'));?>";
                                    $.post(url, function(dat){
                                            if(dat != 'success') {
                                                    dat = $.parseJSON(dat);
                                            } else {
                                                $('#mcover').hide();
                                            }
                                    });
                            },
                            cancel: function () { 
                            // 用户取消分享后执行的回调函数
                            }
                        });

                        //分享给微信朋友
                        wx.onMenuShareAppMessage({
                            title: '<?php  echo $_share['title']?>', // 分享标题
                            desc: '<?php  echo $_share['content']?>', // 分享描述
                            link: '', // 分享链接默认文章连接
                            imgUrl: '<?php  echo $_share['imgUrl']?>', // 分享图标
                            type: '', // 分享类型,music、video或link，不填默认为link
                            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                            success: function () { 
                            // 用户确认分享后执行的回调函数
                                    var url = "<?php  echo url('site/site/handsel/', array('id' => $detail['id'], 'action' => 'share'));?>";
                                    $.post(url, function(dat){
                                            if(dat != 'success') {
                                                    dat = $.parseJSON(dat);
                                            } else {
                                                $('#mcover').hide();
                                            }
                                    });
                            },
                            cancel: function () { 
                            // 用户取消分享后执行的回调函数
                            }
                        });

                        //分享到QQ好友
                        wx.onMenuShareQQ({
                            title: '<?php  echo $_share['title']?>', // 分享标题
                            desc: '<?php  echo $_share['content']?>', // 分享描述
                            link: '', // 分享链接默认文章连接
                            imgUrl: '<?php  echo $_share['imgUrl']?>', // 分享图标
                            success: function () { 
                            // 用户确认分享后执行的回调函数
                                    var url = "<?php  echo url('site/site/handsel/', array('id' => $detail['id'], 'action' => 'share'));?>";
                                    $.post(url, function(dat){
                                            if(dat != 'success') {
                                                    dat = $.parseJSON(dat);
                                            } else {
                                                $('#mcover').hide();
                                            }
                                    });
                            },
                            cancel: function () { 
                            // 用户取消分享后执行的回调函数
                            }
                        });
                        
                        //分享到腾讯微博
                        wx.onMenuShareWeibo({
                            title: '<?php  echo $_share['title']?>', // 分享标题
                            desc: '<?php  echo $_share['content']?>', // 分享描述
                            link: '', // 分享链接默认文章链接
                            imgUrl: '<?php  echo $_share['imgUrl']?>', // 分享图标
                            success: function () { 
                            // 用户确认分享后执行的回调函数
                                    var url = "<?php  echo url('site/site/handsel/', array('id' => $detail['id'], 'action' => 'share'));?>";
                                    $.post(url, function(dat){
                                            if(dat != 'success') {
                                                    dat = $.parseJSON(dat);
                                            } else {
                                                $('#mcover').hide();
                                            }
                                    });
                            },
                            cancel: function () { 
                            // 用户取消分享后执行的回调函数
                            }
                        });

                        //分享到QQ空间
                        wx.onMenuShareQZone({
                            title: '<?php  echo $_share['title']?>', // 分享标题
                            desc: '<?php  echo $_share['content']?>', // 分享描述
                            link: '', // 分享链接默认文章链接
                            imgUrl: '<?php  echo $_share['imgUrl']?>', // 分享图标
                            success: function () { 
                            // 用户确认分享后执行的回调函数
                                    var url = "<?php  echo url('site/site/handsel/', array('id' => $detail['id'], 'action' => 'share'));?>";
                                    $.post(url, function(dat){
                                            if(dat != 'success') {
                                                    dat = $.parseJSON(dat);
                                            } else {
                                                $('#mcover').hide();
                                            }
                                    });
                            },
                            cancel: function () { 
                            // 用户取消分享后执行的回调函数
                            }
                        });

                });
	});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
