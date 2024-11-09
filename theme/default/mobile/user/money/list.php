<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-10">
    	<!---->
        
        <div class="ui-btn-group blue big full line">
            <a class="ui-btn-group-item{if $type==0} active{/if}" href="{U('moneylist')}">全部</a>
            <a class="ui-btn-group-item{if $type==1} active{/if}" href="{U('moneylist','type=1')}">待处理</a>
            <a class="ui-btn-group-item{if $type==2} active{/if}" href="{U('moneylist','type=2')}">成功</a>
            <a class="ui-btn-group-item{if $type==3} active{/if}" href="{U('moneylist','type=3')}">失败</a>
        </div>

        <div class="ui-p ui-bg-white ui-mt-15">
            <ul class="ui-media-list ui-media-border">
            {cms:rs pagesize="20" num="3" table="cms_user_cash" where="$where" order="aid desc" key="aid"}
            {rs:eof}暂无记录{/rs:eof}
                <li class="ui-media">
                    <div class="ui-media-body">
                        <div class="ui-media-header">{$rs.name}　{$rs.alipay}</div>
                        <div class="ui-media-text ui-text-gray">{date('Y-m-d H:i:s',$rs.postdate)}</div>
                        {if $rs.state==3}<div class="ui-mt">原因：<span class="ui-text-red">{$rs.remark}</span></div>{/if}
                    </div>
                    <div class="ui-media-link">
                    	
                    	<div class="ui-block">
                        	<div class="ui-bold">{$rs.money}</div>
                            {if $rs.state==1}
                            	<span class="ui-badge">待处理</span>
                            {elseif $rs.state==2}
                            	<span class="ui-badge green">成功</span>
                            {else}
                            	<span class="ui-badge red">失败</span>
                            {/if}
                        </div>
                    </div>
                </li>
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