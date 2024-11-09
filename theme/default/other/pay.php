<?php if(!defined('IN_CMS')) exit;?>{include file="head.php"}
	
    <form method="post" class="ui-form" data-back="{if ispc()}pay{else}admin{/if}" data-price="{getprice($order_total)}" data-type="3" data-hide="2" data-color="red">
	<div class="width ui-mt-20 ui-mb-30">
		
        <div class="tempshow">
        	<div class="tempshow-left">
            	<!---->
                <div class="temp-box">
					
                    <div class="temp-name">商品清单</div>
                    <div class="ui-list ui-mb-20">
                        {loop data="$goods" val="rs"}
                        <li><div><img src="{$rs.goods_pic}" class="ui-fl ui-mr-20 ui-radius-5" width="80"><span class="ui-font-16">{$rs.goods_name}</span>{if !empty($rs.goods_sku)}<br>【{$rs.goods_sku}】{/if}</div><div><span class="ui-text-red">{getprice($rs.goods_price)} × {$rs.goods_num}</span></div></li>
                        {/loop}
                    </div>
                    
                    {if $ispay==0}
                    <form method="post" class="ui-form" data-back="{if ispc()}pay{else}admin{/if}" data-price="{getprice($order_total)}" data-type="3" data-hide="2" data-color="red">
                        <div class="temp-name">付款方式</div>
                        <input type="hidden" name="payway" id="payway" data-target=".ui-payway" data-rule="付款方式:required;">
                        <div class="ui-payway ui-mt-20">
                            {hook name="pay"}
                        </div>
                        <input type="hidden" name="orderid" value="{$orderid}">
                        <input type="hidden" name="token" value="{$token}">
                        <input type="hidden" name="ispc" id="ispc" value="0">
                        <button type="submit" class="ui-btn blue ui-mt-15">在线付款</button>
                    </form>
                    {/if}
                    
                </div>
                <!---->
            </div>
            
            <div class="tempshow-right">
            	<div class="temp-box">
                	<div class="temp-name">订单信息</div>
                    <ul class="ui-list ui-mb-20 ui-font-15">
                        <li><div class="ui-text-gray">　订单号：</div><div><span>{$order_no}</span></div></li>
                        <li><div class="ui-text-gray">应付金额：</div><div><span class="ui-text-red">{getprice($order_total)}</span></div></li>
                        <li><div class="ui-text-gray">订单状态：</div><div><span>{if $ispay==0}<span class="ui-badge yellow">未付款</span>{else}<span class="ui-badge green">交易完成</span>{/if}</span></div></li>
                    </ul>
                    
                </div>
                
            </div>
            
            
        </div>
        
    </div>
	</form>
    {include file="foot.php"}
</body>
</html>