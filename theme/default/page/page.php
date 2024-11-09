<?php if(!defined('IN_CMS')) exit;?>{include file="head.php"}

    <div class="width ui-mt-20">
    	<div class="ui-bread">
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
                        {loop data="$cate_data" val="rs"}
                        <li{if is_active($rs.cateid,$parentid)} class="active"{/if}><a href="{cateurl($rs.cateid)}">{$rs.cate_name}</a></li>
                        {/loop}
                    </ul>
                </div>
                <!---->
        	</div>
            <div class="page_single_right">
        		
                <div class="page_title">{$cate_name}</div>
                <div class="page_content">
                    <!---->
                    {$content}
                    <!---->
                </div>
                
        	</div>
        </div>
    </div>
    
    {include file="foot.php"}

</body>
</html>