<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-10">
    	<!---->
		<!--表单部分开始-->
        <form method="post" class="ui-form" data-back="{N('mybill')}" data-type="3" data-color="red">
            <div class="ui-form-group ui-form-none">
            	<label>开票金额</label>
                <div class="ui-input-group">
                    <input type="text" name="money" id="money" class="ui-form-ip radius-right-none" placeholder="请输入开票金额" data-rule="开票金额:required;dot">
                    <div class="after blue ui-pl-20 ui-pr-20"><a href="javascript:void($('#money').val('{$bill}'))" tabindex="-1">全部金额</a></div>
                </div>
            </div>
            <div class="ui-form-group ui-form-none">
            	<label>发票抬头</label>
            	<input type="text" name="company" value="{$company}" class="ui-form-ip" placeholder="请输入发票抬头" data-rule="发票抬头:required;">
            </div>
            <div class="ui-form-group ui-form-none">
            	<label>纳税人识别号</label>
            	<input type="text" name="idnumber" value="{$idnumber}" class="ui-form-ip" placeholder="请输入纳税人识别号" data-rule="纳税人识别号:required;">
            </div>
            <div class="ui-form-group ui-form-none">
            	<label>发票内容</label>
            	<div class="ui-pt ui-pl-20">*信息技术服务*网络服务</div>
            </div>
            <div class="ui-bg-gray ui-p-15">以下为选填项目</div>
            <div class="ui-form-group ui-form-none">
            	<label>开户银行</label>
            	<input type="text" name="blank" value="{$blank}" class="ui-form-ip">
            </div>
            <div class="ui-form-group ui-form-none">
            	<label>开户账号</label>
            	<input type="text" name="blanknum" value="{$blanknum}" class="ui-form-ip">
            </div>
            <div class="ui-form-group ui-form-none">
            	<label>注册地址</label>
            	<input type="text" name="address" value="{$address}" class="ui-form-ip">
            </div>
            <div class="ui-form-group ui-form-none">
            	<label>注册电话</label>
            	<input type="text" name="tel" value="{$tel}" class="ui-form-ip">
            </div>
            <div class="ui-form-group ui-mt-20">
                <input type="hidden" name="token" value="{$token}">
                <button type="submit" class="ui-btn blue block ui-radius-4">提交申请</button>
            </div>
        </form>
        <!---->
    </div>

</body>
</html>