<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-10">
    	<!---->
        <div class="ui-p ui-bg-white ui-mt-15">
            <ul class="ui-media-list ui-media-border">
            {cms:rs pagesize="20" num="3" table="cms_user_buy a left join cms_show b on a.cid=b.id" where="$where" order="aid desc" key="aid"}
            	{rs:eof}暂无购买{/rs:eof}
                <li class="ui-media">
                    <div class="ui-media-body">
                        <div class="ui-media-header"><a href="{$rs.link}" target="_blank">{$rs.title}</a></div>
                        <div class="ui-media-text ui-text-gray">{date('Y-m-d H:i:s',$rs.postdate)}</div>
                    </div>
                    <div class="ui-media-link">
                    	<span class="ui-text-red">{$rs.cprice}</span> 元
                    </div>
                </li>
            {/cms:rs}
            </ul>
        </div>
        <div class="ui-page center ui-mt ui-mb">
            <ul>{$showpage}</ul>
        </div>
        <!---->
    </div>

</body>
</html>