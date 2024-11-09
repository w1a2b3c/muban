<?php if(!defined('IN_CMS')) exit;?><div class="footer">
    <div class="copyright">
        <div class="copyright-body">
            <div>{cms[web_name]}版权所有 © {date('Y')} Inc.　<a href="{N('sitemap')}">网站地图</a>　<a href="{N('label')}">Tags标签</a></div>
            <div><a href="{cms[icp_url]}" target="_blank" rel="nofollow">{cms[icp_num]}</a>　{if cms[ga_num]}<a href="{cms[ga_url]}" target="_blank" rel="nofollow"><img src="{WEB_ROOT}public/images/ga.png" class="ui-mr-sm" title="公安备案">{cms[ga_num]}</a>　{/if}{cms[web_count]}</div>
        </div>
    </div>
</div>

<div class="ui-sidebar">
    <ul>
        {loop data="$qqlist"}
        <li><a href="javascript:addqq('{$val.qq}')" title="{$val.name}" rel="nofollow"><i class="ui-icon-qq"></i></a><div>{$val.name}</div></li>
        {/loop}
        {if cms[ct_weixin]}<li><a href="javascript:;" title="微信扫码" rel="nofollow"><i class="ui-icon-weixin"></i><div class="image"><img src="{cms[ct_weixin]}" title="微信扫码" />使用【微信】扫一扫加好友</div></a></li>{/if}
        {if cms[ct_tel]}<li><a href="javascript:;" title="客服电话" rel="nofollow"><i class="ui-icon-phone"></i></a><div>电话：{cms[ct_tel]}</div></li>{/if}
        <li class="ui-backtop"><a href="javascript:;" title="返回顶部" rel="nofollow"><i class="ui-icon-top"></i></a><div>返回顶部</div></li>
    </ul>
</div>
{hook name="footer"}