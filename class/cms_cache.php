<?php
/**
 * 作用：缓存类
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

class cms_cache
{
   protected static $name;
   static function init($type='',$root='')
   {
      $type=($type!='')?$type:config('cache_state');
      cms::load($type,'cache');
      $class="cache_".$type;
      self::$name=new $class($root);
   }

   static function __callstatic($a,$b)
   {
      return call_user_func_array(array(self::$name,$a),$b);
   }

}