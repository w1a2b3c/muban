function check_login()
{
	$.dialog(
	{
		title:"操作提示",
		text:'<i class="ui-icon-warning-circle ui-text-yellow ui-mr ui-ml ui-font-30"></i><span class="ui-font-16">请先登录或注册</span>',
		okval:'登录',
		cancelval:'注册',
		oktheme:'red',
		ok:function()
		{
			location.href=cms.login;
		},
		cancel:function()
		{
			location.href=cms.reg;
		}
	});
};

/*定时检查订单付款状态*/
function freshorder(url,orderid,backurl)
{
	var interval=setInterval(function()
	{
		$.ajax(
		{
			type:"post",
			cache:"false",
			dataType:'json',
			url:url,
			data:"orderid="+orderid,
			success:function(e)
			{
				if(e.state=='success')
				{
					location.href=(backurl!='')?backurl:e.msg;
					clearInterval(interval);
				}
			}
		})
	},1000);
};

function temp_error()
{
	$.dialog(
	{
		title:'错误提示',
		width:'450px',
		text:'<div class="ui-font-18 ui-text-red ui-mb-20">没有正确设置视频内容页模板，设置步骤如下：</div><p class="ui-mb">1、进入您的网站后台，在左侧【内容】下的【栏目管理】，找到您的栏目，进行编辑</p><p class="ui-mb">2、在顶部选项卡【模板设置】下面的【内容模板】设置为：<span class="ui-text-blue">page/show_video.php</span></p>',
		cancelshow:false,
		ok:function(e)
		{
			e.close();
		}
	})
};

function addqq(num)
{
	if((navigator.userAgent.toLowerCase().match(/(iPhone|iPod|Android|ios)/i)))
	{
		location.href="mqqapi://card/show_pslcard?src_type=internal&version=1&uin="+num+"&card_type=person&source=sharecard";
	}
	else
	{
		location.href="http://wpa.qq.com/msgrd?v=3&uin="+num+"&site=qq&menu=yes";
	}
}

function video_play()
{
	var that=$(".video-nicemb");
	var url=that.attr("data-url") || '';
	var id=that.attr("data-id") || '';
	if(url!='')
	{
		var art=new Artplayer({container:that[0],id:id,url:url,volume:1,autoMini:false,autoPlayback:true,autoOrientation:true,mutex:true,theme:'#ffad00'});
		/*art.aspectRatio='16:9';*/
	}
}

$(function()
{
	$('.video-nicemb').bind('contextmenu',function(){return false;})
	
	$("img").lazyload(
	{
		effect:"fadeIn",
		skip_invisible:true,
		failure_limit:20
	});

	/*返回顶部*/
	var top=$(".ui-backtop");
	$(window).scroll(function()
	{
		if($(window).scrollTop()>150)
		{
			top.slideDown();
		}
		else
		{
			top.slideUp();
		}
	});
	
	/*分享点击*/
	$(".ui-share a").click(function()
	{
		var that=$(this);
		var type=that.data('share');
		var title=document.title;
		var desc=$('meta[name="description"]').length ? $('meta[name="description"]').attr("content"):"";
		var pic=$(".ui-show-body img:first").attr("src") || "";
		var url=document.URL;
		var gourl='';
		switch(type)
		{
			case "qq":
				gourl="https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url="+url+"&title="+title+"&desc=&summary=&site=&pics="+pic;
				break;
			case "weibo":
				gourl="http://service.weibo.com/share/share.php?title="+title+"&url="+url+"&source=bookmark&pic="+pic;
				break;
		}
		if(gourl!='')
		{
			that.attr("href",gourl);
			that.attr("target","_blank");
		}
		else
		{
			$("#qrcode").remove();
			$.dialog(
			{
				title:"分享到微信",
				text:'<div class="ui-text-center ui-pt-30"><div id="qrcode" style="width:300px;height:300px;margin:0 auto 15px auto;"></div><div class="pay-footer">请打开【微信】，使用【扫一扫】完成分享。</div></div>',
				footer:false,
				theme:'green'
			});
			new QRCode('qrcode',{text:url,width:280,height:280});
		}
	});
	
	/*支付方式选择*/
	$(".ui-payway-item").click(function()
	{
		$("#payway").val($(this).data("way"));
		$("#ispc").val($(this).data("ispc"));
		$(this).closest("form").attr("data-title",$(this).data("title"));
		$(this).closest("form").attr("data-skin",$(this).data("color"));
		$(this).siblings().removeClass('active').end().addClass('active');
	});
	
	/*会员升级价格计算*/
	$(".vip-list-item").click(function()
	{
		if($(this).hasClass("disabled"))
		{
			return false;
		}
		var price=$(this).data("price") || 0;
		var id=$(this).data("id") || 0;
		$(this).closest("form").find("input[name=id]").val(id);
		$(this).closest("form").attr("data-price",price);
		$(this).siblings().removeClass('active').end().addClass('active');
	});

	/*内容页购买*/
	$(document).on("click",".ui-buy",function()
	{
		var url=$(this).data("url");
		var down=$(this).data('down') || '';
		var user=$(this).data('user') || 0;
		if(cms.islogin=='0' && user=='0')
		{
			check_login();
			return false;
		}
		if(down=='')
		{
			$("#modal-pay").modal('show');
		}
		else
		{
			var str='<a href="'+down+'" target="_blank" id="new_down_url"></a>';
			$('body').append(str);
			$('body').find("#new_down_url")[0].click();
			$('#new_down_url').remove();
		}
	});

	/*内容页收藏*/
	$(document).on("click",".ui-love",function()
	{
		var that=$(this);
		var url=$(this).data("url");
		if(cms.islogin=='0')
		{
			check_login();
			return false;
		}
		$.ajax(
		{
			type:'post',
			dataType:'json',
			url:url,
			data:"token="+cms.token,
			error:function(e){alert(e.responseText);},
			success:function(d)
			{
				if(d.state=='success')
				{
					if(d.msg=='1')
					{
						ui.success('收藏成功');
						that.addClass("active").html('<i class="ui-icon-star-fill ui-mr-sm"></i>收藏');
					}
					else
					{
						ui.success('收藏已取消');
						that.removeClass("active").html('<i class="ui-icon-star ui-mr-sm"></i>收藏');;
					}
				}
				else
				{
					ui.error(d.msg);
				}
			}
		});
	});

	/*内容页点赞*/
	$(document).on("click",".ui-like",function()
	{
		if(cms.islogin=='0')
		{
			check_login();
			return false;
		}
		var that=this;
		var url=$(that).data("url");
		var active=$(that).data("active");
		var num=$(that).find("span").html() || 0;
		num=num*100/100;
		$.ajax(
		{
			url:url,
			type:'post',
			error:function(e){alert(e.responseText);},
			data:"token="+cms.token,
			success:function(d)
			{
				if(d=='1')
				{
					$(that).find("span").html(num+1);
					$(that).addClass(active);
				}
				else
				{
					$(that).find("span").html((num-1==0)?'':(num-1));
					$(that).removeClass(active);
				}
			}
		});
	});
	
	/*头像上传*/
	if($(".upload-face").length>0)
	{
		$(".upload-face").upload(
		{
			params:{token:cms.token},
			success:function(e,s)
			{
				$("#"+$(e).data("name")).attr("src",s.url);
			}
		});
	}
	
	/*验证码点击*/
	$(".verify_code").click(function()
	{
		var src=$(this).attr("src");
		var id=$(this).data("id") || '';
		src+=((src.indexOf("?")>0)?'&':'?')+'&rnd='+Math.random();
		$(this).attr("src",src);
		if(id!='')
		{
			$(id).val("");
		}
	});
	
	/*表单提交*/
	$(".ui-form").form(
	{
		type:2,
		align:"center",
		before:function(e)
		{
			try
			{
				return beforecheck();
			}
			catch(e){}
			return true;
		},
		result:function(form)
		{
			var posturl=$(form).attr("action") || $(form).attr("data-url") || document.location;
			var backurl=$(form).attr("data-back") || document.location;
			var istop=$(form).attr("data-top") || 0;
			var time=$(form).attr("data-time") || 1000;
			var code=$(form).attr("data-code") || 0;
			var data=$(form).attr("data-data") || $(form).serialize();
			var price=$(form).attr("data-price") || 0;
			var title=$(form).attr("data-title") || '';
			var color=$(form).attr("data-skin") || 'blue';
			var payway=$(form).find("input[name=payway]").val();
			var ispc=$(form).find("input[name=ispc]").val() || '0';
			if(istop==2)
			{
				backurl=window.parent.location;
			}
			ui.loading('正在处理，请稍候');
			$(form).find("button[type=submit]").attr("disabled",true);
			$.ajax(
			{
				type:'post',
				cache:false,
				dataType:'json',
				url:posturl,
				data:data,
				error:function(e){alert(e.responseText);},
				success:function(d)
				{
					if(d.state=='success')
					{
						if(backurl=='pay')
						{
							if(ispc=='1')
							{
								var msg=d.msg;
								var arr=msg.split("@@");
								var qrcodeurl=arr[0];
								console.log(qrcodeurl)
								var orderid=arr[1];
								var url=arr[2];
								
								$(form).find("button[type=submit]").attr("disabled",false);
								$("#modal-pay").modal('close');
								$.dialog(
								{
									title:'扫码付款',
									text:'<div class="pay-header">'+title+'扫码付款：<span>'+price+'</span>元</div><div class="ui-loading pay-body" id="qrcode"></div><div class="pay-footer">请打开【'+title+'】，使用【扫一扫】完成付款。</div>',
									payway:color
								});
								$.ajax(
								{
									type:'get',
									url:qrcodeurl,
									dataType:'json',
									error:function(e){alert('error:'+e.responseText);},
									success:function(e)
									{
										if(e.state=='success')
										{
											new QRCode('qrcode',{text:e.msg,width:260,height:260});
											freshorder(url,orderid,document.location);
										}
										else
										{
											console.log(e)
											alert('wrong:'+e.msg)
										}
									}
								});
								return false;
							}
							backurl='admin';
						};
						if(backurl=='admin')
						{
							backurl=d.msg;
						}
						else
						{
							ui.loading('close');
							ui.success(d.msg);
						}
						setTimeout(function()
						{
							if(istop==0)
							{
								location.href=backurl;
							}
							else if(istop==1)
							{
								top.location.href=backurl;
							}
							else if(istop==2)
							{
								parent.location.href=backurl;
							}
							else
							{
								parent.$.dialogclose();
							}
						},time);
					}
					else
					{
						ui.loading('close');
						if(code=='1')
						{
							$(".verify_code").click();
						}
						$(form).find("button[type=submit]").attr("disabled",false);
						ui.error(d.msg);
					}
				}
			});
		}
	});

	$(".ui-confirm").click(function()
	{
		event.preventDefault();
		var url=$(this).attr("href") || $(this).data("url");
		var title=$(this).data("title") || '';
		$.dialog(
		{
			title:"操作提示",
			text:title,
			ok:function(e)
			{
				location.href=url;
			}
		});
    });
	
	$(".ui-iframe").click(function()
	{
		event.preventDefault();
		var url=$(this).attr("href") || $(this).data("url") || document.location;
		var title=$(this).data("title") || $(this).html() || '操作提示';
		var size=$(this).data("size") || '80%:80%';
		var foot=$(this).attr("data-foot") || '1';
		var footer=(foot=='1')?true:false;
		var data=size.split(":");
		var width=data[0];
		var height=data[1];
		$.dialogbox(
		{
			title:title,
			text:url,
			width:width,
			height:height,
			footer:footer,
			type:3,
			oktheme:'red',
			ok:function(e)
			{
				e.iframe().contents().find("#ui-submit").click();
			}
		});
	});
	
	$(".ui-action").click(function()
	{
		if($(this).hasClass("disabled"))
		{
			return false;
		}
		event.preventDefault();
		var url=$(this).attr("href") || $(this).data("url")  || document.location;
		var backurl=$(this).data("back") || document.location;
		var title=$(this).data("title") || '确定要删除？不可恢复！';
		var data=$(this).data("data") || '';
		var tips=$(this).data("tips") || '';
		
		$.dialog(
		{
			title:"操作提示",
			text:title,
			ok:function(e)
			{
				if(tips!='')
				{
					ui.loading(tips);
				}
				$.ajax(
				{
                    url:url,
					type:'post',
					dataType:'json',
					error:function(e){alert(e.responseText);},
					data:data,
                    success:function(d)
                    {
                        e.close();
                        if(d.state=='success')
                        {
                            ui.success(d.msg);
                            setTimeout(function(){location.href=backurl;},1000);
                        }
                        else
                        {
                            ui.error(d.msg);
                        }
                    }
                });
			}
		});
    });
	
	$(document).on("click",".ui-form-image-item .action a",function()
	{
		var type=$(this).attr("data-type") || '';
		var p=$(this).closest(".ui-form-image-item");
		if(type=='')
		{
			p.find("input").val('');
			p.find(".upload").removeClass("ui-hide");
			p.find(".image").addClass("ui-hide");
		}
		else
		{
			p.remove();
		}
	});
	
});