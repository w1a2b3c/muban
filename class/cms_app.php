<?php
/**
 * 作用：接口类
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

class cms_app
{
   protected static $name;
   static function init($type,$name='')
   {
      switch($type)
      {
         case 'cache':
         case 'auth':
         case 'sms':
            $name=($name!='')?$name:config($type.'_state');
            break;
      }
      cms::load($name,'plug');
      $class=$type.'_'.$name;
      self::$name=new $class();
   }

	static function __callstatic($a,$b)
	{
		return call_user_func_array(array(self::$name,$a),$b);
	}

}