/*!
 * CityPicker v1.2.0
 * https://github.com/tshi0912/citypicker
 *
 * Copyright (c) 2015-2018 Tao Shi
 * Released under the MIT license
 *
 * Date: 2018-04-12T04:27:10.483Z
 */
!function(t){"function"==typeof define&&define.amd?define(["jquery","ChineseDistricts"],t):"object"==typeof exports?t(require("jquery"),require("ChineseDistricts")):t(jQuery,ChineseDistricts)}(function(t,e){"use strict";function i(e,s){this.$element=t(e),this.$dropdown=null,this.options=t.extend({},i.DEFAULTS,t.isPlainObject(s)&&s),this.active=!1,this.dems=[],this.needBlur=!1,this.init()}if("undefined"==typeof e)throw new Error('The file "city-picker.data.js" must be included first!');var s="citypicker",n="change."+s,c="province",o="city",d="district";i.prototype={constructor:i,init:function(){this.codeRender(),this.defineDems(),this.render(),this.bind(),this.active=!0},codeRender:function(){var e=this.$element.attr("code");void 0===e||""===e||isNaN(Number(e))||this.$element.val(t.fn.citypicker.getAddressbyCodeId(e))},render:function(){var e=this.getPosition(),i=this.$element.attr("placeholder")||this.options.placeholder,s='<span class="city-picker-span" style="'+this.getWidthStyle(e.width)+"height:"+e.height+"px;line-height:"+(e.height-1)+'px;">'+(i?'<span class="placeholder">'+i+"</span>":"")+'<span class="title"></span><div class="arrow"></div></span>',n='<div class="city-picker-dropdown" style="left:0px;top:100%;'+this.getWidthStyle(e.width,!0)+'"><div class="city-select-wrap"><div class="city-select-tab"><a class="active" data-count="province">省份</a>'+(this.includeDem("city")?'<a data-count="city">城市</a>':"")+(this.includeDem("district")?'<a data-count="district">区县</a>':"")+'</div><div class="city-select-content"><div class="city-select province" data-count="province"></div>'+(this.includeDem("city")?'<div class="city-select city" data-count="city"></div>':"")+(this.includeDem("district")?'<div class="city-select district" data-count="district"></div>':"")+"</div></div>";this.$element.addClass("city-picker-input"),this.$textspan=t(s).insertAfter(this.$element),this.$dropdown=t(n).insertAfter(this.$textspan);var c=this.$dropdown.find(".city-select");t.each(this.dems,t.proxy(function(t,e){this["$"+e]=c.filter("."+e)},this)),this.refresh()},refresh:function(e){var i=this.$dropdown.find(".city-select");i.data("item",null);var s=this.$element.val()||"";s=s.split("/"),t.each(this.dems,t.proxy(function(t,i){s[t]&&t<s.length?this.options[i]=s[t]:e&&(this.options[i]=""),this.output(i)},this)),this.tab(c),this.feedText(),this.feedVal()},defineDems:function(){var e=!1;t.each([c,o,d],t.proxy(function(t,i){e||this.dems.push(i),i===this.options.level&&(e=!0)},this))},includeDem:function(e){return t.inArray(e,this.dems)!==-1},getPosition:function(){var t,e,i,s,n;return t=this.$element.position(),s=this.getSize(this.$element),e=s.height,i=s.width,this.options.responsive&&(n=this.$element.offsetParent().width(),n&&(i/=n,i>.99&&(i=1),i=100*i+"%")),{top:t.top||0,left:t.left||0,height:e,width:i}},getSize:function(e){var i,s,n;return e.is(":visible")?n={width:e.outerWidth(),height:e.outerHeight()}:(i=t("<div />").appendTo(t("body")),i.css({position:"absolute !important",visibility:"hidden !important",display:"block !important"}),s=e.clone().appendTo(i),n={width:s.outerWidth(),height:s.outerHeight()},i.remove()),n},getWidthStyle:function(e,i){return this.options.responsive&&!t.isNumeric(e)?"width:"+e+";":"width:"+(i?Math.max(320,e):e)+"px;"},bind:function(){var e=this;t(document).on("click",this._mouteclick=function(i){var s,n,c,o=t(i.target);o.is(".city-picker-span")?n=o:o.is(".city-picker-span *")&&(n=o.parents(".city-picker-span")),o.is(".city-picker-input")&&(c=o),o.is(".city-picker-dropdown")?s=o:o.is(".city-picker-dropdown *")&&(s=o.parents(".city-picker-dropdown")),(!c&&!n&&!s||n&&n.get(0)!==e.$textspan.get(0)||c&&c.get(0)!==e.$element.get(0)||s&&s.get(0)!==e.$dropdown.get(0))&&e.close(!0)}),this.$element.on("change",this._changeElement=t.proxy(function(){this.close(!0),this.refresh(!0)},this)).on("focus",this._focusElement=t.proxy(function(){this.needBlur=!0,this.open()},this)).on("blur",this._blurElement=t.proxy(function(){this.needBlur&&(this.needBlur=!1,this.close(!0))},this)),this.$textspan.on("click",function(i){var s,n=t(i.target);e.needBlur=!1,n.is(".select-item")?(s=n.data("count"),e.open(s)):e.$dropdown.is(":visible")?e.close():e.open()}).on("mousedown",function(){e.needBlur=!1}),this.$dropdown.on("click",".city-select a",function(){var i=t(this).parents(".city-select"),s=i.find("a.active"),c=0===i.next().length;s.removeClass("active"),t(this).addClass("active"),s.data("code")!==t(this).data("code")&&(i.data("item",{address:t(this).attr("title"),code:t(this).data("code")}),t(this).trigger(n),e.feedText(),e.feedVal(!0),c&&e.close())}).on("click",".city-select-tab a",function(){if(!t(this).hasClass("active")){var i=t(this).data("count");e.tab(i)}}).on("mousedown",function(){e.needBlur=!1}),this.$province&&this.$province.on(n,this._changeProvince=t.proxy(function(){this.output(o),this.output(d),this.tab(o)},this)),this.$city&&this.$city.on(n,this._changeCity=t.proxy(function(){this.output(d),this.tab(d)},this))},open:function(t){t=t||c,this.$dropdown.show(),this.$textspan.addClass("open").addClass("focus"),this.tab(t)},close:function(t){this.$dropdown.hide(),this.$textspan.removeClass("open"),t&&this.$textspan.removeClass("focus")},unbind:function(){t(document).off("click",this._mouteclick),this.$element.off("change",this._changeElement),this.$element.off("focus",this._focusElement),this.$element.off("blur",this._blurElement),this.$textspan.off("click"),this.$textspan.off("mousedown"),this.$dropdown.off("click"),this.$dropdown.off("mousedown"),this.$province&&this.$province.off(n,this._changeProvince),this.$city&&this.$city.off(n,this._changeCity)},getText:function(){var e="";return this.$dropdown.find(".city-select").each(function(){var i=t(this).data("item"),s=t(this).data("count");i&&(e+=(t(this).hasClass("province")?"":"/")+'<span class="select-item" data-count="'+s+'" data-code="'+i.code+'">'+i.address+"</span>")}),e},getPlaceHolder:function(){return this.$element.attr("placeholder")||this.options.placeholder},feedText:function(){var t=this.getText();t?(this.$textspan.find(">.placeholder").hide(),this.$textspan.find(">.title").html(this.getText()).show()):(this.$textspan.find(">.placeholder").text(this.getPlaceHolder()).show(),this.$textspan.find(">.title").html("").hide())},getCode:function(e){var i={},s=[];return this.$textspan.find(".select-item").each(function(){var e=t(this).data("code"),n=t(this).data("count");i[n]=e,s.push(e)}),e?i[e]:s.join("/")},getVal:function(){var e="";return this.$dropdown.find(".city-select").each(function(){var i=t(this).data("item");i&&(e+=(t(this).hasClass("province")?"":"/")+i.address)}),e},feedVal:function(t){this.$element.val(this.getVal()),t&&this.$element.trigger("cp:updated")},output:function(i){var s,n,a,r,h=this.options,l=this["$"+i],p=i===c?{}:[],u=null;l&&l.length&&(s=l.data("item"),r=(s?s.address:null)||h[i],a=i===c?86:i===o?this.$province&&this.$province.find(".active").data("code"):i===d?this.$city&&this.$city.find(".active").data("code"):a,n=t.isNumeric(a)?e[a]:null,t.isPlainObject(n)&&t.each(n,function(t,e){var s;if(i===c){s=[];for(var n=0;n<e.length;n++)e[n].address===r&&(u={code:e[n].code,address:e[n].address}),s.push({code:e[n].code,address:e[n].address,selected:e[n].address===r});p[t]=s}else e===r&&(u={code:t,address:e}),p.push({code:t,address:e,selected:e===r})}),l.html(i===c?this.getProvinceList(p):this.getList(p,i)),l.data("item",u))},getProvinceList:function(e){var i=[],s=this,n=this.options.simple;return t.each(e,function(e,o){i.push('<dl class="clearfix">'),i.push("<dt>"+e+"</dt><dd>"),t.each(o,function(t,e){i.push('<a title="'+(e.address||"")+'" data-code="'+(e.code||"")+'" class="'+(e.selected?" active":"")+'">'+(n?s.simplize(e.address,c):e.address)+"</a>")}),i.push("</dd></dl>")}),i.join("")},getList:function(e,i){var s=[],n=this,c=this.options.simple;return s.push('<dl class="clearfix"><dd>'),t.each(e,function(t,e){s.push('<a title="'+(e.address||"")+'" data-code="'+(e.code||"")+'" class="'+(e.selected?" active":"")+'">'+(c?n.simplize(e.address,i):e.address)+"</a>")}),s.push("</dd></dl>"),s.join("")},simplize:function(t,e){return t=t||"",e===c?t.replace(/[省,市,自治区,壮族,回族,维吾尔]/g,""):e===o?t.replace(/[市,地区,回族,蒙古,苗族,白族,傣族,景颇族,藏族,彝族,壮族,傈僳族,布依族,侗族]/g,"").replace("哈萨克","").replace("自治州","").replace(/自治县/,""):e===d?t.length>2?t.replace(/[市,区,县,旗]/g,""):t:void 0},tab:function(t){var e=this.$dropdown.find(".city-select"),i=this.$dropdown.find(".city-select-tab > a"),s=this["$"+t],n=this.$dropdown.find('.city-select-tab > a[data-count="'+t+'"]');s&&(e.hide(),s.show(),i.removeClass("active"),n.addClass("active"))},reset:function(){this.$element.val(null).trigger("change")},destroy:function(){this.unbind(),this.$element.removeData(s).removeClass("city-picker-input"),this.$textspan.remove(),this.$dropdown.remove()}},i.DEFAULTS={simple:!1,responsive:!1,placeholder:"请选择省/市/区",level:"district",province:"",city:"",district:""},i.setDefaults=function(e){t.extend(i.DEFAULTS,e)},i.other=t.fn.citypicker,t.fn.citypicker=function(e){var n=[].slice.call(arguments,1);return this.each(function(){var c,o,d=t(this),a=d.data(s);if(!a){if(/destroy/.test(e))return;c=t.extend({},d.data(),t.isPlainObject(e)&&e),d.data(s,a=new i(this,c))}"string"==typeof e&&t.isFunction(o=a[e])&&o.apply(a,n)})},t.fn.citypicker.Constructor=i,t.fn.citypicker.setDefaults=i.setDefaults,t.fn.citypicker.noConflict=function(){return t.fn.citypicker=i.other,this},t.fn.citypicker.getAddressbyCodeId=function(i){var s=e,n=s[""+i],c="",o="",d="",a="";if("44"===i.substring(0,2)?(o="广东省",d="440000"):t.each(s[86],function(e,s){t.each(s,function(t,e){if(e.code===i.substring(0,2)+"0000")return o=e.address,d=e.code,!1})}),i.substring(2,4).indexOf("00")==-1){var r=i.substring(0,4)+"00";a=s[d][r]}if(void 0===n){if(n=i.substring(0,4)+"00",null==s[n])return;return c=s[n][i],c=o+"/"+a+"/"+c}if(i.substring(2,4).indexOf("00")!=-1)return c=o;var h=s[i.substring(0,2)+"0000"];return c=o+"/"+h[i]},t(function(){t('[data-toggle="city-picker"]').citypicker()})});