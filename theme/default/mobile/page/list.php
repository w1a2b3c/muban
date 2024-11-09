<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}
    
    {if count($cate_data)>0 || count($filter_data)>0}
    <div class="ui-mwidth">
    	
        <div class="ui-filter">
        	{if count($cate_data)>0}
        	<div class="ui-row">
                <div class="ui-filter-left"><span>分类</span></div>
                <div class="ui-filter-right">
                    <a href="{cateurl($topid)}" {if $classid==$topid} class="active"{/if}>全部</a>
                    {loop data="$cate_data" val="rs"}
                    <a href="{cateurl($rs.cateid)}"{if $rs.isnew==1} target="_blank"{/if} {if is_active($rs.cateid,$parentid)} class="active"{/if}>{$rs.cate_name}</a></li>
                    {/loop}
                </div>
            </div>
            {/if}
            
            {if count($son_data)>0}
        	<div class="ui-row">
                <div class="ui-filter-left">　　</div>
                <div class="ui-filter-right">
                    {loop data="$son_data" val="rs"}
                    <a href="{cateurl($rs.cateid)}"{if $rs.isnew==1} target="_blank"{/if} {if is_active($rs.cateid,$parentid)} class="active"{/if}>{$rs.cate_name}</a></li>
                    {/loop}
                </div>
            </div>
            {/if}
            
            {if count($filter_data)>0 && $classid>0}
        	{loop data="$filter_data"}
        	<div class="ui-row">
                <div class="ui-filter-left">{$key}</div>
                <div class="ui-filter-right">
                	<a href="{filter($classid,$cate_url,'filter='.filter_all($val,$filter).'')}" {filter_check($val,$filter)}>全部</a>
                    {loop data="$val" key="aa" val="bb"}
                    <a href="{filter($classid,$cate_url,'filter='.filter_join($val,$filter,$aa).'')}"{if in_array($aa,explode(',',$filter))} class="active"{/if}>{$bb}</a>
                    {/loop}
                </div>
            </div>
           {/loop}
           {/if}
           
        </div>
    </div>
    {/if}
    
    
    {if $cate_pay<=1}
    <div class="ui-goods ui-mwidth">
    	{cms:rs pagesize="$cate_page" table="$table" join="$join" where="$where" order="ordnum desc,id desc"}
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
    <div class="ui-mwidth ui-mt ui-pl ui-pr">
    	<div class="temp-list">
        	<div class="temp-list-wrap">
                {cms:rs pagesize="$cate_page" table="$table" join="$join" where="$where" order="ordnum desc,id desc"}
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
    
    {if $pg->totalpage>1}
    <!--分页开始-->
    <div class="ui-page mid center ui-mt">
        <ul>{$showpage}</ul>
    </div>
    <!--分页结束-->
    {/if}

</body>
</html>