<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}
    {if $pid==0}
    <div class="ui-mwidth ui-pl-15 ui-pr-15">
        <div class="ui-mt-20"><h1 class="ui-font-20">{$title}</h1></div>
        <div class="ui-menu blue ui-mb-20 ui-mt-20">
            <div class="ui-menu-name">全部评论 {get_num($comments)}</div>
        </div>
    </div>
    {else}
    <div class="ui-p-15">
        <div class="ui-mwidth ui-comment">
            <ul class="ui-media-list ui-mt-20 ui-media-border-none">
                {cms:rs top="1" table="$table" where="state=1 and aid=$pid"}
                {php $num=getint(C('comment_'.$rs.aid))}
                <li class="ui-media ui-pr">
                    <div class="ui-media-img ui-mr-10 ui-radius">
                        <img src="{$rs.uface}" width="40" height="40">
                    </div>
                    <div class="ui-media-body">
                        <div class="ui-media-header ui-bold">{$rs.uname} （楼主）</div>
                        <div class="ui-media-text">{BR($rs.content)}</div>
                        <div class="ui-mt ui-text-gray ui-font-12">{$rs.postcity} {get_date($rs.postdate)} <a href="javascript:;" class="ui-comment-reply" data-pid="{$rs.aid}" data-name="{$rs.uname}">回复</a></div>
                    </div>
                    <div class="ui-media-link ui-media-center"><a href="javascript:;" class="ui-icon-like ui-comment-like{if $num==1} ui-text-red{/if}" data-url="{U('like','aid='.$rs.aid.'')}">{if $rs.likenum>0}{$rs.likenum}{/if}</a></div>
                </li>
                {/cms:rs}
            </ul>
            <div class="ui-menu blue ui-mb-20 ui-mt-20">
                <div class="ui-menu-name">回复 {get_num($rs.sonnum)}</div>
            </div>
        </div>
    </div>
    {/if}
    
    <div class="ui-mwidth ui-comment ui-p-15">
        <ul class="ui-media-list ui-media-border-none">
            {cms:rp pagesize="20" table="$table" where="$where" order="$order" key="aid"}
            {php $num=getint(C('comment_'.$rp.aid))}
            {php $pids=$rp.aid}
            <li class="ui-media ui-pr">
                <div class="ui-media-img ui-mr-10 ui-radius">
                    <img src="{$rp.uface}" width="40" height="40" alt="{$rp.uname}">
                </div>
                <div class="ui-media-body">
                    <div class="ui-media-header ui-bold">{$rp.uname}</div>
                    <div class="ui-media-text">{BR($rp.content)}</div>
                    <div class="ui-mt ui-text-gray ui-font-12">{$rp.postcity} {get_date($rp.postdate)} <a href="javascript:;" class="ui-comment-reply" data-pid="{$rp.aid}" data-name="{$rp.uname}">回复</a></div>
                </div>
                <div class="ui-media-link ui-media-center"><a href="javascript:;" class="ui-icon-like ui-comment-like{if $num==1} ui-text-red{/if}" data-url="{U('like','aid='.$rp.aid.'')}">{if $rp.likenum>0}{$rp.likenum}{/if}</a></div>
            </li>
            {cms:rs top="2" table="$table" where="parentid=$pids and state=1" order="aid" auto="j"}
            {php $nums=getint(C('comment_'.$rs.aid))}
            <li class="ui-media ui-pr">
                <div class="ui-media-img ui-mr-10 ui-radius" style="padding-left:20px;">
                    <img src="{$rs.uface}" width="40" height="40" alt="{$rs.uname}">
                </div>
                <div class="ui-media-body">
                    <div class="ui-media-header ui-bold">{$rs.uname}</div>
                    <div class="ui-media-text">{BR($rs.content)}</div>
                    <div class="ui-mt ui-text-gray ui-font-12">{$rs.postcity} {get_date($rs.postdate)}　
                    {if $rs.sonnum>0}　
                    <a href="{U('more','pid='.$rs.aid.'')}"><span class="ui-badge">{get_num($rs.sonnum)} 回复</span></a>
                    {else}
                    <a href="javascript:;" class="ui-comment-reply" data-pid="{$rs.aid}" data-name="{$rs.uname}">回复</span></a>
                    {/if}
                    </div>
                </div>
                <div class="ui-media-link ui-media-center"><a href="javascript:;" class="ui-icon-like ui-comment-like{if $nums==1} ui-text-red{/if}" data-url="{U('like','aid='.$rs.aid.'')}">{if $rs.likenum>0}{$rs.likenum}{/if}</a></div>
            </li>
            {/cms:rs}
            <div style="padding-left:140px;margin:-20px 0 20px 0;">{if $rp.sonnum>0}<a href="{U('more','pid='.$rp.aid.'')}" class="ui-text-blue">查看全部{$rp.sonnum}条回复</a>{/if}</div>
            {if $i<$total_rp}<div class="ui-line ui-mb-20"></div>{/if}
            {/cms:rp}
        </ul>
        
        {if $pg->totalpage>1}
        <!--分页开始-->
        <div class="ui-page mid center ui-mt">
            <ul>{$showpage}</ul>
        </div>
        <!--分页结束-->
        {/if}
        
    </div>
    <div class="ui-foot-comment ui-mwidth ui-fixed-bottom">
        <div class="ui-foot-comment-left ui-w-100 ui-comment-add" data-pid="{$pid}">
            <i class="ui-icon-edit ui-mr"></i>写评论
        </div>
    </div>
    
    <div class="ui-offside ui-offside-bottom offside-comment">
    	{if $user_auth==0}
        	您的账号未实名认证，<a href="{U('user/auth')}" class="ui-btn blue small">去认证</a>
        {else}
    	<form method="post" class="ui-form" action="{U('comment/index','id='.$id.'')}" data-back="{THIS_LOCAL}" data-type="3" data-hide="2" data-color="red">
            <div class="ui-form-group">
            	<div class="ui-input-group">
                	<textarea name="content" class="ui-form-ip" data-rule="评论内容:required;"></textarea>
                    <div class="after none ui-align-self-end"><button type="submit" class="ui-btn blue">发表</button></div>
                </div>
            </div>
            <div class="ui-ml"><i class="ui-icon-smile"></i></div>
            <div class="ui-comment-emoji ui-hide"></div>
            <input type="hidden" name="pid" value="{$pid}">
        	<input type="hidden" name="token" value="{$token}">
        </form>
        {/if}
    </div>
	<script src="{WEB_THEME}mobile/js/comment.js"></script>
    <script>
	$("body").addClass("ui-bg-white")
	</script>
</body>
</html>