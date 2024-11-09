/*评论功能使用*/
$(function()
{
	$(".ui-icon-smile").click(function()
	{
		$(this).toggleClass("ui-text-yellow");
		$(".ui-comment-emoji").toggleClass("ui-hide");
	});
	$(document).on("click",".ui-comment-emoji-item",function()
	{
		var emoji=$(this).html();
		var name=$(".offside-comment").find("textarea[name=content]");
		var old=name.val();
		var startPos=name[0].selectionStart;
		var endPos=name[0].selectionEnd;
		if (startPos===undefined || endPos===undefined)
		{
			name.val(emoji);
		} 
		else 
		{
			name.val(old.substring(0,startPos)+emoji+old.substring(endPos));
		}
		$(".ui-comment-emoji").toggleClass("ui-hide");
	});
	var str='';
	var data=[[128512,128591],[129296,129342],[127792,127879],[128112,128175],[129412,129412]];
	for(i=0;i<data.length;i++)
	{
		var g=data[i];
		for(var e=g[0];e<=g[1];e++)
		{
			str+='<div class="ui-comment-emoji-item">&#'+e+';</div>';
		}
	}
	$(".ui-comment-emoji").html(str);
	
	$(".ui-comment-add").click(function()
	{
		var pid=$(this).data("pid");
		if($(this).hasClass("disabled"))
		{
			return false;
		}
		if(cms.islogin=='0')
		{
			check_login();
			return false;
		}
		$(".offside-comment").find("input[name=pid]").val(pid);
		$(".offside-comment").find("textarea[name=content]").attr("placeholder","");
		$(".offside-comment").offside('show');
	});
	
	$(".ui-comment-reply").click(function()
	{
		if(cms.islogin=='0')
		{
			check_login();
			return false;
		}
		var pid=$(this).data("pid");
		var name=$(this).data("name");
		$(".offside-comment").offside('show');
		$(".offside-comment").find("input[name=pid]").val(pid);
		$(".offside-comment").find("textarea[name=content]").attr("placeholder","回复："+name);
	});
	
	$(".ui-comment-like").click(function()
	{
		if(cms.islogin=='0')
		{
			check_login();
			return false;
		}
		
		var that=this;
		var url=$(that).data("url");
		var num=$(that).html() || 0;
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
					$(that).html(num+1);
					$(that).addClass("ui-text-red");
				}
				else
				{
					$(that).html((num-1==0)?'':(num-1));
					$(that).removeClass("ui-text-red");
				}
			}
		});
	});
});