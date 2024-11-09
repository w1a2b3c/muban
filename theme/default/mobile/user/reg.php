<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-30">
    	<!---->
        <!--表单部分开始-->
        <form method="post" class="ui-form" data-back="{$lasturl}" data-code="{cms[user_reg_auth]}" data-type="3" data-color="red">
            <div class="ui-form-group">
                <input type="text" name="mobile" id="mobile" maxlength="11" class="ui-form-ip" placeholder="请输入手机号码" data-token="{$token}" data-rule="手机号码:required;mobile;ajax({U('checkuser')});">
            </div>
            {if cms[user_reg_auth]==1 && cms[user_reg_type]==2}
            <div class="ui-form-group">
                <div class="ui-input-group">
                    <input type="text" name="code" id="code" class="ui-form-ip radius-right-none" placeholder="请输入验证码" data-rule="验证码:required;">
                    <div class="code"><img src="{U('index/code')}" height="40" class="verify_code" data-id="#code" title="点击更换验证码"></div>
                </div>
            </div>
            {/if}
            {if cms[user_reg_type]==2 && strlen(cms[sms_state])>0}
            <div class="ui-form-group">
                <div class="ui-input-group">
                    <input type="text" name="ecode" id="ecode" class="ui-form-ip radius-right-none" placeholder="请输入短信验证码" data-rule="短信验证码:required;">
                    <button type="button" class="after blue ui-pl-20 ui-pr-20">发送验证码</button>
                </div>
            </div>
            {/if}
            <div class="ui-form-group">
                <input type="password" name="pwd" maxlength="20" class="ui-form-ip" autocomplete="new-password" placeholder="请输入密码（5-20位字符）" data-rule="密码:required;password;">
            </div>
            {if cms[user_reg_auth]==1 && cms[user_reg_type]!=2}
            <div class="ui-form-group">
                <div class="ui-input-group">
                    <input type="text" name="code" id="code" class="ui-form-ip radius-right-none" placeholder="请输入验证码" data-rule="验证码:required;">
                    <div class="code"><img src="{U('index/code')}" height="40" class="verify_code" data-id="#code" title="点击更换验证码"></div>
                </div>
            </div>
            {/if}
            <div class="ui-form-group ui-row">
            	<label class="ui-checkbox"><input name="agree" type="checkbox" value="1" id="agreement" data-rule="用户协议:checked;"><i></i>已阅读并同意：</label><a href="javascript:;" rel="nofollow" class="ui-text-red ui-modal-show" data-target="#my-modal-agree">用户协议</a>
            </div>
            <div class="ui-form-group ui-mt-20">
                <input type="hidden" name="token" value="{$token}">
                <button type="submit" class="ui-btn blue block big ui-radius-4">注册</button>
                <div class="ui-mt-15 ui-text-center ui-text-link"><a href="{N('login')}">已有账号？立即登录</a></div>
            </div>
        </form>
        <!--表单部分结束-->
        <!---->
    </div>
    
    
	<div class="ui-modal big" id="my-modal-agree">
        <div class="ui-modal-header">
            <div class="ui-modal-title">用户协议</div>
        </div>
        <div class="ui-modal-body ui-height-30">{config("user_agree")}</div>
        <div class="ui-modal-footer">
            <button type="button" class="ui-btn ui-modal-close ui-mr user-disagree">不同意</button>
            <button type="button" class="ui-btn blue user-agree">同意协议</button>
        </div>
    </div>
    
<script>
$(function()
{
	$(".user-disagree").click(function()
	{
		$("#agreement").prop("checked",false);
	});
	$(".user-agree").click(function()
	{
		$("#agreement").prop("checked",true);
		$("#my-modal-agree").modal('close');
	});

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
		{if cms[user_reg_auth]==1}
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
			url:"{U('smscode')}",
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