<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}
<script src="{WEB_ROOT}public/upload/upload.js"></script>

    <div class="ui-mwidth">
        <div class="ui-bg-white ui-p-15">
            <ul class="ui-media-list ui-media-border-none">
                <li class="ui-media">
                    <div class="ui-media-img ui-mr-20 ui-radius upload-face ui-tips" data-title="上传头像" data-type="one" data-name="uface" url="{U('face')}" maxsize="{config('upload_max')}" data-minsize="{config('upload_slice')}">
                        <img src="{$user.uface}" id="uface" width="64" height="64">
                    </div>
                    <div class="ui-media-body">
                        <div class="ui-media-header ui-mt-sm">{$user.umobile}</div>
                        <div class="ui-media-text ui-text-gray">{$user.title} <a href="{N('upgrade')}" class="ui-btn little yellow ui-ml">升级VIP</a> {if cms[auth_state]!=''}<a href="{N('auth')}" class="ui-btn little {if $user.isauth==1}green{else}yellow{/if} ui-ml">{if $user.isauth==1}已认证{else}未认证{/if}</a>{/if}</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="ui-mwidth ui-bg-white ui-mt">
    	<div class="ui-grid icon{if IE()} flex{/if}">
        	<a class="ui-grid-item yellow" href="{U('money')}"><i class="ui-icon-moneycollect"></i>我的佣金{if $user.umoney>0}<span class="ui-badge red">{getprice($user.umoney)}</span>{/if}</a>
            <a class="ui-grid-item blue" href="{U('order')}"><i class="ui-icon-shopping"></i>我的订单</a>
            <a class="ui-grid-item blue" href="{U('bill')}"><i class="ui-icon-file-pdf"></i>发票管理</a>
            <a class="ui-grid-item blue" href="{U('love')}"><i class="ui-icon-star"></i>我的收藏</a>
            <a class="ui-grid-item blue" href="{U('buy')}"><i class="ui-icon-propertysafety"></i>我的购买</a>
            <a class="ui-grid-item blue" href="{U('down')}"><i class="ui-icon-cloud-download"></i>我的下载</a>
        </div>
    </div>
    
    {if config('user_post')==1}
    <div class="ui-mwidth ui-bg-white ui-mt">
		<ul class="ui-list">
			<li><a href="{U('post')}"><i class="ui-icon-file-word ui-text-blue ui-ml-sm ui-mr-sm"></i> 我的稿件</a><div class="list-right"><span class="arrow"></span></div></li>
            <li><a href="{U('postadd')}"><i class="ui-icon-plus ui-text-blue ui-ml-sm ui-mr-sm"></i> 我要投稿</a><div class="list-right"><span class="arrow"></span></div></li>
		</ul>
	</div>
    {/if}
    
    {if getint(config('share_lever'))>0}
    <div class="ui-mwidth ui-bg-white ui-mt">
		<ul class="ui-list">
			<li><a href="{U('line')}"><i class="ui-icon-apartment ui-text-blue ui-ml-sm ui-mr-sm"></i> 我的分销</a><div class="list-right"><span class="arrow"></span></div></li>
		</ul>
	</div>
	{/if}
	<div class="ui-mwidth ui-bg-white ui-mt">
		<ul class="ui-list">
        	<li><a href="{U('my')}"><i class="ui-icon-user ui-text-blue ui-ml-sm ui-mr-sm"></i> 账号信息</a><div class="list-right"><span class="arrow"></span></div></li>
        	<li><a href="{U('bind')}"><i class="ui-icon-switchuser ui-text-blue ui-ml-sm ui-mr-sm"></i> 账号绑定</a><div class="list-right"><span class="arrow"></span></div></li>
			<li><a href="{U('editpass')}"><i class="ui-icon-lock ui-text-blue ui-ml-sm ui-mr-sm"></i> 修改密码</a><div class="list-right"><span class="arrow"></span></div></li>
		</ul>
	</div>
	
	<div class="ui-mwidth ui-bg-white ui-mt">
		<ul class="ui-list">
			<li><a href="{N('out')}" class="ui-confirm" data-title="确定要退出？"><i class="ui-icon-logout text-blue ui-ml-sm ui-mr-sm"></i> 退出登录</a><div class="list-right"><span class="arrow"></span></div></li>
		</ul>
	</div>

</body>
</html>