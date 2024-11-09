<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

	<!--轮播图开始-->
    {if count($piclist)>0}
    <div class="ui-carousel ui-mwidth" data-arrow="false">
        <div class="ui-carousel-inner">
            {loop data="$piclist"}
            <div class="ui-carousel-item{if $step==1} active{/if}"><a href="{$val}" class="ui-lightbox"><img src="{$val}"></a></div>
            {/loop}
        </div>
    </div>
    {/if}
    <!--轮播图结束-->
        
    <div class="ui-mwidth ui-goods-body">
        <div class="title"><h1>{$title}</h1></div>
		<div class="art-info">
            <div class="user"><img src="{$author.face}"></div>
            <div class="info"><a href="{$author.url}">{$author.name}</a><span>{date('Y-m-d H:i',$createdate)}　<code>{$hits}</code> 浏览</span></div>
        </div>
        <div class="content">
        	<!--下载收费开始-->
            {if $cate_pay==3}
            <div class="ui-mb-20 ui-row">
            	<div class="ui-col-3"><span class="ui-text-red ui-font-16 ui-bold">{if $payway==1}免费{elseif $payway==2}VIP免费{else}{$price}{/if}</span>{if $price>0}<span class="ui-text-gray ui-font-13 ui-ml-sm">{config('money')}</span>{/if}</div>
                <div class="ui-col-9 ui-text-right">
                {if $price>0}
                <a class="ui-btn yellow ui-mr-sm ui-buy small" href="javascript:;" {if $is_buy==1}data-down="{U('down','id='.$id.'')}" {else} data-url="{U('buy','id='.$id.'')}"{/if}  data-user="{cms[user_visitor]}">下载</a>
                {else}
                    <a class="ui-btn yellow ui-mr-sm ui-buy small" href="javascript:;" data-down="{U('down','id='.$id.'')}" data-user="{cms[user_visitor]}">下载</a>
                {/if}
                {if !empty($demourl)}
                <a href="{$demourl}" target="_blank" class="ui-btn blue small">演示</a>
                {/if}
                {if $down_type==1}<span class="ui-ml"><span class="ui-text-gray">提取码：</span>{$panpass}</span>{/if}
                </div>
            </div>
            <div class="ui-menu blue ui-mb-20"><div class="ui-menu-name">内容介绍</div></div>
            {/if}
            <!--下载收费结束-->
            
            <!--VIP权限提示开始-->
            {if $payway==2 && $isvip==0 && $cate_pay==1}
            <div id="buytips">
                <div class="ui-dialog-tips">
                    <div class="ui-dialog-header">
                        <div class="ui-dialog-title">友情提示</div>
                    </div>
                    <div class="ui-dialog-body art_buy">
                        <div class="ui-text-center">开通VIP会员，免费查看。</div>
                        <div class="ui-mt-20 ui-text-gray ui-line"><span>当前身份是：{$gname}</span></div>
                        <div>【{$group}】</div>
                        <a class="ui-btn blue block upgrade ui-mt-20" href="{N('upgrade')}">{if USER_ID==0}登录/{/if}开通VIP会员</a>
                    </div>
                </div>
            </div>
            {/if}
            <!--VIP权限提示结束-->
            
            <!--内容部分开始-->
            {$content}
            {if $pagenum>1}<div class="ui-page center mid"><ul>{pagelist($page,$pagenum)}</ul></div>{/if}
            <!--内容部分结束-->
            
            <!--购买提示开始-->
            <!--可以根据$cate_pay的值类显示不同的收费布局效果($cate_pay==1为：内容收费，==2为视频收费)-->
            {if $is_buy==0 && $payway==3 && $cate_pay==1}
            <div id="buytips">
                <div class="ui-dialog-tips">
                    <div class="ui-dialog-header">
                        <div class="ui-dialog-title">友情提示</div>
                    </div>
                    <div class="ui-dialog-body art_buy">
                        <div class="ui-text-center">付款<span class="ui-text-red ui-ml ui-mr">{$price}</span>{config('money')}，继续查看。</div>
                        <div class="ui-mt-20 ui-text-gray ui-line"><span>加入会员享受折扣优惠</span></div>
                        <div class="group-wrap">
                        {loop data="$rate"}
                            {if $val.rate<10}<div class="group">【{$val.title}】<!--<span>{$val.price}</span>{config('money')}--><em>{if $val.rate>0}{$val.rate}折{else}免费{/if}</em></div>{/if}
                        {/loop}
                        </div>
                        <a class="ui-btn blue block ui-buy ui-mt-20" href="javascript:;" data-url="{U('buy','id='.$id.'')}" data-user="{cms[user_visitor]}">购买</a>
                    </div>
                    
                </div>
            </div>
            {/if}
            <!--购买提示结束-->

            <div class="ui-text-center">
            	<a href="javascript:;" class="ui-btn ui-pl-20 ui-pr-20 ui-mt-30 ui-icon-like ui-like outline {if $islike==1} blue{/if}" data-active="blue" data-url="{U('like','id='.$id.'')}">{if $love>0}{$love}{/if}</a>
            </div>
            
            <div class="ui-mt-20 ui-mb-20">
                {loop data="$tagslist" val="rs"}
                <a href="{$rs.url}" title="{$rs.name}" target="_blank" class="ui-btn color-red ui-mr-sm">{$rs.name}</a>
                {/loop}
            </div>

        </div>
        
        {if count($pre)>0}
        <div class="ui-pre">
        	<span>上一篇</span><a href="{$pre.url}" title="{$pre.title}">{$pre.title}</a>
        </div>
        {/if}
        
        {if count($next)>0}
        <div class="ui-next">
        	<span>下一篇</span><a href="{$next.url}" title="{$next.title}">{$next.title}</a>
        </div>
        {/if}
        
    </div>
    
    <div class="ui-mwidth ui-mt-20 ui-mb-20 ui-bg-white ui-p-15">
        <div class="ui-menu blue"><div class="ui-menu-name">相关内容</div></div>
        <ul class="ui-list">
            {cms:rs from="cms_data" where="$like" order="id desc"}
            <li><a href="{$rs.link}" title="{$rs.title}" class="ui-text-hide">{$rs.title}</a></li>
            {/cms:rs}
        </ul>
    </div>
    
    {if $comment_state==1}
    <div class="ui-foot-comment ui-mwidth ui-fixed-bottom">
        <div class="ui-foot-comment-left ui-comment-add {if $isallow==0}disabled{/if}">
            <i class="ui-icon-edit ui-mr"></i>{if $isallow==1}写评论{else}禁止评论{/if}
        </div>
        <div class="ui-foot-comment-right">
        	{if $isallow==1}<a href="{U('comment/index','id='.$id.'')}"><i class="ui-icon-message"></i>{if $comments>0}<span>{get_num($comments)}</span>{/if}</a>{/if}
            <a href="javascript:;" class="ui-love{if $islove} active{/if}" data-url="{U('love','id='.$id.'')}" data-data="token={$token}"><i class="ui-icon-star{if $islove}-fill{/if}"></i></a>
            
            <a href="javascript:;" class="ui-backtop"><i class="ui-icon-top"></i></a>
        </div>
    </div>
    {/if}
    
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
    
    {if $isallow==1 && $comment_state==1}
    <div class="ui-offside ui-offside-bottom offside-comment">
    	{if $user_auth==0}
        	您的账号未实名认证，<a href="{U('user/auth')}" class="ui-btn blue small">去认证</a>
        {else}
    	<form method="post" class="ui-form" action="{U('comment/index','id='.$id.'')}" data-back="{U('comment/index','id='.$id.'')}" data-type="3" data-color="red">
            <div class="ui-form-group">
            	<div class="ui-input-group">
                	<textarea name="content" class="ui-form-ip" data-rule="评论内容:required;"></textarea>
                    <div class="after none ui-align-self-end"><button type="submit" class="ui-btn blue">发表</button></div>
                </div>
            </div>
            <div class="ui-ml"><i class="ui-icon-smile"></i></div>
            <div class="ui-comment-emoji ui-hide"></div>
            <input type="hidden" name="pid" value="0">
        	<input type="hidden" name="token" value="{$token}">
        </form>
        {/if}
    </div>
	<script src="{WEB_THEME}mobile/js/comment.js"></script>
    {/if}
    
    {if $is_part==1}
    <script>
	$(function()
	{
		var html=$("#buytips").html();
		$(".buy-tips").html(html);
		$("#buytips").addClass("ui-hide");
	})
	</script>
    {/if}
    
    {if $cate_pay==2}
    <script>
    $(function()
    {
		temp_error();
    })
    </script>
    {/if}
</body>
</html>