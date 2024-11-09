<?php if(!defined('IN_CMS')) exit;?>{include file="head.php"}

    <div class="banner width">
    	<div class="banner-header">
            <div class="ui-carousel">
                <div class="ui-carousel-inner">
                	<!--以下图片、链接等请至后台轮播图片中上传即可，无需修改此处-->
                    {cms:rs table="cms_ad" where="cid=1 and isshow=1" order="ordnum,id"}
                    	{rs:eof}
                        <div class="ui-carousel-item active"><a href="https://www.nicemb.com/cms-pay" target="_blank" title="极品模板-内容付费管理系统"><img src="/upfile/pc/1.jpg" alt="内容付费管理系统" /></a></div>
                        <div class="ui-carousel-item"><a href="https://www.nicemb.com/cms-order" target="_blank" title="极品模板-微商城订单管理系统"><img src="/upfile/pc/2.jpg" alt="微商城订单管理系统" /></a></div>
                    	{/rs:eof}
                        <div class="ui-carousel-item{if $i==1} active{/if}"><a href="{$rs.url}" target="_blank"><img src="{$rs.pic}" alt="{$rs.name}" /></a></div>
                    {/cms:rs}
                </div>
            </div>
        </div>
        
        <div class="banner-footer">
            <div class="notice">
            	<a class="ui-btn yellow outline small ui-mr-15" href="{cateurl(1)}">公告</a>
            	<div class="ui-scroll" data-time="5">
                    <ul>
                    	<!--cateid="1"中的1为栏目ID，请自行修改-->
                    	{cms:rs from="cms_show" where="isshow=1" cateid="1" order="ordnum desc,id desc"}
                    	<li><a href="{$rs.link}" title="{$rs.title}">{$rs.title}</a></li>
                        {/cms:rs}
                    </ul>
                </div>
            </div>
            
            <div class="search">
            	<form action="{N('search')}" method="post">
                	{php $pathinfo=cms[pathinfo]}
					{if !empty($pathinfo) && cms[url_mode]>1}
                    <input type="hidden" name="s" value="search{cms[url_ext]}" />
                    {/if}
                    {if cms[url_mode]==1}
                    <input type="hidden" name="c" value="other" />
                    <input type="hidden" name="a" value="search" />
                    {/if}
                    <input type="hidden" name="token" value="{$token}">
                	<input type="text" name="keyword" placeholder="请输入关键字" data-rule="关键字:required;"><button type="submit" class="ui-form-submit" title="搜索"><i class="ui-icon-search"></i></button>
                </form>
            </div>
            
        </div>
        
    </div>
    
    {cms:rp top="8" from="cms_class" where="followid=0 and ismenu=1 and cate_type=0" order="cate_order,cateid" auto="j"}
    {php $cateid=$rp.cateid}
    <div class="width ui-mt-20">
    	<div class="home-subject">{$rp.cate_name}</div>
        <div class="home-nav">
        	{cms:rs top="10" from="cms_class" where="followid=$cateid and ismenu=1" order="cate_order,cateid"}
            <a href="{cateurl($rs.cateid)}"{if $rs.isnew==1} target="_blank"{/if}>{$rs.cate_name}</a></li>
            {/cms:rs}
        </div>
        
        <div class="temp-list pro-lists ui-mt-20">
        	<div class="temp-list-wrap">
            	{cms:rs top="8" table="cms_data a left join cms_show b on a.cid=b.id" join="left join cms_user c on b.userid=c.atid" where="isshow=1" cateid="$cateid" order="ordnum desc,id desc"}
            	<div class="temp-list-item">
                	{if $rs.isnice==1}<div class="ui-ring left green small"><span>推荐</span></div>{/if}
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
    {/cms:rp}
    
    <div class="ui-bg-white ui-pt-20 ui-pb-20">
    	<div class="width">
        	<div class="home-subject">友情链接</div>
            <div class="link">
            	{cms:rs top="0" table="cms_link" where="isshow=1" order="ordnum,id"}
                <a href="{$rs.url}" class="ui-tips" data-title="{$rs.name}" target="_blank">{$rs.name}</a>
                {/cms:rs}
            </div>
        </div>
    </div>

    {include file="foot.php"}
</body>
</html>