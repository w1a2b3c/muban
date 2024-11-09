<?php
final class cms_tree
{
	#目录树
	static function tree($data,$fid=0) 
	{
		$tree=[];
		foreach($data as $val)
		{
			if($val['followid']==$fid) 
			{
				$children=self::tree($data,$val['cateid']);
				if($children)
				{
					$val['children']=$children;
				}
				$tree[]=$val;
			}
		}
		return $tree;
	}

	static function select($data=[],$pid=0,&$array=[])
    {
        foreach($data as $key=>$val) 
        {
            if($val['followid']==$pid)
            {
                $array[]=$val;
                self::select($data,$val['cateid'],$array);
            }
        }
        return $array;
    }

	#获取任意节点的所有子级
	static function son($id,$data)
	{
        static $list;
        foreach($data as $k=>$v) 
        {
            if($v['followid']==$id)
            {
                $list[$data[$k]['cateid']]=$data[$k];
                self::son($v['cateid'],$data);
            }
            else
            {
            	if($v['cateid']==$id)
            	{
            		$list[$data[$k]['cateid']]=$data[$k];
            	}	
            }
        }
        return $list;
	}

	#获取任意节点的所有父级
	static function parent($id,$data)
	{
		static $list=[];
		foreach($data as $k=>$v)
		{
			if($v['cateid']==$id)
			{
				$list[$v['cateid']]=$v;
				self::parent($v['followid'],$data);
			}	
		}
		return $list;
	}

	static function array_keys($array)
	{
	    $keys=[];
	    foreach($array as $key=>$val)
	    {
	        $keys[]=$key;
	        if(is_array($val['son']) && count($val['son'])>0) 
	        {
	        	$arr=self::array_keys($val['son']);
	            $keys=array_merge($keys,$arr);
	        }
	    }
	    return $keys;
	}

	static function depth(&$data,$pid=0)
	{
	    if(empty($data))
	    {
	        return;
	    }
	    static $level=1;
	    foreach($data as $key=>$node)
	    {
	        if($node['followid']==$pid)
	        {
	            $node['depth']=$level;
	            $data[$key]=$node;
	            if(count($node['son'])>0)
	            {
	                $level++;
	                self::depth($data,$node['cateid']);
	                $level--;
	            }
	        }
	    }
	}

}