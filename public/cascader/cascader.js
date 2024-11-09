/**
 * 作用：级联选择器
 * 官网：Https://www.nicemb.com
 * 极品模板修改版
 * 版权来源：https://github.com/yixiaco/lay_cascader
**/

function stopPropagation(a){a=a||window.event,a.stopPropagation?a.stopPropagation():a.cancelBubble=!0}function Node(a,b,c,d){this.data=a,this.cascader=b,this.config=b.config,this.props=b.props,this.level=c,this.parentNode=d,this.icons=b.icons,this._checked=0,this._loading=!1,this.nodeId=b.data.nodeId++}function getDefaultConfig(){return{el:"",value:null,data:[],empty:"暂无数据",placeholder:"请选择",disabled:!1,clearable:!1,showAllLevels:!0,collapseTags:!1,minCollapseTagsNumber:1,separator:" / ",filterable:!1,filterMethod:function(a,b){return a.path.some(function(a){return-1!==a.label.indexOf(b)})},debounce:300,beforeFilter:function(){return!0},popperClass:"",extendClass:!1,extendStyle:!1,disabledFixed:!1,maxSize:0,props:{strictMode:!1,expandTrigger:"click",multiple:!1,checkStrictly:!1,lazy:!1,lazyLoad:function(){},value:"value",label:"label",children:"children",disabled:"disabled",leaf:"leaf"}}}function Cascader(a){this.config=$.extend(!0,getDefaultConfig(),a),this.data={nodeId:1,nodes:[],menuData:[],activeNodeId:null,activeNode:null,checkedNodeIds:[],checkedNodes:[]},this.showPanel=!1,this.event={change:[],open:[],close:[],destroy:[]},this.filtering=!1;var b=this;$(function(){b._init()}),this.closeEventId=0,this._maxSizeMode=null}Node.prototype={constructor:Node,get topParentNode(){return!this.parentNode&&this||this.topParentNode},childrenNode:void 0,get loading(){return this._loading},set loading(a){var c,d,e,b=this.$li;return b&&(c=this.icons.right,d=this.icons.loading,e=b.find("i"),a?(e.addClass(d),e.removeClass(c)):(e.addClass(c),e.removeClass(d))),this._loading=a},get label(){return this.data[this.props.label]},get value(){return this.data[this.props.value]},get disabled(){var f,g,h,a=this.props.multiple,b=this.config.maxSize,c=this.cascader.data.checkedNodeIds,d=this.props.disabled,e=this.props.checkStrictly;if(a&&0!==b&&c.length>=b&&-1===c.indexOf(this.nodeId)){if(e)return!0;if(f=this.getAllLeafChildren(),g=f.map(function(a){return a.nodeId}),!g.some(function(a){return-1!==c.indexOf(a)}))return!0}return e?this.data[d]:(h=this.path,h.some(function(a){return a.data[d]}))},get children(){return this.data[this.props.children]},set children(a){this.data[this.props.children]=a},get leaf(){var a=this.data[this.props.leaf];return"boolean"==typeof a?a:this.children?this.children.length<=0:!0},get activeNodeId(){return this.cascader.data.activeNodeId},get checkedNodeIds(){return this.cascader.data.checkedNodeIds},get path(){var a=this.parentNode;return a?a.path.concat([this]):[this]},get isFiltering(){return this.cascader.isFiltering},get $tag(){var a=this.cascader,b=this.config.showAllLevels,c=this.config.disabled,d=this.disabled,e=this.config.disabledFixed,f=this.getPathLabel(b),g=a.get$tag(f,!(c||d&&e)),h=this;return g.find("i").click(function(b){stopPropagation(b),h.selectedValue(),a.removeTag(h.value,h)}),g},getPathLabel:function(a){var d,b=this.path,c=this.config.separator;return d=a?b.map(function(a){return a.label}).join(c):b[b.length-1].label},init:function(){var a=this.props.multiple,b=this.props.checkStrictly,c=this.icons.from,d=this.icons.right,e="",f=this.label;this.leaf||(e=d),this.$li=$('<li role="menuitem" id="cascader-menu" tabindex="-1" class="el-cascader-node" aria-haspopup="true" aria-owns="cascader-menu"><span class="el-cascader-node__label">'+f+'</span><i class="'+c+" "+e+'"></i></li>'),a||b?!a&&b?this._renderRadioCheckStrictly():a&&!b?this._renderMultiple():a&&b&&this._renderMultipleCheckStrictly():this._renderRadio()},initSuggestionLi:function(){var a=this.getPathLabel(!0);this.$suggestionLi=$('<li tabindex="-1" class="el-cascader__suggestion-item"><span>'+a+"</span></li>"),this._renderFiltering()},bind:function(a){this.init(),a.append(this.$li)},bindSuggestion:function(a){this.initSuggestionLi(),a.append(this.$suggestionLi)},_renderFiltering:function(){var a=this.$suggestionLi,b=this.nodeId,c=this.icons.from,d=this.icons.ok,e=this,f=this.cascader,g=this.props.multiple,h='<i class="'+c+" "+d+' el-icon-check"></i>';a.click(function(c){stopPropagation(c),e.selectedValue(),g?-1===e.checkedNodeIds.indexOf(b)?(a.removeClass("is-checked"),a.find(".el-icon-check").remove()):(a.addClass("is-checked"),a.append(h)):f.close()}),(g&&-1!==e.checkedNodeIds.indexOf(b)||!g&&e.activeNodeId===b)&&(a.addClass("is-checked"),a.append(h))},_renderRadio:function(){var a=this.$li,b=this.nodeId,c=this.icons.from,d=this.icons.ok,e=this.level,f=this.leaf,g=this,h=this.cascader,i=this.cascader.data.activeNode,j=this.parentNode;return g.activeNodeId&&i.path.some(function(a){return a.nodeId===b})&&(g.activeNodeId===b&&a.prepend('<i class="'+c+" "+d+' el-cascader-node__prefix"></i>'),a.addClass("is-active"),a.addClass("in-checked-path")),this.disabled?(a.addClass("is-disabled"),void 0):(a.addClass("is-selectable"),j&&(j.$li.siblings().removeClass("in-active-path"),j.$li.addClass("in-active-path")),this._liClick(function(a){stopPropagation(a);var b=g.childrenNode;f&&"click"===a.type&&(g.selectedValue(),h.close()),h._appendMenu(b,e+1,g)}),void 0)},_renderRadioCheckStrictly:function(){var i,a=this.$li,b=this.nodeId,c=this.level,d=this.leaf,e=this,f=this.cascader,g=f.data.activeNode,h=this.parentNode;return a.addClass("is-selectable"),i=$('<label role="radio" tabindex="0" class="el-radio"><span class="el-radio__input"><span class="el-radio__inner"></span><input type="radio" aria-hidden="true" tabindex="-1" class="el-radio__original" value="'+b+'"></span><span class="el-radio__label"><span></span></span></label>'),this.$radio=i,a.prepend(i),h&&(h.$li.siblings().removeClass("in-active-path"),h.$li.addClass("in-active-path")),this._liClick(function(a){stopPropagation(a);var b=e.childrenNode;!e.disabled&&d&&"click"===a.type&&e.selectedValue(),f._appendMenu(b,c+1,e)}),e.activeNodeId&&g.path.some(function(a){return a.nodeId===b})&&(e.activeNodeId===b&&i.find(".el-radio__input").addClass("is-checked"),a.addClass("is-active"),a.addClass("in-checked-path")),this.disabled?(i.addClass("is-disabled"),i.find(".el-radio__input").addClass("is-disabled"),void 0):(i.click(function(a){a.preventDefault(),!d&&e.selectedValue()}),void 0)},_renderMultiple:function(){var h,a=this.$li,b=this.level,c=this.leaf,d=this,e=this.cascader,f=this._checked,g=this.parentNode;return a.addClass("el-cascader-node"),h=$('<label class="el-checkbox"><span class="el-checkbox__input"><span class="el-checkbox__inner"></span><input type="checkbox" aria-hidden="false" class="el-checkbox__original" value=""></span></label>'),this.$checked=h,a.prepend(h),1===f?this.$checked.find(".el-checkbox__input").addClass("is-checked"):2===f&&this.$checked.find(".el-checkbox__input").addClass("is-indeterminate"),g&&(g.$li.siblings().removeClass("in-active-path"),g.$li.addClass("in-active-path")),this._liClick(function(a){stopPropagation(a);var f=d.childrenNode;!d.disabled&&c&&"click"===a.type&&d.selectedValue(),e._appendMenu(f,b+1,d)}),this.disabled?(a.addClass("is-disabled"),h.addClass("is-disabled"),h.find(".el-checkbox__input").addClass("is-disabled"),void 0):(h.click(function(a){if(a.preventDefault(),!c){var f=d.childrenNode;d.selectedValue(),e._appendMenu(f,b+1,d)}}),void 0)},_renderMultipleCheckStrictly:function(){var j,k,a=this.$li,b=this.level,c=this.leaf,d=this,e=this.cascader,f=e.data.checkedNodeIds,g=e.data.checkedNodes,h=this.nodeId,i=this.parentNode;return a.addClass("el-cascader-node is-selectable"),j=$('<label class="el-checkbox"><span class="el-checkbox__input"><span class="el-checkbox__inner"></span><input type="checkbox" aria-hidden="false" class="el-checkbox__original" value=""></span></label>'),this.$checked=j,a.prepend(j),k=g.some(function(a){return a.path.some(function(a){return a.nodeId===h})}),k&&(a.addClass("in-checked-path"),-1!==f.indexOf(h)&&this.$checked.find(".el-checkbox__input").addClass("is-checked")),i&&(i.$li.siblings().removeClass("in-active-path"),i.$li.addClass("in-active-path")),this._liClick(function(a){stopPropagation(a);var f=d.childrenNode;!d.disabled&&c&&"click"===a.type&&d.selectedValue(),e._appendMenu(f,b+1,d)}),this.disabled?(j.addClass("is-disabled"),j.find(".el-checkbox__input").addClass("is-disabled"),void 0):(j.click(function(a){if(a.preventDefault(),!c){d.selectedValue();var f=d.childrenNode;e._appendMenu(f,b+1,d)}}),void 0)},transferParent:function(a,b,c){if(c||(c=this),this!==c||b){var d=a&&a(this);if(d===!1)return}this.parentNode&&this.parentNode.transferParent(a,b,c)},transferChildren:function(a,b,c){var d,e,f;if(c||(c=this),(this===c&&!b||(d=a&&a(this),d!==!1))&&(e=this.getChildren(),e&&e.length>0))for(f in e)e[f].transferChildren(a,b,c)},selectedValue:function(){var f,g,h,i,j,k,l,m,n,a=this.nodeId,b=this.cascader,c=this.props.multiple,d=this.props.checkStrictly,e=this.leaf;c||!e&&!d?c&&(f=b.data.checkedNodeIds,g=b.data.checkedNodes,h=this.config.disabledFixed,d?(j=f.indexOf(a),-1===j?i=g.concat([this]):(i=g.concat(),i.splice(j,1))):(k=this.getAllLeafChildren(),l=1!==this._checked&&h?this._getMultipleChecked(k):this._checked,1===l?i=g.filter(function(a){return!k.some(function(b){return a.nodeId===b.nodeId})}):(m=k.filter(function(a){return-1===f.indexOf(a.nodeId)}),i=g.concat(m))),n=i.map(function(a){return a.nodeId}),b._setCheckedValue(n,i)):b._setActiveValue(a,this)},_liLoad:function(a,b){var c=this.leaf,d=this.props.lazy,e=this.props.lazyLoad,f=this.children,g=this,h=this.cascader,i=this.level,j=this.props.multiple,k=this.props.checkStrictly;c||f&&0!==f.length||!d?b&&b(a):g.loading||(g.loading=!0,e(g,function(c){g.loading=!1,g.setChildren(h.initNodes(c,i+1,g)),g.children=c,b&&b(a),j&&!k&&g.transferParent(function(a){a.syncStyle()},!0)}))},_liClick:function(a){function e(b){d._liLoad(b,a)}var b=this.leaf,c=this.$li,d=this;("click"===this.props.expandTrigger||b)&&c.click(e),"hover"===this.props.expandTrigger&&c.mouseenter(e)},setChildren:function(a){this.childrenNode=a},getChildren:function(){return this.childrenNode},syncStyle:function(){var a=this.props.multiple,b=this.props.checkStrictly;a?b?this._sync.syncMultipleCheckStrictly(this):this._sync.syncMultiple(this):b?this._sync.syncRadioCheckStrictly(this):this._sync.syncRadio(this)},_sync:{syncRadio:function(a){var h,i,b=a.$li,c=a.icons.from,d=a.icons.ok,e=a.props.multiple,f=a.props.checkStrictly,g=a.nodeId;!b||e||f||(h=a.cascader.data.activeNode,a.activeNodeId===g?(i=b.find("."+d),0===i.length&&b.prepend('<i class="'+c+" "+d+' el-cascader-node__prefix"></i>')):b.find("."+d).remove(),h&&h.path.some(function(a){return a.nodeId===g})?(b.addClass("is-active"),b.addClass("in-checked-path")):(b.removeClass("is-active"),b.removeClass("in-checked-path")))},syncRadioCheckStrictly:function(a){var e,f,g,b=a.$li,c=a.props.checkStrictly,d=a.props.multiple;b&&!d&&c&&(e=a.$radio,f=a.cascader.data.activeNode,g=a.nodeId,a.activeNodeId===g?e.find(".el-radio__input").addClass("is-checked"):e.find(".el-radio__input").removeClass("is-checked"),f&&f.path.some(function(a){return a.nodeId===g})?(b.addClass("is-active"),b.addClass("in-checked-path")):(b.removeClass("is-active"),b.removeClass("in-checked-path")))},syncMultiple:function(a){var f,g,h,b=a.$li,c=a.props.checkStrictly,d=a.props.multiple,e=a.config.disabledFixed;d&&!c&&(f=a.getAllLeafChildren(e),g=a._getMultipleChecked(f),a._checked=g,b&&(h=a.$checked.find(".el-checkbox__input"),0===g?(h.removeClass("is-checked"),h.removeClass("is-indeterminate")):1===g?(h.removeClass("is-indeterminate"),h.addClass("is-checked")):2===g&&(h.removeClass("is-checked"),h.addClass("is-indeterminate"))))},syncMultipleCheckStrictly:function(a){var e,f,g,h,i,b=a.$li,c=a.props.checkStrictly,d=a.props.multiple;b&&d&&c&&(e=a.cascader.data.checkedNodes,f=a.checkedNodeIds,g=a.nodeId,h=e.some(function(a){return a.path.some(function(a){return a.nodeId===g})}),i=a.$checked.find(".el-checkbox__input"),f.some(function(a){return a===g})?i.addClass("is-checked"):i.removeClass("is-checked"),h?b.addClass("in-checked-path"):b.removeClass("in-checked-path"))}},getAllLeafChildren:function(a){var c,b=this.leaf;return b?[this]:(c=[],this.transferChildren(function(b){return b.disabled&&!a?!1:(b.leaf&&c.push(b),void 0)}),c)},expandPanel:function(){var a=this.path,b=this.cascader;a.forEach(function(a,c,d){if(c!==d.length-1){var e=a.childrenNode;b._appendMenu(e,a.level+1,a.parentNode)}})},_getMultipleChecked:function(a){var f,g,b=this.cascader,c=b.data.checkedNodeIds,d=a.map(function(a){return a.nodeId}),e=0;for(f=0;f<d.length&&(g=d[f],2!==e);f++)e=-1!==c.indexOf(g)?f>0&&1!==e?2:1:1===e?2:0;return e}},Cascader.prototype={constructor:Cascader,get props(){return this.config.props},get isFiltering(){return this.filtering},set isFiltering(a){if(this.filtering!==a){this.filtering=!!a;var b=this.$panel;this.filtering?(b.find(".el-cascader-panel").hide(),b.find(".el-cascader__suggestion-panel").show()):(b.find(".el-cascader-panel").show(),b.find(".el-cascader__suggestion-panel").hide(),this.$tagsInput&&this.$tagsInput.val(""))}},set maxSizeMode(a){this._maxSizeMode!==a&&(this._maxSizeMode=a,this.refreshMenu())},icons:{from:"ui-icon",down:"ui-icon-down",close:"ui-icon-close",right:"ui-icon-right",ok:"ui-icon-check",loading:"layui-icon-loading-1 layui-anim layui-anim-rotate layui-anim-loop"},_init:function(){var a,b,c,d;this._checkConfig(this.config),this._initInput(),this._initPanel(),this.setOptions(this.config.data),a=this,b=function(){a._resetXY()},c=$(window),c.scroll(b),a.event.destroy.push(function(){c.unbind("scroll",b)}),d=function(){a._resetXY()},c.resize(d),a.event.destroy.push(function(){c.unbind("resize",d)}),this.$div.click(function(){if(!a.config.disabled){var c=a.showPanel;c?a.close():a.open()}})},_checkConfig:function(a){var c,b=a.el;if(!b||0===$(b).length)throw new Error("缺少elem节点选择器");if(c=a.maxSize,"number"!=typeof c||0>c)throw new Error("maxSize应是一个大于等于0的有效的number值");if(!Array.isArray(a.data))throw new Error("data不是一个有效的数组")},_initRoot:function(){var a=this.props.lazy,b=this.props.lazyLoad,c=this,d=this.data.nodes;d.length>0||!a?this._appendMenu(d,0):a&&(this._appendMenu(d,0),b({root:!0,level:0},function(a){c.data.nodes=c.initNodes(a,0,null),c._appendMenu(c.data.nodes,0)}))},setConfig:function(a){var b=this;b._checkConfig(a),b.clearCheckedNodes(!0),a.props&&!a.props.multiple?b.$tags&&b.$tags.hide():b.$tags&&b.$tags.show(),b.config=$.extend(!0,getDefaultConfig(),a),b.setOptions(a.data)},setOptions:function(a){this.config.data=a,this.data.nodes=this.initNodes(a,0,null),this._initRoot(),this.setValue(this.config.value)},_resetXY:function(){var d,e,f,g,h,i,j,k,l,m,a=this.$div,b=a.offset(),c=this.$panel;c&&(d=window.innerHeight,e=window.innerWidth,f=c.height(),g=c.width(),h=a.height(),i=a[0].getBoundingClientRect(),j=c.find(".popper__arrow"),k=Math.min(e-i.x-g-5,0),l=d-(i.top+h),f>l&&i.top>f+20?(c.attr("x-placement","top-start"),c.css({top:b.top-20-f+"px",left:b.left+k+"px"})):(c.attr("x-placement","bottom-start"),m=Math.max(f-(d-i.y-h-15),0),c.css({top:b.top+h-m+"px",left:b.left+k+"px"})),j.css("left",35-k+"px"))},get $menus(){return this.$panel&&this.$panel.find(".el-cascader-panel .el-cascader-menu")},_initInput:function(){var c,d,e,f,g,h,i,j,k,a=$(this.config.el),b=this;null===this.config.value&&a.attr("value")&&(this.config.value=a.attr("value")),c=this.config.placeholder,d=this.icons.from,e=this.icons.down,f=this.props.multiple,g=this.config.extendClass,h=this.config.extendStyle,this.$div=$('<div class="el-cascader"></div>'),h&&(i=a.attr("style"),i&&this.$div.attr("style",i)),g&&(j=a.attr("class"),j&&j.split(" ").forEach(function(a){b.$div.addClass(a)})),this.$input=$('<div class="el-input el-input--suffix"><input type="text" readonly="readonly" autocomplete="off" placeholder="'+c+'" class="el-input__inner">'+'<span class="el-input__suffix">'+'<span class="el-input__suffix-inner">'+'<i class="el-icon-arrow-down '+d+" "+e+'" style="font-size: 12px"></i>'+"</span></span>"+"</div>"),this.$div.append(this.$input),this.$inputRow=this.$input.find(".el-input__inner"),this.$tags=$('<div class="el-cascader__tags"><!----></div>'),this.$div.append(this.$tags),f||this.$tags.hide(),k=this._initHideElement(a),k.after(this.$div),this.$icon=this.$input.find("i"),this._initFilterableInputEvent(),this.disabled(this.config.disabled)},_initHideElement:function(a){var c,d,e,f,g,b=a.prop("tagName").toLowerCase();if("input"===b)return a.hide(),a.attr("type","hidden"),this.$ec=a,a;c=a[0].attributes,d=$("<input />"),e=Object.keys(c);for(f in e)g=c[f],d.attr(g.name,g.value);return d.hide(),d.attr("type","hidden"),this.$ec=d,a.before(d),a.remove(),d},_initFilterableInputEvent:function(){function j(){var c=this;b&&clearTimeout(b),b=setTimeout(function(){var a,d,e;return b=null,(a=$(c).val())?(i.open(),"function"==typeof f&&f(a)&&(i.isFiltering=!0,d=i.getNodes(),e=d.filter(function(b){var c;return c=h?b.disabled:b.path.some(function(a){return a.disabled}),(b.leaf||h)&&!c&&"function"==typeof g&&g(b,a)?!0:!1}),i._setSuggestionMenu(e)),void 0):(i.isFiltering=!1,void 0)},d)}var b,c,d,e,f,g,h,i,k,l,a=this.config.filterable;a&&(c=this.props.multiple,d=this.config.debounce,e=this.config.placeholder,f=this.config.beforeFilter,g=this.config.filterMethod,h=this.props.checkStrictly,i=this,c?(this.$tagsInput=$('<input type="text" autocomplete="off" placeholder="'+e+'" class="el-cascader__search-input">'),k=this.$tagsInput,this.$tags.append(k),k.on("keydown",j),k.click(function(a){i.isFiltering&&stopPropagation(a)})):(l=this.$inputRow,l.removeAttr("readonly"),l.on("keydown",j),l.click(function(a){i.isFiltering&&stopPropagation(a)})))},_initPanel:function(){var a=this.$panel,b=this.config.popperClass||"";a||(this.$panel=$('<div class="el-popper el-cascader__dropdown '+b+'" style="position: absolute; z-index: 109891015;display: none;" x-placement="bottom-start"><div class="el-cascader-panel"></div><div class="popper__arrow" style="left: 35px;"></div></div>'),a=this.$panel,a.appendTo("body"),a.click(function(a){stopPropagation(a)}),this._initSuggestionPanel())},_appendMenu:function(a,b,c,d){var e,f,g;this._removeMenu(b),c&&c.leaf||(e=this.data.menuData,f=$('<div class="el-scrollbar el-cascader-menu" role="menu" id="cascader-menu"><div class="el-cascader-menu__wrap el-scrollbar__wrap"><ul class="el-scrollbar__view el-cascader-menu__list"></ul></div></div>'),this.$panel.find(".el-cascader-panel").append(f),this._appendLi(f,a),g={nodes:a,level:b,parentNode:c,scrollbar:{top:0,left:0}},d&&(g.scrollbar=d.scrollbar),this._initScrollbar(f,g),this._resetXY(),e.push(g))},_removeMenu:function(a){var c,b=a-1;-1!==b?this.$panel.find(".el-cascader-panel .el-cascader-menu:gt("+b+")").remove():this.$panel.find(".el-cascader-panel .el-cascader-menu").remove(),c=this.data.menuData,c.length>a&&c.splice(a,c.length-a)},_appendLi:function(a,b){var d,c=a.find(".el-cascader-menu__list");return b&&0!==b.length?($.each(b,function(a,b){b.bind(c)}),void 0):(d=this.config.empty,c.append('<div class="el-cascader-menu__empty-text">'+d+"</div>"),void 0)},refreshMenu:function(){var a=this.data.menuData.concat([]),b=this;a.forEach(function(a){b._appendMenu(a.nodes,a.level,a.parentNode,a)})},_initSuggestionPanel:function(){var b,a=this.config.filterable;a&&(b=this.$suggestionPanel,b||(this.$suggestionPanel=$('<div class="el-cascader__suggestion-panel el-scrollbar" style="display: none;"><div class="el-scrollbar__wrap"><ul class="el-scrollbar__view el-cascader__suggestion-list" style="min-width: 222px;"></ul></div></div>'),b=this.$suggestionPanel,this.$panel.find(".popper__arrow").before(b),b.click(function(a){stopPropagation(a)})))},_setSuggestionMenu:function(a){var b=this.$suggestionPanel,c=b.find(".el-cascader__suggestion-list");return c.empty(),b.find(".el-scrollbar__bar").remove(),a&&0!==a.length?($.each(a,function(a,b){b.bindSuggestion(c)}),this._initScrollbar(b,{scrollbar:{top:0,left:0}}),this._resetXY(),void 0):(c.append('<li class="el-cascader__empty-text">无匹配数据</li>'),void 0)},initNodes:function(a,b,c){var e,f,g,d=[];for(e in a)f=a[e],g=new Node(f,this,b,c),null!==g.value&&void 0!==g.value&&(d.push(g),g.children&&g.children.length>0&&g.setChildren(this.initNodes(g.children,b+1,g)));return d},_setActiveValue:function(a,b){if(this.data.activeNodeId!==a){var c=this.data.activeNode;this.data.activeNodeId=a,this.data.activeNode=b,c&&c.transferParent(function(a){a.syncStyle()},!0),b&&b.transferParent(function(a){a.syncStyle()},!0),this.change(b&&b.value,b),null!==a&&this._setClear()}},_setCheckedValue:function(a,b){var e,f,g,c=this.data.checkedNodes,d=this.config.maxSize;a.length>0&&0!==d&&a.length>=d?(a=a.slice(0,d),b=b.slice(0,d),e=!0):e=!1,this.data.checkedNodeIds=a||[],this.data.checkedNodes=b||[],f=[],g=[],c.forEach(function(a){a.path.forEach(function(a){-1===g.indexOf(a.nodeId)&&(f.push(a),g.push(a.nodeId))})}),b.forEach(function(a){a.path.forEach(function(a){-1===g.indexOf(a.nodeId)&&(f.push(a),g.push(a.nodeId))})}),f.forEach(function(a){a.syncStyle()}),this.change(b.map(function(a){return a.value}),b),this._setClear(),this.maxSizeMode=e},setValue:function(a){var b,c,d,e,f,g,h,i,j,k,l,m;if((this.data.activeNodeId||this.data.checkedNodeIds.length>0)&&this.clearCheckedNodes(),a){if(b=this.props.strictMode,b&&!Array.isArray(a))throw new Error("严格模式下,value必须是一个包含父子节点数组结构.");if(c=this.getNodes(this.data.nodes),d=this.props.checkStrictly,e=this.props.multiple,f=this.config.disabledFixed,e)g=c.filter(function(c){return!d&&!c.leaf||c.disabled&&!f?!1:b?a.some(function(a){if(!Array.isArray(a))throw new Error("多选严格模式下,value必须是一个二维数组结构.");var b=c.path;return a.length===b.length&&a.every(function(a,c){return b[c].value===a})}):-1!==a.indexOf(c.value)}),h=g.map(function(a){return a.nodeId}),this._setCheckedValue(h,g),g.length>0&&(i=g[0],i.expandPanel());else for(j=0;j<c.length;j++)if(k=c[j],(d||k.leaf)&&(l=!1,b?(m=k.path,l=a.length===m.length&&a.every(function(a,b){return m[b].value===a})):k.value===a&&(l=!0),l)){this._setActiveValue(k.nodeId,k),k.expandPanel();break}}},getNodes:function(a,b){b||(b=[]),a||(a=this.data.nodes);var c=this;return a.forEach(function(a){b.push(a);var d=a.getChildren();d&&c.getNodes(d,b)}),b},_initScrollbar:function(a,b){function l(b,c){1>j&&(d.css("height",100*j+"%"),d.css("transform","translateY("+100*(b/a.height())+"%)")),1>k&&(e.css("width",100*k+"%"),e.css("transform","translateY("+100*(c/a.width())+"%)"))}var d,e,f,g,h,i,j,k,c=$('<div class="el-scrollbar__bar is-onhoriztal"><div class="el-scrollbar__thumb" style="transform: translateX(0%);"></div></div><div class="el-scrollbar__bar is-vertical"><div class="el-scrollbar__thumb" style="transform: translateY(0%);"></div></div>');a.append(c),d=$(c[1]).find(".el-scrollbar__thumb"),e=$(c[0]).find(".el-scrollbar__thumb"),f=a.find(".el-scrollbar__wrap"),g=this.$panel,h=a.find("li"),i=Math.max(g.height(),a.height()),j=(i-6)/(h.height()*h.length),k=g.width()/h.width(),d.mousedown(function(a){var b,c,d,e,g;a.stopImmediatePropagation(),stopPropagation(a),b=function(){return!1},c=$(document),c.bind("selectstart",b),d=a.clientY,e=f.scrollTop(),g=function(a){a.stopImmediatePropagation();var b=e+(a.clientY-d)/j;f.scrollTop(b)},c.bind("mousemove",g),c.one("mouseup",function(a){stopPropagation(a),a.stopImmediatePropagation(),c.off("mousemove",g),c.off("selectstart",b)})}),f.scroll(function(){var a=$(this);b.scrollbar.top=a.scrollTop(),b.scrollbar.left=a.scrollLeft(),l(b.scrollbar.top,b.scrollbar.left)}),f.scrollTop(b.scrollbar.top),l(b.scrollbar.top,b.scrollbar.left)},_fillingPath:function(){var h,i,j,k,l,m,n,o,p,a=this.props.multiple,b=this.config.showAllLevels,c=this.config.separator,d=this.config.collapseTags,e=this.$inputRow,f=this.config.placeholder,g=this;a?(this.$tags.find(".el-tag").remove(),j=this.$tagsInput,e.css("height",""),k=this.data.checkedNodes,l=Math.max(this.config.minCollapseTagsNumber,1),k.length>0&&(m=[],n=k,d&&(n=k.slice(0,Math.min(k.length,l))),n.forEach(function(a){m.push(a.$tag)}),d&&k.length>l&&m.push(g.get$tag("+ "+(k.length-l),!1)),j?j.before(m):g.$tags.append(m)),o=g.$tags.height(),p=e.height(),o>p&&e.css("height",o+4+"px"),this._resetXY(),k.length>0?(e.removeAttr("placeholder"),j&&j.removeAttr("placeholder",f)):(e.attr("placeholder",f),j&&j.attr("placeholder",f))):(h=this.data.activeNode,i=h&&h.path||[],b?this._$inputRowSetValue(i.map(function(a){return a.label}).join(c)):this._$inputRowSetValue(h&&h.label||""))},_$inputRowSetValue:function(a){a=a||"";var b=this.$inputRow;b.attr("value",a),b.val(a)},get$tag:function(a,b){var c=this.icons.from,d=this.icons.close,e=b?'<i class="el-tag__close el-icon-close '+c+" "+d+'"></i>':"";return $('<span class="el-tag el-tag--info el-tag--small el-tag--light"><span>'+a+"</span>"+e+"</span>")},_setClear:function(){function b(){a.$icon.removeClass(a.icons.down),a.$icon.addClass(a.icons.close)}function c(){a.$icon.removeClass(a.icons.close),a.$icon.addClass(a.icons.down)}var d,e,a=this;a.$div.mouseenter(function(){b()}),a.$div.mouseleave(function(){c()}),a.$icon.off("click"),d=this.props.multiple,e=d?this.data.checkedNodeIds.length>0:!!this.data.activeNodeId,e&&!this.config.disabled&&this.config.clearable?a.$icon.one("click",function(b){stopPropagation(b),a.close(),a.clearCheckedNodes(),c(),a.$icon.off("mouseenter"),a.$div.off("mouseenter"),a.$div.off("mouseleave")}):(c(),a.$icon.off("mouseenter"),a.$div.off("mouseenter"),a.$div.off("mouseleave"))},disabled:function(a){this.config.disabled=!!a,this.config.disabled?(this.$div.addClass("is-disabled"),this.$div.find(".el-input--suffix").addClass("is-disabled"),this.$inputRow.attr("disabled","disabled"),this.$tagsInput&&this.$tagsInput.attr("disabled","disabled").hide()):(this.$div.removeClass("is-disabled"),this.$div.find(".el-input--suffix").removeClass("is-disabled"),this.$inputRow.removeAttr("disabled"),this.$tagsInput&&this.$tagsInput.removeAttr("disabled").show()),this._setClear(),this._fillingPath()},change:function(a,b){var c=this.props.multiple;c?a&&a.length>0?(this.$ec.attr("value",JSON.stringify(a)),this.$ec.val(JSON.stringify(a))):(this.$ec.removeAttr("value"),this.$ec.val("")):(this.$ec.attr("value",a||""),this.$ec.val(a)),this._fillingPath(),this.event.change.forEach(function(c){"function"==typeof c&&c(a,b)})},close:function(a){if(this.showPanel&&(!a||this.closeEventId===a)){this.showPanel=!1,this.$div.find(".ui-icon-down").removeClass("is-reverse"),this.$panel.slideUp(100),this.visibleChange(!1),this.$input.removeClass("is-focus");var b=this.config.filterable;b&&(this.isFiltering=!1,this._fillingPath()),this.event.close.forEach(function(a){"function"==typeof a&&a()})}},open:function(){if(!this.showPanel){this.showPanel=!0,this.closeEventId++;var a=this;setTimeout(function(){$(document).one("click",a.close.bind(a,a.closeEventId))}),this._resetXY(),this.$div.find(".ui-icon-down").addClass("is-reverse"),this.$panel.slideDown(200),this.visibleChange(!0),this.$input.addClass("is-focus"),this.event.open.forEach(function(a){"function"==typeof a&&a()})}},destroy:function(){this.$div.remove(),this.$panel.remove(),this.event.destroy.forEach(function(a){a&&a()})},visibleChange:function(){},removeTag:function(){},getCheckedValues:function(){var b,c,a=this.props.strictMode;return this.props.multiple?(b=this.data.checkedNodes,a?b.map(function(a){return a.path.map(function(a){return a.value})}):b.map(function(a){return a.value})):(c=this.data.activeNode,a?c&&c.path.map(function(a){return a.value}):c&&c.value)},getCheckedNodes:function(){var b,c,a=this.props.strictMode;return this.props.multiple?(b=this.data.checkedNodes,a?b&&b.map(function(a){return a.path}):b):(c=this.data.activeNode,a?c&&c.path:c)},clearCheckedNodes:function(a){var c,d,e,f,b=this.props.multiple;b?(c=this.config.disabledFixed,!a&&c?(d=this.data.checkedNodes,e=d.filter(function(a){return a.disabled}),f=e.map(function(a){return a.nodeId}),this._setCheckedValue(f,e)):this._setCheckedValue([],[])):this._setActiveValue(null,null)}};var thisCas=function(){var a=this;return{setOptions:function(b){a.setOptions(b)},setValue:function(b){a.setValue(b)},changeEvent:function(b){if("function"!=typeof b)throw new Error("changeEvent回调事件不是一个有效的方法");a.event.change.push(b)},closeEvent:function(b){if("function"!=typeof b)throw new Error("closeEvent回调事件不是一个有效的方法");a.event.close.push(b)},openEvent:function(b){if("function"!=typeof b)throw new Error("openEvent回调事件不是一个有效的方法");a.event.open.push(b)},destroyEvent:function(b){if("function"!=typeof b)throw new Error("destroyEvent回调事件不是一个有效的方法");a.event.destroy.push(b)},disabled:function(b){a.disabled(b)},close:function(){a.close()},open:function(){a.open()},destroy:function(){a.destroy()},getCheckedNodes:function(){return a.getCheckedNodes()},getCheckedValues:function(){return a.getCheckedValues()},clearCheckedNodes:function(b){a.clearCheckedNodes(b)},expandNode:function(b){var d,e,c=a.getNodes(a.data.nodes);for(d=0;d<c.length;d++)if(e=c[d],e.value===b){e.expandPanel();break}},getConfig:function(){return $.extend(!0,{},a.config)},setConfig:function(b){a.setConfig(b)},getData:function(){return $.extend(!0,{},a.data)}}};$(function(){$.extend({Cascader:function(a){var b=new Cascader(a);return thisCas.call(b)}})});