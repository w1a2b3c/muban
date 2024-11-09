<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-30">
    	<!---->
        <!--表单部分开始-->
        <form method="post" class="ui-form" data-back="{U('my')}" data-type="3" data-color="red">
            <div class="ui-form-group">
                <div class="ui-input-group">
                    <div class="before blue">昵称</div>
                    <input type="text" name="uname" maxlength="30" class="ui-form-ip radius-left-none" value="{$uname}" placeholder="请输入昵称" data-rule="昵称:required;">
                </div>
            </div>
              <div class="ui-form-group">
                <div class="ui-input-group">
                    <div class="before blue">简介</div>
                    <input type="text" name="uintro" maxlength="255" class="ui-form-ip radius-left-none" value="{$uintro}" placeholder="请输入个人简介">
                </div>
            </div>
            <div class="ui-form-group ui-mt-20">
                <input type="hidden" name="token" value="{$token}">
                <button type="submit" class="ui-btn block blue ui-btn-radius">保存</button>
            </div>
        </form>
        <!--表单部分结束-->
        <!---->
    </div>

</body>
</html>