<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}

    <div class="ui-mwidth ui-bg-white ui-p-30">
    	<!---->
        <div class="page-bind">
             <div class="ui-alert red ui-mt-30 ui-mb-30">找不到您的账号，请先注册或绑定！</div>
             
             <div class="bind-list">
             	<a href="{N('reg')}">注册新账号<p>没有账号，我需要注册一个账号。</p></a>
                <a href="{N('login')}">绑定已有账号<p>已经有账号了，我需要绑定账号。</p></a>
             </div>
             
        </div>
        <!---->
    </div>

</body>
</html>