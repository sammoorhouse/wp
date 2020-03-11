﻿(function(a,b){function c(a,b,c){["-webkit-","-moz-","-o-","-ms-",""].forEach(function(d){a.css(d+b,c)})}function d(b,c){this._timeouts=[],this.element=a(b),this.options=a.extend({},e,c),this._defaults=e,this._name="circleMenu",this.init(),this.hook()}var e={depth:0,item_diameter:30,circle_radius:80,circle_radius_tablet:0,circle_radius_mobile:0,angle:{start:0,end:90},speed:500,delay:1e3,step_out:20,step_in:-20,trigger:"hover",transition_function:"ease"};d.prototype.init=function(){var c,d=this,e=b.innerWidth,f={"bottom-left":[180,90],bottom:[135,45],right:[-45,45],left:[225,135],top:[225,315],"bottom-half":[180,0],"right-half":[-90,90],"left-half":[270,90],"top-half":[180,360],"top-left":[270,180],"top-right":[270,360],full:[-90,270-Math.floor(360/(d.element.children("li").length-1))],"bottom-right":[0,90]};d._state="closed",d.element.addClass("circleMenu-closed"),"string"==typeof d.options.direction&&(c=f[d.options.direction.toLowerCase()],c&&(d.options.angle.start=c[0],d.options.angle.end=c[1])),d.menu_items=d.element.children("li:not(:first-child)"),d.initCss(),d.item_count=d.menu_items.length,d._step=(d.options.angle.end-d.options.angle.start)/(d.item_count-1),d.menu_items.each(function(b){var c=a(this),f=(d.options.angle.start+d._step*b)*(Math.PI/180);if(1200<=e)var g=Math.round(d.options.circle_radius*Math.cos(f)),h=Math.round(d.options.circle_radius*Math.sin(f));else if(1199>=e&&768<=e){if(0!=d.options.circle_radius_tablet&&null!=d.options.circle_radius_tablet)var g=Math.round(d.options.circle_radius_tablet*Math.cos(f)),h=Math.round(d.options.circle_radius_tablet*Math.sin(f));else var g=Math.round(d.options.circle_radius*Math.cos(f)),h=Math.round(d.options.circle_radius*Math.sin(f));}else if(767>=e)if(0!=d.options.circle_radius_mobile&&null!=d.options.circle_radius_mobile)var g=Math.round(d.options.circle_radius_mobile*Math.cos(f)),h=Math.round(d.options.circle_radius_mobile*Math.sin(f));else var g=Math.round(d.options.circle_radius*Math.cos(f)),h=Math.round(d.options.circle_radius*Math.sin(f));c.data("plugin_circleMenu-pos-x",g),c.data("plugin_circleMenu-pos-y",h),c.on("click",function(){d.select(b+2)})}),["open","close","init","select"].forEach(function(a){var b;d.options[a]&&(b=d.options[a],d.element.on("circleMenu-"+a,function(){return b.apply(d,arguments)}),delete d.options[a])}),d.submenus=d.menu_items.children("ul"),d.submenus.circleMenu(a.extend({},d.options,{depth:d.options.depth+1})),d.trigger("init")},d.prototype.trigger=function(){var a,b,c=[];for(a=0,b=arguments.length;a<b;a++)c.push(arguments[a]);this.element.trigger("circleMenu-"+c.shift(),c)},d.prototype.hook=function(){var a=this;"hover"===a.options.trigger?a.element.on("mouseenter",function(){a.open()}).on("mouseleave",function(){a.close()}):"click"===a.options.trigger?a.element.children("li:first-child").on("click",function(b){return b.preventDefault(),"closed"===a._state||"closing"===a._state?a.open():a.close(!0),!1}):"none"===a.options.trigger},d.prototype.open=function(){var b,d=this,e=this.element;return(d.clearTimeouts(),"open"===d._state)?d:(e.addClass("circleMenu-open"),e.removeClass("circleMenu-closed"),b=0<=d.options.step_out?d.menu_items:a(d.menu_items.get().reverse()),b.each(function(b){var e=a(this);d._timeouts.push(setTimeout(function(){e.css({left:e.data("plugin_circleMenu-pos-x")+"px",top:e.data("plugin_circleMenu-pos-y")+"px"}),c(e,"transform","scale(1)")},0+Math.abs(d.options.step_out)*b))}),d._timeouts.push(setTimeout(function(){"opening"===d._state&&d.trigger("open"),d._state="open"},0+Math.abs(d.options.step_out)*b.length)),d._state="opening",d)},d.prototype.close=function(b){var d=this,e=this.element,f=function(){var b;return(d.submenus.circleMenu("close"),d.clearTimeouts(),"closed"===d._state)?d:(b=0<=d.options.step_in?d.menu_items:a(d.menu_items.get().reverse()),b.each(function(b){var e=a(this);d._timeouts.push(setTimeout(function(){e.css({top:0,left:0}),c(e,"transform","scale(.5)")},0+Math.abs(d.options.step_in)*b))}),d._timeouts.push(setTimeout(function(){"closing"===d._state&&d.trigger("close"),d._state="closed"},0+Math.abs(d.options.step_in)*b.length)),e.removeClass("circleMenu-open"),e.addClass("circleMenu-closed"),d._state="closing",d)};return b?f():d._timeouts.push(setTimeout(f,d.options.delay)),this},d.prototype.select=function(a){var b,d,e=this;("open"===e._state||"opening"===e._state)&&(e.clearTimeouts(),d=e.element.children("li:not(:nth-child("+a+"),:first-child)"),b=e.element.children("li:nth-child("+a+")"),e.trigger("select",b),c(b.add(d),"transition","all 500ms ease-out"),c(b,"transform","scale(2)"),c(d,"transform","scale(0)"),b.css("opacity","0"),d.css("opacity","0"),e.element.removeClass("circleMenu-open"),setTimeout(function(){e.initCss()},500))},d.prototype.clearTimeouts=function(){for(var a;a=this._timeouts.shift();)clearTimeout(a)},d.prototype.initCss=function(){var a,b=this;b._state="closed",b.element.removeClass("circleMenu-open"),b.element.css({"list-style":"none",margin:0,padding:0,width:b.options.item_diameter+"px"}),a=b.element.children("li"),a.attr("style",""),a.css({display:"block",width:b.options.item_diameter+"px",height:b.options.item_diameter+"px","text-align":"center","line-height":b.options.item_diameter+"px",position:"absolute","z-index":1,opacity:""}),b.element.children("li:first-child").css({"z-index":1e3-b.options.depth}),b.menu_items.css({top:0,left:0}),c(a,"border-radius",b.options.item_diameter+"px"),c(b.menu_items,"transform","scale(.5)"),setTimeout(function(){c(a,"transition","all "+b.options.speed+"ms "+b.options.transition_function)},0)},a.fn.circleMenu=function(b){return this.each(function(){var c=a.data(this,"plugin_circleMenu"),e={init:function(){c.init()},open:function(){c.open()},close:function(){c.close(!0)}};"string"==typeof b&&c&&e[b]&&e[b](),c||a.data(this,"plugin_circleMenu",new d(this,b))})}})(jQuery,window,document);