<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

	<div class="ui-mwidth ui-pl-15 ui-pr-15 ui-bg-white">
        <div class="art-info">
            <div class="user"><img src="{$user.uface}"></div>
            <div class="info">{$user.uname}<span>{$user.uintro}</span></div>
        </div>
    </div>
    
    <div class="ui-goods ui-mwidth">
        {cms:rs pagesize="16" from="$table" join="left join cms_user c on b.userid=c.atid" where="$where" order="ordnum desc,id desc"}
        <a class="ui-goods-item" href="{$rs.link}" title="{$rs.title}">
        	{if $rs.isnice==1}<div class="ui-ring green little"><span>推荐</span></div>{/if}
        	{if $rs.ispic==1}<div class="image"><img data-original="{$rs.pic}" src="/public/images/spacer.gif" alt="{$rs.title}"></div>{/if}
        	<div class="body">
            	<div class="name">{$rs.title}</div>
                <div class="desc ui-text-hide">{$rs.intro}</div>
                <div class="foot"><div class="info"><em>{if $rs.payway==1}免费{elseif $rs.payway==2}VIP{else}付费{/if}</em></div></div>
            </div>
        </a>
        {/cms:rs}
    </div>
    
    {if $pg->totalpage>1}
    <!--分页开始-->
    <div class="ui-page mid center ui-mt">
        <ul>{$showpage}</ul>
    </div>
    <!--分页结束-->
    {/if}

</body>
</html>