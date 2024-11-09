<?php if(!defined('IN_CMS')) exit;?>{include file="head.php"}
    
    <div class="width ui-mt-20">
		
        <div class="tempshow">
        	<div class="tempshow-left">
                <div class="artshow">
					<!---->
                    {if $pid==0}
                    <div class="art-title">
                    	<h1>{$title}</h1>
                    </div>
                    <div class="temp-name ui-mt-20">全部评论{if $comments>0} {get_num($comments)}{/if}</div>
                    {else}
                    <div class="ui-comment">
                        <ul class="ui-media-list ui-media-border-none">
                            {cms:rs top="1" table="$table" where="state=1 and aid=$pid"}
                            {php $num=getint(C('comment_'.$rs.aid))}
                            <li class="ui-media ui-pl ui-pr">
                                <div class="ui-media-img ui-mr-20 ui-radius">
                                    <img src="{$rs.uface}" width="50" height="50" alt="{$rs.uname}">
                                </div>
                                <div class="ui-media-body">
                                    <div class="ui-media-header ui-bold">{$rs.uname} （楼主）</div>
                                    <div class="ui-media-text">{BR($rs.content)}</div>
                                    <div class="ui-mt ui-text-gray ui-font-12">{$rs.postcity}网友　{get_date($rs.postdate)}　<a href="javascript:;" class="ui-comment-reply" data-pid="{$rs.aid}" data-name="{$rs.uname}">回复</a></div>
                                </div>
                                <div class="ui-media-link ui-media-center"><a href="javascript:;" class="ui-icon-like ui-comment-like{if $num==1} ui-text-red{/if}" data-url="{U('like','aid='.$rs.aid.'')}">{if $rs.likenum>0}{$rs.likenum}{/if}</a></div>
                            </li>
                            {/cms:rs}
                        </ul>
                        <div class="ui-menu ui-mb-20 ui-mt-20">
                            <div class="ui-menu-name">回复 {get_num($rs.sonnum)}</div>
                        </div>
                    </div>
                    {/if}
                    
                    <div class="ui-comment">
                        <ul class="ui-media-list ui-media-border-none">
                            {cms:rp pagesize="20" table="$table" where="$where" order="$order" key="aid"}
                            {rp:eof}<p class="ui-font-14">暂无评论</p>{/rp:eof}
                            {php $num=getint(C('comment_'.$rp.aid))}
                            {php $pids=$rp.aid}
                            <li class="ui-media ui-pl ui-pr">
                                <div class="ui-media-img ui-mr-20 ui-radius">
                                    <img src="{$rp.uface}" width="50" height="50" alt="{$rp.uname}">
                                </div>
                                <div class="ui-media-body">
                                    <div class="ui-media-header ui-bold">{$rp.uname}</div>
                                    <div class="ui-media-text">{BR($rp.content)}</div>
                                    <div class="ui-mt ui-text-gray ui-font-12">{$rp.postcity}网友　{get_date($rp.postdate)}　<a href="javascript:;" class="ui-comment-reply" data-pid="{$rp.aid}" data-name="{$rp.uname}">回复</a></div>
                                </div>
                                <div class="ui-media-link ui-media-center"><a href="javascript:;" class="ui-icon-like ui-comment-like{if $num==1} ui-text-red{/if}" data-url="{U('like','aid='.$rp.aid.'')}">{if $rp.likenum>0}{$rp.likenum}{/if}</a></div>
                            </li>
                            {cms:rs top="2" table="$table" where="parentid=$pids and state=1" order="aid" auto="j"}
                            {php $nums=getint(C('comment_'.$rs.aid))}
                            <li class="ui-media ui-pl ui-pr">
                                <div class="ui-media-img ui-mr-20 ui-radius" style="padding-left:60px;">
                                    <img src="{$rs.uface}" width="50" height="50" alt="{$rs.uname}">
                                </div>
                                <div class="ui-media-body">
                                    <div class="ui-media-header ui-bold">{$rs.uname}</div>
                                    <div class="ui-media-text">{BR($rs.content)}</div>
                                    <div class="ui-mt ui-text-gray ui-font-12">{$rs.postcity}网友　{get_date($rs.postdate)}
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
                            <div style="padding-left:140px;margin:-20px 0 20px 0;">{if $rp.sonnum>2}<a href="{U('more','pid='.$rp.aid.'')}" class="ui-text-blue">查看全部{$rp.sonnum}条回复</a>{/if}</div>
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
                     <!---->
              	</div>

                <div class="temp-box ui-mt-30 offside-comment">
                	<div class="temp-name">评论</div>
                    
                    {if USER_ID==0}
                    <div class="comment-post">
                    	<div class="comment-post-face"><img src="/upfile/noface.jpg" alt="游客"></div>
                        <div class="comment-post-body">
                        	<div class="comment-post-body-wrap">
                                <div class="comment-post-body-area"><textarea placeholder="说两句"></textarea></div>
                                <div class="comment-post-login"><a href="{N('login')}">登陆</a></div>
                            </div>
                        </div>
                    </div>
                    {else}
                    {if $user_auth==0 && USER_ID>0}
                        您的账号未实名认证，<a href="{U('user/auth')}" class="ui-btn blue small">去认证</a>
                    {else}
                    <form method="post" class="ui-form" action="{U('comment/index','id='.$id.'')}" data-back="{THIS_LOCAL}" data-type="3" data-hide="2" data-color="red">
                    <div class="comment-post">
                    	<div class="comment-post-face"><img src="{$tuser.uface}" alt="{$tuser.uname}"></div>
                        <div class="comment-post-body">
                        	<div class="comment-post-body-wrap">
                                <div class="comment-post-body-area"><textarea placeholder="说两句" name="content" data-rule="评论内容:required;"></textarea></div>
                            </div>
                            <div class="comment-post-action">
                            	<div class="comment-post-action-name">{$tuser.uname}<i class="ui-icon-smile"></i></div>
                                
                                <div class="comment-post-action-btn"><button type="submit" class="ui-btn blue">发表评论</button></div>
                            </div>
                        </div>
                    </div>
                    <div class="ui-comment-emoji ui-hide"></div>
                    <input type="hidden" name="pid" value="{$pid}">
                    <input type="hidden" name="token" value="{$token}">
                    </form>
                    {/if}
                    {/if}
                    
                    <script src="{WEB_THEME}js/comment.js"></script>
                </div>
                
                <!---->
            </div>
            
            <div class="tempshow-right">
            	
                <div class="temp-box">
                	<div class="temp-name">随机推荐</div>
                    <ul class="artlist">
                    	{cms:rs from="cms_show" where="isshow=1" order="rand()"}
                    	<li><a href="{$rs.link}" title="{$rs.title}"><em>{if $i<10}0{/if}{$i}</em>{$rs.title}</a></li>
                        {/cms:rs}
                    </ul>         
                </div>
                
            </div>
            
            
        </div>
        
    </div>

    {include file="foot.php"}
    
    
</body>
</html>