<?php
/**
 * 作用：路由类
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

final class cms_route
{
	#url重组解决方案
	static function url($key,$str)
	{
		$key=trim($key,'/');
		if($key=='')
		{
			return '';
		}
		$route=D(CMS_ROUTE);
		$arr=explode('/',$key);
		if(count($arr)==1)
		{
			$key=M_NAME.'/'.C_NAME.'/'.$key;
		}
		elseif(count($arr)==2)
		{
			$key=M_NAME.'/'.$key;
		}
		$data=[];
		if(is_array($route) && count($route)>0)
		{
			if(is_string($str))
			{
				parse_str($str,$data);
			}
			elseif(!is_array($str))
			{
				$data=[];
			}
			$url=self::find($key,$route,$data);
			$url=$url?$url:'';
			return str_replace('/',config('url_mid'),$url);
		}
		else
		{
			return '';
		}
	}

	private static function find($key,$route,$data)
	{
		$url=array_search($key,$route);
		unset($route[$url]);
		if($url)
		{
			foreach($data as $kk=>$val)
			{
				$url=str_replace(['[:'.$kk.']','<'.$kk.'?>',':'.$kk,'<'.$kk.'>'],$val,$url);
			}
			if(strpos($url,":"))
			{
				$url=self::find($key,$route,$data);
			}
			return $url;
		}
	}

	#自定义路由处理
	static function route($url)
	{
		$url=str_replace(config('url_mid'),'/',$url);
		$route=D(CMS_ROUTE);

		if(is_array($route) && count($route)>0)
		{
			if(isset($route[$url]))
			{
				define('ROUTE_KEY',$route[$url]);
				return $route[$url];
			}
			$k=[];
			foreach($route as $key=>$val)
			{
				$arr=explode('/',$key);
				$t=[];
				foreach($arr as $v)
				{
					if(substr($v,0,1)==":")
					{
						$t[]="\/(?<".substr($v,1,strlen($v)-1).">[0-9a-zA-Z-,]*)";
					}
					else
					{
						$t[]=$v;
					}
				}
				$k[$key]=join($t).'$';
			}
			foreach($k as $key=>$val)
			{
				if(preg_match('/^'.$val.'/u',$url,$match))
				{
					#根据key获取对应的路由隐射
					$str=$route[$key];
					define('ROUTE_KEY',$route[$key]);
					foreach($match as $kk=>$vv)
					{
		                if(is_string($kk)) 
		                {
		                	$str.="/".$kk."/".$vv;
		                }
		            }
		            $str=str_replace('/',config('url_mid'),$str);
	                return $str;
	            }
			}
		}
		define('ROUTE_KEY','');
		return '';
	}

	static function geturl()
	{
		$pathinfo=config('pathinfo');
		if(!empty($pathinfo))
		{				
			$pathinfo=F('get.'.$pathinfo);
		}
		else
		{
			if(!isset($_SERVER['PATH_INFO']))
			{
				$_SERVER['PATH_INFO']='';
			}
			if(!isset($_SERVER['ORIG_PATH_INFO']))
			{
				$_SERVER['ORIG_PATH_INFO']='';
			}
			$pathinfo='';
			if(!empty($_SERVER['PATH_INFO']))
			{
				$pathinfo=$_SERVER['PATH_INFO'];
			}
			else
			{
				if(!empty($_SERVER['ORIG_PATH_INFO']))
				{
					$pathinfo=$_SERVER['ORIG_PATH_INFO'];
				}
			}
		}

		$pathinfo=str_replace($_SERVER['SCRIPT_NAME'],'',$pathinfo);
		$url_ext=config('url_ext');
		if(!empty($url_ext))
		{
			if(strpos($pathinfo,$url_ext))
			{
				$pathinfo=substr($pathinfo,0,(strlen($pathinfo)-strlen($url_ext)));
			}
		}

		$pathinfo=str_replace(['/','_'],config('url_mid'),$pathinfo);
		$pathinfo=trim($pathinfo,config('url_mid'));
		$pathinfo=trim($pathinfo,'/');
		$pathinfo=str_replace(['../',"'","%27"],'',$pathinfo);
		return $pathinfo;
	}

	static function check($route,$alias)
	{
		if(isset($route['m']))
		{
			#先检查是否为别名
			if(isset($alias[$route['m']]))
			{
				$route['alias']=$route['m'];
				$d=$alias[$route['m']];
				$route['m']='home';
				if($d['types']==1)
				{
					switch($d['app'])
					{
						case 'goods':
							$route['c']='goods';
							$route['a']='show';
							$route['id']=$d['sid'];
							$route['param']='id';
							$_GET['id']=$d['sid'];
							break;
						case 'class':
							$route['c']='index';
							$route['a']='cate';
							$route['id']=$d['sid'];
							$route['param']='id';
							$_GET['classid']=$d['sid'];
							break;
						case 'show':
							$route['c']='index';
							$route['a']='show';
							$route['id']=$d['sid'];
							$route['param']='id';
							$_GET['id']=$d['sid'];
							break;
					}
				}
				else
				{
					$str=explode('/',$d['app']);
					$route['c']=$str[0];
					$route['a']=$str[1];
				}
			}
		}
		return $route;
	}
	
	static function init($a)
	{
		$route=[];
		$alias=[];
		$alias_key=[];
		if($a)
		{
			#加载系统配置
			if(cms_cache::check('alias'))
			{
				$alias=cms_cache::get('alias');
			}
			else
			{
				$alias=cms_cache::set('alias',cms::alias());
			}
			$alias_key=self::get_alias($alias);
		}
		
		if(config('url_mode')==1)
		{
			$pathinfo=$_SERVER['QUERY_STRING'];
			$pathinfo=str_replace(['../',"'","%27"],'',$pathinfo);
			parse_str($pathinfo,$route);
			if(isset($route['m']))
			{
				$routes=D(CMS_ROUTE);
				if(is_array($routes) && count($routes)>0)
				{
					if(isset($routes[$route['m']]))
					{
						$v=$routes[$route['m']];
						$arr=explode('/',$v);
						$route['m']=$arr[0];
						$route['c']=$arr[1];
						$route['a']=$arr[2];
					}
				}
			}
			$route=self::check($route,$alias);
		}
		else
		{
			$pathinfo=self::geturl();

			#通过正则提取page页数
			$m=(config('url_mid')=='/')?'\\'.config('url_mid'):config('url_mid');
			$num=preg_match_all("/(.+?)".$m."page".$m."(\d+)/s",$pathinfo,$match);
			if($num)
			{
				#页数赋值给数组
				$_GET['page']=$match[2][0];
				$pathinfo=$match[1][0];
			}
			
			if(empty($pathinfo))
			{
				$pathinfo=str_replace('/',config('url_mid'),cms::$url);
			}
			else
			{
				$pinfo=self::route($pathinfo);
				#修复url间隔符不是/造成部分页面打不开的BUG
				$pinfo=str_replace('/',config('url_mid'),$pinfo);
				if($pinfo)
				{
					$pathinfo=$pinfo;
				}
				else
				{
					#将$_GET参数添加到$pathinfo中
					if(is_array($_GET))
					{
						#剔除掉s变量
						$pinfo=config('pathinfo');
						if(!empty($pinfo))
						{				
							unset($_GET[$pinfo]);
						}
						foreach($_GET as $key=>$val)
						{
							#剔除微信分享加的字符串，以及为空的变量
							if(!($key=='from' && $val=='singlemessage') && $val!='')
							{
								$pathinfo=$pathinfo.config('url_mid').$key.config('url_mid').$val;
							}
						}
					}

					#内容页映射
					$num=preg_match_all("/\/([0-9a-zA-Z_-]*?)$m(\d+)/is",'/'.$pathinfo,$match);
					if($num)
					{
						#获取ID所在栏目ID是否为别名对应栏目的子栏目
						if(array_key_exists(strtolower($match[1][0]),$alias_key))
						{
							$route['alias']=substr($match[0][0],1);
							$route['param']='id';
							$_GET['id']=$match[2][0];
							$pathinfo='home/index/show/id/'.$match[2][0];
							$pathinfo=str_replace('/',config('url_mid'),$pathinfo);
							define('ALIAS_CLASS',$match[1][0]);
						}
					}

				}
			}
			
			$arr=explode(config('url_mid'),$pathinfo);


			$route['m']=array_shift($arr);

			if($route['m']=='plug')
			{
				$route['p']=array_shift($arr);
				$route['c']=array_shift($arr);
				$route['a']=array_shift($arr);
			}
			else
			{
				#先检查是否为别名
				$route['m']=strtolower($route['m']);
				if(isset($alias[$route['m']]))
				{
					$route=self::check($route,$alias);
				}
				else
				{
					$route['c']=array_shift($arr);
					$route['a']=array_shift($arr);
				}
			}
			if(count($arr) % 2!=0)
			{
				cms::error('Url Error');
			}
			else
			{
				$step=1;
				foreach($arr as $k=>$v)
				{
					if($step % 2!=0)
					{
						#修复搜索汉字出错的Bug
						$result=$arr[$k+1];
						$result=rawurldecode($result);
						$encode=mb_detect_encoding($result,['UTF-8','GBK','GB2312']);
						if($encode!='UTF-8')
						{
							$result=mb_convert_encoding($result,'utf-8',$encode);   
						}
						$route[$arr[$k]]=$result;
					}
					$step++;
				}
				
				#删除为空的变量
				foreach($_GET as $k=>$v)
				{
					if(trim($v)=='')
					{
						unset($_GET[$k]);
					}
				}
				$_GET=array_merge($_GET,$route);
			}
		}
		
		if(empty($route['m']))
		{
			$route['m']='home';
		}
		if(empty($route['c']))
		{
			$route['c']='index';
		}
		if(empty($route['a']))
		{
			$route['a']='index';
		}
		if(empty($route['p']))
		{
			$route['p']='';
		}
		foreach($route as $key=>$val)
		{
			$route[enhtml($key)]=enhtml($val);
		}
		cms::$url=$pathinfo;
		cms::$route=$route;
		unset($pathinfo);
		return $route;
	}

	private static function get_alias($alias)
	{
		foreach($alias as $key=>$val)
		{
			#剔除掉非栏目和内容页的别名
			if($val['types']==0)
			{
				unset($alias[$key]);
			}
		}
		return $alias;
	}

}