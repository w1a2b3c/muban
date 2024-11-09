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
        	<div class="tempshow-left">
            	<!---->
                <div class="artshow">
                	{if $isnice==1}<div class="ui-ring left red small"><span>推荐</span></div>{/if}
                    <div class="art-title">
                    	<h1>{$title}</h1>
                    </div>
                    <div class="art-info">
                    	<div class="art-info-left">
                            <div class="user"><img src="{$author.face}"></div>
                            <div class="info"><a href="{$author.url}">{$author.name}</a><span>{date('Y-m-d H:i',$createdate)}　<code>{$hits}</code> 浏览</span></div>
                        </div>
                        <!--<div class="art-info-right ui-share">
                            分享：<a href="javascript:;" data-share="qq" data-title="分享到QQ空间" class="ui-tips"><i class="ui-icon-qq"></i></a><a href="javascript:;" data-share="weibo" data-title="分享到微博" class="ui-tips"><i class="ui-icon-weibo"></i></a><a href="javascript:;" data-share="weixin" data-title="分享到微信" class="ui-tips"><i class="ui-icon-weixin"></i></a>
                        </div>-->
                        <div class="art-info-right">
                            <div class="ui-btn-group yellow ui-mt-20">
                                <span class="ui-btn-group-item ui-cursor ui-like{if $islike==1} active{/if}" data-active="active" data-url="{U('like','id='.$id.'')}"><i class="ui-icon-like ui-mr-sm"></i>点赞<span>{if $love>0}{$love}{/if}</span></span>
                                <span class="ui-btn-group-item ui-cursor ui-love{if $islove} active{/if}" data-url="{U('love','id='.$id.'')}" data-data="token={$token}"><i class="ui-icon-star{if $islove}-fill{/if} ui-mr-sm"></i>收藏</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="art-body">
                    	<!---->
                        <!--下载收费开始-->
                        {if $cate_pay==3}
                        <div class="ui-mb-20 ui-row">
                            <div class="ui-col-5"><span class="ui-text-red ui-font-20 ui-bold">{if $payway==1}免费{elseif $payway==2}VIP免费{else}{$price}{/if}</span>{if $price>0}<span class="ui-text-gray ui-font-13 ui-ml-sm">{config('money')}</span>{/if}</div>
                            <div class="ui-col-7 ui-text-right">
                            {if $price>0}
                            <a class="ui-btn yellow ui-mr-sm ui-buy" href="javascript:;" {if $is_buy==1}data-down="{U('down','id='.$id.'')}" {else} data-url="{U('buy','id='.$id.'')}"{/if}  data-user="{cms[user_visitor]}">下载地址</a>
                            {else}
                                <a class="ui-btn yellow ui-mr-sm ui-buy" href="javascript:;" data-down="{U('down','id='.$id.'')}" data-user="{cms[user_visitor]}">下载地址</a>
                            {/if}
                            {if !empty($demourl)}
                            <a href="{$demourl}" target="_blank" class="ui-btn blue">查看演示</a>
                            {/if}
                            {if $down_type==1}<span class="ui-ml"><span class="ui-text-gray">提取码：</span>{$panpass}</span>{/if}
                            </div>
                        </div>
                        <div class="ui-menu blue ui-mb-20"><div class="ui-menu-name">内容介绍</div></div>
                        {/if}
                        <!--下载收费结束-->
                        
                        <!--内容部分开始-->
                        {$content}
                        {if $pagenum>1}<div class="ui-page center mid"><ul>{pagelist($page,$pagenum)}</ul></div>{/if}
                        <!--内容部分结束-->
                        
                        <!--VIP权限提示开始-->
                        {if $payway==2 && $isvip==0 && $cate_pay==1}
                        <div id="buytips">
                            <div class="ui-dialog-tips">
                                <div class="ui-dialog-header">
                                    <div class="ui-dialog-title">友情提示</div>
                                </div>
                                <div class="ui-dialog-body art_buy">
                                    <div class="ui-text-center">开通VIP会员，免费查看。</div>
                                    <div class="ui-mt-20 ui-text-gray ui-line"><span>当前身份是：{$gname}，以下级别免费：</span></div>
                                    <div>【{$group}】</div>
                                    <a class="ui-btn blue block upgrade ui-mt-20" href="{N('upgrade')}">{if USER_ID==0}登录/{/if}开通VIP会员</a>
                                </div>
                            </div>
                        </div>
                        {/if}
                        <!--VIP权限提示结束-->
                        
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

                    	<!---->
                    </div>
                    
                    <div class="ui-mt-30">
                    	{loop data="$tagslist" val="rs"}
                        <a href="{$rs.url}" title="{$rs.name}" target="_blank" class="ui-btn color-red ui-mr-sm">{$rs.name}</a>
                        {/loop}
                    </div>
                    
              	</div>
                
                <div class="prenext">
                	<div class="prenext-item">
                    	<div class="temp-box">
                        <div class="ui-ring {if count($pre)==0}gray{else}yellow{/if} left little"><span>上一篇</span></div>
                        <!--<div class="ui-text-gray ui-mb">上一篇：</div>-->
                        <div class="ui-text-hide ui-pl-30">{if count($pre)==0}没有了{else}<a href="{$pre.url}" title="{$pre.title}">{$pre.title}</a>{/if}</div>
                        </div>
                    </div>
                    
                    <div class="prenext-item">
                    	<div class="temp-box">
                        <div class="ui-ring {if count($next)==0}gray{else}yellow{/if} little"><span>下一篇</span></div>
                        <!--<div class="ui-text-gray ui-mb">下一篇：</div>-->
                        <div class="ui-text-hide">{if count($next)==0}没有了{else}<a href="{$next.url}" title="{$next.title}">{$next.title}</a>{/if}</div>
                        </div>
                    </div>
                </div>
                
                {if $isallow==1 && $comment_state==1}
                <div class="temp-box ui-mt-30 offside-comment">
                	<div class="temp-name">评论<a href="{U('comment/index','id='.$id.'')}"><span>{get_num($comments)}</span>评论</a></div>
                    {if USER_ID==0}
                    <div class="comment-post">
                    	<div class="comment-post-face"><img src="/upfile/noface.jpg" alt="游客"></div>
                        <div class="comment-post-body">
                        	<div class="comment-post-body-wrap">
                                <div class="comment-post-body-area"><textarea placeholder="说两句" readonly></textarea></div>
                                <div class="comment-post-login"><a href="{N('login')}">登陆</a></div>
                            </div>
                        </div>
                    </div>
                    {else}
                    {if $user_auth==0 && USER_ID>0}
                        您的账号未实名认证，<a href="{U('user/auth')}" class="ui-btn blue small">去认证</a>
                    {else}
                    <form method="post" class="ui-form" action="{U('comment/index','id='.$id.'')}" data-back="{U('comment/index','id='.$id.'')}" data-type="3" data-color="red">
                    <div class="comment-post">
                    	<div class="comment-post-face"><img src="{$tuser.uface}" alt="{$tuser.uname}"></div>
                        <div class="comment-post-body">
                        	<div class="comment-post-body-wrap">
                                <div class="comment-post-body-area"><textarea placeholder="说两句" name="content" data-rule="评论内容:required;"></textarea></div>
                            </div>
                            <div class="comment-post-action">
                            	<div class="comment-post-action-name">{$tuser.uname}<i class="ui-icon-smile"></i></div>
                                
                                <div class="comment-post-action-btn"><button type="submit" class="ui-btn blue">发表评论</button></div>
                            </div>
                        </div>
                    </div>
                    <div class="ui-comment-emoji ui-hide"></div>
                    <input type="hidden" name="pid" value="0">
                    <input type="hidden" name="token" value="{$token}">
                    </form>
                    {/if}
                    {/if}
                    <script src="{WEB_THEME}js/comment.js"></script>
                </div>
                {/if}
                
                <!---->
            </div>
            
            <div class="tempshow-right">
            	<div class="temp-box">
                	<div class="temp-name">{get_catename($topid)}</div>
                    <!--类别部分开始-->
                    <div class="ui-fold">
                        {cms:rp top="0" table="cms_class" where="followid=$topid and ismenu=1" order="cate_order,cateid"}
                        {php $sub_sonid=$rp.cateid}
                        {php $sub_num=get_sonid_num($rp.cateid)}
                        {rp:eof}
                        <div class="ui-fold-menu active">
                            <a href="{cateurl($topid)}">{get_catename($topid)}</a>
                        </div>
                        {/rp:eof}
                        <div class="ui-fold-menu {if is_active($rp.cateid,$parentid)} active{/if}">
                            <a href="{cateurl($rp.cateid)}" title="{$rp.cate_name}">{$rp.cate_name}</a>{if $sub_num>0}<i class="ui-icon-right"></i>{/if}
                        </div>
                        {if $sub_num>0}
                        <div class="ui-fold-body {if is_active($rp.cateid,$parentid)} show{/if}">
                            <ul>
                                {cms:rs top="0" table="cms_class" where="followid=$sub_sonid and ismenu=1" order="cate_order,cateid"}
                                <li {if is_active($rs[cateid],$parentid)} class="active"{/if}><a href="{cateurl($rs.cateid)}" title="{$rs.cate_name}">{$rs.cate_name}</a></li>
                                {/cms:rs}
                            </ul>
                        </div>
                        {/if}
                        {/cms:rp}
                    </div>
                    <!--类别部分结束-->
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