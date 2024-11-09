<?php if(!defined('IN_CMS')) exit;?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{WEB_ROOT}public/css/ui.css?v={time()}" rel="stylesheet" type="text/css">
    <link href="{WEB_THEME}mobile/css/cms.css?v={time()}" rel="stylesheet" type="text/css">
    <title>{$seo_title}</title>
    <meta name="keywords" content="{$seo_key}">
    <meta name="description" content="{$seo_desc}">
    <script src="{WEB_ROOT}public/js/jq.js"></script>
    <script src="{WEB_ROOT}public/js/ui.js"></script>
    <script src="{WEB_THEME}mobile/js/cms.js"></script>
    <script src="{WEB_ROOT}public/js/qrcode.js"></script>
    <script src="{WEB_ROOT}public/js/pjax.js"></script>
    <script src="{WEB_ROOT}public/js/lazyload.js"></script>
    <script>var cms={token:"{$token}",islogin:"{USER_ID}",login:"{N('login')}",reg:"{N('reg')}"};</script>
    {hook name="header"}
</head>

<body>

	{if IS_HOME}
    <div class="ui-fixed-top">
        <div class="ui-topbar ui-topbar-home ui-mwidth">
            <div class="ui-topbar-logo"><a href="{WEB_ROOT}"><img src="{cms[mobile_logo]}" alt="{cms[web_name]}"></a></div>
            <div class="ui-topbar-search">
                <form action="{U('other/search')}" method="post">
                	{php $pathinfo=cms[pathinfo]}
					{if !empty($pathinfo) && cms[url_mode]>1}<input type="hidden" name="s" value="search{cms[url_ext]}" />{/if}
					{if cms[url_mode]==1}<input type="hidden" name="c" value="index" /><input type="hidden" name="a" value="search" />{/if}
                    <input type="text" name="keyword" maxlength="11" placeholder="请输入关键字" data-rule="关键字:required;">
                    <input type="hidden" name="token" value="{$token}">
                    <button type="submit" class="ui-form-submit" data-type="2"><i class="ui-icon-search"></i></button>
                </form>
            </div>
            <div class="ui-ml-15"><a href="javascript:;" rel="nofollow" class="ui-offside-show" data-target="#offside-nav"><i class="ui-icon-menu ui-text-white"></i></a></div>
        </div>
    </div>
    
    <div class="ui-offside ui-offside-left ui-p-15" id="offside-nav">

        <div class="ui-panel"><a href="{WEB_ROOT}" title="网站首页">网站首页</a></div>
        {cms:rp top="0" table="cms_class" where="followid=0" order="cate_order,cateid"}
		{php $sub_sonid=$rp[cateid]}
		{php $sub_num=get_sonid_num($rp[cateid])}
		<div class="ui-panel {is_active($rp[cateid],$parentid,'active',1)}" data-loop="1">
			<a href="{cateurl($rp[cateid])}" title="{$rp[cate_name]}">{$rp[cate_name]}</a>{if $sub_num>0}<i class="ui-icon-right"></i>{/if}
		</div>
		{if $sub_num>0}
		<div class="ui-panel-body {is_active($rp[cateid],$parentid,'show',1)}">
			<ul>
				{cms:rs top="0" table="cms_class" where="followid=$sub_sonid" order="cate_order,cateid"}
				<li{is_active($rs[cateid],$parentid)}><a href="{cateurl($rs[cateid])}" title="{$rs[cate_name]}"{if $rs[isnew]==1} target="_blank"{/if}><i class="ui-icon-square ui-text-gray ui-font-16 ui-mr"></i>{$rs[cate_name]}</a></li>
				{/cms:rs}
			</ul>
		</div>
		{/if}
		{/cms:rp}
    </div>
    
    {else}
    	{if !isset($pagebar)}
    	<div class="ui-topbar ui-topbar-three ui-mwidth ui-fixed-top">
            <div class="ui-topbar-left"><a class="ui-icon-left ui-back" href="{$backurl}"></a></div>
            <div class="ui-topbar-title ui-text-hide">{$page_name}</div>
            <div class="ui-topbar-right"></div>
        </div>
        {/if}
    {/if}