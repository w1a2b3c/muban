<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-10">
    	<!---->
        <div class="ui-btn-group blue big full line">
            <a class="ui-btn-group-item{if $type==0} active{/if}" href="{U('post')}">全部</a>
            <a class="ui-btn-group-item{if $type==1} active{/if}" href="{U('post','type=1')}">已发布</a>
            <a class="ui-btn-group-item{if $type==2} active{/if}" href="{U('post','type=2')}">待审核</a>
            <a class="ui-btn-group-item{if $type==3} active{/if}" href="{U('post','type=3')}">未通过</a>
        </div>

        <div class="ui-p ui-bg-white ui-mt-15">
            <ul class="ui-media-list ui-media-border">
            	{cms:rs pagesize="20" num="3" table="cms_show" where="$where" order="id desc"}
                {rs:eof}暂无稿件{/rs:eof}
                <div class="ui-media">
                    <div class="ui-media-body">
                        <div class="ui-media-header"><a href="{U('postedit','id='.$rs.id.'')}">{$rs.title}</a></div>
                        <div class="ui-media-text ui-text-gray">{date('Y-m-d H:i:s',$rs.createdate)}</div>
                        <div class="ui-btn-group blue little ui-mt">
                            <a href="{U('postedit','id='.$rs.id.'')}" class="ui-btn-group-item active">编辑</a>
                            {if $rs.isshow==1}<a href="{$rs.link}" target="_blank" class="ui-btn-group-item">查看</a>{/if}
                            {if $rs.isshow==-1}<a href="javascript:;" class="ui-btn-group-item ui-tips" data-color="red" data-title="{$rs.reason}">查看原因</a>{/if}
                        </div>
                    </div>
                    <div class="ui-media-arrow ui-media-center">
                        {if $rs.isshow==0}
                            <span class="ui-badge ui-mr">待审核</span>
                        {elseif $rs.isshow==1}
                            <span class="ui-badge green ui-mr">已发布</span>
                        {else}
                            <span class="ui-badge yellow ui-mr">未通过</span>
                        {/if}
                    </div>
                </div>
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