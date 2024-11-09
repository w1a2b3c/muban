<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth">
        <div class="ui-carousel" data-arrow="false">
        
            <div class="ui-carousel-inner">
            	<!--以下图片、链接等请至后台轮播图片中上传即可，无需修改此处-->
            	{cms:rs table="cms_ad" where="cid=2 and isshow=1" order="ordnum,id"}
                	{rs:eof}
                    <div class="ui-carousel-item active"><a href="https://www.nicemb.com/cms-pay" target="_blank" title="极品模板-内容付费管理系统"><img src="/upfile/mobile/1.jpg" alt="内容付费管理系统" /></a></div>
                    <div class="ui-carousel-item"><a href="https://www.nicemb.com/cms-order" target="_blank" title="极品模板-微商城订单管理系统"><img src="/upfile/mobile/2.jpg" alt="微商城订单管理系统" /></a></div>
                    {/rs:eof}
                    <div class="ui-carousel-item{if $i==1} active{/if}"><a href="{$rs.url}" title="{$rs.name}"><img src="{$rs.pic}" alt="{$rs.name}" /></a></div>
                {/cms:rs}
            </div>
            
        </div>
    
    </div>

    {cms:rp top="8" from="cms_class" where="followid=0 and ismenu=1 and cate_type=0" order="cate_order,cateid" auto="j"}
    {php $cateid=sonid($rp.cateid)}
    {if $rp.cate_pay==1}
    <div class="ui-goods ui-mwidth">
    	<div class="ui-menu blue">
            <div class="ui-menu-name ui-bold">{$rp.cate_name}</div>
            <div class="ui-menu-more"><a href="{cateurl($rp.cateid)}">更多</a></div>
        </div>
    	{cms:rs top="10" table="cms_data" join="left join cms_user on cms_show.userid=cms_user.atid" cateid="$cateid" where="isshow=1" order="ordnum desc,id desc" cache="false" cachetime="60"}
        <div class="ui-goods-item">
            {if $rs.isnice==1}<div class="ui-ring left green small"><span>推荐</span></div>{/if}
            {if $rs.ispic==1}<div class="image"><a href="{$rs.link}" title="{$rs.title}"><img data-original="{$rs.pic}" src="/public/images/spacer.gif" alt="{$rs.title}"></a></div>{/if}
            <div class="body">
                <div class="name ui-text-hide"><a href="{$rs.link}" title="{$rs.title}">{$rs.title}</a></div>
                <div class="desc ui-text-hide"><a href="{$rs.link}" title="{$rs.title}">{$rs.intro}</a></div>
                <div class="foot">
                    <div class="user">{if $rs.userid>0}<a href="{U('index/center','id='.$rs.userid.'')}"><img src="{$rs.uface}">{$rs.uname}</a>{else}<img src="{WEB_ROOT}upfile/noface.jpg">{config('author')}{/if}</div>
                    <div class="info"><em>{if $rs.payway==1}免费{elseif $rs.payway==2}VIP{else}付费{/if}</em></div>
                </div>
            </div>
        </div>
        {/cms:rs}
    </div>
    {else}
    <div class="ui-mwidth ui-mt-20 ui-pl ui-pr">
    	<div class="ui-menu blue ui-mb-20">
            <div class="ui-menu-name ui-bold">{$rp.cate_name}</div>
            <div class="ui-menu-more"><a href="{cateurl($rp.cateid)}">更多</a></div>
        </div>
    	<div class="temp-list pro-lists">
        	<div class="temp-list-wrap">
                {cms:rs top="10" table="cms_data" join="left join cms_user on cms_show.userid=cms_user.atid" where="isshow=1" cateid="$cateid" order="ordnum desc,id desc"}
            	<div class="temp-list-item">
                	{if $rs.isnice==1}<div class="ui-ring left green little"><span>推荐</span></div>{/if}
                	<div class="temp-list-box">
                    	<div class="item-image">
                        	<a href="{$rs.link}" title="{$rs.title}">{if $rs.ispic==1}<img data-original="{$rs.pic}" src="/public/images/spacer.gif" alt="{$rs.title}">{else}<svg width="100%" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img"><rect width="100%" height="100%" fill="#F8FBFF" /><text x="50%" y="50%" fill="#666666" dy=".3em">暂无图片</text></svg>{/if}</a>
                        </div>
                        <div class="item-text">
                        	<a href="{$rs.link}" title="{$rs.title}" class="ui-text-hide">{$rs.title}</a>
                            <p class="ui-text-hide-2">{$rs.intro}</p>
                        </div>
                        <div class="item-footer">
                        	<div class="user">{if $rs.userid>0}<a href="{U('index/center','id='.$rs.userid.'')}"><img src="{$rs.uface}">{$rs.uname}</a>{else}<img src="{WEB_ROOT}upfile/noface.jpg">{config('author')}{/if}</div>
                            <div class="info"><em>{if $rs.payway==1}免费{elseif $rs.payway==2}VIP{else}付费{/if}</em></div>
                        </div>
                    </div>
                </div>
                {/cms:rs}
            </div>
        </div>
    </div>   
    {/if}
    {/cms:rp}
    
    <div class="mwidth ui-text-center ui-mt-20 ui-height-30 ui-font-12">
    	{cms[web_name]}　版权所有 © {date('Y')} Inc.　<br>
		<a href="https://beian.miit.gov.cn" rel="nofollow" target="_blank">{cms[icp_num]}</a>　{if cms[ga_num]}<a href="{cms[ga_url]}" rel="nofollow" target="_blank"><img src="{WEB_ROOT}public/images/ga.png" class="ui-mr-sm">{cms[ga_num]}</a>{/if}
    </div>
    
    <div class="ui-footnav ui-fixed-bottom ui-mwidth">
        <a href="{WEB_ROOT}" class="active"><i class="ui-icon-home"></i>首页</a>
        {if cms[ct_weixin]}<a href="{cms[ct_weixin]}" class="ui-lightbox" data-name="weixin" data-title="扫码加微信"><i class="ui-icon-weixin"></i>咨询</a>{/if}
        {if cms[ct_tel]}<a href="tel:{cms[ct_tel]}"><i class="ui-icon-phone"></i>电话</a>{/if}
        <a href="{N('user')}"><i class="ui-icon-user"></i>会员</a>
    </div>
    {hook name="footer"}
    {cms[web_count]}
    
</body>
</html>