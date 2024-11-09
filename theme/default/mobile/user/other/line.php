<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-10">
    	<!---->
		<div class="ui-menu blue">
            <div class="ui-menu-name">邀请好友</div>
        </div>
        <div class="ui-p-20">
        	<div class="ui-form-group ui-mt">
            	<div class="ui-input-group">
            		<input type="text" class="ui-form-ip radius-right-none" id="myline" value="{WEB_URL}{U('reg','uid='.$userid.'')}"><button type="button" class="blue after ui-copy" data-target="#myline">复制</button>
            	</div>
                <div class="ui-pt ui-text-gray">您的好友通过推广链接注册后，将成为您的下线。购买付费内容后您将获得佣金提成。</div>
                
            </div>
        </div>
        
        <div class="ui-menu blue">
            <div class="ui-menu-name">推广二维码</div>
        </div>
        <div id="qrcode" style="width:100%;text-align:center;padding:20px;"></div>
        
        <div class="ui-menu blue">
            <div class="ui-menu-name">我的下线</div>
        </div>
        
        {if getint(config('share_lever'))>1}
        <div class="ui-btn-group full ui-mt-20">
            <a class="ui-btn-group-item{if $type==1} active{/if}" href="{U('line','type=1')}">一级分销</a>
            {if getint(config('share_lever'))>1}<a class="ui-btn-group-item{if $type==2} active{/if}" href="{U('line','type=2')}">二级分销</a>{/if}
            {if getint(config('share_lever'))>2}<a class="ui-btn-group-item{if $type==3} active{/if}" href="{U('line','type=3')}">三级分销</a>{/if}
        </div>
        {/if}
        
        <div class="ui-p ui-bg-white ui-mt-15">
        	<ul class="ui-media-list ui-media-border">
               	{cms:rs pagesize="20" num="3" table="cms_user" where="$where" order="atid desc" key="atid"}
                {rs:eof}暂无下线{/rs:eof}
                <li class="ui-media">
                    <div class="ui-media-img ui-mr-20 ui-radius">
                        <img src="{$rs.uface}" width="80" height="80">
                    </div>
                    <div class="ui-media-body">
                        <div class="ui-media-header"><div>{$rs.umobile}</div><span style="font-weight:normal;font-size:14px;">{$rs.uname}</span></div>
                        <div class="ui-mt ui-text-gray ui-font-12">{date('Y-m-d H:i:s',$rs.regdate)}</div>
                    </div>
                    <div class="ui-media-link ui-font-14 ui-text-gray ui-pt">累计消费：<br><span class="ui-text-red">{$rs.amount}</span></div>
                </li>
                {/cms:rs}
            </ul>
        </div>
        <div class="ui-page center ui-mt ui-mb">
            <ul>{$showpage}</ul>
        </div>
        <!---->
    </div>
<script>new QRCode('qrcode',{text:"{WEB_URL}{U('reg','uid='.$userid.'')}",width:300,height:300});</script>
</body>
</html>