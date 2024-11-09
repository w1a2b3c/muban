<?php
/**
 * 作用：Http类
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

final class cms_http
{
	static function check()
	{
		if(!function_exists('curl_init'))
	    {
			exit('curl_init函数未开启，请检查');
	    }
	}
	
	static function get($array)
	{
		self::check();

		$url=(isset($array['url']))?$array['url']:'';
        $type=(isset($array['type']))?$array['type']:0;
        $time=(isset($array['time']))?$array['time']:10000;
        $header=(isset($array['header']))?$array['header']:[];

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_TIMEOUT,$time);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch,CURLOPT_HEADER,false);
		#设置header
		curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		#301
		@curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
		$result=curl_exec($ch);
		$code=curl_getinfo($ch,CURLINFO_HTTP_CODE);
		curl_close($ch);
		return ($type==1)?['state'=>$code,'msg'=>$result]:$result;
	}

	static function post($array)
	{
		self::check();
		
		$url=(isset($array['url']))?$array['url']:'';
		$method=(isset($array['method']))?$array['method']:'POST';
		$data=(isset($array['data']))?$array['data']:'';
        $type=(isset($array['type']))?$array['type']:0;
        $time=(isset($array['time']))?$array['time']:10000;
        $head=(isset($array['head']))?$array['head']:false;
        $header=(isset($array['header']))?$array['header']:[];
        $auth=(isset($array['auth']))?$array['auth']:'';

		$ch=curl_init();
		curl_setopt($ch,CURLOPT_CUSTOMREQUEST,$method);
		curl_setopt($ch,CURLOPT_TIMEOUT,$time);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		
		#关闭后只获取body部分
		curl_setopt($ch,CURLOPT_HEADER,$head);
		#设置header
		curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		#301
		@curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
		#post
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		#身份认证
	    if($auth!='')
	    {
	        curl_setopt($ch,CURLOPT_USERPWD,$auth); 
	    }
		$result=curl_exec($ch);
		$code=curl_getinfo($ch,CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($head)
		{
			$arr=explode("\r\n",$result);
			$result=array_filter($arr);
			$dt=[];
			foreach($result as $key=>$val)
			{
				if(strpos($val,':'))
				{
					list($a,$b)=explode(":",$val);
					$dt[strtolower(trim($a))]=trim($b);
				}
			}
			$result=$dt;
		}
		
		return ($type==1)?['state'=>$code,'msg'=>$result]:$result;
	}

}