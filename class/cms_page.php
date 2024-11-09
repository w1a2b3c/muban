<?php
/**
 * 作用：分页类
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

final class cms_page
{
	public $totalnum;#总记录数
	private $pagesize;#每页显示多少条
	private $thispage;#当前页数,外部传递的
	public $totalpage;#总页数
	private $url;
	private $config=[
		'home'  => '首页',
		'pre'   => '上一页',
		'next'  => '下一页',
		'last'  => '末页',
	];

	function __construct($totalnum,$totalpage,$pagesize=20,$thispage=1)
	{
		$this->totalnum=$totalnum;
		$this->totalpage=$totalpage;
		$this->pagesize=$pagesize;
		$this->thispage=$thispage;
		$this->url=$this->getParam();
		if($this->totalnum==0)
		{
			$this->totalpage=0;
		}
		if($this->totalpage==0)
		{
			$this->thispage=1;
		}
		if($this->thispage>$this->totalpage)
		{
			$this->thispage=1;
		}
		if(ismobile())
		{
			$this->config=[
				'home'  => '<<',
				'pre'   => '<',
				'next'  => '>',
				'last'  => '>>'
			];
		}
	}
	
	private function getParam()
	{
		$url=$_SERVER["REQUEST_URI"].(strpos($_SERVER["REQUEST_URI"],"?")?"":"?");
		$parse=parse_url(enhtml($url));
		if(isset($parse['query']))
		{
			parse_str($parse['query'],$params);
			unset($params['page']);
			$url=$parse['path'].'?'.http_build_query($params);
		}
		return $url;
	}

	private function getUrl($num)
	{
		if(config('url_mode')==1)
		{
			$str=$this->url.'&page='.$num;
			$str=str_replace("?&","?",$str);
			if($num==1)
			{
				$str=str_replace('&page='.$num.'','',$str);
			}
		}
		else
		{
			$arr=cms::$route;
			if(defined('ROUTE_KEY') && ROUTE_KEY!='')
			{
				unset($arr['m']);
				unset($arr['c']);
				unset($arr['a']);
				unset($arr['p']);
				if($num>1)
				{
					$arr['page']=$num;
				}
				else
				{
					unset($arr['page']);
				}
				return U(ROUTE_KEY,http_build_query($arr));
			}
			$pathinfo=config('pathinfo');
			if(!empty($pathinfo))
			{
				unset($arr[config('pathinfo')]);
			}
			if(!empty($arr['alias']))
			{
				$arr['m']=$arr['alias'];
				unset($arr['c']);
				unset($arr['a']);
				if(isset($arr['param']))
				{
					unset($arr[$arr['param']]);
					unset($arr['param']);
				}
				unset($arr['alias']);
			}
			else
			{
				$arr=array_merge($arr,$_GET);
			}
			$arr['page']=$num;
			if($arr['m']!='plug')
			{
				unset($arr['p']);
			}
			$str='';
			if(isset($arr['m']))
			{
				$str.=$arr['m'].config('url_mid');
				unset($arr['m']);
			}
			if(isset($arr['p']))
			{
				$str.=$arr['p'].config('url_mid');
				unset($arr['p']);
			}
			if(isset($arr['c']))
			{
				$str.=$arr['c'].config('url_mid');
				unset($arr['c']);
			}
			if(isset($arr['a']))
			{
				$str.=$arr['a'].config('url_mid');
				unset($arr['a']);
			}
			$arr=http_build_query($arr,'','&');
			$str.=str_replace(['=','&'],config('url_mid'),$arr);
			$str=str_replace('+',' ',$str);
			$pathinfo=config('pathinfo');
			if(empty($pathinfo))
			{
				$str=(config('url_mode')==2)?(WEB_ROOT.'index.php/'.$str.config('url_ext')):(WEB_ROOT.$str.config('url_ext'));
			}
			else
			{
				$str=(config('url_mode')==2)?(WEB_ROOT.'index.php?'.config('pathinfo').'='.$str.config('url_ext')):(WEB_ROOT.$str.config('url_ext'));
			}
			$str=str_replace('%2F','/',$str);
			if($num==1)
			{
				$m=(config('url_mid')=='/')?'\\'.config('url_mid'):config('url_mid');
				$str=str_replace(config('url_mid').'page'.config('url_mid').$num.'','',$str);
			}
		}
		return str_replace('%2C',',',$str);
	}

	function pageList($j=5)
	{
		if(ismobile())
		{
			return self::pageList_mobile($j);
		}
		else
		{
			return self::pageList_pc();
		}
	}

	function pageList_pc($j=5)
	{
		$i=$j;
		$begin=$this->thispage;
   		$end=$this->thispage; 
   		while(true)
   		{
   			if($begin>1)
   			{
   				$begin=$begin-1;
   				$i=$i-1;
   			}
   			if($i>1&&$end<$this->totalpage)
   			{
   				$end=$end+1;
   				$i=$i-1;
   			}
   			if(($begin<=1&&$end>=$this->totalpage)||$i<=1)
   			{
   				break;
   			}
   		}
   		$str='';
   		$str.='<li><a>总数：'.$this->totalnum.'</a></li>';
  		
   		if($this->thispage>1)
   		{
   			$str.='<li><a href="'.$this->getUrl($this->thispage-1).'">'.$this->config['pre'].'</a></li>';
   		}
   		if($begin!=1)
   		{
   			$str.='<li><a href="'.$this->getUrl(1).'">1...</a></li>';
   		}
   		for($i=$begin;$i<=$end;$i++)
   		{
   			if($i==$this->thispage)
   			{
   				$str.='<li class="active"><a href="'.$this->getUrl($i).'">'.$this->thispage.'</a></li>';
   			}
   			else
   			{
   				$str.='<li><a href="'.$this->getUrl($i).'">'.$i.'</a></li>';
   			}
   		}
   		if($end!=$this->totalpage)
   		{
   			$str.='<li><a href="'.$this->getUrl($this->totalpage).'">...'.$this->totalpage.'</a></li>';
   		}
   		if($this->thispage<$this->totalpage)
   		{
   			$str.='<li><a href="'.$this->getUrl($this->thispage+1).'">'.$this->config['next'].'</a></li>';
   		}
   		$str.='<li><a>'.$this->thispage.'/'.$this->totalpage.'</a></li>';
   		return $str;
  	}

  	#组合
	function showpage($a)
	{
		if($this->totalpage==0)
		{
			return '';
		}
		return self::pageList($a);
	}

	function pageList_mobile()
	{
		if($this->totalpage<=1)
		{
			return '';
		}
		$str='';
		$str.='<li>'.self::home().'</li>';
		$str.='<li>'.self::pre().'</li>';
		$str.='<li>'.self::next().'</li>';
		$str.='<li>'.self::last().'</li>';
		$str.='<li><a>'.$this->thispage.'/'.$this->totalpage.'</a></li>';
		return $str;
	}

	#首页
	function home()
	{
		if($this->totalpage==0)
		{
			return '';
		}
		if($this->thispage==1)
		{
			return '<a>'.$this->config['home'].'</a>';
		}
		else
		{
			return '<a href="'.$this->getUrl(1).'">'.$this->config['home'].'</a>';
		}
	}

	#上一页
	function pre()
	{
		if($this->totalpage==0)
		{
			return '';
		}
		if($this->thispage>1&&$this->thispage<=$this->totalpage)
		{
			return '<a href="'.$this->getUrl($this->thispage-1).'">'.$this->config['pre'].'</a>';
		}
		else
		{
			return '<a>'.$this->config['pre'].'</a>';
		}
		
	}

	#下一页
	function next()
	{
		if($this->totalpage==0)
		{
			return '';
		}
		if($this->thispage<$this->totalpage)
		{
			return '<a href="'.$this->getUrl($this->thispage+1).'">'.$this->config['next'].'</a>';
		}
		else
		{
			return '<a>'.$this->config['next'].'</a>';
		}
	}

	#末页
	function last()
	{
		if($this->totalpage==0)
		{
			return '';
		}
		if($this->thispage<$this->totalpage)
		{
			return '<a href="'.$this->getUrl($this->totalpage).'">'.$this->config['last'].'</a>';
		}
		else
		{
			return '<a>'.$this->config['last'].'</a>';
		}
	}
	
}