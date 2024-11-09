<?php if(!defined('IN_CMS')) exit;?>{include file="mobile/head.php"}
<link rel="stylesheet" href="{WEB_ROOT}public/cascader/cascader.css"/>
<script>{$tree}</script>
<script src="{WEB_ROOT}public/cascader/cascader.js"></script>
<script src="{WEB_ROOT}public/upload/upload.js?v={time()}"></script>
<script src="{WEB_ROOT}public/editor/editor.js?v={time()}"></script>
    <div class="ui-mwidth ui-bg-white ui-p-10">
    	<!---->
        <form class="ui-form" data-back="{$backurl}">
            <div class="ui-form-group">
                <div class="ui-input-group">
                    <div class="before">稿件标题</div>
                    <input type="text" name="title" maxlength="255" class="ui-form-ip radius-left-none" placeholder="请输入标题" data-rule="标题:required;">
                </div>
            </div>
            <div class="ui-form-group">
                <div class="ui-input-group">
                    <div class="before">所属分类</div>
                    <input name="classid" id="classid" data-rule="栏目:required;" class="ui-form-ip radius-left-none" />
                </div>
                <div class="ui-mt ui-text-red">保存后分类不可修改</div>
            </div>
            <div class="ui-form-group pay_type ui-hide">
                <div class="ui-input-group">
                    <div class="before">收费方式</div>
                    <div class="ui-form-ip radius-left-none">
                        <label class="ui-radio"><input type="radio" name="payway" id="payway_1" value="1"{if config("pay_way")==1} checked{/if}><i></i>免费</label>
                        <label class="ui-radio"><input type="radio" name="payway" id="payway_2" value="2"{if config("pay_way")==2} checked{/if}><i></i>VIP免费</label>
                        <label class="ui-radio"><input type="radio" name="payway" id="payway_3" value="3"{if config("pay_way")==3} checked{/if}><i></i>收费</label>
                     </div>
                </div>
            </div>
            <div class="ui-form-group payway_money ui-hide">
                <div class="ui-input-group">
                    <div class="before">出售价格</div>
                    <input type="text" name="price" class="ui-form-ip radius-none" maxlength="8" value="0" data-rule="价格:required;dot">
                    <span class="after blue">元</span>
                </div>
            </div>
            {if getint(config('discount_type'))==1}
            <div class="ui-form-group payway_money ui-hide">
                <div class="ui-input-group">
                    <div class="before">折扣设置</div>
                    <div class="ui-form-ip">
                        <div class="ui-row" style="margin:-10px 0 0 -15px;">
                            {cms:rs top="0" table="cms_user_group" order="ordnum,aid"}
                            <div class="ui-col-12 ui-mt">
                                <div class="ui-input-group">
                                    <div class="before blue">{$rs[title]}</div>
                                    <input type="text" name="discount[{$rs[aid]}]" class="ui-form-ip radius-none" maxlength="4" value="{getprice($rs[discount])}">
                                    <span class="after">%</span>
                                </div>
                            </div>
                            {/cms:rs}
                        </div>
                    </div>
                </div>
            </div>
            {/if}
            <div class="ui-form-group payway_vip ui-hide">
                <div class="ui-input-group">
                    <div class="before">阅读权限</div>
                    <div class="ui-form-ip radius-left-none">
                        {cms:rs top="0" table="cms_user_group" where="type>0" order="ordnum,aid"}
                        <label class="ui-checkbox"><input type="checkbox" name="vipgroup[]" value="{$rs[aid]}"><i></i>{$rs[title]}</label>
                        {/cms:rs}
                     </div>
                </div>
            </div>
            <div class="ui-form-group money_down ui-hide">
                <div class="ui-input-group">
                    <div class="before">下载类型</div>
                    <div class="ui-form-ip radius-left-none ui-pt">
                        <label class="ui-radio"><input type="radio" name="down_type" id="down_type_0" value="0"{if config('down_type')==0} checked{/if}><i></i>本地资源</label>
                        <label class="ui-radio"><input type="radio" name="down_type" id="down_type_1" value="1"{if config('down_type')==1} checked{/if}><i></i>网盘资源</label>
                     </div>
                </div>
            </div>
            <div class="ui-form-group money_down down_local ui-hide">
                <div class="ui-input-group">
                    <div class="before">下载地址</div>
                    <input type="text" name="downurl" id="downurl" placeholder="请输入下载地址" class="ui-form-ip radius-none" data-rule="下载地址:required;">
                    <a class="after blue ui-upload" data-name="downurl" data-accept="zip" data-type="one" url="{U('upload/upfile','type=4')}" maxsize="{config('upload_max')}" data-minsize="{config('upload_slice')}">上传</a>
                </div>
            </div>
            <div class="ui-form-group money_down down_pan ui-hide">
                <div class="ui-input-group">
                    <div class="before">网盘地址</div>
                    <input type="text" name="panurl" class="ui-form-ip radius-right-none" data-rule="网盘地址:required;">
                    <span class="after blue"><input type="text" name="panpass" placeholder="提取码" class="radius-left-none" data-rule="提取码:required;"></span>
                </div>
            </div>
            <div class="ui-form-group money_down ui-hide">
                <div class="ui-input-group">
                    <div class="before">演示链接</div>
                    <input type="text" name="demourl" maxlength="255" class="ui-form-ip radius-left-none">
                 </div>
            </div>
            <div class="ui-form-group money_video ui-hide">
                <div class="ui-input-group">
                    <div class="before">视频类型</div>
                    <div class="ui-form-ip ui-pt">
                        <label class="ui-radio"><input type="radio" name="video_type" id="video_type_0" value="0"{if config('video_type')==0} checked{/if}><i></i>单个视频</label>
                        <label class="ui-radio"><input type="radio" name="video_type" id="video_type_1" value="1"{if config('video_type')==1} checked{/if}><i></i>视频集</label>
                     </div>
                </div>
            </div>
            <div class="ui-form-group money_video video_one ui-hide">
                <div class="ui-input-group">
                    <div class="before">视频地址</div>
                    <input type="text" name="videourl" id="videourl" placeholder="请输入视频地址" class="ui-form-ip radius-none" data-rule="视频地址:required;">
                    <a class="after blue ui-upload" data-name="videourl" data-accept="video" data-type="one" url="{U('upload/upfile','type=3')}" maxsize="{config('upload_max')}" data-minsize="{config('upload_slice')}">上传</a>
                </div>
            </div>
            <div class="ui-form-group money_video video_more ui-hide">
                <div class="ui-input-group">
                    <div class="before">视频地址</div>
                    <!---->
                    <div class="ui-form-ip" style="position:relative;padding:0;">
                        <div class="ui-btn-group ui-mb-15 ui-ml ui-mt">
                            <a class="ui-btn-group-item video-add">单个添加</a>
                            <a class="ui-btn-group-item video-more">批量添加</a>
                            <a class="ui-btn-group-item ui-upload" data-type="more" data-video="1" data-name="video_list" url="{U('upload/upfile','type=3&thumb=0&water=0')}" maxsize="{config('upload_max')}" data-minsize="{config('upload_slice')}">本地上传</a>
                        </div>
                        {if !ismobile()}
                        <table class="ui-table border blue" style="position:absolute;top:65px;left:0;right:0;">
                            <thead>
                                <tr>
                                    <th width="60">排序</th>
                                    <th>视频标题</th>
                                    <th>视频地址</th>
                                    <th width="60">免费<i class="ui-icon-info-circle ui-font-16 ui-text-gray ui-ml-sm ui-tips" data-title="是否免费播放"></i></th>
                                    <th width="70" id="fixed-width">操作</th>
                                </tr>
                            </thead>
                        </table>
                        {/if}
                        <div class="ui-table-wrap" id="video-wrap" style="max-height:350px;overflow-y:scroll">
                        <table class="ui-table border blue">
                            <thead>
                                <tr>
                                    <th width="60">排序</th>
                                    <th>视频标题</th>
                                    <th>视频地址</th>
                                    <th width="60">免费<i class="ui-icon-info-circle ui-font-16 ui-text-gray ui-ml-sm ui-tips" data-title="是否免费播放"></i></th>
                                    <th width="70">操作</th>
                                </tr>
                            </thead>
                            <tbody id="video_list"></tbody>
                            </table>
                        </div>
                    </div>
                    <!---->
                </div>
            </div>
            <div class="ui-form-group group_pic ui-hide">
                <label>组图</label>
                <!---->
                <div class="ui-form-image">
                    <div class="ui-form-image-item">
                        <input type="text" name="piclist" class="ui-hide" id="piclist" value="">
                        <div class="upload ui-upload ui-w-100" data-type="more" data-name="piclist[]" url="{U('upload/upfile','type=1&thumb='.getint(config('thumb_piclist')).'&water='.getint(config('water_piclist')).'')}" maxsize="{config('upload_max')}" data-minsize="{config('upload_slice')}"><i class="ui-icon-cloud-upload"></i></div>
                    </div> 
                </div>
                <!---->
                <div class="ui-mt-20 ui-text-red">尺寸建议：500*500</div>
            </div>
            <div class="ui-form-group filter ui-hide">
                <label>筛选：</label>
                <div class="">
                    <!---->
                    <div style="border:1px dashed #ccc;padding:15px 15px 0 15px;">
                    {loop data="$filter_data"}
                    <div class="ui-form-group ui-row filter_list" data-num="{$key}">
                        <span style="width:100px;" class="ui-inline-block ui-bold">{$val.name}：</span>
                        <div style="flex:1;overflow:hidden;" class="ui-inline-block">
                            {loop data="$val.list" key="aa" val="bb"}
                            <label class="ui-checkbox"><input type="checkbox" name="filter[]" value="{$aa}"><i></i>{$bb}</label>
                            {/loop}
                        </div>
                    </div>
                    {/loop}
                    </div>
                    <!---->
                </div>
            </div>
            <div class="ui-form-group">
                <label>内容介绍</label>
                <script id="content" name="content" class="ui-editor" type="text/plain"></script>
            </div>
            
            <div class="ui-form-group">
                <div class="ui-col-right">
                    <div class="ui-input-group">
                        <div class="before">缩略图</div>
                        <input type="text" name="pic" id="pic" class="ui-form-ip radius-none">
                        <a class="after ui-upload blue radius-none" data-name="pic" data-type="one" url="{U('upload/upfile','type=1')}" title="上传">上传</a>
                        <a class="after ui-lightbox blue" data-hide="true" data-id="pic" data-name="ui-lightbox-pic" title="缩略图">预览</a>
                    </div>
                 </div>
            </div>
            <div class="ui-form-group">
                <input type="hidden" name="token" value="{$token}">
                <button type="submit" class="ui-btn blue block ui-btn-radius">提交</button>
            </div>
        </form>
        <!---->
    </div>
<script src="{WEB_ROOT}public/js/sortable.js"></script>
<script>
var api="{U('upload/imagelist')}";
var url="{U('upload/index')}";
var save="{U('upload/outimage')}";
var max="{config('upload_max')}";
var size="{config('upload_slice')}";
var token='{$token}';
var classid=0;
var disabled=false;
new Sortable($(".ui-form-image")[0],
{
	animation: 150,
	handle:'.ui-form-image-item',
	filter:'.disabled'
});

new Sortable($("#video_list")[0],
{
	animation: 150,
	handle:'.ui-select-tr',
});
</script>
<script src="{WEB_THEME}js/post.js?v={time()}"></script>
</body>
</html>