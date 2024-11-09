<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-30">
    	<!---->
        <!--表单部分开始-->
        <form method="post" class="ui-form" action="{U('loginsms')}" data-back="{$lasturl}" data-type="3" data-color="red">
            <div class="ui-form-group">
                  <input type="text" name="mobile" id="mobile" maxlength="11" class="ui-form-ip" placeholder="请输入手机号码" data-rule="手机号码:required;mobile;">
            </div>
            {if cms[user_login_auth]==1}
            <div class="ui-form-group">
                <div class="ui-input-group">
                    <input type="text" name="code" id="code" class="ui-form-ip radius-right-none" placeholder="请输入验证码" data-rule="验证码:required;">
                    <div class="code"><img src="{U('index/code')}" height="40" class="verify_code" data-id="#code" title="点击更换验证码"></div>
                </div>
            </div>
            {/if}
            <div class="ui-form-group">
                <div class="ui-input-group">
                    <input type="text" name="ecode" id="ecode" class="ui-form-ip radius-right-none" placeholder="请输入短信验证码" data-rule="短信验证码:required;">
                    <button type="button" class="after blue ui-pl-20 ui-pr-20">发送验证码</button>
                </div>
            </div>
            <div class="ui-form-group ui-mt-20">
                <input type="hidden" name="token" value="{$token}">
                <button type="submit" class="ui-btn blue block big ui-radius-4">登录</button>
                <div class="ui-mt-15 ui-text-center ui-text-link"><a href="{N('reg')}">没有账号？立即注册</a></div>
            </div>
        </form>
        
        <div class="ui-line center"><span class="ui-text-gray">其他方式登录</span></div>
        <div class="ui-text-center"><a href="{N('login')}">账号密码登录</a></div>
        <!--表单部分结束-->
        <!---->
    </div>

<script>
$(function()
{
	$(".after").click(function(event)
	{
		var that=$(this);
		var mobile=$("#mobile").val();
		if(mobile=='')
		{
			$.tips({id:"#mobile",text:"请输入手机号码",align:'top-left',color:'red'});
			$("#mobile").focus();
			return false;
		}			
		var code='';
		{if cms[user_login_auth]==1}
		var code=$("#code").val();
		if(code=='')
		{
			$.tips({id:"#code",text:"请输入验证码",align:'top-left',color:'red'});
			$("#code").focus();
			return false;
		}
		{/if}
		$.ajax(
		{
			url:"{U('smscode','type=1')}",
			type:'post',
			cache:false,
			dataType:'json',
			data:'token={$token}&mobile='+encodeURIComponent(mobile)+'&code='+encodeURIComponent(code),
			error:function(e){alert(e.responseText);},
			success:function(d)
			{
				if(d.state=='success')
				{
					that.backtime();
					ui.success(d.msg);
				}
				else
				{
					ui.error(d.msg);
				}
			}
		});
		
	});
});
</script>
</body>
</html>