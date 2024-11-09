<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-30">
    	<!---->
        <!--表单部分开始-->
        <form method="post" class="ui-form" data-back="{if ispc()}pay{else}admin{/if}" data-price="{$price}" data-type="3" data-hide="2" data-color="red">
            <div class="ui-menu blue ui-mb-20">
                <div class="ui-menu-name">{$page_name}</div>
            </div>
            
            <div class="vip-list">
                <input type="hidden" name="id" data-target=".vip-list" data-rule="VIP类型:required;">
                {cms:rs top="0" from="cms_user_group" where="price>0" order="ordnum,aid"}
                <div class="vip-list-item{if $price>=$rs.price} disabled{/if}" data-id="{$rs.aid}" data-price="{getprice($rs.price)}">
                    <div class="vip-list-wrap">
                        <div class="left">
                            <div class="icon"><i class="ui-icon-adduser"></i></div>
                            <div>
                                <h3>{$rs.title} <span class="ui-ml ui-badge green">有效期：{if $rs.type<4}{$rs.num}{/if}{if $rs.type==1}天{elseif $rs.type==2}月{elseif $rs.type==3}年{else}永久{/if}</span></h3>
                                <span>{$rs.freenum}个/天</span>
                            </div>
                        </div>
                        <div class="price">{getprice($rs.price)}<span>元</span></div>
                    </div>
                    <span class="ischeck ui-icon-check"></span>
                </div>
                {/cms:rs}
            </div>

            <div class="ui-menu blue ui-mt-20 ui-mb">
                <div class="ui-menu-name">付款方式</div>
            </div>
            <input type="hidden" name="payway" id="payway" data-target=".ui-payway" data-rule="付款方式:required;">
            <div class="ui-payway">
                {hook name="pay"}
            </div>
            <div class="ui-form-group ui-mt-20">
                <input type="hidden" name="token" value="{$token}">
                <input type="hidden" name="ispc" id="ispc" value="0">
                <button type="submit" class="ui-btn blue block big ui-btn-radius">下一步</button>
            </div>
        </form>
        <!--表单部分结束-->
        <!---->
    </div>
    
</body>
</html>