<?php
/**
 * 控制器
 * By IT平民
**/

class PlugController extends Controller
{
	function _initialize()
	{
		if(IS_POST)
		{
			if(config('app_plug_token'))
			{
				$token=F('token');
				if($token!=S("_token_plug_"))
				{
					$this->error('Token检验失败,请刷新重试');
					exit();
				}
			}
		}

		$plugname=P_NAME;
		$this->tp->themeRoot="plug/$plugname/view/";
		$this->tp->cacheRoot=config('cache_dir')."/plug/$plugname/";
		#是否后台只读模式
		define('APP_DEMO',(get_admin_info('readonly')==1?true:false));
	}

	function check_login()
	{
		#禁止手机自适应模板
		define('SYS_MOBILE',false);
		
		if(ADMIN_ID==0)
		{
			exit('没有管理权限');
		}
		$mname=S('admindir');
		if($mname=='')
		{
			exit('登录超时');
		}
		if(getint(get_admin_info('pid'))!=0)
		{
			define('PAGE_LEVER',get_admin_info('page_list'));
			$page_lever=PAGE_LEVER;
			if(empty($page_lever))
			{
				exit('没有管理权限');
			}
			else
			{
				$rs=$this->db->load("select cname from cms_admin_menu where followid>0 and id in($page_lever)");
				if($rs)
				{
					foreach($rs as $key=>$value)
					{
						$rs[$key]=$mname.'/'.$value['cname'];
					}
					if(!(in_array(''.$mname.'/plug',$rs)))
					{
						exit('没有管理权限');
					}
				}
			}
		}
	}
	
	function _before_action()
	{
		if(IS_POST && defined('APP_DEMO'))
		{
			if(APP_DEMO)
			{
				$this->success('操作成功！！');
				exit();
			}
		}
	}

	function _after_action()
	{
		if(IS_POST && !defined('SYS_LOG'))
		{
			$this->api->log($this->msg);
		}
	} 

}