<?php
/**
 * 作用：Redis缓存
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

class cache_redis extends cms_cache
{
	protected $redis;
	function __construct($root='')
	{
		try
        {
        	if(extension_loaded('redis'))
        	{
        		$this->redis=new Redis();
        		
	            #连接redis
	            $this->redis->connect(config('cache_host'),config('cache_port'));

	            #密码
	            if(config('cache_pass')!='')
	            {
	            	$this->redis->auth(config('cache_pass'));
	            }
	            
	            #选择库
	            $this->redis->select(config('cache_table'));
        	}
        	else
        	{
        		exit('Php环境不支持：Redis');
        	}
        }
        catch(RedisException $e)
        {
            exit('Redis连接失败：'.$e->getMessage());
        }
	}

	function get($name='config',$type='')
	{
		$name=($type!='')?$type.':'.$name:$name;
		$result=$this->redis->get($name);
		return (!is_null(D($result)))?D($result):$result;
	}

	function set($name='config',$data=[],$time=0,$type='')
	{
		$data=($data===false)?[]:$data;
		$result=is_array($data)?E($data):$data;
		$time=(getint($time)==0)?null:$time;
		$name=($type!='')?$type.':'.$name:$name;
        $this->redis->set($name,$result,$time);
		return $data;
	}

	function check($name='config',$time=0,$type='')
	{
		$name=($type!='')?$type.':'.$name:$name;
		return (bool)$this->redis->exists($name);
	}

	function del($name='config')
	{
		$this->redis->del($name);
	}

	function del_group($name)
	{
		$res=$this->redis->keys($name.'*');
		if($res)
		{
			$this->redis->del($res);
		}
	}

	#清理当前库
    function clear()
    {
        $this->redis->flushdb();
    }

}