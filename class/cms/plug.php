<?php
if(!defined('IN_CMS')) exit;

class cms_plug extends cms
{
	#读取插件数据
	function plug()
	{
		$rs=cms::$db->load("select id,name,root,state,version,config from cms_plug where state=1 order by id");
		$data=[];
		foreach($rs as $k=>$v)
		{
			$v['config']=D($v['config'],1);
			$data[strtolower($v['root'])]=$v;
		}
		return $data;
	}
}
