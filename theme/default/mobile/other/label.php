<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-goods-body">
    	<div class="ui-menu blue">
        	<div class="ui-menu-name">{$page_name}</div>
        </div>
        
        <div class="content">
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
    
    

    
</body>
</html>