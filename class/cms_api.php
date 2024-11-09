<?php
/**
 * 作用：接口处理程序
 * 官网：Https://www.nicemb.com
 * 作者：IT平民
 * ===========================================================================
 * 未经授权不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
**/

final class cms_api
{
    private $db;
    function __construct()
    {
        $this->db=cms::$db;
    }

    #后台日志记录
    function log($msg='')
    {
        if(getint(config('admin_log_admin'))==0)
        {
            return;
        }
        $url=rawurldecode(str_replace(WEB_URL,'',THIS_LOCAL));
        $encode=mb_detect_encoding($url,['UTF-8','GBK','GB2312']);
        if($encode!='UTF-8')
        {
            $url=mb_convert_encoding($url,'utf-8',$encode);   
        }
        $arr=[];
        $arr['ip']=getip();
        $arr['title']=get_admin_info('adminname');
        $arr['msg']=cut(enhtml($msg),255);
        $arr['createdate']=time();
        $arr['url']=cut(enhtml($url),255);
        $this->db->add('cms_admin_log',$arr);
    }

    function encode($a,$b='nicemb.com')
    {
        if($a=='')
        {
            return '';
        }
        $pre=($b!='')?$b:config('prefix');
        return authcode(E($a),'E',$pre);
    }
    
    function decode($a,$b='nicemb.com')
    {
        if($a=='')
        {
            return '';
        }
        $pre=($b!='')?$b:config('prefix');
        $a=D(authcode($a,'D',$pre));
        $a=$a?$a:[];
        return $a;
    }

    function task()
    {
        if(!IS_INSTALL)
        {
            return;
        }
        #间隔时间，单位分钟
        $unit=1;
        $sys_task=0;
        if(cms_cache::check('task'))
        {
            $data=(cms_cache::get('task'));
            $sys_task=$data['task'];
        }
        if((time()-$sys_task)>=(60*$unit))
        {
            $sys_task=cms_cache::set('task',$this->task_action());
        }
    }

    function task_action()
    {
        $time=time();
        #定时发布
        $rs=$this->db->load("select id from cms_show where isauto=1 and createdate<=$time order by id desc limit 100");
        if($rs)
        {
            $dt=[];
            foreach($rs as $key=>$val)
            {
                $dt[]=$val['id'];
            }
            $res=implode(',',$dt);
            $this->db->update("cms_show","id in($res)",['isshow'=>1,'isauto'=>0]);
        }
     
        #过期会员
        $rs=$this->db->load("select atid from cms_user where uid>1 and enddate<=$time order by atid desc limit 100");
        if($rs)
        {
            $dt=[];
            foreach($rs as $key=>$val)
            {
                $dt[]=$val['atid'];
            }
            $res=implode(',',$dt);
            $this->db->update("cms_user","atid in($res)",['uid'=>1,'enddate'=>$time]);
        }

        #过期token
        $rs=$this->db->load("select id from cms_token where overdate<=$time order by id desc limit 10");
        if($rs)
        {
            $dt=[];
            foreach($rs as $key=>$val)
            {
                $dt[]=$val['id'];
            }
            $res=implode(',',$dt);
            $this->db->del("cms_token","id in($res)");
        }
        #预埋Hook
        cms_hook::add('task',$this);
        unset($rs);
        return ['task'=>$time];
    }

    #自动登录(微信openid)
    function auto_login()
    {
        if(isweixin() && S('user_out'))
        {
            return;
        }
        $openid=S('openid');
        $unionid=S('unionid');
        if($openid!='' || $unionid!='' && IS_GET)
        {
            S('lasturl',THIS_LOCAL);
            $this->quick_login('loginweixin',$openid,$unionid,1);
        }
    }

    #微信访问自动获取Openid
    function get_openid()
    {
        if(isweixin() && getint(config('weixin_openid'))==1)
        {
            #自定义加密解密key，可以留空
            $key=md5($_SERVER['HTTP_HOST']);
            if(S('openid')=='')
            {
                if(F('get.openid')=='')
                {
                    $backurl=THIS_LOCAL;
                    if(F('get.backurl')!='')
                    {
                        $backurl=F('get.backurl');
                    }
					$backkey=F('get.backkey');
                    if($backkey=='')
                    {
                        $backkey=$key;
                    }
                    S('backurl',$backurl);
                    S('backkey',$backkey);
                    $openid_url=config('weixin_openid_url');
                    if($openid_url=='')
                    {
                        $openid_url=U('pay/index/openid');
                    }
                    else
                    {
                        $openid_url=trim($openid_url,"/")."/?backkey=".$backkey."&backurl=".WEB_URL.THIS_LOCAL;
                    }
                    G($openid_url);
                    return;
                }
                else
                {
                    $backkey=F('get.backkey');
                    if($backkey=='')
                    {
                        $openid=F('get.openid');
                        $unionid=F('get.unionid');
                    }
                    else
                    {
                        $str=F('get.openid');
                        if($backkey==$key)
                        {
                            $str=$this->decode(base64_decode($str),$key);
                            list($openid,$unionid)=explode("||",$str,2);
                        }
                    }
                    S('openid',$openid);
                    S('unionid',$unionid);
                }
            }
            else
            {
                if(F('get.backurl')!='')
                {
                    $backurl=F('get.backurl');
                    S('backurl',$backurl);
                }
            }
            $backurl=S('backurl');
            if($backurl!='' && strpos($backurl,'://'))
            {
                S('backurl','[del]');
                $backurl=$backurl.'?openid='.S('openid').'&unionid='.S('unionid');
                G($backurl);
            }
        }
    }
    
    function quick_login($type,$openid,$unionid='',$mode=0)
    {
        if($mode==1 && USER_ID>0)
        {
            return;
        }
        $where="openid='$openid'";
        if($unionid!='')
        {
            $where="(unionid='$unionid')";
        }
        $rs=$this->db->row("select userid from cms_user_login where $where and type='$type' limit 1");
        if($rs)
        {
            $userid=$rs['userid'];
            S('user_id',$userid);
            #增加登录次数
            $rs=$this->db->row("select logintimes from cms_user where atid=$userid limit 1");
            if($rs)
            {
                $this->db->update("cms_user","atid=$userid",['logintimes'=>($rs['logintimes']+1)]);
            }
            #登录hook
            cms_hook::add('user_login',$this,$userid);
            $lasturl=S("lasturl");
			if(empty($lasturl))
			{
				$lasturl=U('home/user/index');
			}
            S("lasturl",'[del]');
            echo "<script>parent.location.href='$lasturl'</script>";
        }
        else
        {
            if($mode==0)
            {
                S('quick_info',['type'=>$type,'openid'=>$openid,'unionid'=>$unionid]);
                G(U('home/user/tips'));
                return;
            }
        }
    }

    function quick_bind($type,$openid,$unionid='')
    {
        if(USER_ID==0)
        {
            cms::error('账号未登录或登录超时',WEB_ROOT);
        }
        else
        {
            $where="openid='$openid'";
            if($unionid!='')
            {
                $where="(openid='$openid' or unionid='$unionid')";
            }
            $rs=$this->db->row("select userid from cms_user_login where $where and type='$type' limit 1");
            if(!$rs)
            {
                $this->db->add("cms_user_login",['userid'=>USER_ID,'type'=>$type,'openid'=>$openid,'unionid'=>$unionid,'binddate'=>time()]);
                G(U('home/user/bind'));
            }
            else
            {
                cms::error('已绑定其他账号，请解绑后再试',WEB_ROOT);
            }
        }
    }

    function api_reg($userid)
    {
        $quick_info=S('quick_info');
        if(!empty($quick_info))
        {
            $type=$quick_info['type'];
            $openid=$quick_info['openid'];
            $unionid=$quick_info['unionid'];
            $where="openid='$openid'";
            if($unionid!='')
            {
                $where="(openid='$openid' or unionid='$unionid')";
            }
            $rs=$this->db->row("select userid from cms_user_login where type='$type' and userid=$userid limit 1");
            if(!$rs)
            {
                $this->db->add("cms_user_login",['userid'=>$userid,'type'=>$type,'openid'=>$openid,'unionid'=>$unionid,'binddate'=>time()]);
            }
            S('quick_info','[del]');
        }
    }

    function api_login($userid)
    {
        $res='success';
        $quick_info=S('quick_info');
        if(!empty($quick_info))
        {
            $type=$quick_info['type'];
            $openid=$quick_info['openid'];
            $unionid=$quick_info['unionid'];
            $where="openid='$openid'";
            if($unionid!='')
            {
                $where="(openid='$openid' or unionid='$unionid')";
            }
            $rs=$this->db->row("select userid from cms_user_login where type='$type' and userid=$userid limit 1");
            if(!$rs)
            {
                $this->db->add("cms_user_login",['userid'=>$userid,'type'=>$type,'openid'=>$openid,'unionid'=>$unionid,'binddate'=>time()]);
            }
            else
            {
                $res='fail';
            }
        }
        return $res;
    }

    #筛选数据
    function filter($a)
    {
        $filter=trim($a,',');
        $data=[];
        if($filter!='')
        {
            $rs=$this->db->load("select b.name,a.name as val from cms_filter_list a left join cms_filter b on a.pid=b.aid where id in($filter) order by ordnum,aid");
            if($rs)
            {
                foreach($rs as $key=>$val)
                {
                    $data[$val['name']]=$val['val'];
                }
            }
            unset($rs);
        }
        return $data;
    }

    #内容Url
    function url($id)
    {
        $url='';
        $rs=$this->db->row("select id,alias,classid from cms_show where id=$id limit 1");
        if($rs)
        {
            $url=show_url($rs['id'],$rs['alias'],$rs['classid']);
        }
        return $url;
    }

    #自动升级
    function auto_upgrade($userid)
    {
        if($userid==0)
        {
            return;
        }
        $rs=$this->db->row("select amount,uid,isupgrade from cms_user a left join cms_user_group b on a.uid=b.aid where atid=$userid");
        if($rs)
        {
            if($rs['isupgrade']==0)
            {
                return;
            }
            $amount=$rs['amount'];
            $uid=$rs['uid'];
            if($amount>0 && $uid>0)
            {
                $rg=$this->db->row("select aid from cms_user_group where $amount>=upvalue and aid<>$uid and isupgrade=1 order by upvalue desc");
                if($rg)
                {
                    $this->db->update("cms_user","atid=$userid",['uid'=>$rg['aid']]);
                }
            }
        }
    }

    #增加消费累计
    function pay_total($userid,$money)
    {
        if($money<=0 || $userid==0)
        {
            return;
        }
        $rs=$this->db->row("select amount from cms_user where atid=$userid");
        if(!$rs)
        {
            return;
        }
        $this->db->update("cms_user","atid=$userid",['amount'=>$rs['amount']+$money]);
    }

    #佣金入账
    function entry_money($userid,$money,$remark)
    {
        if($money<=0 || $userid<=0)
        {
            return;
        }
        $rs=$this->db->row("select umoney from cms_user where atid=$userid");
        if(!$rs)
        {
            return;
        }
        $umoney=$rs['umoney'];
        $d=[];
        $d['userid']=$userid;
        $d['money']=$money;
        $d['type']=1;
        $d['title']=$remark;
        $d['oldmoney']=$umoney;
        $d['newmoney']=$umoney+$money;
        $d['createdate']=time();
        $this->db->add('cms_user_money',$d);
        $this->db->update("cms_user","atid=$userid",['umoney'=>getprice($umoney+$money)]);
    }

    #分销返现
    function dealshare($pid,$total,$lever,$orderid)
    {
        if($lever<=getint(config('share_lever'),1))
        {
            $ru=$this->db->row("select pid from cms_user where atid=$pid and islock=1 limit 1");
            if($ru)
            {
                $mypid=$ru['pid'];
                if($mypid>0)
                {
                    $amount=getprice($total*config('share_lever_'.$lever.'')/100);
                    #如果只开启一级，则给100%
                    if(config('share_lever')==1)
                    {
                        $amount=getprice($total);
                    }
                    $remark="分销收入（{$lever}级），订单号：{$orderid}";
                    $this->entry_money($mypid,$amount,$remark);
                    
                    if($lever<3)
                    {
                        return $this->dealshare($mypid,$total,($lever+1),$orderid);
                    }
                }
                
            }
        }
    }

    #支付业务处理
    function paycall($orderid,$type,$trade_no)
    {
        $rs=$this->db->row("select order_id,ispay,order_type,goods_total,goods_id,order_total,goods_money,userid,userkey from cms_order where order_no='$orderid' and ispay=0 limit 1");
        if($rs)
        {
            $order_id=$rs['order_id'];
            $order_total=$rs['order_total'];
            $goods_money=$rs['goods_money'];
            $userid=$rs['userid'];
            $userkey=$rs['userkey'];
            $goods_id=$rs['goods_id'];
            $goods_total=$rs['goods_total'];
            $order_type=$rs['order_type'];
            $author=0;

            #修改订单付款状态
            $this->db->update("cms_order","order_id=$order_id",['ispay'=>1,'paydate'=>time(),'payway'=>$type,'trade_no'=>$trade_no]);

            #购买内容
            if($order_type==1)
            {
                $rg=$this->db->row("select tsales,userid from cms_show where id=$goods_id limit 1");
                if($rg)
                {
                    $author=$rg['userid'];
                    $this->db->update("cms_show","id=$goods_id",['tsales'=>($rg['tsales']+1)]);
                }
                
                #写入购买记录
                $this->db->add("cms_user_buy",['userid'=>$userid,'userkey'=>$userkey,'cid'=>$goods_id,'cprice'=>$goods_total,'postdate'=>time()]);

                #通知管理员交易信息
                if(config('sms_state')!='')
                {
                    cms_app::init('sms');
                    cms_app::send(config('sms_admin'),'order_pay',['orderid'=>$orderid,'money'=>$order_total]);
                }
            }

            #会员组
            if($order_type==2)
            {
                $rg=$this->db->row("select type,num,price from cms_user_group where aid=$goods_id limit 1");
                if($rg)
                {
                    $date=time();
                    switch($rg['type'])
                    {
                        case "1":
                            $date=strtotime("+".$rg['num']." day");
                            break;
                        case "2":
                            $date=strtotime("+".$rg['num']." month");
                            break;
                        case "3":
                            $date=strtotime("+".$rg['num']." year");
                            if($date=='')
                            {
                                $date=time()+86400*365*$rg['num'];
                            }
                            break;
                        case "4":
                            $date=strtotime("+100 year");
                            #修复部分虚拟主机获取不到的BUG
                            if($date=='')
                            {
                                $date=time()+86400*365*100;
                            }
                            break;
                    }
                    $this->db->update("cms_user","atid=$userid",['uid'=>$goods_id,'enddate'=>$date]);
                    $goods_money=getprice($rg['price']*getint(config('share_user'))/100);
                }
            }

            #佣金入账(分销返现)
            if($goods_money>0 && getint(config('share_lever'))>0)
            {
                $this->dealshare($userid,$goods_money,1,$orderid);
            }

            #投稿收入
            if($author>0)
            {
                $ru=$this->db->row("select percent from cms_user where atid=$author");
                $percent=($rs)?$ru['percent']:0;
                if($percent>0 && $percent<=100)
                {
                    $pre=($userid>0)?'会员':'游客';
                    $this->entry_money($author,$order_total*$percent/100,"投稿收入，{$pre}购买(商品ID：{$goods_id})");
                }
            }

            #增加消费累计
            $this->pay_total($userid,$order_total);

            #自动升级
            if($order_type!=2)
            {
                $this->auto_upgrade($userid);
            }
        }
    }
    
}