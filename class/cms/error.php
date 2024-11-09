<?php
if(!defined('IN_CMS')) exit;

class cms_error extends cms
{
	#错误提示
	function error($a='',$b='',$c='抱歉，出错了！',$d='立即跳转')
	{
		if($b=='')
		{
			$b=PRE_URL;
		}
		if($b=='close')
		{
			$b='';
		}
		$e=['title'=>$c,'msg'=>$a,'url'=>enhtml($b),'btn'=>enhtml($d)];
		$agent=isset($_SERVER['HTTP_USER_AGENT'])?strtolower($_SERVER['HTTP_USER_AGENT']):'nothing';
		if(strpos($agent,'spider') || strpos($agent,'bot') || strpos($agent,'ia_archiver'))
		{
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
		}
		#预埋Hook
		cms_hook::add('error');
		include 'data/404.php';
		exit();
	}
}
