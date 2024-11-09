<?php if(!defined('IN_CMS')) exit;?>{include file="head.php"}
    
    <div class="width ui-mt-20">
    
    	<div class="ui-bread ui-mb-20">
            <ul>
                <li><a href="{WEB_ROOT}" title="首页">首页</a></li>
                {loop data="$position"}
                <li><a href="{$val.url}" title="{$val.name}">{$val.name}</a></li>
                {/loop}
            </ul>
        </div>
		
        <div class="tempshow">
        	{if $isnice==1}<div class="ui-ring left red small"><span>推荐</span></div>{/if}
        	<div class="tempshow-left">
            	<!---->
                <div class="proshow tempshow-head">
                    <div class="proshow-left">
                        <!---->
                        <div class="ui-carousel" data-arrow="true">
                            <div class="ui-carousel-inner">
                            	{loop data="$piclist"}
                                <div class="ui-carousel-item{if $step==1} active{/if}"><a href="{$val}" class="ui-lightbox"><img src="{$val}"></a></div>
                                {/loop}
                            </div>
                        </div>
                        <!---->
                    </div>
                    
                    <div class="proshow-right">
                        <div class="pro-name">{$title}</div>
                        <div class="pro-intro">{$intro}</div>
                        <div class="pro-price">
                            <div class="price">
                            <span>{if $payway==1}免费{elseif $payway==2}VIP免费{else}{$price}{/if}</span>{if $price>0}<span class="ui-text-gray ui-font-13 ui-ml-sm">{config('money')}</span>{/if}
                            {if $payway==3}
                            <div class="ui-mt-15 ui-font-14 ui-text-gray down_tips">
                                <div class="group-wrap">
                                    {loop data="$rate"}
                                        {if $val.rate<10}<div class="group">【{$val.title}】<!--<span>{$val.price}</span>{config('money')}--><em>{if $val.rate>0}{$val.rate}折{else}免费{/if}</em></div>{/if}
                                    {/loop}
                                </div>
                            </div>
                            {/if}
                            </div>
                            <!--<div class="info">
                            	{if $tsales+$vsales>0 && $price>0}
                                <div class="ui-btn-group yellow bgcolor small">
                                    <span class="ui-btn-group-item active">已售</span>
                                    <span class="ui-btn-group-item">{$tsales+$vsales}</span>
                                </div>
                                {/if}
                            </div>-->
                        </div>
                        <ul class="pro-ul">
                            <li><em>人气：</em>{$hits}</li>
                        </ul>
                        <!---->
                        <div class="pro-action">
                            <div class="temp-action-left">
                                {if $price>0}
                                <a class="ui-btn red ui-buy" href="javascript:;" {if $is_buy==0} data-url="{U('buy','id='.$id.'')}"{else}data-down="{U('down','id='.$id.'')}"{/if} data-user="{cms[user_visitor]}">下载模板</a>
                                {else}
                                	<a class="ui-btn red ui-buy" href="javascript:;" data-down="{U('down','id='.$id.'')}" data-user="{cms[user_visitor]}">下载模板</a>
                                {/if}
                                {if $down_type==1}<div class="ui-ml-15"><span class="ui-text-gray">提取码：</span>{$panpass}</div>{/if}
                            </div>
                            <div class="pro-action-right">
                            	{if !empty($demourl)}
                            	<a class="ui-btn blue outline" href="{$demourl}" target="_blank">模板演示</a>
                                {/if}
                            </div>
                        </div>
                        {if $payway==2}<div class="ui-mt-15 ui-font-14 ui-text-gray">当前身份是：<span class="ui-text-red">{$gname}</span>，【{$group}】免费下载</div>{/if}
                        <!---->
                    </div>
                
                </div>
                
                <div class="proshow-intro">
                    <div class="proshow-nav ui-tab">
                        <ul>
                            <li class="active"><a href="javascript:;">模板介绍</a></li>
                        </ul>
                    </div>
                    
                    <div class="ui-tab-content">
                        <div class="ui-tab-panel">
                        	<!--{loop data="$filter"}
                            {$key}：{$val}<br>
                            {/loop}-->
                            {$content}
                            <div class="ui-mt-30">
                                {loop data="$tagslist" val="rs"}
                                <a href="{$rs.url}" title="{$rs.name}" target="_blank" class="ui-btn color-red ui-mr-sm">{$rs.name}</a>
                                {/loop}
                            </div>
                        </div>
                    </div>

                </div>
                <!---->
            </div>
            
            <div class="tempshow-right">
            	<div class="temp-box">
                	<div class="temp-name">开发者</div>
                    <div class="temp-author">
                    	<div class="face"><img src="{$author.face}" alt="{$author.name}"></div>
                        <div class="body">
                        	<div class="name">{$author.name}</div>
                            <a href="{$author.url}" class="ui-btn outline yellow small ui-mt ui-font-13">我的作品</a>
                        </div>
                    </div>
                    <div class="ui-btn-group blue full ui-mt-20">
                        <span class="ui-btn-group-item ui-cursor ui-like{if $islike==1} active{/if}" data-active="active" data-url="{U('like','id='.$id.'')}"><i class="ui-icon-like ui-mr-sm"></i>点赞<span>{if $love>0}{$love}{/if}</span></span>
                        <span class="ui-btn-group-item ui-cursor ui-love{if $islove} active{/if}" data-url="{U('love','id='.$id.'')}" data-data="token={$token}"><i class="ui-icon-star{if $islove}-fill{/if} ui-mr-sm"></i>收藏</span>
                    </div>      
                </div>
                
                <div class="temp-box ui-mt-30">
                	<div class="temp-name">随机推荐</div>
                    <ul class="artlist">
                    	{cms:rs from="cms_show" where="isshow=1" order="rand()"}
                    	<li><a href="{$rs.link}" title="{$rs.title}"><em>{if $i<10}0{/if}{$i}</em>{$rs.title}</a></li>
                        {/cms:rs}
                    </ul>
                </div>
                
                <div class="temp-box ui-mt-30">
                	<div class="temp-name">相关内容</div>
                    <ul class="artlist">
                    	{cms:rs from="cms_data" where="$like" order="id desc"}
                    	<li><a href="{$rs.link}" title="{$rs.title}"><em>{if $i<10}0{/if}{$i}</em>{$rs.title}</a></li>
                        {/cms:rs}
                    </ul>
                </div>
                
            </div>

        </div>
        
    </div>
    
    <div class="ui-modal small" id="modal-pay">
        <div class="ui-modal-header">
            <div class="ui-modal-title">付款方式</div>
            <div class="ui-modal-close">×</div>
        </div>
        <div class="ui-modal-body">
            <form method="post" class="ui-form" action="{U('buy','id='.$id.'')}" data-back="{if ispc()}pay{else}admin{/if}" data-price="{$vprice}" data-type="3" data-hide="2" data-color="red">
                <input type="hidden" name="payway" id="payway" data-target=".ui-payway" data-rule="付款方式:required;">
                <div class="ui-payway one">
                	{hook name="pay"}
                </div>
                <input type="hidden" name="token" value="{$token}">
                <input type="hidden" name="ispc" id="ispc" value="0">
                <button type="submit" class="ui-btn blue block ui-mt-30">支付：{$vprice} 元{if config('money')!='元'}（1{config('money')}=1元）{/if}</button>
                {if USER_ID==0}<div class="ui-mt-20 ui-text-gray">当前是【<span class="ui-text-red">游客</span>】，建议<a href="{N('login')}" class="ui-text-blue ui-ml-sm ui-mr-sm">登录</a>后购买。</div>{/if}
            </form>
        </div>
    </div>
    
    {include file="foot.php"}

</body>
</html>