/*投稿功能使用*/
function videomove()
{
	var scrollHeight=$('#video-wrap').prop("scrollHeight");
	$('#video-wrap').animate({scrollTop:scrollHeight},500);
}

function setWidth(val)
{
    var noScroll,scroll,oDiv=document.createElement("DIV");
    oDiv.style.cssText="position:absolute;top:-9999px;width:100px;height:100px;overflow:hidden;";
    noScroll=document.body.appendChild(oDiv).clientWidth;
    oDiv.style.overflowY="scroll";
    scroll=oDiv.clientWidth;
    document.body.removeChild(oDiv);
	$("#fixed-width").attr("width",(noScroll-scroll+val)+'px');
}

$(function()
{
	setWidth(70);
	$(window).on('resize',function()
	{
		setWidth(70);
	});
	
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
		html+='<td><input name="video['+num+'][name]" class="ui-form-ip" id="video_name_'+num+'" data-rule="标题:required;"></td>';
		html+='<td><input name="video['+num+'][url]" class="ui-form-ip" id="video_url_'+num+'" data-rule="地址:required;"></td>';
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
				var data=val.split(/[\s\n]/);
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
						html+='<td><input name="video['+num+'][name]" class="ui-form-ip" id="video_name_'+num+'" value="'+name+'" data-rule="标题:required;"></td>';
						html+='<td><input name="video['+num+'][url]" class="ui-form-ip" id="video_url_'+num+'" value="'+url+'" data-rule="地址:required;"></td>';
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
	
	$("#content").editor({toolbar:'post',upload:url,url:api,save:save,max:max,size:size,params:{token:token}});
	
	function filter(filter,group,pay)
	{
		money(pay);
		if(group==1)
		{
			$(".group_pic").removeClass("ui-hide");
		}
		else
		{
			$(".group_pic").addClass("ui-hide");
		}
		$(".filter").addClass("ui-hide");
		if(filter!='' || filter!='0')
		{
			var arr=filter.split(",");
			$(".filter_list").each(function()
			{
				var num=$(this).data("num");
				if($.inArray(''+num+'',arr)>=0)
				{
					$(this).removeClass("ui-hide");
					$(".filter").removeClass("ui-hide");
				}
				else
				{
					$(this).addClass("ui-hide");
				}
			});
		}
	};
	
	function money(type)
	{
		var ptype=$('input[name="payway"]:checked').val();
		var vtype=$('input[name="video_type"]:checked').val();
		var dtype=$('input[name="down_type"]:checked').val();
		if(type>0)
		{
			$(".money_price,.pay_type").removeClass("ui-hide");
		}
		else
		{
			$(".money_price,.pay_type").addClass("ui-hide");
		}
		$("#payway_"+ptype).click();
		
		switch(type)
		{
			case "1":
				$(".money_down,.money_video").addClass("ui-hide");
				$("#content")[0].btnMode('pay','show');
				break;
			case "2":
				$(".money_video").removeClass("ui-hide");
				if(vtype=='0')
				{
					$(".video_one").removeClass("ui-hide");
					$(".video_more").addClass("ui-hide");
				}
				else
				{
					$(".video_one").addClass("ui-hide");
					$(".video_more").removeClass("ui-hide");
				}
				$(".money_down").addClass("ui-hide");
				$("#content")[0].btnMode('pay','close');
				break;
			case "3":
				$(".money_down").removeClass("ui-hide");
				if(dtype=='0')
				{
					$(".down_local").removeClass("ui-hide");
					$(".down_pan").addClass("ui-hide");
				}
				else
				{
					$(".down_local").addClass("ui-hide");
					$(".down_pan").removeClass("ui-hide");
				}
				$(".money_video").addClass("ui-hide");
				$("#content")[0].btnMode('pay','close');
				break;
			default:
				$(".money_down,.money_video").addClass("ui-hide");
				$("#content")[0].btnMode('pay','close');
				break;
		}
	};
	
	var classid=$.Cascader(
	{
		el:'#classid',
		value:classid,
		data:options,
		showAllLevels:true,
		disabled:disabled,
		props:{
			value:'cateid',
			label:'cate_name',
		}
    });

	classid.changeEvent(function(value,node)
	{
		var f=node.data.cate_filter;
		var group=node.data.isgroup;
		var pay=node.data.cate_pay;
		filter(f,group,pay);
    });

	$(".ui-upload").upload(
	{
		params:{token:token},
		success:function(e,s)
		{
			var name=$(e).data("name") || '';
			var video=$(e).data("video") || '0';
			if(e.type=='image')
			{
				var p=$(e).closest(".ui-form-image-item");
				p.find(".upload").addClass("ui-hide");
				p.find(".image").removeClass("ui-hide");
				p.find(".image a").attr("href",s.url);
				p.find(".image img").attr("src",s.url);
				$("#"+name).val(s.url);
			}
			else if(e.type=='more')
			{
				if(video==0)
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
					html+='<td><input name="video['+num+'][name]" class="ui-form-ip" id="video_name_'+num+'" value="'+s.name+'" data-rule="标题:required;"></td>';
					html+='<td><input name="video['+num+'][url]" class="ui-form-ip" id="video_url_'+num+'" value="'+s.url+'" data-rule="地址:required;"></td>';
					html+='<td><label class="ui-checkbox"><input type="checkbox" name="video['+num+'][free]" id="video_free_'+num+'" value="1"><i></i></label></td>';
					html+='<td><a href="javascript:;" class="video-del">删除</a></td>';
					html+='</tr>';
					$("#video_list").append(html);
					videomove();
				}
			}
			else
			{
				$("#"+name).val(s.url);
			}
		}
	});
})