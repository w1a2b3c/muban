<?php
/**
 * 作用：日志
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

final class cms_log
{
    static function log($str,$pre='')
    {
        #强制使用file模式
        cms_cache::init('file','data/log');
        $pre=($pre!='')?$pre:'log_';
        cms_cache::set($pre.date('Y-m-d-H-i-s'),$str);
        #切换为原来的模式
        cms_cache::init();
        return true;
    }
     
}