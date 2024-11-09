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
        
        <!---->
        <div class="temp-list">
        	<div class="temp-list-wrap">
            	{cms:rs pagesize="20" from="$table" join="left join cms_user c on b.userid=c.atid" where="$where" order="ordnum desc,id desc"}
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
        <!---->
        
        {if $pg->totalpage>1}
        <!--分页开始-->
        <div class="ui-page center ui-mb-20">
            <ul>{$showpage}</ul>
        </div>
        <!--分页结束-->
        {/if}
        
    </div>
    
    {include file="foot.php"}

</body>
</html>