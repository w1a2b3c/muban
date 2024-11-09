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
                    <!--网站地图开始-->
                    <div class="ui-timeline blue">
                        {cms:rp top="0" table="cms_class" where="followid=0 and ismenu=1" order="cate_order,cateid"}
                        {php $map_sonid=$rp.cateid}
                        <div class="ui-timeline-item">
                            <div class="ui-timeline-dot"></div>
                            <div class="ui-timeline-title"><a href="{cateurl($rp.cateid)}" title="{$rp.cate_name}" target="_blank">{$rp.cate_name}</a></div>
                            <div class="ui-timeline-text">
                                {cms:rs top="0" table="cms_class" where="followid=$map_sonid and ismenu=1" order="cate_order,cateid"}
                                <a href="{cateurl($rs.cateid)}" title="{$rs.cate_name}" target="_blank" class="ui-btn color-blue ui-mr ui-mb">{$rs.cate_name}</a>
                                {/cms:rs}
                            </div>
                        </div>
                        {/cms:rp}
                    </div>
                    <!--网站地图结束-->
                </div>
                
        	</div>
        </div>
    </div>
    
    {include file="foot.php"}

</body>
</html>