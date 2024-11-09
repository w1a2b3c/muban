<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-10">
    	<!---->
        <div class="ui-btn-group blue big full line">
            <a class="ui-btn-group-item{if $type==0} active{/if}" href="{U('order')}">全部</a>
            <a class="ui-btn-group-item{if $type==1} active{/if}" href="{U('order','type=1')}">待支付</a>
            <a class="ui-btn-group-item{if $type==2} active{/if}" href="{U('order','type=2')}">交易成功</a>
        </div>

        <div class="ui-p ui-bg-white ui-mt-15">
            <ul class="ui-media-list ui-media-border">
            	{cms:rs pagesize="20" num="3" table="cms_order" where="$where" order="order_id desc" key="order_id"}
                {rs:eof}暂无订单{/rs:eof}
                <a class="ui-media" href="{U('ordershow','id='.$rs.order_id.'')}">
                    <div class="ui-media-body">
                        <div class="ui-media-header">订单号：{$rs.order_no}</div>
                        <div class="ui-media-text ui-text-gray">{date('Y-m-d H:i:s',$rs.createdate)}</div>
                        <div class="ui-block ui-text-red">金额：{getprice($rs.order_total)}</div>
                    </div>
                    <div class="ui-media-arrow ui-media-center">
                        {if $rs.ispay==0}
                            <span class="ui-badge yellow ui-mr-20">未付款</span>
                        {elseif $rs.ispay==1}
                            <span class="ui-badge green ui-mr-20">交易完成</span>
                        {else}
                            <span class="ui-badge ui-mr-20">已取消</span>
                        {/if}
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