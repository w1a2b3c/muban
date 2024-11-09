<?php
if(!defined('IN_CMS')) exit;

class cms_debug extends cms
{
	function debug()
	{
		if(ERROR_STATE==1)
		{
		    ini_set('display_errors','On');
		    error_reporting(E_ALL);
		}
		else
		{
		    ini_set('display_errors','Off');
		    error_reporting(0);
		}
	}
}
