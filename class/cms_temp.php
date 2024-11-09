<?php
/**
 * 作用：模板引擎
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

final class cms_temp
{
	public $themeRoot;
	public $includeRoot;
	public $cacheRoot;
	public $isZip;
	public $php_dim=[];
	public $content='';
	public $limit,$field,$table,$join,$group,$where,$order,$auto,$cache,$cachetime,$pagesize,$sql,$pagenum,$num,$key,$type;
	public $eof,$loop,$head,$foot;
	public $cateid;
	public $literal=[];

	function __construct()
	{
		$this->init();
	}

	function init()
	{
		$this->themeRoot="theme/".config('theme_dir')."/";
		$this->includeRoot="";
		$this->cacheRoot=config('cache_dir')."/home/".config('theme_dir')."/";
		$this->isZip=config('html_zip');
	}

	function show($a,$b='')
	{
		$pre='';
		if(config('mobile_open')==1 && M_NAME=='home')
		{
			if(iswap())
			{
				$pre="mobile/";
				$a=$pre.$a;
			}
			else
			{
				if(!defined('SYS_MOBILE') && ismobile() && config('mobile_auto')==1)
				{
					$pre="mobile/";
					$a=$pre.$a;
				}
			}
		}
		
		if(!is_file($root="theme/".config('THEME_DIR')."/".$a) && $b!='')
		{
			$a=$pre.$b;
		}
		
		#添加token
		$m=strtolower(M_NAME);
		if(S("_token_{$m}_")=="")
		{
			$token=md5(uniqid('',true));
			S("_token_{$m}_",$token);
		}
		else
		{
			$token=S("_token_{$m}_");
		}
		$this->add("token",$token);
		if($m=='plug')
		{
			$this->add("plug",U(S('admindir').'/plug/index','',1));
		}
		if($a=='')
		{
			exit('找不到模板配置');
		}
		$a=str_replace(['..','./'],'',$a);
		#模板文件名
		$tplName=$this->themeRoot.$a;
		#编译文件名
		$comName=$this->cacheRoot.$a;
		#获取文件名，示范：index.html
		$name=basename($comName);
		$comName=dirname($comName)."/".$name;
		unset($name);

		mkfolder(dirname($comName));

		#检查文件是否存在
		if(!file_exists($tplName))
		{
			exit($tplName.'模板文件不存在，请检查');
		}
		#检查编译文件是否存在
		if(!file_exists($comName) || filemtime($comName)<filemtime($tplName))
		{
			#开始编译文件
			$this->parse_temp($tplName,$comName);
		}
		if(!isset($this->php_dim['parentid']))
		{
			$this->php_dim['parentid']='0';
		}
		extract($this->php_dim,EXTR_OVERWRITE);
		ob_start();
		include $comName;
		$contents=ob_get_contents();
		ob_end_clean();
		$this->content=$contents;
		$this->parse_free();
		echo $this->content;
		unset($this->content);
		$GLOBALS['end']=['0'=>microtime(true),'1'=>memory_get_usage()];
		if(config('PROCESSED'))
		{
			echo '<!--Processed in '.number_format(($GLOBALS['end'][0]-$GLOBALS['begin'][0]),6).' s , Memory '.formatBytes(($GLOBALS['end'][1]-$GLOBALS['begin'][1])).' , '.$GLOBALS['query'].' queries-->';
		}
	}

	function add($a,$b)
	{
		if(isset($a) && !empty($a))
		{
			$this->php_dim[$a]=$b;
		}
	}

	#编译模板
	function parse_temp($a,$b,$type='')
	{
		#读取模板内容
		if(!is_file($a))
		{
			exit('找不到文件：'.$a);
		}
		mkfolder(dirname($b));
		$this->content=file_get_contents($a);
		#解析包含文件
		$this->parse_include($type);
		#解析区块
		$this->parse_block();
		#解析hook
		$this->parse_hook();
		#解析模块
		$this->parse();
		#生成编译文件
		if(!strpos($this->content,"!defined('IN_CMS')"))
		{
			$this->content="<?php if(!defined('IN_CMS')) exit;?>".$this->content;
		}
		#优化生成的php代码
		$this->content=str_replace('?><?php','',$this->content);
		#压缩HTML
		if($this->isZip)
		{
			$this->content=preg_replace("~>\s+<~", "><",preg_replace("~>\s+\r\n~", ">",$this->content));
		}
		#去掉注释
		$this->content=preg_replace('#<!--[^\!\[]*?(?<!\/\/)-->#','',$this->content);
		#去除空白行
		$this->content=preg_replace('/($\s*$)|(^\s*^)/m','',$this->content);
		$res=check_bad($this->content);
		if($res!='')
		{
			exit('模板文件中有非法代码('.$res.')');
		}
		if(!savefile($b,$this->content))
		{
			exit('模板编译文件保存失败：('.$b.')');
		}
	}

	function parse()
	{
		$this->content=preg_replace_callback('/{no}(.*?){\/no}/is',array($this,'parseLiteral'),$this->content);
		$this->content=preg_replace('/\{if\s+(.*?)\}/','<?php if ($1) { ?>',$this->content);
		$this->content=preg_replace('/\{elseif\s+(.*?)\}/','<?php } elseif ($1) { ?>',$this->content);
		$this->content=preg_replace('/\{else\}/','<?php } else { ?>',$this->content);
		$this->content=preg_replace('/\{\/if\}/','<?php }?>',$this->content);
		$this->content=preg_replace('/\{foreach\s+(\S+)\s+as\s+(\S+)\}/','<?php foreach($1 as $2) { ?>',$this->content);
		$this->content=preg_replace('/\{foreach\s+(\S+)\s+as\s+(\S+)\s*=>\s*(\S+)\}/','<?php foreach($1 as $2 => $3) { ?>',$this->content);
		$this->content=preg_replace('/\{\/foreach\}/','<?php }?>',$this->content);
		$num=preg_match_all("/\{loop(.*?)\}/s",$this->content,$match);
		if($num)
		{
			for($i=0;$i<$num;$i++)
			{
				$a=$match[0][$i];
				$data=$this->get_foreach_param($match[1][$i]);
				$b=$data[0];
				$c=$data[1];
				$str="<?php $$c=0;foreach ($b){ $$c++;?>";
				$this->content=str_replace($a,$str,$this->content);
			}
		}
		$this->content=preg_replace('/\{\/loop\}/','<?php }?>',$this->content);
		$num=preg_match_all("/\{for(.*?)\}(.*?){\/for\}/s",$this->content,$match);
		if($num)
		{
			for($i=0;$i<$num;$i++)
			{
				$a=$match[0][$i];
				$b=$this->get_for_param($match[1][$i]);
				$c=$match[2][$i];
				$str="<?php for ($b){?>$c<?php }?>";
				$this->content=str_replace($a,$str,$this->content);
			}
		}
		$num=preg_match_all("/\{switch\s+(.*?)\}(.*?){\/switch\}/s",$this->content,$match);
		if($num)
		{
			for($i=0;$i<$num;$i++)
			{
				$a=$match[0][$i];
				$b=$match[1][$i];
				$c=str_replace("\r\n","",$match[2][$i]);
				$c=ltrim($c," ");
				$c=ltrim($c,"　");
				$str="<?php switch ($b){?>$c<?php }?>";
				$this->content=str_replace($a,$str,$this->content);
			}
		}

		$this->content=preg_replace('/\{case\s+(.*?)\}/','<?php case $1: ?>',$this->content);
		$this->content=preg_replace('/\{\/case\}/','<?php break; ?>',$this->content);
		$this->content=preg_replace('/\{default}/','<?php default: ?>',$this->content);
		$this->content=self::deal_loop();
		$this->content=preg_replace('/cms\[(.*?)\]/i','config(strtoupper(\'$1\'))',$this->content);
		$this->content=preg_replace('/\$(\w+)\.(\w+)\.(\w+)\.(\w+)/is','$$1[\'$2\'][\'$3\'][\'$4\']',$this->content);
		$this->content=preg_replace('/\$(\w+)\.(\w+)\.(\w+)/is','$$1[\'$2\'][\'$3\']',$this->content);
		$this->content=preg_replace('/\$(\w+)\.(\w+)/is','$$1[\'$2\']',$this->content);
		$this->content=preg_replace('/\{\$(.*?)\}/','<?php echo $$1;?>',$this->content);
		$this->content=preg_replace('/\{php\s+(.*?)\}/','<?php $1;?>',$this->content);
		$this->content=preg_replace('/\{:(.*?)}/','<?php echo $1;?>',$this->content);
		$this->content=preg_replace('/\{([a-zA-Z0-9_-])}/','<?php echo $1;?>',$this->content);
		$this->content=preg_replace('/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s','<?php echo $1;?>',$this->content);
		$this->content=preg_replace('/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/','<?php echo $1;?>',$this->content);
		$this->content=preg_replace_callback('/<!--###nocompile(\d+)###-->/is',array($this,'restoreLiteral'),$this->content);
		$this->content=preg_replace('#<!--[^\!\[]*?(?<!\/\/)-->#','',$this->content);
	}

	function parse_free()
	{
		if(CMS_AUTH==0 && (cms_parse::getHost())!='localhost' && M_NAME!='install')
		{
			$title=(mt_rand(0,100)<30)?'nicemb免费版 - $1':'$1 - nicemb免费版';
			$this->content=preg_replace("/\<title>(.*?)<\/title\>/s",'<title>'.$title.'</title>',$this->content);
		}
	}

	public function parse_block()
	{
		$this->content=preg_replace('/\{block\([\"]?([^\"]+)[\"]\)\}/','<?php echo block("$1");?>',$this->content);
	}

	function parse_hook()
	{
		$num=preg_match_all("/\{hook(.*?)}/",$this->content,$match);
		if($num)
		{
			for($i=0;$i<$num;$i++)
			{
				$a=$match[0][$i];
				$b=$this->get_hook_param($match[1][$i]);
				$str="<?php $b;?>";
				$this->content=str_replace($a,$str,$this->content);
			}
		}
	}

	function parse_include($type='')
	{
		$num=preg_match_all("/\{include(.*?)}/",$this->content,$match);
		if($num)
		{
			for($i=0;$i<$num;$i++)
			{
				$arr=$this->parse_attr($match[1][$i]);
				$oldname=$arr['file'];
				$type=isset($arr['type'])?$arr['type']:$type;
				$oldname=str_replace(['..','./','…/'],'',$oldname);
				$cache=($type!='')?$type."_":'';
				$md5name=$this->cacheRoot.$cache.$arr['file'];
				$content=$this->content;
				$name=$this->parse_include_twos($oldname,$md5name,$type);
				$this->content=$content;
				unset($content);
				$file=($name!='')?'<?php include $this->tp->parse_include_twos("'.$oldname.'","","'.$type.'");?>':'';
				$this->content=str_replace($match[0][$i],$file."\r\n",$this->content);
				$type='';
			}
			$this->parse_include($type);
		}
	}

	#包含文件二次解析
	function parse_include_twos($comName,$cacheName='',$type='')
	{
		$root=($this->includeRoot!='')?$this->includeRoot:$this->themeRoot;
		switch($type)
		{
			case "admin":
				$root="app/".S('admindir')."/view/";
				break;
			case "home":
				$root="theme/".config('theme_dir')."/";
				break;
			case "plug":
				$root=$this->includeRoot;
				break;
		}
		$comName=$root.$comName;
		if(!is_file($comName))
		{
			cms_log::log('找不到文件：'.$comName);
			return '';
		}
		if($cacheName=='')
		{
			$cache=($type!='')?$type."_":'';
			$cacheName=$this->cacheRoot.$cache.$comName;
		}
		if(!file_exists($cacheName) || filemtime($cacheName)<filemtime($comName))
		{
			#开始编译文件
			$this->parse_temp($comName,$cacheName,$type);
		}
		return $cacheName;
	}

	function deal_loop($html='')
	{
		if($html=='')
		{
			$html=$this->content;
		}
		$num=preg_match_all("/\{cms:([\w.]+)\s+(.*?)\}(.*?){\/cms:\\1\}/s",$html,$match);
		if($num)
		{
			for($i=0;$i<$num;$i++)
			{
				$a=$match[0][$i];#主体部分
				$b=$match[1][$i];#rs部分
				$c=$match[2][$i];#参数1,继续使用正则，提取参数
				$d=$match[3][$i];#循环部分
				$d=self::deal_loop($d);
				self::get_loop_body($b,$d);
				$this->get_loop_param($c);
				$s_sql=$this->sql;
				if($this->pagesize==0 && empty($this->pagesize))
				{
					if(!$s_sql)
					{
						$s_sql="select $this->field from $this->table $this->join $this->where $this->group $this->order $this->limit";
					}
					$str="<?php ";
					$str.='$array_'.$b.'=$this->db->load("'.$s_sql.'",0,'.$this->cache.','.$this->cachetime.');';
					$str.='$total_'.$b.'=count($array_'.$b.');';
					$str.='if($total_'.$b.'==0){ ?>';
					$str.=$this->eof."\n";
					$str.='<?php } else{ ?>';
					$str.=$this->head."\n";
					$str.='<?php '.$this->auto.'=0;} ';
					$str.='foreach($array_'.$b.' as $'.$b.'){ '.$this->auto.'++;?>';
					$str.=self::parse_field($b,$this->loop,$this->table);
					$str.='<?php }?>';
					$str.='<?php if($total_'.$b.'>0){ ?>';
					$str.=$this->foot."\n";
					$str.='<?php }?>';
					$html=str_replace($a,$str,$html);
				}
				else
				{
					$t_sql="select count(1) from $this->table $this->join $this->where $this->group";
					$str="<?php ";
					#获得总的记录数量
					$str.='$total_'.$b.'=$this->db->count("'.$t_sql.'");'."";
					#每页数量
					$str.='$pagesize='.$this->pagesize.';'."";
					#获取总页数
					$str.='$totalpage=ceil($total_'.$b.'/$pagesize);'."";
					#如果超过总页，则返回第一页
					$str.='if($page>$totalpage){';
					$str.="\n";
					$str.='$page=1;}'."";

					#默认取值
					$str.='$offset=($page-1)*$pagesize;'."";
					$str.='$way=0;'."";
					
					#如果当前页数大于1000，总页数大于2000，当前页数大于总页数的一半
					$str.='if($offset>1000 && $total_'.$b.'>2000 && $offset>$total_'.$b.'/2)'."";
					$str.='{'."";
					$str.='	$offset=$total_'.$b.'-$offset-$pagesize;'."";
					$str.='	$way=1;'."";
					$str.='}'."";
					$str.='if($offset<0)'."";
					$str.='{'."";
					$str.='	$pagesize+=$offset;'."";
					$str.='	$offset=0;'."";
					$str.='}'."";
					
					#获取ID的记录
					$str.='$key_="'.$this->key.'";'."";
					$str.='$table_="'.$this->table.'";'."";
					$str.='$join_="'.$this->join.'";'."";
					$str.='$where_="'.$this->where.'";'."";
					$str.='$group_="'.$this->group.'";'."";
					$str.='$order_="'.$this->order.'";'."";
					$str.='$field_="'.$this->field.'";'."";
					$str.='$type_="'.$this->type.'";'."";
					
					$str.='$keylist=$this->db->getkeylist($key_,$table_,$join_,$where_,$order_,$offset,$pagesize,$way,$type_);'."";
					$str.='$array_'.$b.'=$this->db->load("select $field_ from $table_ $join_ $keylist $group_ $order_");'."";
					$str.='$pg=new cms_page($total_'.$b.',$totalpage,$pagesize,$page);'."";
					$str.='$showpage=$pg->showpage('.$this->num.');'."";
					$str.='if($total_'.$b.'==0){ ?>';
					$str.=$this->eof."";
					$str.='<?php } else{ ?>';
					$str.=$this->head."";
					$str.='<?php '.$this->auto.'=0;} ';
					$str.='foreach($array_'.$b.' as $'.$b.'){ '.$this->auto.'++;?>';
					$str.=self::parse_field($b,$this->loop,$this->table);
					$str.='<?php }?>';
					$str.='<?php if($total_'.$b.'>0){ ?>';
					$str.=$this->foot."";
					$str.='<?php }?>';
					$html=str_replace($a,$str,$html);
				}
				unset($s_sql);
			}
			return $html;
		}
		else
		{
			return $html;
		}
	}
	
	function enhtml($a)
	{
		$a=str_replace("<","&lt;",$a);
		$a=str_replace(">","&gt;",$a);
		return $a;
	}

	function dehtml($a)
	{
		$a=str_replace("&lt;","<",$a);
		$a=str_replace("&gt;",">",$a);
		return $a;
	}

	function parse_attr($a)
	{
		$a=self::enhtml($a);
		$arr=[];
		$xml='<xml><tag '.$a.' /></xml>';
        $xml=@simplexml_load_string($xml);
        if($xml)
        {
	        $xml=(array)($xml->tag->attributes());
        	$arr=array_change_key_case($xml['@attributes']);
        }
        return $arr;
	}

	function get_foreach_param($a)
	{
		$arr=$this->parse_attr($a);
        $data=isset($arr['data'])?$arr['data']:[];
        $key=isset($arr['key'])?$arr['key']:'key';
        $val=isset($arr['val'])?$arr['val']:'val';
        $step=isset($arr['step'])?$arr['step']:'step';
        $str="$data as $$key=>$$val";
        return [$str,$step];
	}

	function get_for_param($a)
	{
		$arr=$this->parse_attr($a);
        $var=isset($arr['var'])?'$'.$arr['var']:'$i';
        $min=isset($arr['min'])?$arr['min']:0;
        $max=isset($arr['max'])?$arr['max']:10;
        $step=isset($arr['step'])?$arr['step']:1;
        $str="$var=$min;$var<$max;$var+=$step"; 
        return $str;
	}

	function get_hook_param($a)
	{
		$arr=$this->parse_attr($a);
        $name=isset($arr['name'])?$arr['name']:'';
        $param=isset($arr['param'])?$arr['param']:'';
        if($param!='')
        {
        	$str="cms_hook::add('$name',this,$param)";
        }
        else
        {
        	$str="cms_hook::add('$name',this)";
        }
        return str_replace([',this,',',this)'],[',$this,',',$this)'],$str);
	}

	function get_loop_param($a)
	{
		$arr=$this->parse_attr($a);
		$top=isset($arr['top'])?$arr['top']:10;
		$this->limit=($top!="0")?"limit $top":'';
		$this->field=isset($arr['field'])?$arr['field']:'*';
		$this->table=isset($arr['from'])?$arr['from']:'';
		if($this->table=='')
		{
			$this->table=isset($arr['table'])?$arr['table']:'';
		}
		$this->join=isset($arr['join'])?$arr['join']:'';
		if($this->table=='cms_data')
		{
			$this->join='left join cms_show on cms_data.cid=cms_show.id '.$this->join;
		}
		$this->group=isset($arr['group'])?'group by '.$arr['group']:'';
		$this->where=self::dehtml(isset($arr['where'])?$arr['where']:'1=1');
		$this->cateid=isset($arr['cateid'])?$arr['cateid']:'';
		$class_where='';
		$side_where='';
		if($this->cateid!='' && (strpos($this->table,'cms_data') || strpos($this->table,'cms_show') || in_array($this->table, ['cms_data','cms_show'])))
		{
			if(substr($this->cateid,0,1)=='$')
			{
				$class_where=" classid in(\".sonid($this->cateid).\") ";
			}
			else
			{
				$class_where=" classid in(".sonid($this->cateid).") ";
			}
			if(config('side_class')==1)
			{
				$side_where=" \".deal_side($this->cateid).\" ";
			}
		}
		if($class_where!='' && $side_where!='')
		{
			$this->where="(".$this->where.") and ($class_where or $side_where)";
		}
		else
		{
			if($class_where!='' && $side_where=='')
			{
				$this->where="(".$this->where.") and $class_where";
			}
			if($class_where=='' && $side_where!='')
			{
				$this->where="(".$this->where.") and $side_where";
			}
		}
		$this->where='where '.$this->where;

		$this->order=isset($arr['order'])?'order by '.$arr['order']:'';
		$this->cache=isset($arr['cache'])?$arr['cache']:'false';
		$this->cachetime=isset($arr['cachetime'])?$arr['cachetime']:0;
		$this->pagesize=isset($arr['pagesize'])?$arr['pagesize']:0;
		$this->auto=isset($arr['auto'])?'$'.$arr['auto']:'$i';
		$this->sql=isset($arr['sql'])?$arr['sql']:'';
		$this->num=isset($arr['num'])?$arr['num']:3;
		#自增主键，如果没有填写就为id
		$this->key=isset($arr['key'])?$arr['key']:'id';
		$this->type=isset($arr['type'])?$arr['type']:'0';
	}

	function get_loop_body($a,$b)
	{
		$this->head='';
		$num=preg_match_all("/\{$a:head\}(.*?){\/$a:head\}/s",$b,$match);
		if($num)
		{
			$this->head=$match[1][0];
			$b=str_replace($match[0][0],'',$b);
		}
		$this->foot='';
		$num=preg_match_all("/\{$a:foot\}(.*?){\/$a:foot\}/s",$b,$match);
		if($num)
		{
			$this->foot=$match[1][0];
			$b=str_replace($match[0][0],'',$b);
		}
		$this->eof='';
		$this->loop=$b;
		$num=preg_match_all("/\{$a:eof\}(.*?){\/$a:eof\}/s",$b,$match);
		if($num)
		{
			$this->eof=$match[1][0];
			$this->loop=str_replace($match[0][0],'',$b);
		}
	}

	function parse_field($a,$b,$table)
	{
		#兼容$rs.link模式
		$b=str_replace($a.".link",$a."[link]",$b);
		$num=preg_match_all("/$a\[(.*?)\]/i",$b,$match);
		for($i=0;$i<$num;$i++)
		{
			$c=str_replace($match[1][$i], "'".$match[1][$i]."'", $match[0][$i]);
			if($match[1][$i]=='link')
			{
				$c='show_url($'.$a.'[\'id\'],$'.$a.'[\'alias\'],$'.$a.'[\'classid\'])';
				$b=str_replace('$'.$match[0][$i],$c,$b);
			}
			else
			{
				$b=str_replace($match[0][$i],$c,$b);
			}
		}
		return $b;
	}

	function parseLiteral($a)
	{
        if(is_array($a))
        {
        	$a=$a[1];
        }
        if(trim($a)=='')
        {
        	return '';
        }
        $i=count($this->literal);
        $parseStr="<!--###nocompile{$i}###-->";
        $this->literal[$i]=$a;
        return $parseStr;
    }

    function restoreLiteral($a)
    {
        if(is_array($a))
        {
        	$a=$a[1];
        }
        $parseStr=$this->literal[$a];
        unset($this->literal[$a]);
        return $parseStr;
    }
	
}