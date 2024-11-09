<?php
/**
 * 作用：数据库
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

final class cms_db
{
	public $conn=null;
	public $newid;
	public $sql;
	public $prefix;
	public static $instance=null;
	protected $links=[];
	protected $link_id;
	protected $link_write;
	protected $link_read;
	protected $config;

	private function __construct(){}

	function __destruct()
	{
		$this->conn=null;
		$this->link_id=null;
		$this->link_write=null;
		$this->link_read=null;
		foreach($this->links as $k)
		{
			$k=null;
		}
	}

	static function instance()
	{
		if(empty(self::$instance))
		{
			self::$instance=new cms_db();
			self::$instance->config=config('default_db');
			self::$instance->init();
		}
		return self::$instance;
	}

	function init($type=true)
	{
		#表前缀
		if(!$this->prefix)
		{
			$this->prefix=$this->config['prefix'];
		}
		if(getint($this->config['deploy'])==1)
		{
			if($type)
			{
				if(!$this->link_write)
				{
					$this->link_write=$this->multiConnect(true);
				}
				$this->link_id=$this->link_write;
			}
			else
			{
				if(!$this->link_read)
				{
					$this->link_read=$this->multiConnect(false);
				}
				$this->link_id=$this->link_read;
			}
		}
		else
		{
			if(!$this->link_id)
			{
				$this->link_id=$this->multiConnect(true);
			}
		}
	}

	private function multiConnect($type=false)
	{
		$_config=[];
		$db=$this->config;
		$this->prefix=$db['prefix'];

        $_config=[];

        #分布式数据库配置解析
        foreach(['host','port','name','user','pass'] as $name) 
        {
            $_config[$name]=explode(',',$db[$name]);
        }

        #主服务器
        $dbMaster=[];
        $m=0;
        foreach(['host','port','name','user','pass'] as $name) 
        {
        	$dbMaster[$name]=isset($_config[$name][$m])?$_config[$name][$m]:$_config[$name][0];
        }
  
        #从服务器
        $dbConfig=[];
        $r=($type)?$m:floor(mt_rand(1,count($_config['host'])-1));
        if($m!=$r)
        {
        	foreach(['host','port','name','user','pass'] as $name) 
	        {
	        	$dbConfig[$name]=isset($_config[$name][$r])?$_config[$name][$r]:$_config[$name][0];
	        }
        }
        else
        {
        	$dbConfig=$dbMaster;
        }
        return $this->connect($dbConfig,$r,($r==$m)?false:$dbMaster);
	}

	function connect($db=[],$num=0,$auto=false)
	{
		if(isset($this->links[$num]))
		{
            return $this->links[$num];
        }
        try
		{
			$this->links[$num]=new PDO('mysql:host='.$db['host'].';port='.$db['port'].';dbname='.$db['name'].'',$db['user'],$db['pass']);
			return $this->links[$num];
		}
		catch(PDOException $e)
		{
			if($auto)
			{
				return $this->connect($auto,$r);
			}
			else
			{
				exit('数据库连接失败：'.$e->getMessage());
			}
		}
	}

	private function getlink($sql)
	{
		if(strpos($sql,'select')!==false) 
		{
			#读库
			$this->init(false);
		} 
		else 
		{
			#写库
			$this->init();
		}
		$this->conn=$this->link_id;
		if($this->conn)
		{
			$this->conn->exec("set names 'utf8mb4'");
			$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,true);
			$this->conn->setAttribute(PDO::ATTR_STRINGIFY_FETCHES,false); 
		}
	}

	function query($sql,$type=0)
	{
		$this->getlink($sql);
		#echo $sql.'<br>';
		$GLOBALS['query']+=1;
		if($type==0)
		{
			$sql=self::filter_table($sql);
		}
		try
		{
			$db=$this->conn->query($sql);
			return $db;
		}
		catch(PDOException $e)
		{
			#写错误日志
			$error=$this->conn->errorInfo();
			$str="Sql：$sql<br>日期：".date('Y-m-d H:i:s')."<br>详细：".$error[2]."<br>Url：".THIS_LOCAL."<br>IP：".getip()."";
			cms_log::log($str);
			echo E(['state'=>'error','msg'=>'SQL错误，详细请查阅日志']);
			exit();
		}
	}

	function count($sql)
	{
		$array=$this->query($sql)->fetch(PDO::FETCH_NUM);
		return $array[0];
	}

	function load($sql,$type=0,$cache='false',$time=0)
	{
		$root=$this->root($sql);
		if($cache=='true' || $cache===true)
		{
			$name=md5($sql);
			if(cms_cache::check($name,$time,$root))
			{
				return cms_cache::get($name,$root);
			}
			else
			{
				return cms_cache::set($name,self::load_data($sql,$type),$time,$root);
			}
		}
		else
		{
			return self::load_data($sql,$type);
		}
	}

	function load_data($sql,$type=0)
	{
		$array=[];
		$this->sql=$sql;
		$result=$this->query($sql,$type);
		try
		{
			while($data=$result->fetch(PDO::FETCH_ASSOC))
			{
				$array[]=$data;
			}
			unset($result);
		}
		catch(PDOException $e)
		{
			exit("{$sql}语句出错");
		}
		return $array;
	}

	function row($sql,$type=0,$cache='false',$time=0)
	{
		$root=$this->root($sql);
		if($cache=='true' || $cache===true)
		{
			$name=md5($sql);
			if(cms_cache::check($name,$time,$root))
			{
				return cms_cache::get($name,$root);
			}
			else
			{
				return cms_cache::set($name,self::row_data($sql,$type),$time,$root);
			}
		}
		else
		{
			return self::row_data($sql,$type);
		}
	}

	function row_data($sql,$type=0)
	{
		$result=$this->query($sql,$type);
		if($result)
		{
			return $result->fetch(PDO::FETCH_ASSOC);
		}
		else
		{
			return false;
		}
	}

	function getkeylist($id,$table,$join,$where,$order,$begin,$end,$way=0,$type=0)
	{
		$table=self::filter_table($table);
		$join=self::filter_table($join);
		$where=self::filter_table($where);
		$order=self::filter_table($order);
		$str=$where;
		if($type==1)
		{
			return $str;
		}
		if($way==1)
		{
			$order=str_replace("desc","asc",$order);
		}
		$sql="select $id from $table $join $where $order limit $begin,$end";
		$data_id=$this->load($sql,1);
		if(count($data_id)>0)
		{
			foreach($data_id as $key=>$val)
			{
				$data_id[$key]=$val[$id];
			}
			$str="where $id in(".implode(',',$data_id).")";
		}
		$str=str_replace($this->prefix,'cms_',$str);
		return $str;
	}

	function add($table,$array,$type=0)
	{
		$table=self::filter_table($table);
		$field=array_keys($array);
		$value=array_values($array);
		array_walk($field,array($this,'add_special_char'));
		array_walk($value,array($this,'escape_string'));
		$field=implode(',',$field);
		$value=implode(',',$value);
		if($type==1)
		{
			$value=str_replace('\\"','\\\\"',$value);
		}
		$result=$this->query("insert into $table ($field) values ($value)",1);
		$this->newid=$this->conn->lastInsertId();
		return $result;
	}

	function update($table,$where,$array)
	{
		$table=self::filter_table($table);
		if($where!='')
		{
			$where='where '.$where;
		}
		$field=[];
		foreach($array as $key=>$value)
		{
			$field[]=$this->add_special_char($key).'='.$this->escape_string($value);
		}
		$field=implode(',',$field);
		return $this->query("update $table set $field $where",1);
	}

	function del($table,$where)
	{
		$table=self::filter_table($table);
		if($where!='')
		{
			$where='where '.$this->filter_char($where);
		}
		return $this->query("delete from $table $where",1);
	}

	function load_field($field,$table,$where,$data='')
	{
		$table=self::filter_table($table);
		if($where!='')
		{
			$where='where '.$where;
		}
		$sql="select $field from $table $where limit 1";
		$rs=$this->row($sql,1);
		return ($rs)?$rs[$field]:$data;
	}

	function root($sql)
	{
		$arr=[
			'show'=>'show',
			'class'=>'class',
			'ad'=>'ad',
			'link'=>'link',
			'filter'=>'filter',
			'filter_list'=>'filter',
			'field'=>'field',
			'plug_sitelink'=>'sitelink',
			'plug'=>'plug'
		];
		$num=preg_match_all("/select(.*?)cms_(.*?)where/",$sql,$match);
		if($num==1)
		{
			$res=trim($match[2][0]);
			if(isset($arr[$res]))
			{
				return $arr[$res];
			}
		}
		return '';
	}

	function add_special_char(&$value)
	{
		if('*'==$value || false!==strpos($value,'(') || false!==strpos($value,'.') || false!==strpos ($value,'`'))
		{
			#不处理包含* 或者 使用了sql方法。
		} 
		else 
		{
			$value='`'.trim($value).'`';
		}
		return $this->filter_char($value);
	}

	function filter_char($val)
	{
		return preg_replace("/\b(select|insert|create|update|delete|alter|sleep|payload|_schema|hex\(|replace\(|concat\(|assert|cmdshell|wshshell|extractvalue|benchmark|greatest|least|passthru|popen|get_lock|updatexml)\b/i",'',$val);
	}
	
	function escape_string(&$value,$key='',$quotation=1)
	{
		$q=($quotation)?'\'':'';
		$value=str_replace("\\'","&#039;",$value);
		$value=$q.$value.$q;
		return $value;
	}

	function filter_table($table,$type=0)
	{
		if($type==0)
		{
			$table=str_replace('cms_',$this->prefix,$table);
			$table=str_replace('%s',$this->prefix,$table);
		}
		else
		{
			$tb=strtolower($table);
			if(strpos($tb,"insert") && strpos($tb,"into") && strpos($tb,"values"))
			{
				$dt=explode("values",$tb);
				$frist=self::filter_table($dt[0],0);
				unset($dt[0]);
				return $frist.implode($dt);
			}
			else
			{
				return self::filter_table($table,0);
			}
		}
		return $table;
	}
	
}