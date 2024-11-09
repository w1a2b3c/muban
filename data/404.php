<?php if(!defined('IN_CMS')) exit;?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $e['title'];?></title>
    <link href="/public/css/ui.css" rel="stylesheet" type="text/css">
    <style>
	html{display:flex;justify-content:center;align-items:center;height:100%;}
	body{background:#F0F4FB;display:flex;justify-content:center;align-items:center;height:100%;}
	.page-main{margin:0 auto;}
	.ui-dialog-tips{position:relative;border-radius:10px;}
	.ui-dialog-tips .ui-dialog-header .ui-dialog-title{cursor:auto;font-size:16px;}
	.ui-dialog-tips .ui-dialog-body{padding:20px;min-height:60px;}
	.ui-dialog-tips .ui-dialog-footer{padding-bottom:20px;}
	.ui-dialog-tips .ui-dialog-footer a{color:#666;}
	.ui-dialog-tips .ui-dialog-footer a:hover{color:#1890FF}
	.ui-dialog-tips .ui-dialog-footer a.blue{color:#fff;}
	@media screen and (max-width:640px)
	{
		.page-main{min-width:96%;max-width:96%;padding:30px;}
	}
    </style>
</head>
<body>

    <div class="page-main">
    	<div class="ui-dialog ui-dialog-tips">
            <div class="ui-dialog-header">
                <div class="ui-dialog-title"><?php echo $e['title'];?></div>
            </div>
            <div class="ui-dialog-body"><?php echo $e['msg'];?></div>
            <?php if($e['url']!=''){?>
            <div class="ui-dialog-footer ui-row ui-align-items-center ui-justify-content-between">
            	<div class="ui-font-15 ui-text-left"><span class="ui-text-red" id="wait">10</span> 秒后，自动跳转</div>
                <div class="ui-text-right"><a class="ui-btn blue small ui-font-12 ui-mr-sm" id="href" href="<?php echo $e['url'];?>"><?php echo $e['btn'];?></a></div>
            </div>
            <?php }?>
        </div>
    </div>
<?php if($e['url']!=''){?>
<script>
(function()
{
	var wait=document.getElementById('wait');
	var href=document.getElementById('href').href;
	var interval=setInterval(function()
	{
		var time=--wait.innerHTML;
		if(time<=0)
		{
			location.href=href;
			clearInterval(interval);
		};
	},1000);
})();
</script>
<?php }?>
</body>
</html>