<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-goods-body">
    	<div class="ui-menu blue">
        	<div class="ui-menu-name">{$page_name}</div>
        </div>
        
        <div class="content">
        	<!--网站地图开始-->
            <div class="ui-timeline yellow">
                {cms:rp top="0" table="cms_class" where="followid=0 and ismenu=1" order="cate_order,cateid"}
                {php $map_sonid=$rp.cateid}
                <div class="ui-timeline-item">
                    <div class="ui-timeline-dot"></div>
                    <div class="ui-timeline-title"><a href="{cateurl($rp.cateid)}" title="{$rp.cate_name}" target="_blank">{$rp.cate_name}</a></div>
                    <div class="ui-timeline-text">
                        {cms:rs top="0" table="cms_class" where="followid=$map_sonid and ismenu=1" order="cate_order,cateid"}
                        <a href="{cateurl($rs.cateid)}" title="{$rs.cate_name}" target="_blank" class="ui-btn color-red ui-mr ui-mb">{$rs.cate_name}</a>
                        {/cms:rs}
                    </div>
                </div>
                {/cms:rp}
            </div>
            <!--网站地图结束-->
    
        </div>
       
    </div>
    
    

    
</body>
</html>