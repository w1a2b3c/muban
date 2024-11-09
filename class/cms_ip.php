<?php
/**
 * 作用：IP转省市
 * 官网：Https://www.nicemb.com
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

class cms_ip
{
    function ip2addr($ip)
    {
        if($ip=='127.0.0.1' || strpos($ip,'192.168.'))
        {
            return '';
        }
        $add=S('post_adds');
        if(empty($add))
        {
            $url="http://whois.pconline.com.cn/ipJson.jsp?json=true&ip={$ip}";
            $res=trim(cms_http::get(['url'=>$url]));
            $res=mb_convert_encoding($res,"utf-8",mb_detect_encoding($res,['UTF-8','GBK','GB2312']));
            $data=D($res);
            if(is_array($data))
            {
                $add=['country'=>$data['pro'],'city'=>$data['city']];
                S('post_adds',$add);
            }
            else
            {
                return '';
            }
        }
        return $add;
    }
    
}