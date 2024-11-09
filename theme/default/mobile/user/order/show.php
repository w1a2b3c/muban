<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-10">
    	<!---->
        <div class="ui-menu blue">
        	<div class="ui-menu-name">订单信息</div>
        </div>
        
        <ul class="ui-list ui-mb ui-font-15">
            <li><div>订单号：</div><div><span>{$order_no}</span></div></li>
            <!--<li><div>商品金额：</div><div><span>{getprice($goods_total)}</span></div></li>
            <li><div>代金券：</div><div><span>- {getprice($cost_total)}</span></div></li>
            <li><div>应付金额：</div><div><span class="ui-text-red">{getprice($order_total)}</span></div></li>-->
            <li><div>订单状态：</div><div>{if $ispay==0}<span class="ui-badge yellow">未付款</span>{elseif $ispay==1}<span class="ui-badge green">交易完成</span>{else}<span class="ui-badge">已取消</span>{/if}</div></li>
            <li><div>创建时间：</div><div><span class="ui-text-gray">{date('Y-m-d H:i:s',$createdate)}</span></div></li>
            {if $ispay==1}
            <li><div>付款时间：</div><div><span>{date('Y-m-d H:i:s',$paydate)}</span></div></li>
            <li><div>付款方式：</div><div><span>{$payway}</span></div></li>
            {/if}
        </ul>
        
        <div class="ui-menu blue ui-mt-20">
        	<div class="ui-menu-name">商品清单</div>
        </div>
        <div class="ui-list">
        	{loop data="$goods" val="rs"}
            <li><div>{if $order_type==1}[<a href="{$this->api->url($rs.goods_id)}" class="ui-text-red" target="_blank">查看</a>]　{/if}<span class="ui-bold ui-font-16">{$rs.goods_name}</span>{if !empty($rs.goods_sku)}<br>【{$rs.goods_sku}】{/if}</div><div><span class="ui-text-red">{getprice($rs.goods_price)} × {$rs.goods_num}</span></div></li>
            {/loop}
        </div>
        <!---->
    </div>
    
    {if $ispay<=0}
    <div class="ui-footbar ui-mwidth ui-fixed-bottom">
        <div class="ui-footbar-left">
            订单金额：<span class="ui-text-red">{getprice($order_total)}</span>
        </div>
        <div class="ui-footbar-right">
            {if $ispay==0}
            <a class="ui-confirm" data-title="确定要取消订单？" href="{U('ordercancel','orderid='.$order_no.'')}">取消订单</a>
            <a class="blue" href="{U('order/pay','orderid='.$order_no.'')}">立即支付</a>
            {else}
            <a class="ui-confirm" data-title="确定要删除订单？" href="{U('orderdel','orderid='.$order_no.'')}">删除订单</a>
            {/if}
        </div>
    </div>
	{/if}

</body>
</html>