<?php
/**
 * 作用：入口文件
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * 
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * 
 * 本文件请勿使用记事本修改
 * 本文件请勿使用记事本修改
 * 本文件请勿使用记事本修改
 * 
 * ===========================================================================
**/

#错误提示开关(以下选项中，1：开启，0：关闭)
define('ERROR_STATE',0);

ini_set('session.auto_start',1);

ini_set("session.cookie_httponly",1);

#使用SSL时，可以开启本条设置
ini_set('session.cookie_secure',0);

ini_set('session.use_cookies',1);

ini_set('session.use_trans_sid',1);

#时区
date_default_timezone_set("Asia/Shanghai");

#响应头
header("Content-Type:text/html;charset=utf-8");

#跨域设置
#header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST');

#如果需要被其他网站iframe调用，请去掉下面两行(或注释)
header('X-Frame-Options:SAMEORIGIN');
header("Content-Security-Policy:frame-ancestors 'self'");

header('X-XSS-Protection:1;mode=block');

header('X-Content-Type-Options:nosniff');

header('X-Permitted-Cross-Domain-Policies:master-only');

header('X-Download-Options:noopen');

header('Access-Control-Allow-Headers:x-requested-with,content-type');

#PHP版本检查
if(version_compare(PHP_VERSION,'5.4.0','<'))
{
    exit('您使用的Php版本是：'.PHP_VERSION.'，程序要求：php版本必须>=5.4');
}

#加载核心文件
require 'app/cms.php';