<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-10">
    	<!---->
		
        <div class="ui-row ui-align-items-center ui-mb-20">
        	<div class="ui-col-4">
            	<div class="ui-text-gray">累计金额：</div><div><span class="ui-font-20 ui-mr-sm">{$bill}</span>元</div>
            </div>
            <div class="ui-col-4">
            	<div class="ui-text-gray">已开票：</div><div><span class="ui-font-20 ui-mr-sm">{$invoice}</span>元</div>
            </div>
            <div class="ui-col-4 ui-text-right">
                {if $bill>=config('user_bill_min')}
                	<a href="{U('billadd')}" class="ui-btn blue outline ui-radius-20 ui-pl-20 ui-pr-20 small">立即开票</a>
                {else}
                	<button class="ui-btn blue outline ui-radius-20 ui-pl-20 ui-pr-20 ui-tips small" data-title="满 <span class='ui-text-red'>{config('user_bill_min')}</span> 元起开">立即开票</button>
                {/if}
            </div>
        </div>
        
        <div class="ui-menu blue">
        	<div class="ui-menu-name">开票记录</div>
        </div>
        <div class="ui-p ui-bg-white ui-mt-15">
            <ul class="ui-media-list ui-media-border">
            	{cms:rs pagesize="20" num="3" table="cms_user_bill" where="$where" order="aid desc" key="aid"}
                {rs:eof}暂无记录{/rs:eof}
                <a class="ui-media" href="{U('billshow','id='.$rs.aid.'')}">
                    <div class="ui-media-body">
                        <div class="ui-media-header">{$rs.company}</div>
                        <div class="ui-media-text ui-text-gray">{date('Y-m-d H:i:s',$rs.postdate)}</div>
                        <div class="ui-mt">发票金额：<span class="ui-text-red">{getprice($rs.money)}</span></div>
                    </div>
                    <div class="ui-media-arrow ui-media-link ui-media-center">
                    	<div class="ui-block ui-mr">
                        	{if empty($rs.downurl)}
                            	<span class="ui-badge yellow">待开票</span>
                            {else}
                            	<span class="ui-badge green">已开票</span>
                            {/if}
                        </div>
                    </div>
                </a>
            	{/cms:rs}
            </ul>
        </div>
        <div class="ui-page center ui-mt ui-mb">
            <ul>{$showpage}</ul>
        </div>
        <!---->
    </div>


</body>
</html>