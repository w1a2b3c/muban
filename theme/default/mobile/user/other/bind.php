<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-10">
    	<!---->
        
        <div class="ui-p ui-bg-white ui-mt-15">
        	<ul class="ui-media-list ui-media-border">
            	{cms:rs from="cms_plug" where="state=1 and type='login'" order="id desc"}
                {php $key=$rs.root}
                {if $key=='loginwx'}
                {php $key='loginweixin'}
                {/if}
                <li class="ui-media {if $rs.root=='loginweixin' && !ispc()}ui-hide{/if} {if $rs.root=='loginwx' && !isweixin()}ui-hide{/if}">
                    <div class="ui-media-img ui-mr-20 ui-radius" style="margin-top:-10px;">
                        <img src="{WEB_ROOT}plug/{$rs.root}/{$rs.icon}" width="80">
                    </div>
                    <div class="ui-media-body">
                        <div class="ui-media-header">{$rs.name}</div>
                        <div class="ui-mt">
                        {if in_array($key,$data)}<span class="ui-badge green">已绑定</span>{else}<span class="ui-badge">未绑定</span>{/if}
                        </div>
                    </div>
                    <div class="ui-media-link ui-pt">
                    {if in_array($key,$data)}<a href="{THIS_LOCAL}" data-data="token={$token}&key={$key}" class="ui-btn red small ui-action" data-title="确定要解除绑定？">解绑</a>{else}<a href="{U('plug/'.$rs.root.'/index/index','type=bind')}" class="ui-btn blue outline small">绑定</a>{/if}
                    </div>
                </li>
                {/cms:rs}
            </ul>
        </div>
        <!---->
    </div>

</body>
</html>