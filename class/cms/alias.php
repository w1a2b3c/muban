<?php
if(!defined('IN_CMS')) exit;

class cms_alias extends cms
{
	#读取别名数据
	function alias()
	{
		$rs=cms::$db->load("select * from cms_alias order by id");
		$data=[];
		foreach($rs as $k=>$v)
		{
			$data[strtolower($v['alias'])]=$rs[$k];
		}
		return $data;
	}
}
