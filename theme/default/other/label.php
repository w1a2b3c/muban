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
        
		<div class="page_single">
        	<div class="page_single_left">
        		<!---->
                <div class="page_nav">
                    <ul>
                        <li{if A_NAME=='label'} class="active"{/if}><a href="{U('label')}">Tags标签</a></li>
                        <li{if A_NAME=='sitemap'} class="active"{/if}><a href="{U('sitemap')}">网站地图</a></li>
                    </ul>
                </div>
                <!---->
        	</div>
            <div class="page_single_right">
        		
                <div class="page_title">{$page_name}</div>
                <div class="page_content">
                    <!---->
                    {cms:rs pagesize="100" table="cms_tags" where="isshow=1" order="id desc"}
                    <a href="{U('other/tags','id='.$rs.id.'')}" data-title="{$rs.title}" data-align="top-left" target="_blank" class="ui-mb ui-mr ui-btn ui-tips color-red">{$rs.title}</a>
                    {/cms:rs}
                    
                    {if $pg->totalpage>1}
                    <!--分页开始-->
                    <div class="ui-page center ui-mb-20">
                        <ul>{$showpage}</ul>
                    </div>
                    <!--分页结束-->
                    {/if}
                    <!---->
                </div>
                
        	</div>
        </div>
    </div>
    
    {include file="foot.php"}

</body>
</html>