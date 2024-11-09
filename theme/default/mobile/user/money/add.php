<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-10">
    	<!---->
		<!--表单部分开始-->
        <div class="ui-alert ui-mt-30 ui-mb-30 ui-bg-white">
            <span class="ui-text-red ui-bold">友情提示：</span><br>
            {if $num>0}
                今日提现次数已用完，请明天再试！
            {else}
                每个账户每天仅允许提现<span class="ui-text-red ui-mr-sm ui-ml-sm">1</span>次。今日还可以提现<span class="ui-text-blue ui-mr-sm ui-ml-sm">1</span>次
            {/if}
        </div>
                
        <form method="post" class="ui-form" data-back="{N('mymoney')}" data-type="3" data-color="red">
            <div class="ui-form-group ui-form-none">
            	<label>提现金额</label>
                <div class="ui-input-group">
                    <input type="text" name="money" id="money" class="ui-form-ip radius-right-none" placeholder="请输入提现金额" data-rule="提现金额:required;dot">
                    <div class="after blue ui-pl-20 ui-pr-20" style="box-shadow:none;"><a href="javascript:void($('#money').val('{$umoney}'))" tabindex="-1">全部金额</a></div>
                </div>
            </div>
            <div class="ui-form-group ui-form-none">
            	<label>收款人</label>
            	<input type="text" name="name" value="{$name}" class="ui-form-ip" placeholder="请输入收款人" data-rule="收款人:required;">
            </div>
            <div class="ui-form-group ui-form-none">
            	<label>支付宝账号</label>
            	<input type="text" name="alipay" value="{$alipay}" class="ui-form-ip" placeholder="请输入支付宝账号" data-rule="支付宝账号:required;">
            </div>
            <div class="ui-form-group ui-mt-20">
                <input type="hidden" name="token" value="{$token}">
                <button type="submit" class="ui-btn blue block ui-radius-4"{if $num>0} disabled="disabled"{/if}>提交申请</button>
            </div>
        </form>
        <!---->
    </div>

</body>
</html>