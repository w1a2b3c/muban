<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-30">
    	<!---->
        <!--表单部分开始-->
        <form method="post" class="ui-form" data-back="{$lasturl}" data-code="{cms[user_login_auth]}" data-type="3" data-color="red">
            <div class="ui-form-group">
            	<input type="text" name="mobile" maxlength="11" class="ui-form-ip" placeholder="请输入手机号码" data-rule="手机号码:required;mobile;">
            </div>
            <div class="ui-form-group">
                <div class="ui-input-group">
                    <input type="password" name="pwd" maxlength="20" class="ui-form-ip radius-right-none" autocomplete="new-password" placeholder="请输入密码" data-rule="密码:required;password;">
                    <div class="after blue ui-pl-20 ui-pr-20"><a href="{N('getpass')}" tabindex="-1">忘记密码？</a></div>
                </div>
            </div>
            {if cms[user_login_auth]==1}
            <div class="ui-form-group">
                <div class="ui-input-group">
                    <input type="text" name="code" id="code" class="ui-form-ip radius-right-none" placeholder="请输入验证码" data-rule="验证码:required;">
                    <div class="code"><img src="{U('index/code')}" height="40" class="verify_code" data-id="#code" title="点击更换验证码"></div>
                </div>
            </div>
            {/if}
            <div class="ui-form-group ui-mt-20">
                <input type="hidden" name="token" value="{$token}">
                <button type="submit" class="ui-btn blue block big ui-radius-4">登录</button>
                <div class="ui-mt-15 ui-text-center ui-text-link"><a href="{N('reg')}">没有账号？立即注册</a></div>
            </div>
        </form>
        <!--表单部分结束-->
        {if $login_sms==1}
        <div class="ui-line center"><span class="ui-text-gray">其他方式登录</span></div>
        <div class="ui-text-center"><a href="{U('login','type=1')}">短信登录</a></div>
        {/if}
        
        {if config('login_state')==1}
        <div class="quicklogin">其他登录方式：{hook name="quick"}</div>
        {/if}
        
        <!---->
    </div>

</body>
</html>