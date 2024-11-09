<?php
if(!defined('IN_CMS')) exit;

class cms_bot extends cms
{
	function bot()
	{
		$agent=isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'nothing';
		if($agent=='nothing')
		{
			return;
		}
		$list='Pandalytics|oBot|Neevabot|CCBot|SEOkicks|Timpibot|AwarioBot|DataForSeoBot|HTTrack|SemrushBot|python|MJ12bot|AhrefsBot|AhrefsBot|hubspot|opensiteexplorer|leiki|webmeup|Apache-HttpClient|harvest|audit|dirbuster|pangolin|nmap|sqln|hydra|Parser|libwww|BBBike|sqlmap|w3af|owasp|Nikto|fimap|havij|zmeu|BabyKrokodil|netsparker|httperf';
		$data=explode('|',trim($list,'|'));
		foreach($data as $key=>$val)
		{
			if(strpos($agent,$val))
			{
			    header("HTTP/1.1 404 Not Found");
				header("Status: 404 Not Found");
				exit;
			}
		}
		
	}
}
