<?php if(!defined('IN_CMS')) exit;?>{include file="top.php"}

	<div class="header-wrap ui-fixed-tops">
    	<div class="header">
        	<div class="header-left">
                <div class="logo"><a href="{WEB_URL}" title="{cms[web_name]}"><img src="{cms[web_logo]}" alt="{cms[web_name]}"></a></div>
                <div class="ui-nav nav">
                    <ul>
                        <li{if IS_HOME} class="active"{/if}><a href="{WEB_URL}">首页</a></li>
                        {cms:rp top="10" from="cms_class" where="followid=0 and ismenu=1" order="cate_order,cateid" cache="true"}
                        {php $ppid=$rp.cateid}
                        <li{if is_active($rp.cateid,$parentid)} class="active"{/if}><a href="{cateurl($rp.cateid)}"{if $rp.isnew==1} target="_blank"{/if}>{$rp.cate_name}</a>
                        	<ul>
                        	{cms:rs top="10" from="cms_class" where="followid=$ppid and ismenu=1" order="cate_order,cateid" cache="true"}
                            <li><a href="{cateurl($rs.cateid)}"{if $rs.isnew==1} target="_blank"{/if}>{$rs.cate_name}</a></li>
                            {/cms:rs}
                            </ul>
                        </li>
                        {/cms:rp}
                    </ul>
                </div>
            </div>
            
            <div class="header-right">
            	{if cms[mobile_domain]}
            	<div class="toplink">
                    <a href="{cms[mobile_http]}{cms[mobile_domain]}" target="_blank"><i class="ui-icon-mobile"></i>手机站</a>
                </div>
                {/if}
            	{if USER_ID==0}
                <div class="nologin">
                	<a href="{N('login')}">登录</a><a href="{N('reg')}" class="active">免费注册</a>
                </div>
                {else}
                <div class="islogin">
                    {php $tuser=D(USER_INFO);}
                    <div class="account">
                    	<a href="{N('user')}"><img src="{$tuser.uface}">{$tuser.uname}<i class="ui-icon-down ui-ml-sm"></i></a>
                        <div class="panel">
                            <a class="panel-header" href="{N('user')}">
                                <div class="face"><img src="{$tuser.uface}"></div>
                                <div class="info">{$tuser.uname}<span>{$tuser.umobile}</span></div>
                            </a>
                            <ul class="panel-list">
                                <li><a href="{U('user/order')}"><i class="ui-icon-shopping"></i>我的订单</a></li>
                                <li><a href="{U('user/money')}"><i class="ui-icon-moneycollect"></i>我的佣金<span>{getprice($tuser.umoney)}</span></a></li>
                                {if config('user_post')==1}<li><a href="{U('user/post')}"><i class="ui-icon-file-word"></i>我的稿件</a></li>{/if}
                                <li><a href="{N('out')}" class="ui-confirm" data-title="确定要退出？"><i class="ui-icon-logout"></i>退出登录</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                {/if}
            </div>
            
        </div>
    </div>