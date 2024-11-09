<?php
if(!defined('IN_CMS')) exit;

class cms_config extends cms
{
	#读取系统配置数据
	function config()
	{
		$rs=cms::$db->load("select ckey,cvalue from cms_config where islock=1 and ctype>0 order by id");
		$data=[];
	    foreach($rs as $c)
	    {
	        $data[strtoupper($c['ckey'])]=$c['cvalue'];
	    }
	    return $data;
	}
}
