<?php
/**
 * 作用：File缓存
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

class cache_file extends cms_cache
{
	protected $root;
	function __construct($root='')
	{
		if($root!='')
		{
			$this->root=$root;
		}
	}
	
	function getName($name='',$type='')
	{
		$root='';
		if($this->root=='')
		{
			$root=(strlen($name)==32)?'cache/data':'cache/config';
			if($type!='')
			{
				$root=$root.'/'.$type;
			}
			mkfolder($root);
		}
		else
		{
			$root=$this->root;
		}
		$ext=(in_array(substr($name,0,4),['log_','api_','sms_','sys_','err_']))?'txt':'php';
		return "$root/$name.$ext";
	}

	function get($name='config',$type='')
	{
		return include($this->getName($name,$type));
	}

	function set($name='config',$data=[],$time=0,$type='')
	{
		$data=($data===false)?[]:$data;
		if(is_array($data))
		{
			$dt="<?php\nif(!defined('IN_CMS')) exit;\nreturn ".var_export($data,true).";\n?>";
		}
		else
		{
			$dt=$data;
		}
		if(!savefile($this->getName($name,$type),$dt))
		{
			exit($this->getName($name).'写入权限不足，请检查');
		}
		return $data;
	}

	function check($name='config',$time=0,$type='')
	{
		$time=(float)$time;
		$name=$this->getName($name,$type);
		if(is_file($name))
		{
			if($time>0)
			{
				if(time()-filemtime($name)>=$time)
				{
					unlink($name);
					return false;
				}
			}
			return true;
		}
		return false;
	}

	function del($name='config')
	{
		if($this->check($name))
		{
			unlink($this->getName($name));
		}
	}

	function del_group($name)
	{
		delfolder(config('cache_dir').'/data/'.$name);
	}

}