<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}
    
    <div class="ui-mwidth ui-bg-white ui-p-15">

        <div class="ui-menu blue">
        	<div class="ui-menu-name">订单信息</div>
            <div class="ui-menu-more"><a href="{N('myorder')}">我的订单</a></div>
        </div>
        
        <ul class="ui-list ui-mb ui-font-15">
            <li><div>订单号：</div><div><span>{$order_no}</span></div></li>
            <!--<li><div>商品金额：</div><div><span>{getprice($goods_total)}</span></div></li>
            <li><div>代金券：</div><div><span>- {getprice($cost_total)}</span></div></li>-->
            <li><div>应付金额：</div><div><span class="ui-text-red">{getprice($order_total)}</span></div></li>
            <li><div>订单状态：</div><div><span>{if $ispay==0}<span class="ui-badge yellow">未付款</span>{else}<span class="ui-badge green">交易完成</span>{/if}</span></div></li>
        </ul>
        
        <div class="ui-menu blue ui-mt-20">
            <div class="ui-menu-name">商品清单</div>
        </div>
        <div class="ui-list">
            {loop data="$goods" val="rs"}
            <li><div><span class="ui-bold ui-font-16">{$rs.goods_name}</span>{if !empty($rs.goods_sku)}<br>【{$rs.goods_sku}】{/if}</div><div><span class="ui-text-red">{getprice($rs.goods_price)} × {$rs.goods_num}</span></div></li>
            {/loop}
        </div>
        
        {if $ispay==0}
        <form method="post" class="ui-form" data-back="{if ispc()}pay{else}admin{/if}" data-price="{getprice($order_total)}" data-type="3" data-hide="2" data-color="red">
            <div class="ui-menu blue ui-mt-20">
                <div class="ui-menu-name">付款方式</div>
            </div>
            <input type="hidden" name="payway" id="payway" data-target=".ui-payway" data-rule="付款方式:required;">
            <div class="ui-payway one ui-mt-20">
                {hook name="pay"}
            </div>
            <input type="hidden" name="orderid" value="{$orderid}">
            <input type="hidden" name="token" value="{$token}">
            <input type="hidden" name="ispc" id="ispc" value="0">
            <button type="submit" class="ui-btn blue block ui-mt-15">付款</button>
        </form>
        {/if}
    </div>
</body>
</html>