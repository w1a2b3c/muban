<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-goods-body">
    	<div class="ui-menu blue ui-mb-20">
        	<div class="ui-menu-name">{$page_name}</div>
        </div>
        
        <div class="ui-goods ui-mwidth">
            {cms:rs pagesize="20" num="3" from="$table" join="left join cms_user c on b.userid=c.atid" where="$where" order="ordnum desc,id desc"}
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
        
        {if $pg->totalpage>1}
        <!--分页开始-->
        <div class="ui-page mid center ui-mt">
            <ul>{$showpage}</ul>
        </div>
        <!--分页结束-->
        {/if}
       
    </div>

</body>
</html>