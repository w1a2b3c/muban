<?php
if(!defined('IN_CMS')) exit;

class cms_cate extends cms
{
	#读取分类数据
	function cate()
	{
		$rs=cms::$db->load("select * from cms_class order by cate_order,cateid");
		$tree=[];
		#第一步，将分类id作为数组key,并创建son单元
		foreach($rs as $val)
		{
			$tree[$val['cateid']]=$val;
			$tree[$val['cateid']]['son']=[];
		}

		#第二步，利用引用，将每个分类添加到父类son数组中，这样一次遍历即可形成树形结构。
		$step=0;
		foreach($tree as $k=>$v)
		{
			$step++;
			if($v['followid']!=0)
			{
				$tree[$v['followid']]['son'][$v['cateid']]=&$tree[$k];
			}
		}

		#获取depth
		cms_tree::depth($tree);

		#第三步：处理sonid和child
		foreach($tree as $key=>$val)
		{
			$arr=cms_tree::array_keys($tree[$key]['son']);
			$tree[$key]['sonid']=trim($val['cateid'].",".join(",",$arr),",");
			$tree[$key]['child']=count(explode(',',$tree[$key]['sonid']))-1;
		}

		#第四步：剔除son，降低缓存大小
		foreach($tree as $key=>$val)
		{
			unset($tree[$key]['son']);
		}
		unset($rs);
		return $tree;
	}
}
