<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-30">
    	<!---->
        <!--表单部分开始-->
        {if $state==0}<div class="ui-alert red ui-mb-20">您的账号还未申请认证！</div>{/if}
        {if $state==2}
            <div class="ui-alert green ui-mb-20">您的认证已通过审核。</div>
            <ul class="ui-list">
                <li><div>　<span class="ui-text-gray">姓名：</span>{$name}</div></li>
                <li><div><span class="ui-text-gray">身份证：</span>{substr($idcard,0,4)}**********{substr($idcard,-4)}</div></li>
            </ul>
        {else}
            {if $state==1}<div class="ui-alert yellow ui-mb-20">您的认证申请正在审核中！</div>{/if}
            {if $state==3}<div class="ui-alert red ui-mb-20">您的认证审核失败，原因：{$msg}。</div>{/if}
            <form method="post" class="ui-form" data-type="3" data-color="red">
                <div class="ui-form-group">
                    <div class="ui-input-group">
                        <div class="before red">姓　名</div>
                        <input type="text" name="name" class="ui-form-ip radius-left-none" value="{$name}" placeholder="请输入姓名" data-rule="姓名:required;">
                    </div>
                </div>
                <div class="ui-form-group">
                    <div class="ui-input-group">
                        <div class="before red">身份证</div>
                        <input type="text" name="idcard" class="ui-form-ip radius-left-none" value="{$idcard}" placeholder="请输入身份证" data-rule="身份证:required;idcard;">
                    </div>
                </div>
                <div class="ui-form-group ui-mt-20">
                    <input type="hidden" name="token" value="{$token}">
                    <button type="submit" class="ui-btn ui-btn-radius"{if $state==1} disabled{/if}>{if $state==1}审核中{else}提交认证{/if}</button>
                </div>
            </form>
        {/if}
        <!--表单部分结束-->
        <!---->
    </div>

</body>
</html>