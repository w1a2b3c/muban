<?php
/**
 * 作用：Hook
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

final class cms_hook
{
    static $hooks=[];

    static function init($plug)
    {
        foreach($plug as $key=>$val)
        {
            $root=$val['root'];
            if(is_file("plug/$root/app/hook.php"))
            {
                cms::load($root,'hook');
                $class="plug_".$root;
                new $class();
            }
        }
    }

    #预埋Hook
    static function reg($name,$ref,$method)
    {
        $name=strtolower($name);
        $class=get_class($ref);
        cms_hook::$hooks[$name][$class]=$method;
    }

    #监听并执行hook
    static function add($name,$ref=null,&$param=null)
    {
        $name=strtolower($name);
        if(isset(cms_hook::$hooks[$name]))
        {
            foreach(cms_hook::$hooks[$name] as $key=>$val)
            {
                $class=new $key($ref);
                if(method_exists($class,$val))
                {
                    $class->$val($param);
                }
            }
        }
    }

}