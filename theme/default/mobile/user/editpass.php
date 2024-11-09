<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-30">
    	<!---->
        <!--表单部分开始-->
        <form method="post" class="ui-form" data-back="{N('user')}" data-type="3" data-color="red">
            <div class="ui-form-group">
                <div class="ui-input-group">
                    <div class="before blue">原密码</div>
                    <input type="password" name="oldpass" class="ui-form-ip radius-left-none" autocomplete="new-password" placeholder="请输入原密码" data-rule="原密码:required;password;">
                </div>
            </div>
            <div class="ui-form-group">
                <div class="ui-input-group">
                    <div class="before blue">新密码</div>
                    <input type="password" name="pass" id="pass" maxlength="20" class="ui-form-ip radius-left-none" autocomplete="new-password" placeholder="请输入新密码" data-rule="新密码:required;password;">
                </div>
            </div>
            <div class="ui-form-group">
                <div class="ui-input-group">
                    <div class="before blue">新密码</div>
                    <input type="password" name="repass" maxlength="20" class="ui-form-ip radius-left-none" autocomplete="new-password" placeholder="请再次输入新密码" data-rule="确认新密码:required;password;match(pass)">
                </div>
            </div>

            <div class="ui-form-group ui-mt-20">
                <input type="hidden" name="token" value="{$token}">
                <button type="submit" class="ui-btn block blue ui-btn-radius">保存密码</button>
            </div>
        </form>
        <!--表单部分结束-->
        <!---->
    </div>

</body>
</html>