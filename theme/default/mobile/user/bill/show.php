<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-10">
    	<!---->
            <div class="ui-form-group ui-form-none">
            	<label>开票金额</label>
                <div class="text"><span class="ui-text-red">{$money}</span> 元</div>
            </div>
            <div class="ui-form-group ui-form-none">
            	<label>发票抬头</label>
                <div class="text">{$company}</div>
            </div>
            <div class="ui-form-group ui-form-none">
            	<label>纳税人识别号</label>
                <div class="text">{$idnumber}</div>
            </div>
            
            <div class="ui-form-group ui-form-none">
            	<label>开户银行</label>
                <div class="text">{$blank}</div>
            </div>
            <div class="ui-form-group ui-form-none">
            	<label>开户账号</label>
                <div class="text">{$blanknum}</div>
            </div>
            <div class="ui-form-group ui-form-none">
            	<label>注册地址</label>
                <div class="text">{$address}</div>
            </div>
            <div class="ui-form-group ui-form-none">
            	<label>注册电话</label>
                <div class="text">{$tel}</div>
            </div>
            <div class="ui-form-group ui-mt-20">
            	{if empty($downurl)}
                	<button type="button" class="ui-btn blue block ui-radius-4" disabled>待开票</button>
                {else}
                	<a class="ui-btn yellow block ui-radius-4" href="{$downurl}">下载发票</a>
                {/if}
            </div>
        <!---->
    </div>

</body>
</html>