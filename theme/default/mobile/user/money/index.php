<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-10">
    	<!---->
        <div class="ui-row ui-align-items-center ui-mb-20">
        	<div class="ui-col-4">
            	<div class="ui-text-gray">佣金：</div><div><span class="ui-font-20 ui-mr-sm ui-text-red">{$umoney}</span>元</div>
            </div>
            <div class="ui-col-4">
            	<div class="ui-text-gray">冻结：</div><div><span class="ui-font-20 ui-mr-sm">{$fmoney}</span>元</div>
            </div>
            <div class="ui-col-4 ui-text-right">
                <a href="{U('moneyadd')}" class="ui-btn blue outline ui-radius-20 ui-pl-20 ui-pr-20 small">提现</a>
            </div>
        </div>
        
        <div class="ui-btn-group blue big full line">
            <a class="ui-btn-group-item{if $type==0} active{/if}" href="{U('money')}">全部</a>
            <a class="ui-btn-group-item{if $type==1} active{/if}" href="{U('money','type=1')}">收入</a>
            <a class="ui-btn-group-item{if $type==2} active{/if}" href="{U('money','type=2')}">支出</a>
        </div>

        <div class="ui-p ui-bg-white ui-mt-15">
            <ul class="ui-media-list ui-media-border">
            {cms:rs pagesize="20" num="8" table="cms_user_money" where="$where" order="aid desc" key="aid"}
            {rs:eof}暂无记录{/rs:eof}
                <li class="ui-media">
                    <div class="ui-media-body">
                        <div class="ui-media-header">{$rs.title}</div>
                        <div class="ui-media-text ui-text-gray">{date('Y-m-d H:i:s',$rs.createdate)}</div>
                    </div>
                    <div class="ui-media-link ui-pt">
                    	<div class="ui-block ui-text-right">
                            {if $rs.type==1}+{else}-{/if}{getprice($rs.money)}
                            <div class="ui-block ui-text-gray">佣金：{getprice($rs.newmoney)}</div>
                        </div>
                    </div>
                </li>
            {/cms:rs}
            </ul>
        </div>
        <div class="ui-page center mid ui-mt ui-mb">
            <ul>{$showpage}</ul>
        </div>
        <!---->
    </div>
    
    <script>
	$(".ui-topbar-right").html('<a href="{U('moneylist')}">提现明细</a>');
	</script>

</body>
</html>