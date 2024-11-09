function noiframe()
{
	if(self!=top){top.location=self.location;}
}

function unique(arr)
{
	return arr.filter(function(item,index,arr)
	{
		return arr.indexOf(item,0)===index;
	});
};

function gettag(str)
{
	var arr=str.toString().split(",");
	var data=[];
	var j=0;
	for(i=0;i<arr.length;i++)
	{
		if(j<10)
		{
			data.push(arr[i]);
		}
		j++;
	}
	return data.join(',');
}

function videomove()
{
	var scrollHeight=$('#video-wrap').prop("scrollHeight");
	$('#video-wrap').animate({scrollTop:scrollHeight},500);
}

$(function()
{
	$("#video_type_0").click(function()
	{
		$(".video_one").removeClass("ui-hide");
		$(".video_more").addClass("ui-hide");
	});
	$("#video_type_1").click(function()
	{
		$(".video_one").addClass("ui-hide");
		$(".video_more").removeClass("ui-hide");
	});
	$("#down_type_0").click(function()
	{
		$(".down_local").removeClass("ui-hide");
		$(".down_pan").addClass("ui-hide");
	});
	$("#down_type_1").click(function()
	{
		$(".down_local").addClass("ui-hide");
		$(".down_pan").removeClass("ui-hide");
	});
	$("#payway_1").click(function()
	{
		$("#price").val('0');
		$("#money").val('0');
		$(".payway_money").addClass("ui-hide");
		$(".payway_vip").addClass("ui-hide");
	});
	$("#payway_2").click(function()
	{
		$("#price").val('0');
		$("#money").val('0');
		$(".payway_money").addClass("ui-hide");
		$(".payway_vip").removeClass("ui-hide");
	});
	$("#payway_3").click(function()
	{
		var price=$("#price").data('old') || 0;
		var money=$("#money").data('old') || 0;
		$("#price").val(price);
		$("#money").val(money);
		$(".payway_money").removeClass("ui-hide");
		$(".payway_vip").addClass("ui-hide");
	});

	$(".video-add").click(function()
	{
		var num=1;
		$("#video_list tr").each(function()
		{
			var maxnum=parseInt($(this).attr("num"));
			if (maxnum>=num)
			{
				num=maxnum+1;
			}
		});
		var html='';
		html+='<tr num="'+num+'">';
		html+='<td class="ui-select-tr"><i class="ui-icon-select ui-text-gray"></i></td>';
		html+='<td><input name="video['+num+'][name]" class="ui-form-ip" id="video_name_'+num+'"></td>';
		html+='<td><input name="video['+num+'][url]" class="ui-form-ip" id="video_url_'+num+'"></td>';
		html+='<td><label class="ui-checkbox"><input type="checkbox" name="video['+num+'][free]" id="video_free_'+num+'" value="1"><i></i></label></td>';
		html+='<td><a href="javascript:;" class="video-del">删除</a></td>';
		html+='</tr>';
		$("#video_list").append(html);
		videomove();
	});
	
	$(".video-more").click(function()
	{
		$.dialogbox(
		{
			title:"批量添加",
			width:'50%',
			height:'400px',
			rows:10,
			type:2,
			inputholder:'视频名称|视频地址',
			tips:'格式：视频名称|视频地址，一行一条视频',
			ok:function(e)
			{
				var val=e.inputval();
				if(val=='')
				{
					ui.warn('至少添加1个吧？');
					return false;
				}
				var num=1;
				$("#video_list tr").each(function()
				{
					var maxnum=parseInt($(this).attr("num"));
					if (maxnum>=num)
					{
						num=maxnum+1;
					}
				});
				var data=val.split(/[\n]/);
				for(i=0;i<data.length;i++)
				{
					var v=$.trim(data[i]);
					if(v!='')
					{
						var arr=v.split("|");
						var name='';
						var url='';
						if(arr.length==2)
						{
							name=arr[0];
							url=arr[1];
						}
						else
						{
							name=arr[0];
						}
						var html='';
						html+='<tr num="'+num+'">';
						html+='<td class="ui-select-tr"><i class="ui-icon-select ui-text-gray"></i></td>';
						html+='<td><input name="video['+num+'][name]" class="ui-form-ip" id="video_name_'+num+'" value="'+name+'"></td>';
						html+='<td><input name="video['+num+'][url]" class="ui-form-ip" id="video_url_'+num+'" value="'+url+'"></td>';
						html+='<td><label class="ui-checkbox"><input type="checkbox" name="video['+num+'][free]" id="video_free_'+num+'" value="1"><i></i></label></td>';
						html+='<td><a href="javascript:;" class="video-del">删除</a></td>';
						html+='</tr>';
						num++;
						$("#video_list").append(html);
					}
				}
				videomove();
				e.close();
			},
		});
	});

	$(document).on("click",".video-del",function()
	{
		var total=$("#video_list tr").length;
		if(total<=1)
		{
			/*
			ui.warn('至少留1个吧？');
			return false;
			*/
		}
		$(this).closest("tr").remove();
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
	
	$('.ui-state input[type=checkbox]').on('click',function()
	{
		var url=$(this).data("url");
		var token=$(this).data("token") || '';
		var result=($(this).is(':checked'))?1:0;
		var callback=$(this).data("callback") || '';
		$.ajax(
		{
			url:url,
			type:"post",
			dataType:'json',
			data:"state="+result+"&token="+token,
			error:function(e){alert(e.responseText);},
			success:function(d)
			{
				if(d.state=='success')
				{
					ui.success(d.msg);
					if(callback!="")
					{
						parent.loadnav();
					}
					//setTimeout(function(){location.href=document.location},500)
				}
				else
				{
					ui.error(d.msg);
				}
			}
		});
	});
	
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
						if(backurl=='admin')
						{
							setTimeout(function(){ui.loading('close');},500)
							backurl=d.msg;
						}
						else
						{
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
							else if(istop==4)
							{
								top.$.dialogclose();
							}
							else
							{
								top.$("#iframe_body").attr("src",backurl);
								parent.$.dialogclose();
							}
						},time);
					}
					else
					{
						if(code=='1')
						{
							$(".verify_code").click();
						}
						$(form).find("button[type=submit]").attr("disabled",false);
						ui.error(d.msg);
					}
				},
				complete:function()
				{
					setTimeout(function(){ui.loading('close');},1000)
				}
			});
		}
	});
	
	$(".ui-iframe").click(function()
	{
		if($(this).hasClass("disabled"))
		{
			return false;
		}
		event.preventDefault();
		var url=$(this).attr("href") || $(this).data("url") || document.location;
		var title=$(this).data("title") || $(this).html() || '操作提示';
		var size=$(this).data("size") || '80%:80%';
		var foot=$(this).attr("data-foot") || '1';
		var state=$(this).attr("data-state") || '1';
		var isback=$(this).attr("data-back") || '0';
		var footer=(foot=='1')?true:false;
		var data=size.split(":");
		var width=data[0];
		var height=data[1];
		var dialog=(state=='1')?top.$.dialog:$.dialog;
		dialog(
		{
			title:title,
			text:url,
			width:width,
			height:height,
			footer:footer,
			type:3,
			ok:function(e)
			{
				e.iframe().contents().find("#ui-submit").click();
			},
			close:function()
			{
				if(isback=='1')
				{
					location.href=document.location;
				}
			}
		});
	});
	
	$(".ui-confirm").click(function()
	{
		event.preventDefault();
		var url=$(this).attr("href") || $(this).data("url");
		var title=$(this).data("title") || '';
		top.$.dialog(
		{
			title:"操作提示",
			text:title,
			ok:function(e)
			{
				location.href=url;
			}
		});
    });
	
	$(".ui-action").click(function()
	{
		if($(this).hasClass("disabled") || $(this).hasClass("ui-disabled"))
		{
			return false;
		}
		event.preventDefault();
		var type=$(this).data("type") || 'del';
		var url=$(this).attr("href") || $(this).data("url")  || document.location;
		var backurl=$(this).data("back") || document.location;
		var title=$(this).data("title") || '确定要删除？不可恢复！';
		var data=$(this).data("data") || '';
		var tips=$(this).data("tips") || '';
		var theme='blue';
		var state=$(this).attr("data-state") || '1';
		var dialog=(state=='1')?top.$.dialog:$.dialog;
		var callback=$(this).data("callback") || '';

		switch(type)
		{
			case "copy":
				title='确定要复制？';
				break;
			case "recycle":
				title='确定要放入回收站？';
				break;
			case "recovery":
				title='确定要恢复？';
				break;
			case "clear":
				title='确定要清理？';
				theme='red';
				break;
			case "install":
				title='确定要安装此插件？';
				break;
			case "uninstall":
				title='确定卸载此插件？';
				break;
			case "upgrade":
				title='确定要升级此插件？';
				break;
			default:
				theme='red';
				break;
		};
		dialog(
		{
			title:"操作提示",
			text:title,
			oktheme:theme,
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
							if(callback!="")
							{
								parent.loadnav();
							}
                            setTimeout(function(){location.href=backurl;},500);
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
	
	if($(".ui-upload").length>0)
	{
		$(".ui-upload").upload(
		{
			params:{token:cms.token},
			success:function(e,s)
			{
				var name=$(e).data("name") || '';
				if(e.type=='image')
				{
					var p=$(e).closest(".ui-form-image-item");
					p.find(".upload").addClass("ui-hide");
					p.find(".image").removeClass("ui-hide");
					p.find(".image a.ui-lightbox").attr("href",s.url);
					p.find(".image img").attr("src",s.url);
					$("#"+name).val(s.url);
				}
				else if(e.type=='more')
				{
					var p=$(e).closest(".ui-form-image");
					var html='';
					html+='<div class="ui-form-image-item">';
					html+='	<input type="text" name="'+name+'" class="ui-hide" value="'+s.url+'">';
					html+='	<div class="image"><a href="'+s.url+'" class="ui-lightbox"><img src="'+s.url+'" /></a><div class="action"><a href="javascript:;" data-type="item">×</a></div></div>';
					html+='</div>';
					p.append(html)
				}
				else
				{
					$("#"+name).val(s.url);
				}
			}
		});
	};

	$(document).on("click",".ui-choose",function()
	{
		var name=$(this).data("name");
		var target=$(this).data("target");
		var url=$(this).data("url");
		var type=$(this).data("type");
		var multiple=$(this).data("multiple");
		var placeholer=$(this).data("replace");
		var spec=$(this).data("spec");
		var that=this;
		$.dialogbox(
		{
			title:"附件选择",
			text:url,
			width:'90%',
			height:'80%',
			type:3,
			ok:function(e)
			{
				var data=e.iframe().contents().find(".ui-upload-list .success");
				if(data.length==0)
				{
					ui.error('请至少选择一个文件');
					return false;
				}
				else
				{
					var file=$(data[0]).attr("data-url");
					if(multiple==0)
					{
						if(name=='preview')
						{
							$(that).closest(".preview").find("input").val(file);
							$(that).closest(".preview").find("img").attr("src",file);
						}
						else
						{
							$("#"+name).val(file);
							if(placeholer!='undefined')
							{
								$("."+placeholer).html('<img src='+file+'>');
							}
							if(spec!='undefined')
							{
								$("#"+name).closest(".uploadspec").find(".upload_action").addClass("ui-hide");
								$("#"+name).closest(".uploadspec").find(".upload_image").attr("src",file);
								$("#"+name).closest(".uploadspec").find(".pre").removeClass("ui-hide");
							}
						}
					}
					else if(multiple==1)
					{
						if(name=='videodata')
						{
							file=data;
							for(i=0;i<file.length;i++)
							{
								var f=file[i];
								var dname=$(f).attr("data-name");
								var arr=dname.split(".mp4");
								var title=arr[0];
								var url=$(f).attr("data-url");
								var num=1;
								$("#video_list tr").each(function()
								{
									var maxnum=parseInt($(this).attr("num"));
									if (maxnum>=num)
									{
										num=maxnum+1;
									}
								});
								var html='';
								html+='<tr num="'+num+'">';
								html+='<td class="ui-select-tr"><i class="ui-icon-select ui-text-gray"></i></td>';
								html+='<td><input name="video['+num+'][name]" class="ui-form-ip" id="video_name_'+num+'" value="'+title+'"></td>';
								html+='<td><input name="video['+num+'][url]" class="ui-form-ip" id="video_url_'+num+'" value="'+url+'"></td>';
								html+='<td><label class="ui-checkbox"><input type="checkbox" name="video['+num+'][free]" id="video_free_'+num+'" value="1"><i></i></label></td>';
								html+='<td><a href="javascript:;" class="video-del">删除</a></td>';
								html+='</tr>';
								num++;
								$("#video_list").append(html);
							}
							videomove();
						}
						else
						{
							file=data;
							for(i=0;i<file.length;i++)
							{
								var f=file[i];
								var url=$(f).attr("data-url");
								var html='';
								html+='<div class="ui-form-image-item">';
								html+='	<input type="text" name="'+name+'" class="ui-hide" value="'+url+'">';
								html+='	<div class="image"><a href="'+url+'" class="ui-lightbox"><img src="'+url+'" /></a><div class="action"><a href="javascript:;" data-type="item">×</a></div></div>';
								html+='</div>';
								$(target).append(html);
							}
						}
					}
					else
					{
						file=data;
						for(i=0;i<file.length;i++)
						{
							var f=file[i];
							var url=$(f).attr("data-url")
							var num=1;
							$("#downlist_"+name+" tr").each(function()
							{
								var maxnum=parseInt($(this).attr("num"));
								if (maxnum>=num)
								{
									num=maxnum+1;
								}
							});
							var html='';
							html+='<tr num="'+num+'">';
							html+='    <td><input type="text" name="'+name+'['+num+'][name]" id="'+name+'_name_'+num+'" value="下载地址'+num+'" class="ui-form-ip" data-rule="名称:required;">';
							html+='    <td><input type="text" name="'+name+'['+num+'][url]" id="'+name+'_url_'+num+'" value="'+url+'" class="ui-form-ip" data-rule="下载地址:required;">';
							html+='    <td class="link">';
							html+='        <a href="javascript:;" class="down-prev ui-mr-sm gray">上移</a>';
							html+='        <a href="javascript:;" class="down-next ui-mr-sm gray">下移</a>';
							html+='        <a href="javascript:;" class="down-del ui-mr-sm">删除</a>';
							html+='    </td>';
							html+='</tr>';
							$("#downlist_"+name).append(html);
						}
					}
					e.close();
				};
			}
		});
	});
	
	$(".ui-download").click(function()
	{
		var name=$(this).attr("data-name");
		var num=1;
		$("#downlist_"+name+" tr").each(function()
		{
			var max=parseInt($(this).attr("num"));
			if (max>=num)
			{
				num=max+1;
			}
		});
		var html='';					
		html+='<tr num="'+num+'">';
		html+='    <td><input type="text" name="'+name+'['+num+'][name]" id="'+name+'_name_'+num+'" value="下载地址'+num+'" class="ui-form-ip" data-rule="名称:required;">';
		html+='    <td><input type="text" name="'+name+'['+num+'][url]" id="'+name+'_url_'+num+'" class="ui-form-ip" data-rule="下载地址:required;">';
		html+='    <td class="link">';
		html+='        <a href="javascript:;" class="down-prev ui-mr-sm gray">上移</a>';
		html+='        <a href="javascript:;" class="down-next ui-mr-sm gray">下移</a>';
		html+='        <a href="javascript:;" class="down-del ui-mr-sm">删除</a>';
		html+='    </td>';
		html+='</tr>';
		$("#downlist_"+name+"").append(html);
	});
	
	$(document).on("click",".down-prev",function()
	{
		var $li=$(this).parent().parent();
		var $pre=$li.prev("tr");
		$pre.insertAfter($li)
	})
	$(document).on("click",".down-next",function()
	{
		var $li=$(this).parent().parent();
		var $next=$li.next("tr");
		$next.insertBefore($li);
	});
	$(document).on("click",".down-del",function()
	{
		$(this).parent().parent().remove();
	});
	
	$(".ui-tags").click(function()
	{
		var name=$(this).attr("data-name");
		var url=$(this).attr("data-url");
		var old=$("#"+name).val();
		if(url.indexOf("?")>0)
		{
			url+="&old="+old;
		}
		else
		{
			url+="?old="+old;
		}
		top.$.dialogbox(
		{
			title:"标签选择",
			text:url,
			width:'50%',
			height:'400px',
			type:3,
			ok:function(e)
			{
				var data=e.iframe().contents().find("#taglist").val();
				if(data=='')
				{
					ui.error('请至少选择一个标签');
					return false;
				}
				else
				{
					$("#"+name).val(gettag(unique(data.split(','))));
					e.close();
				}
			}
		});
	});
	
	$(".template").click(function()
	{
		var name=$(this).attr("data-name");
		var url=$(this).attr("data-url");
		top.$.dialogbox(
		{
			title:"模板选择",
			text:url,
			width:'60%',
			height:'60%',
			type:3,
			ok:function(e)
			{
				var data=e.iframe().contents().find("#go").val();
				if(data=='')
				{
					ui.error('请选择模板');
					return false;
				}
				else
				{
					$("#"+name).val(data);
					e.close();
				}
			}
		});
	});

});