
// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  if(this.console) console.log( Array.prototype.slice.call(arguments) );
};



// place any jQuery/helper plugins in here, instead of separate, slower script files.


/*!
* Placeholder plugin for jQuery
* ---
* Copyright 2010, Daniel Stocks (http://webcloud.se)
* Released under the MIT, BSD, and GPL Licenses.
*/
(function(b){function d(a){this.input=a;a.attr("type")=="password"&&this.handlePassword();b(a[0].form).submit(function(){if(a.hasClass("placeholder")&&a[0].value==a.attr("placeholder"))a[0].value=""})}d.prototype={show:function(a){if(this.input[0].value===""||a&&this.valueIsPlaceholder()){if(this.isPassword)try{this.input[0].setAttribute("type","text")}catch(b){this.input.before(this.fakePassword.show()).hide()}this.input.addClass("placeholder");this.input[0].value=this.input.attr("placeholder")}},
hide:function(){if(this.valueIsPlaceholder()&&this.input.hasClass("placeholder")&&(this.input.removeClass("placeholder"),this.input[0].value="",this.isPassword)){try{this.input[0].setAttribute("type","password")}catch(a){}this.input.show();this.input[0].focus()}},valueIsPlaceholder:function(){return this.input[0].value==this.input.attr("placeholder")},handlePassword:function(){var a=this.input;a.attr("realType","password");this.isPassword=!0;if(b.browser.msie&&a[0].outerHTML){var c=b(a[0].outerHTML.replace(/type=(['"])?password\1/gi,
"type=$1text$1"));this.fakePassword=c.val(a.attr("placeholder")).addClass("placeholder").focus(function(){a.trigger("focus");b(this).hide()});b(a[0].form).submit(function(){c.remove();a.show()})}}};var e=!!("placeholder"in document.createElement("input"));b.fn.placeholder=function(){return e?this:this.each(function(){var a=b(this),c=new d(a);c.show(!0);a.focus(function(){c.hide()});a.blur(function(){c.show(!1)});b.browser.msie&&(b(window).load(function(){a.val()&&a.removeClass("placeholder");c.show(!0)}),
a.focus(function(){if(this.value==""){var a=this.createTextRange();a.collapse(!0);a.moveStart("character",0);a.select()}}))})}})(jQuery);


/*!
* jQuery.query - Query String Modification and Creation for jQuery
* Written by Blair Mitchelmore (blair DOT mitchelmore AT gmail DOT com)
* Licensed under the WTFPL (http://sam.zoy.org/wtfpl/).
* Date: 2009/8/13
*
* @author Blair Mitchelmore
* @version 2.1.7
*
**/
new function (settings) {
    // Various Settings
    var $separator = settings.separator || '&';
    var $spaces = settings.spaces === false ? false : true;
    var $suffix = settings.suffix === false ? '' : '[]';
    var $prefix = settings.prefix === false ? false : true;
    var $hash = $prefix ? settings.hash === true ? "#" : "?" : "";
    var $numbers = settings.numbers === false ? false : true;

    jQuery.query = new function () {
        var is = function (o, t) {
            return o != undefined && o !== null && (!!t ? o.constructor == t : true);
        };
        var parse = function (path) {
            var m, rx = /\[([^[]*)\]/g, match = /^([^[]+)(\[.*\])?$/.exec(path), base = match[1], tokens = [];
            while (m = rx.exec(match[2])) tokens.push(m[1]);
            return [base, tokens];
        };
        var set = function (target, tokens, value) {
            var o, token = tokens.shift();
            if (typeof target != 'object') target = null;
            if (token === "") {
                if (!target) target = [];
                if (is(target, Array)) {
                    target.push(tokens.length == 0 ? value : set(null, tokens.slice(0), value));
                } else if (is(target, Object)) {
                    var i = 0;
                    while (target[i++] != null);
                    target[--i] = tokens.length == 0 ? value : set(target[i], tokens.slice(0), value);
                } else {
                    target = [];
                    target.push(tokens.length == 0 ? value : set(null, tokens.slice(0), value));
                }
            } else if (token && token.match(/^\s*[0-9]+\s*$/)) {
                var index = parseInt(token, 10);
                if (!target) target = [];
                target[index] = tokens.length == 0 ? value : set(target[index], tokens.slice(0), value);
            } else if (token) {
                var index = token.replace(/^\s*|\s*$/g, "");
                if (!target) target = {};
                if (is(target, Array)) {
                    var temp = {};
                    for (var i = 0; i < target.length; ++i) {
                        temp[i] = target[i];
                    }
                    target = temp;
                }
                target[index] = tokens.length == 0 ? value : set(target[index], tokens.slice(0), value);
            } else {
                return value;
            }
            return target;
        };

        var queryObject = function (a) {
            var self = this;
            self.keys = {};

            if (a.queryObject) {
                jQuery.each(a.get(), function (key, val) {
                    self.SET(key, val);
                });
            } else {
                jQuery.each(arguments, function () {
                    var q = "" + this;
                    q = q.replace(/^[?#]/, ''); // remove any leading ? || #
                    q = q.replace(/[;&]$/, ''); // remove any trailing & || ;
                    if ($spaces) q = q.replace(/[+]/g, ' '); // replace +'s with spaces

                    jQuery.each(q.split(/[&;]/), function () {
                        var key = decodeURIComponent(this.split('=')[0] || "");
                        var val = decodeURIComponent(this.split('=')[1] || "");

                        if (!key) return;

                        if ($numbers) {
                            if (/^[+-]?[0-9]+\.[0-9]*$/.test(val)) // simple float regex
                                val = parseFloat(val);
                            else if (/^[+-]?[0-9]+$/.test(val)) // simple int regex
                                val = parseInt(val, 10);
                        }

                        val = (!val && val !== 0) ? true : val;

                        if (val !== false && val !== true && typeof val != 'number')
                            val = val;

                        self.SET(key, val);
                    });
                });
            }
            return self;
        };

        queryObject.prototype = {
            queryObject: true,
            has: function (key, type) {
                var value = this.get(key);
                return is(value, type);
            },
            GET: function (key) {
                if (!is(key)) return this.keys;
                var parsed = parse(key), base = parsed[0], tokens = parsed[1];
                var target = this.keys[base];
                while (target != null && tokens.length != 0) {
                    target = target[tokens.shift()];
                }
                return typeof target == 'number' ? target : target || "";
            },
            get: function (key) {
                var target = this.GET(key);
                if (is(target, Object))
                    return jQuery.extend(true, {}, target);
                else if (is(target, Array))
                    return target.slice(0);
                return target;
            },
            SET: function (key, val) {
                var value = !is(val) ? null : val;
                var parsed = parse(key), base = parsed[0], tokens = parsed[1];
                var target = this.keys[base];
                this.keys[base] = set(target, tokens.slice(0), value);
                return this;
            },
            set: function (key, val) {
                return this.copy().SET(key, val);
            },
            REMOVE: function (key) {
                return this.SET(key, null).COMPACT();
            },
            remove: function (key) {
                return this.copy().REMOVE(key);
            },
            EMPTY: function () {
                var self = this;
                jQuery.each(self.keys, function (key, value) {
                    delete self.keys[key];
                });
                return self;
            },
            load: function (url) {
                var hash = url.replace(/^.*?[#](.+?)(?:\?.+)?$/, "$1");
                var search = url.replace(/^.*?[?](.+?)(?:#.+)?$/, "$1");
                return new queryObject(url.length == search.length ? '' : search, url.length == hash.length ? '' : hash);
            },
            empty: function () {
                return this.copy().EMPTY();
            },
            copy: function () {
                return new queryObject(this);
            },
            COMPACT: function () {
                function build(orig) {
                    var obj = typeof orig == "object" ? is(orig, Array) ? [] : {} : orig;
                    if (typeof orig == 'object') {
                        function add(o, key, value) {
                            if (is(o, Array))
                                o.push(value);
                            else
                                o[key] = value;
                        }
                        jQuery.each(orig, function (key, value) {
                            if (!is(value)) return true;
                            add(obj, key, build(value));
                        });
                    }
                    return obj;
                }
                this.keys = build(this.keys);
                return this;
            },
            compact: function () {
                return this.copy().COMPACT();
            },
            toString: function () {
                var i = 0, queryString = [], chunks = [], self = this;
                var encode = function (str) {
                    str = str + "";
                    if ($spaces) str = str.replace(/ /g, "+");
                    return encodeURIComponent(str);
                };
                var addFields = function (arr, key, value) {
                    if (!is(value) || value === false) return;
                    var o = [encode(key)];
                    if (value !== true) {
                        o.push("=");
                        o.push(encode(value));
                    }
                    arr.push(o.join(""));
                };
                var build = function (obj, base) {
                    var newKey = function (key) {
                        return !base || base == "" ? [key].join("") : [base, "[", key, "]"].join("");
                    };
                    jQuery.each(obj, function (key, value) {
                        if (typeof value == 'object')
                            build(value, newKey(key));
                        else
                            addFields(chunks, newKey(key), value);
                    });
                };

                build(this.keys);

                if (chunks.length > 0) queryString.push($hash);
                queryString.push(chunks.join($separator));

                return queryString.join("");
            }
        };

        return new queryObject(location.search, location.hash);
    };
} (jQuery.query || {}); // Pass in jQuery.query as settings object




/*!
*	 jQuery Expandable Accordion
*	 v0.11
*     author: Nick Evans (based on jquery ui accordion)
*	 See https://github.com/nwe44/Expandable-accordion
*
*     This is a heavilly cut down version of jquery ui accordion 
*	 to be used when more than one accordion needs to be open
*	 inspired by the advice at http://jqueryui.com/demos/accordion/
*	 
*/
(function ($) {

    $.fn.expandableAccordion = function (options) {
        var opts = $.extend({}, $.fn.expandableAccordion.defaults, options);
        var numberOfAccordions = this.length;
        var state = [];
        for (var k = 0; $("#ui-expandable-accordion-" + k).length; k++);
        return this.each(function (i) {
        	if ($(this).hasClass('ui-expandable-accordion')) { // don't reinitialize
        		return true;
        	}
            if ($(this).attr('id')) {
                var myID = $(this).attr('id');
            } else {
                var myID = "ui-expandable-accordion-" + (i + k);
                $(this).attr('id', myID);
            }
            $(this).addClass( "ui-expandable-accordion ui-widget ui-helper-reset" )
                // in lack of child-selectors in CSS
                // we need to mark top-LIs in a UL-accordion for some IE-fix
                .children("li")
                    .addClass("ui-accordion-li-fix");
                var headers = "> li > :first-child,> :not(li):even";
                var iconHeaderSelected = "ui-icon-triangle-1-s";
                var iconHeader = "ui-icon-triangle-1-e";
            $(this).find(headers)
                .addClass( "ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" )
                .bind( "mouseenter.expandableAccordion", function () {
                    $(this).addClass("ui-state-hover");
                })
                .bind( "mouseleave.expandableAccordion", function () {
                    $(this).removeClass("ui-state-hover");
                })
                .bind( "focus.expandableAccordion", function () {
                    $(this).addClass("ui-state-focus");
                })
                .bind( "blur.expandableAccordion", function () {
                    $(this).removeClass( "ui-state-focus");
                }).bind("click",function (event) {
                    $(this)
                    .toggleClass("ui-state-active ui-corner-top")
                    .toggleClass("ui-state-default ui-corner-all")
                    .next()
                    .slideToggle()
                    .toggleClass("ui-accordion-content-active"); // this is where the magic happens
                    
                    if (opts.useBBQ && $.bbq && $(this).attr("id") && $(this).hasClass("ui-state-active")) {
                        state[$(this).parent().attr("id")]=$(this).attr("id");
                        $.bbq.pushState(state);
                    }
                    if ( opts.toggleControls && opts.hideRedundantToggles) {
                        if (!$('#' + myID).find('.ui-accordion-content-active').length) {
                            //Nothing's open, so add a class to the collapse toggles
                            $('#' + myID).prev()
                                        .find('.ui-collapse-all')
                                        .addClass('ui-expand-collapse-toggle-disabled')
                                        .end()
                                        .find('.ui-expand-all')
                                        .removeClass('ui-expand-collapse-toggle-disabled')
                                        .parent()
                                        .addClass('ui-expand-collapse-toggles-single');
                            $('#' + myID).next()
                                        .find('.ui-collapse-all')
                                        .addClass('ui-expand-collapse-toggle-disabled')
                                        .end()
                                        .find('.ui-expand-all')
                                        .removeClass('ui-expand-collapse-toggle-disabled')
                                        .parent()
                                        .addClass('ui-expand-collapse-toggles-single');
                                        
                        }else if (!$('#' + myID).find('.ui-state-default').length) {
                            // everything's open, so add a class to the expand toggles
                            $('#' + myID).prev()
                                        .find('.ui-expand-all')
                                        .addClass('ui-expand-collapse-toggle-disabled')
                                        .end()
                                        .find('.ui-collapse-all')
                                        .removeClass('ui-expand-collapse-toggle-disabled')
                                        .parent()
                                        .addClass('ui-expand-collapse-toggles-single');
                            $('#' + myID).next()
                                        .find('.ui-expand-all')
                                        .addClass('ui-expand-collapse-toggle-disabled')
                                        .end()
                                        .find('.ui-collapse-all')
                                        .removeClass('ui-expand-collapse-toggle-disabled')
                                        .parent()
                                        .addClass('ui-expand-collapse-toggles-single');
                        }else{
                            // it's a mix, so enable both toggle switches
                            $('#' + myID).prev()
                                        .find('a')
                                        .removeClass('ui-expand-collapse-toggle-disabled')
                                        .parent()
                                        .removeClass('ui-expand-collapse-toggles-single');
                            $('#' + myID).next()
                                        .find('a')
                                        .removeClass('ui-expand-collapse-toggle-disabled')
										.parent()
                                        .removeClass('ui-expand-collapse-toggles-single');
                        }
                    }
                    $(this)
                    .children( ".ui-icon" )
                        .toggleClass( iconHeaderSelected )
                        .toggleClass( iconHeader );
                    event.preventDefault();
                }).next()
                .addClass( "ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom" );
            $("<span></span>" )
                .addClass( "ui-icon " + iconHeader )
                .prependTo( $(this).find(headers) );
            $(this).children( ".ui-icon" )
                .toggleClass(iconHeader)
                .toggleClass(iconHeaderSelected);
            $(this).addClass( "ui-accordion-icons" );
            

            //open saved state             
            if (opts.useBBQ && $.bbq && $.bbq.getState($(this).attr("id"))) {
                $(this).find( "#" + $.bbq.getState($(this).attr("id"))).not(".ui-state-active").click();
            }
            
            // open the first slide if it's in the settings to do so.
            else if (opts.firstOpen) {
            	$(this) // we're not firing the click event here so as not to animate
            		.find( ".ui-accordion-header")
            		.eq(0)
                    .addClass("ui-state-active ui-corner-top")
                    .removeClass("ui-state-default ui-corner-all")
                    .next()
                    .css('display', 'block')
                    .addClass("ui-accordion-content-active")
                    .end()
                    .children( ".ui-icon" )
                    .addClass( iconHeaderSelected )
                    .removeClass( iconHeader ); // TODO: Handle toggle switches here too.

            	}
            
            if (opts.toggleControls) {
            // add the expand all markup in js so non-js users, who will never be able to use it
            // never see it, they'll never miss it after all.

                // first, build the buttons
                var toggleButtons = function (options, thisID) {
                    var myClass = $.extend({ className: 'ui-expand-collapse-toggle-before'}, options);
                    var toggler = ['<div class="' + myClass.className + ' ui-expand-collapse-toggle ui-expand-collapse-toggles-single clearfix">'];
                    toggler.push('<a href="#" target="#' + thisID + '" class="ui-expand-all">Expand All <span class="ui-icon ui-icon-triangle-1-s"></span></a>');
                    if (opts.toggleDivider) {toggler.push('<span class="ui-expand-collapse-toggle-divider">|</span>');}
                    toggler.push('<a href="#" target="#' + thisID + '" class="ui-collapse-all ui-expand-collapse-toggle-disabled">Collapse All <span class="ui-icon ui-icon-triangle-1-n"></span></a>');
                    toggler.push('</div>');
                    return toggler.join('');
                };

                if (! opts.multiple && ! $(this).hasClass('ui-accordion-no-toggles') || // add expand all button before every one (that wants one)
                	i === 0 && ! $(this).hasClass('ui-accordion-no-toggles')) {// ... or before the first one that wants one.
                    $(this).before(toggleButtons({}, myID));
                }
                if (! opts.multiple && ! $(this).hasClass('ui-accordion-no-toggles') || // add expand all button after every one (that wants one)
                	i == numberOfAccordions - 1 && ! $(this).hasClass('ui-accordion-no-toggles')) {// ... or after the last one (that wants one.)
                    $(this).after(toggleButtons({ className: 'ui-expand-collapse-toggle-after'}, myID));
                }

                $('.ui-expand-collapse-toggle a').click(function (event) {

					if ($(this).hasClass("ui-expand-all")) {
					
						( ! opts.multiple ? $($(this).attr("target")) : $(".ui-expandable-accordion")).each(function () {

	                		if (! $(this).hasClass('ui-accordion-no-toggles') ) { // only try to toggle elements that have opted in

	                			$(this).find(".ui-accordion-header").each(function () {

	                				if (! $(this).hasClass('ui-state-active')) { // if we had access to :not (n/a in ie7/8) we could do this much faster
	                				
		                				$(this).click();

	                				}

	                			});
	                		
	                		}
	
	                	});

					} else {

						( ! opts.multiple ? $($(this).attr("target")) : $(".ui-expandable-accordion")).each(function () {

	                		if (! $(this).hasClass('ui-accordion-no-toggles') ) { // only try to toggle elements that have opted in

	                			$(this).find(".ui-state-active").click();

	                		}
	
	                	});
					
					}

                    event.preventDefault();
                });
            }
        });
    };
    $.fn.expandableAccordion.defaults = {
        firstOpen: false, // will the first item in the nav be open
        multiple: false, //  should there be one set of toggle controls for all accordions on the page
        toggleDivider: false, // should I try to render a divider between the toggle controls?
        toggleControls : true, // controls to expand/collapse all elements of one or several accordions
        hideRedundantToggles:true, // Hide the collapse button when all elements are collapsed and visa versa
        useBBQ:false // use Ben Almans BBQ hash state plugin to store status of accordion // TODO: experiment with pushState
    };
})(jQuery);

/*!
* HTML Truncator for jQuery
* by Henrik Nyh <http://henrik.nyh.se> 2008-02-28.
* Free to modify and redistribute with credit.
*/
(function(g){var h=true;g.fn.truncate=function(i){var j=g.extend({},g.fn.truncate.defaults,i);g(this).each(function(){var m=g.trim(a(g(this).text())).length;if(m<=j.max_length){return}var n=j.max_length-j.more.length-3;var l=e(this,n);var k=g(this).css("overflow","hidden").hide();l.insertAfter(k);c(l).append(' (<a href="#show more content">'+j.more+"</a>)");b(k).append(' (<a href="#show less content">'+j.less+"</a>)");l.find("a:last").click(function(){var o=l.css("height");l.hide();k.show(0,function(){var p=k.css("height");k.css("height",o).animate({height:p},"fast")});return false});k.find("a:last").click(function(){l.show();k.hide();return false})})};g.fn.truncate.defaults={max_length:100,more:"Émore",less:"less"};function e(i,j){return(i.nodeType==3)?f(i,j):d(i,j)}function d(i,l){var i=g(i);var k=i.clone().empty();var j;i.contents().each(function(){var m=l-k.text().length;if(m==0){return}j=e(this,m);if(j){k.append(j)}});return k}function f(i,k){var j=a(i.data);if(h){j=j.replace(/^ /,"")}h=!!j.match(/ $/);var j=j.slice(0,k);j=g("<div/>").text(j).html();return j}function a(i){return i.replace(/\s+/g," ")}function c(k){var i=g(k);var j=i.children(":last");if(!j){return k}var l=j.css("display");if(!l||l=="inline"){return i}return c(j)}function b(k){var i=g(k);var j=i.children(":last");if(j&&j.is("p")){return j}return k}})(jQuery);
/*!
 * jQuery BBQ: Back Button & Query Library - v1.2.1 - 2/17/2010
 * http://benalman.com/projects/jquery-bbq-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($,p){var i,m=Array.prototype.slice,r=decodeURIComponent,a=$.param,c,l,v,b=$.bbq=$.bbq||{},q,u,j,e=$.event.special,d="hashchange",A="querystring",D="fragment",y="elemUrlAttr",g="location",k="href",t="src",x=/^.*\?|#.*$/g,w=/^.*\#/,h,C={};function E(F){return typeof F==="string"}function B(G){var F=m.call(arguments,1);return function(){return G.apply(this,F.concat(m.call(arguments)))}}function n(F){return F.replace(/^[^#]*#?(.*)$/,"$1")}function o(F){return F.replace(/(?:^[^?#]*\?([^#]*).*$)?.*/,"$1")}function f(H,M,F,I,G){var O,L,K,N,J;if(I!==i){K=F.match(H?/^([^#]*)\#?(.*)$/:/^([^#?]*)\??([^#]*)(#?.*)/);J=K[3]||"";if(G===2&&E(I)){L=I.replace(H?w:x,"")}else{N=l(K[2]);I=E(I)?l[H?D:A](I):I;L=G===2?I:G===1?$.extend({},I,N):$.extend({},N,I);L=a(L);if(H){L=L.replace(h,r)}}O=K[1]+(H?"#":L||!K[1]?"?":"")+L+J}else{O=M(F!==i?F:p[g][k])}return O}a[A]=B(f,0,o);a[D]=c=B(f,1,n);c.noEscape=function(G){G=G||"";var F=$.map(G.split(""),encodeURIComponent);h=new RegExp(F.join("|"),"g")};c.noEscape(",/");$.deparam=l=function(I,F){var H={},G={"true":!0,"false":!1,"null":null};$.each(I.replace(/\+/g," ").split("&"),function(L,Q){var K=Q.split("="),P=r(K[0]),J,O=H,M=0,R=P.split("]["),N=R.length-1;if(/\[/.test(R[0])&&/\]$/.test(R[N])){R[N]=R[N].replace(/\]$/,"");R=R.shift().split("[").concat(R);N=R.length-1}else{N=0}if(K.length===2){J=r(K[1]);if(F){J=J&&!isNaN(J)?+J:J==="undefined"?i:G[J]!==i?G[J]:J}if(N){for(;M<=N;M++){P=R[M]===""?O.length:R[M];O=O[P]=M<N?O[P]||(R[M+1]&&isNaN(R[M+1])?{}:[]):J}}else{if($.isArray(H[P])){H[P].push(J)}else{if(H[P]!==i){H[P]=[H[P],J]}else{H[P]=J}}}}else{if(P){H[P]=F?i:""}}});return H};function z(H,F,G){if(F===i||typeof F==="boolean"){G=F;F=a[H?D:A]()}else{F=E(F)?F.replace(H?w:x,""):F}return l(F,G)}l[A]=B(z,0);l[D]=v=B(z,1);$[y]||($[y]=function(F){return $.extend(C,F)})({a:k,base:k,iframe:t,img:t,input:t,form:"action",link:k,script:t});j=$[y];function s(I,G,H,F){if(!E(H)&&typeof H!=="object"){F=H;H=G;G=i}return this.each(function(){var L=$(this),J=G||j()[(this.nodeName||"").toLowerCase()]||"",K=J&&L.attr(J)||"";L.attr(J,a[I](K,H,F))})}$.fn[A]=B(s,A);$.fn[D]=B(s,D);b.pushState=q=function(I,F){if(E(I)&&/^#/.test(I)&&F===i){F=2}var H=I!==i,G=c(p[g][k],H?I:{},H?F:2);p[g][k]=G+(/#/.test(G)?"":"#")};b.getState=u=function(F,G){return F===i||typeof F==="boolean"?v(F):v(G)[F]};b.removeState=function(F){var G={};if(F!==i){G=u();$.each($.isArray(F)?F:arguments,function(I,H){delete G[H]})}q(G,2)};e[d]=$.extend(e[d],{add:function(F){var H;function G(J){var I=J[D]=c();J.getState=function(K,L){return K===i||typeof K==="boolean"?l(I,K):l(I,L)[K]};H.apply(this,arguments)}if($.isFunction(F)){H=F;return G}else{H=F.handler;F.handler=G}}})})(jQuery,this);
/*!
 * jQuery hashchange event - v1.2 - 2/11/2010
 * http://benalman.com/projects/jquery-hashchange-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($,i,b){var j,k=$.event.special,c="location",d="hashchange",l="href",f=$.browser,g=document.documentMode,h=f.msie&&(g===b||g<8),e="on"+d in i&&!h;function a(m){m=m||i[c][l];return m.replace(/^[^#]*#?(.*)$/,"$1")}$[d+"Delay"]=100;k[d]=$.extend(k[d],{setup:function(){if(e){return false}$(j.start)},teardown:function(){if(e){return false}$(j.stop)}});j=(function(){var m={},r,n,o,q;function p(){o=q=function(s){return s};if(h){n=$('<iframe src="javascript:0"/>').hide().insertAfter("body")[0].contentWindow;q=function(){return a(n.document[c][l])};o=function(u,s){if(u!==s){var t=n.document;t.open().close();t[c].hash="#"+u}};o(a())}}m.start=function(){if(r){return}var t=a();o||p();(function s(){var v=a(),u=q(t);if(v!==t){o(t=v,u);$(i).trigger(d)}else{if(u!==t){i[c][l]=i[c][l].replace(/#.*/,"")+"#"+u}}r=setTimeout(s,$[d+"Delay"])})()};m.stop=function(){if(!n){r&&clearTimeout(r);r=0}};return m})()})(jQuery,this);

/*!
 * jQuery UI selectmenu
 *
 * Copyright (c) 2009 AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://docs.jquery.com/UI
 * https://github.com/fnagel/jquery-ui/wiki/Selectmenu
 */
(function(a){a.widget("ui.selectmenu",{getter:"value",version:"1.8",eventPrefix:"selectmenu",options:{transferClasses:true,typeAhead:"sequential",style:"dropdown",positionOptions:{my:"left top",at:"left bottom",offset:null},width:null,menuWidth:null,handleWidth:26,maxHeight:null,icons:null,format:null,bgImage:function(){},wrapperElement:""},_create:function(){var b=this,g=this.options;var f=this.element.attr("id")||"ui-selectmenu-"+Math.random().toString(16).slice(2,10);this.ids=[f+"-button",f+"-menu"];this._safemouseup=true;if(a.browser.msie){g.typeAhead=""}this.newelement=a('<a class="'+this.widgetBaseClass+' ui-widget ui-state-default ui-corner-all" id="'+this.ids[0]+'" role="button" href="#" tabindex="0" aria-haspopup="true" aria-owns="'+this.ids[1]+'"></a>').insertAfter(this.element);this.newelement.wrap(g.wrapperElement);var e=this.element.attr("tabindex");if(e){this.newelement.attr("tabindex",e)}this.newelement.data("selectelement",this.element);this.selectmenuIcon=a('<span class="'+this.widgetBaseClass+'-icon ui-icon"></span>').prependTo(this.newelement);this.newelement.prepend('<span class="'+b.widgetBaseClass+'-status" />');a('label[for="'+this.element.attr("id")+'"]').attr("for",this.ids[0]).bind("click.selectmenu",function(){b.newelement[0].focus();return false});this.newelement.bind("mousedown.selectmenu",function(h){b._toggle(h,true);if(g.style=="popup"){b._safemouseup=false;setTimeout(function(){b._safemouseup=true},300)}return false}).bind("click.selectmenu",function(){return false}).bind("keydown.selectmenu",function(i){var h=false;switch(i.keyCode){case a.ui.keyCode.ENTER:h=true;break;case a.ui.keyCode.SPACE:b._toggle(i);break;case a.ui.keyCode.UP:if(i.altKey){b.open(i)}else{b._moveSelection(-1)}break;case a.ui.keyCode.DOWN:if(i.altKey){b.open(i)}else{b._moveSelection(1)}break;case a.ui.keyCode.LEFT:b._moveSelection(-1);break;case a.ui.keyCode.RIGHT:b._moveSelection(1);break;case a.ui.keyCode.TAB:h=true;break;default:h=true;b._typeAhead(i.keyCode,"mouseup");break}return h}).bind("mouseover.selectmenu focus.selectmenu",function(){if(!g.disabled){a(this).addClass(b.widgetBaseClass+"-focus ui-state-hover")}}).bind("mouseout.selectmenu blur.selectmenu",function(){if(!g.disabled){a(this).removeClass(b.widgetBaseClass+"-focus ui-state-hover")}});a(document).bind("mousedown.selectmenu",function(h){b.close(h)});this.element.bind("click.selectmenu",function(){b._refreshValue()}).bind("focus.selectmenu",function(){if(b.newelement){b.newelement[0].focus()}});var d=this.element.width();this.newelement.width(g.width?g.width:d);this.element.hide();this.list=a('<ul class="'+b.widgetBaseClass+'-menu ui-widget ui-widget-content" aria-hidden="true" role="listbox" aria-labelledby="'+this.ids[0]+'" id="'+this.ids[1]+'"></ul>').appendTo("body");this.list.wrap(g.wrapperElement);this.list.bind("keydown.selectmenu",function(i){var h=false;switch(i.keyCode){case a.ui.keyCode.UP:if(i.altKey){b.close(i,true)}else{b._moveFocus(-1)}break;case a.ui.keyCode.DOWN:if(i.altKey){b.close(i,true)}else{b._moveFocus(1)}break;case a.ui.keyCode.LEFT:b._moveFocus(-1);break;case a.ui.keyCode.RIGHT:b._moveFocus(1);break;case a.ui.keyCode.HOME:b._moveFocus(":first");break;case a.ui.keyCode.PAGE_UP:b._scrollPage("up");break;case a.ui.keyCode.PAGE_DOWN:b._scrollPage("down");break;case a.ui.keyCode.END:b._moveFocus(":last");break;case a.ui.keyCode.ENTER:case a.ui.keyCode.SPACE:b.close(i,true);a(i.target).parents("li:eq(0)").trigger("mouseup");break;case a.ui.keyCode.TAB:h=true;b.close(i,true);break;case a.ui.keyCode.ESCAPE:b.close(i,true);break;default:h=true;b._typeAhead(i.keyCode,"focus");break}return h});a(window).bind("resize.selectmenu",a.proxy(b._refreshPosition,this))},_init:function(){var p=this,f=this.options;var b=[];this.element.find("option").each(function(){b.push({value:a(this).attr("value"),text:p._formatText(a(this).text()),selected:a(this).attr("selected"),disabled:a(this).attr("disabled"),classes:a(this).attr("class"),typeahead:a(this).attr("typeahead"),parentOptGroup:a(this).parent("optgroup"),bgImage:f.bgImage.call(a(this))})});var m=(p.options.style=="popup")?" ui-state-active":"";this.list.html("");for(var k=0;k<b.length;k++){var e=a('<li role="presentation"'+(b[k].disabled?' class="'+this.namespace+'-state-disabled"':"")+'><a href="#" tabindex="-1" role="option"'+(b[k].disabled?' aria-disabled="true"':"")+' aria-selected="false"'+(b[k].typeahead?' typeahead="'+b[k].typeahead+'"':"")+">"+b[k].text+"</a></li>").data("index",k).addClass(b[k].classes).data("optionClasses",b[k].classes||"").bind("mouseup.selectmenu",function(i){if(p._safemouseup&&!p._disabled(i.currentTarget)&&!p._disabled(a(i.currentTarget).parents("ul>li."+p.widgetBaseClass+"-group "))){var j=a(this).data("index")!=p._selectedIndex();p.index(a(this).data("index"));p.select(i);if(j){p.change(i)}p.close(i,true)}return false}).bind("click.selectmenu",function(){return false}).bind("mouseover.selectmenu focus.selectmenu",function(){p._selectedOptionLi().addClass(m);p._focusedOptionLi().removeClass(p.widgetBaseClass+"-item-focus ui-state-hover");a(this).removeClass("ui-state-active").addClass(p.widgetBaseClass+"-item-focus ui-state-hover")}).bind("mouseout.selectmenu blur.selectmenu",function(){if(a(this).is(p._selectedOptionLi().selector)){a(this).addClass(m)}a(this).removeClass(p.widgetBaseClass+"-item-focus ui-state-hover")});if(b[k].parentOptGroup.length){var l=p.widgetBaseClass+"-group-"+this.element.find("optgroup").index(b[k].parentOptGroup);if(this.list.find("li."+l).length){this.list.find("li."+l+":last ul").append(e)}else{a(' <li role="presentation" class="'+p.widgetBaseClass+"-group "+l+(b[k].parentOptGroup.attr("disabled")?" "+this.namespace+'-state-disabled" aria-disabled="true"':'"')+'><span class="'+p.widgetBaseClass+'-group-label">'+b[k].parentOptGroup.attr("label")+"</span><ul></ul></li> ").appendTo(this.list).find("ul").append(e)}}else{e.appendTo(this.list)}this.list.bind("mousedown.selectmenu mouseup.selectmenu",function(){return false});if(f.icons){for(var h in f.icons){if(e.is(f.icons[h].find)){e.data("optionClasses",b[k].classes+" "+p.widgetBaseClass+"-hasIcon").addClass(p.widgetBaseClass+"-hasIcon");var n=f.icons[h].icon||"";e.find("a:eq(0)").prepend('<span class="'+p.widgetBaseClass+"-item-icon ui-icon "+n+'"></span>');if(b[k].bgImage){e.find("span").css("background-image",b[k].bgImage)}}}}}var d=(f.style=="dropdown");this.newelement.toggleClass(p.widgetBaseClass+"-dropdown",d).toggleClass(p.widgetBaseClass+"-popup",!d);this.list.toggleClass(p.widgetBaseClass+"-menu-dropdown ui-corner-bottom",d).toggleClass(p.widgetBaseClass+"-menu-popup ui-corner-all",!d).find("li:first").toggleClass("ui-corner-top",!d).end().find("li:last").addClass("ui-corner-bottom");this.selectmenuIcon.toggleClass("ui-icon-triangle-1-s",d).toggleClass("ui-icon-triangle-2-n-s",!d);if(f.transferClasses){var q=this.element.attr("class")||"";this.newelement.add(this.list).addClass(q)}var g=this.element.width();if(f.style=="dropdown"){this.list.width(f.menuWidth?f.menuWidth:(f.width?f.width:g))}else{this.list.width(f.menuWidth?f.menuWidth:(f.width?f.width-f.handleWidth:g-f.handleWidth))}if(f.maxHeight){if(f.maxHeight<this.list.height()){this.list.height(f.maxHeight)}}else{if(!f.format&&(a(window).height()/3)<this.list.height()){f.maxHeight=a(window).height()/3;this.list.height(f.maxHeight)}}this._optionLis=this.list.find("li:not(."+p.widgetBaseClass+"-group)");if(this.element.attr("disabled")===true){this.disable()}this.index(this._selectedIndex());window.setTimeout(function(){p._refreshPosition()},200)},destroy:function(){this.element.removeData(this.widgetName).removeClass(this.widgetBaseClass+"-disabled "+this.namespace+"-state-disabled").removeAttr("aria-disabled").unbind(".selectmenu");a(window).unbind(".selectmenu");a(document).unbind(".selectmenu");a("label[for="+this.newelement.attr("id")+"]").attr("for",this.element.attr("id")).unbind(".selectmenu");if(this.options.wrapperElement){this.newelement.find(this.options.wrapperElement).remove();this.list.find(this.options.wrapperElement).remove()}else{this.newelement.remove();this.list.remove()}this.element.show();a.Widget.prototype.destroy.apply(this,arguments)},_typeAhead:function(f,e){var b=this,d=false,j=String.fromCharCode(f);c=j.toLowerCase();if(b.options.typeAhead=="sequential"){window.clearTimeout("ui.selectmenu-"+b.selectmenuId);var i=typeof(b._prevChar)=="undefined"?"":b._prevChar.join("");function g(k,l,m){d=true;a(k).trigger(e);typeof(b._prevChar)=="undefined"?b._prevChar=[m]:b._prevChar[b._prevChar.length]=m}this.list.find("li a").each(function(k){if(!d){var l=a(this).attr("typeahead")||a(this).text();if(l.indexOf(i+j)==0){g(this,k,j)}else{if(l.indexOf(i+c)==0){g(this,k,c)}}}});if(!d){}window.setTimeout(function(k){k._prevChar=undefined},1000,b)}else{if(!b._prevChar){b._prevChar=["",0]}var d=false;function h(k,l){d=true;a(k).trigger(e);b._prevChar[1]=l}this.list.find("li a").each(function(k){if(!d){var l=a(this).text();if(l.indexOf(j)==0||l.indexOf(c)==0){if(b._prevChar[0]==j){if(b._prevChar[1]<k){h(this,k)}}else{h(this,k)}}}});this._prevChar[0]=j}},_uiHash:function(){var b=this.index();return{index:b,option:a("option",this.element).get(b),value:this.element[0].value}},open:function(d){var b=this;if(this.newelement.attr("aria-disabled")!="true"){this._closeOthers(d);this.newelement.addClass("ui-state-active");if(b.options.wrapperElement){this.list.parent().appendTo("body")}else{this.list.appendTo("body")}this.list.addClass(b.widgetBaseClass+"-open").attr("aria-hidden",false);this._refreshPosition();this.list.find("li:not(."+b.widgetBaseClass+"-group):eq("+this._selectedIndex()+") a")[0].focus();if(this.options.style=="dropdown"){this.newelement.removeClass("ui-corner-all").addClass("ui-corner-top")}this._trigger("open",d,this._uiHash())}},close:function(d,b){if(this.newelement.is(".ui-state-active")){this.newelement.removeClass("ui-state-active");this.list.attr("aria-hidden",true).removeClass(this.widgetBaseClass+"-open");if(this.options.style=="dropdown"){this.newelement.removeClass("ui-corner-top").addClass("ui-corner-all")}if(b){this.newelement.focus()}this._trigger("close",d,this._uiHash())}},change:function(b){this.element.trigger("change");this._trigger("change",b,this._uiHash())},select:function(b){this._trigger("select",b,this._uiHash())},_closeOthers:function(b){a("."+this.widgetBaseClass+".ui-state-active").not(this.newelement).each(function(){a(this).data("selectelement").selectmenu("close",b)});a("."+this.widgetBaseClass+".ui-state-hover").trigger("mouseout")},_toggle:function(d,b){if(this.list.is("."+this.widgetBaseClass+"-open")){this.close(d,b)}else{this.open(d)}},_formatText:function(b){return(this.options.format?this.options.format(b):b)},_selectedIndex:function(){return this.element[0].selectedIndex},_selectedOptionLi:function(){return this._optionLis.eq(this._selectedIndex())},_focusedOptionLi:function(){return this.list.find("."+this.widgetBaseClass+"-item-focus")},_moveSelection:function(e){var d=parseInt(this._selectedOptionLi().data("index"),10);var b=d+e;return this._optionLis.eq(b).trigger("mouseup")},_moveFocus:function(f){if(!isNaN(f)){var e=parseInt(this._focusedOptionLi().data("index")||0,10);var d=e+f}else{var d=parseInt(this._optionLis.filter(f).data("index"),10)}if(d<0){d=0}if(d>this._optionLis.size()-1){d=this._optionLis.size()-1}if(d===recIndex){return false}var b=this.widgetBaseClass+"-item-"+Math.round(Math.random()*1000);this._focusedOptionLi().find("a:eq(0)").attr("id","");if(this._optionLis.eq(d).hasClass(this.namespace+"-state-disabled")){(f>0)?f++:f--;this._moveFocus(f,d)}else{this._optionLis.eq(d).find("a:eq(0)").attr("id",b).focus()}this.list.attr("aria-activedescendant",b)},_scrollPage:function(d){var b=Math.floor(this.list.outerHeight()/this.list.find("li:first").outerHeight());b=(d=="up"?-b:b);this._moveFocus(b)},_setOption:function(b,d){this.options[b]=d;if(b=="disabled"){this.close();this.element.add(this.newelement).add(this.list)[d?"addClass":"removeClass"](this.widgetBaseClass+"-disabled "+this.namespace+"-state-disabled").attr("aria-disabled",d)}},disable:function(b,d){if(!b){this._setOption("disabled",true)}else{if(d=="optgroup"){this._disableOptgroup(b)}else{this._disableOption(b)}}},enable:function(b,d){if(!b){this._setOption("disabled",false)}else{if(d=="optgroup"){this._enableOptgroup(b)}else{this._enableOption(b)}}},_disabled:function(b){return a(b).hasClass(this.namespace+"-state-disabled")},_disableOption:function(b){var d=this._optionLis.eq(b);if(d){d.addClass(this.namespace+"-state-disabled").find("a").attr("aria-disabled",true);this.element.find("option").eq(b).attr("disabled","disabled")}},_enableOption:function(b){var d=this._optionLis.eq(b);if(d){d.removeClass(this.namespace+"-state-disabled").find("a").attr("aria-disabled",false);this.element.find("option").eq(b).removeAttr("disabled")}},_disableOptgroup:function(d){var b=this.list.find("li."+this.widgetBaseClass+"-group-"+d);if(b){b.addClass(this.namespace+"-state-disabled").attr("aria-disabled",true);this.element.find("optgroup").eq(d).attr("disabled","disabled")}},_enableOptgroup:function(d){var b=this.list.find("li."+this.widgetBaseClass+"-group-"+d);if(b){b.removeClass(this.namespace+"-state-disabled").attr("aria-disabled",false);this.element.find("optgroup").eq(d).removeAttr("disabled")}},index:function(b){if(arguments.length){if(!this._disabled(a(this._optionLis[b]))){this.element[0].selectedIndex=b;this._refreshValue()}else{return false}}else{return this._selectedIndex()}},value:function(b){if(arguments.length){this.element[0].value=b;this._refreshValue()}else{return this.element[0].value}},_refreshValue:function(){var e=(this.options.style=="popup")?" ui-state-active":"";var d=this.widgetBaseClass+"-item-"+Math.round(Math.random()*1000);this.list.find("."+this.widgetBaseClass+"-item-selected").removeClass(this.widgetBaseClass+"-item-selected"+e).find("a").attr("aria-selected","false").attr("id","");this._selectedOptionLi().addClass(this.widgetBaseClass+"-item-selected"+e).find("a").attr("aria-selected","true").attr("id",d);var b=(this.newelement.data("optionClasses")?this.newelement.data("optionClasses"):"");var f=(this._selectedOptionLi().data("optionClasses")?this._selectedOptionLi().data("optionClasses"):"");this.newelement.removeClass(b).data("optionClasses",f).addClass(f).find("."+this.widgetBaseClass+"-status").html(this._selectedOptionLi().find("a:eq(0)").html());this.list.attr("aria-activedescendant",d)},_refreshPosition:function(){var e=this.options;if(e.style=="popup"&&!e.positionOptions.offset){var d=this._selectedOptionLi();var b="0 -"+(d.outerHeight()+d.offset().top-this.list.offset().top)}this.list.css({zIndex:this.element.zIndex()}).position({of:e.positionOptions.of||this.newelement,my:e.positionOptions.my,at:e.positionOptions.at,offset:e.positionOptions.offset||b})}})})(jQuery);

/*!
* hoverIntent r6 // 2011.02.26 // jQuery 1.5.1+
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
* 
* @param  f  onMouseOver function || An object with configuration options
* @param  g  onMouseOut function  || Nothing (use configuration options object)
* @author    Brian Cherne brian(at)cherne(dot)net
*/
(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:0};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev])}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev])};var handleHover=function(e){var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t)}if(e.type=="mouseenter"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob)},cfg.timeout)}}};return this.bind('mouseenter',handleHover).bind('mouseleave',handleHover)}})(jQuery);
/*!
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 *
 * Open source under the BSD License.
 *
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * Neither the name of the author nor the names of contributors may be used to endorse
 * or promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
*/
jQuery.easing.jswing=jQuery.easing.swing;jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(e,f,a,h,g){return jQuery.easing[jQuery.easing.def](e,f,a,h,g)},easeInQuad:function(e,f,a,h,g){return h*(f/=g)*f+a},easeOutQuad:function(e,f,a,h,g){return -h*(f/=g)*(f-2)+a},easeInOutQuad:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f+a}return -h/2*((--f)*(f-2)-1)+a},easeInCubic:function(e,f,a,h,g){return h*(f/=g)*f*f+a},easeOutCubic:function(e,f,a,h,g){return h*((f=f/g-1)*f*f+1)+a},easeInOutCubic:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f+a}return h/2*((f-=2)*f*f+2)+a},easeInQuart:function(e,f,a,h,g){return h*(f/=g)*f*f*f+a},easeOutQuart:function(e,f,a,h,g){return -h*((f=f/g-1)*f*f*f-1)+a},easeInOutQuart:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f+a}return -h/2*((f-=2)*f*f*f-2)+a},easeInQuint:function(e,f,a,h,g){return h*(f/=g)*f*f*f*f+a},easeOutQuint:function(e,f,a,h,g){return h*((f=f/g-1)*f*f*f*f+1)+a},easeInOutQuint:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f*f+a}return h/2*((f-=2)*f*f*f*f+2)+a},easeInSine:function(e,f,a,h,g){return -h*Math.cos(f/g*(Math.PI/2))+h+a},easeOutSine:function(e,f,a,h,g){return h*Math.sin(f/g*(Math.PI/2))+a},easeInOutSine:function(e,f,a,h,g){return -h/2*(Math.cos(Math.PI*f/g)-1)+a},easeInExpo:function(e,f,a,h,g){return(f==0)?a:h*Math.pow(2,10*(f/g-1))+a},easeOutExpo:function(e,f,a,h,g){return(f==g)?a+h:h*(-Math.pow(2,-10*f/g)+1)+a},easeInOutExpo:function(e,f,a,h,g){if(f==0){return a}if(f==g){return a+h}if((f/=g/2)<1){return h/2*Math.pow(2,10*(f-1))+a}return h/2*(-Math.pow(2,-10*--f)+2)+a},easeInCirc:function(e,f,a,h,g){return -h*(Math.sqrt(1-(f/=g)*f)-1)+a},easeOutCirc:function(e,f,a,h,g){return h*Math.sqrt(1-(f=f/g-1)*f)+a},easeInOutCirc:function(e,f,a,h,g){if((f/=g/2)<1){return -h/2*(Math.sqrt(1-f*f)-1)+a}return h/2*(Math.sqrt(1-(f-=2)*f)+1)+a},easeInElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return -(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e},easeOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return g*Math.pow(2,-10*h)*Math.sin((h*k-i)*(2*Math.PI)/j)+l+e},easeInOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k/2)==2){return e+l}if(!j){j=k*(0.3*1.5)}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}if(h<1){return -0.5*(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e}return g*Math.pow(2,-10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j)*0.5+l+e},easeInBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*(f/=h)*f*((g+1)*f-g)+a},easeOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*((f=f/h-1)*f*((g+1)*f+g)+1)+a},easeInOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}if((f/=h/2)<1){return i/2*(f*f*(((g*=(1.525))+1)*f-g))+a}return i/2*((f-=2)*f*(((g*=(1.525))+1)*f+g)+2)+a},easeInBounce:function(e,f,a,h,g){return h-jQuery.easing.easeOutBounce(e,g-f,0,h,g)+a},easeOutBounce:function(e,f,a,h,g){if((f/=g)<(1/2.75)){return h*(7.5625*f*f)+a}else{if(f<(2/2.75)){return h*(7.5625*(f-=(1.5/2.75))*f+0.75)+a}else{if(f<(2.5/2.75)){return h*(7.5625*(f-=(2.25/2.75))*f+0.9375)+a}else{return h*(7.5625*(f-=(2.625/2.75))*f+0.984375)+a}}}},easeInOutBounce:function(e,f,a,h,g){if(f<g/2){return jQuery.easing.easeInBounce(e,f*2,0,h,g)*0.5+a}return jQuery.easing.easeOutBounce(e,f*2-g,0,h,g)*0.5+h*0.5+a}});
/*!
 * greenishSlides: jQuery Slideshow plugin - v0.1 - beta (4/6/2011)
 * http://www.philippadrian.com
 *
 * Copyright (c) 2011 Philipp C. Adrian
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 */
(function(a){a.fn.greenishSlides=function(b){return a(this).each(function(){a.gS.init(a(this),b)})};a.gS=a().greenishSlides;a.extend(a.gS,{timer:{},timing:function(c,f,b){return;var e,d;f=f||"";e=new Date();a.gS.timer[c]=a.gS.timer[c]||new Date();d=e-a.gS.timer[c];a.gS.timer[c]=e;if(true&&!b){console.log(c+":"+f+"////////////////////");console.log("Time: "+d+"ms")}},defaults:{stayOpen:false,resizable:true,vertical:false,circle:false,transitionSpeed:400,easing:"swing",events:{activate:"click",deactivate:"click"},keyEvents:true,swipeEvents:true,swipeThreshold:{x:30,y:10},hooks:{},limits:{},active:false,classes:{active:"active",vertical:"gSVertical",horizontal:"gSHorizontal",slide:"gSSlide"},handle:".gSSlide",cache:true},init:function(d,h){d=a(d);h=this.opts=this.setOpts(h);a.gS.hook("preInit",d);var b=a.gS,f=d.css(b.css.context).children().addClass(h.classes.slide).css(b.css.gSSlide),e=function(k,j){var i=j?a(k.target):c(k);if(i&&!i.hasClass(h.classes.active)){b.activate(i)}},g=function(k,j){var i=j?a(k.target):c(k);if(i&&i.has(k.relatedTarget).length<=0&&i!=k.relatedTarget){b.deactivate(i)}},c=function(l){var k=a(l.target),j=k.is(h.handle)?k:a(h.handle,d).has(k),i=j.hasClass(h.classes.slide)?j:d.children().has(j);return i.length?i:false};b.timing("init","Start");if(h.vertical){f.addClass(h.classes.vertical).css(b.css.gSVertical);a.extend(h,b.orientation.vertical)}else{f.addClass(h.classes.horizontal).css(b.css.gSHorizontal);a.extend(h,b.orientation.horizontal)}if(h.keyEvents){a(document).bind("keydown",function(i){if(i.which==39||i.which==40){b.next(d)}else{if(i.which==37||i.which==38){b.prev(d)}}})}if(h.swipeEvents&&d.swipe){d.swipe({threshold:h.swipeThreshold,swipeLeft:function(){a.gS.next(d)},swipeRight:function(){a.gS.prev(d)}})}if(!h.handle){h.events.activate="gSactivate"}d.bind(h.events.activate+" focusin",e);if(!h.stayOpen){d.bind(h.events.deactivate+" focusout",g)}if(a("."+h.classes.active,d).length){a("."+h.classes.active,d).eq(0).removeClass(h.classes.active).trigger(h.events.activate,true)}else{if(h.active!==false){!isNaN(h.active)?f.eq(h.active).removeClass(h.classes.active).trigger(h.events.activate,true):a(h.active,d).eq(0).removeClass(h.classes.active).trigger(h.events.activate,true)}else{b.update(d)}}b.hook("postInit",d);b.timing("init","Done")},activate:function(b){a.gS.timing("activation","Start",true);var c=a.gS,d=c.opts,e;!b.is("."+d.classes.slide+", ."+d.classes.slide+d.handle)?b=a("."+d.classes.slide).has(a(b)):b=a(b);if(b.hasClass(d.classes.active)){return}if(!d.stayOpen&&d.handle){b.siblings("."+d.classes.active).trigger(d.events.deactivate,true)}b.siblings("."+d.classes.active).removeClass(d.classes.active).addClass("gSdeactivated");e=b.siblings(".gSdeactivated");if(e.length>0){e.removeClass("gSdeactivated");c.hook("postDeactivate",b)}b.addClass(d.classes.active);c.hook("preActivate",b);c.update(b.parent())},deactivate:function(b){var c=a.gS,d=c.opts;!b.is("."+d.classes.slide+", ."+d.classes.slide+d.handle)?b=a("."+d.classes.slide).has(a(b)):b=a(b);if(!b.hasClass(d.classes.active)){return}b.removeClass(d.classes.active).addClass("gSdeactivated");c.hook("preDeactivate",b);c.update(b.parent())},prev:function(d,b){var c=a.gS._step(d,-1,b);c=a.gS.hook("prev",d,c);if(c!==false){a.gS.activate(a(d).children().eq(c))}},next:function(d,b){var c=a.gS._step(d,1,b);c=a.gS.hook("next",d,c);if(c!==false){a.gS.activate(a(d).children().eq(c))}},_step:function(d,h,c){var b=a.gS,g=b.opts,f=a(d).children(),e;c=c||f.filter("."+g.classes.slide+"."+g.classes.active);if(!f.filter(c).length){return}e=a(c).index()+(parseFloat(h)%f.length);if(e<0){b.opts.circle?e=f.length+e:e=0}else{if(e>=f.length){b.opts.circle?e=e-f.length:e=f.length-1}}return e},setOpts:function(b){return a.extend(true,{},this.defaults,this.opts||{},b||{})},hook:function(d,c,b){if(this.opts.hooks[d]){return a.proxy(this.opts.hooks[d],c)(b)}else{return b}},cssFloat:function(c,e){var d={minWidth:true,"min-width":true,minHeight:true,"min-height":true},b=d[e];e=a(c).css(e);if(b&&e=="0px"){return undefined}e=parseFloat(e.replace(["px","%"],""));return(!isNaN(e)?e:undefined)},capitalize:function(b){return b.charAt(0).toUpperCase()+b.slice(1)},clearCache:function(b){a(b).removeData("cache")},_getCSS:function(c,e,f,j){var h=a.gS,b=h.opts,g=e[f],d=g.obj.hasClass("posAct"),k=j==f&&g.obj.hasClass(b.LoT);g.css=g.css||{};g.css[b.WoH]=e[f].obj[b.WoH]();g.active=(f==j?true:false);if(b.resizable&&g.active){k?g=h._positioning(c,g,b.LoT,true):g=h._positioning(c,g,b.RoB,true)}else{if((f<j)||j<0||(g.active&&k)){if(!g.obj.hasClass(b.LoT)||d){g=h._positioning(c,g,b.LoT)}g.align=b.LoT;g.css[b.LoT]=h.cssFloat(g.obj,b.LoT)}else{if(!g.obj.hasClass(b.RoB)||d){g=h._positioning(c,g,b.RoB)}g.align=b.RoB;g.css[b.RoB]=h.cssFloat(g.obj,b.RoB)}}return e},_positioning:function(d,f,i,e){var g=a.gS,b=g.opts,c=f.obj.position(),l=i==b.LoT?b.RoB:b.LoT,m=a(d)["inner"+g.capitalize(b.WoH)](),k=f.obj["outer"+g.capitalize(b.WoH)](),h="margin-"+b.LoT,j="margin-"+b.RoB;if(e){css={zIndex:0,position:"relative"};css[b.WoH]="auto";f.css[h]=css[h]=c[b.LoT];f.css[j]=css[j]=m-c[b.LoT]-k;css[i]=0;f.obj.addClass("posAct");f.align=i}else{css={zIndex:1,position:"absolute"};css[i]=g.cssFloat(f.obj,"margin-"+i);if(!css[i]&&!g.cssFloat(f.obj,"margin-"+l)){i==b.LoT?css[i]=c[b.LoT]:css[i]=m-c[b.LoT]-k}css[h]=0;css[j]=0;f.css[b.WoH]=css[b.WoH]=k;f.css[i]=css[i];f.css[l]="auto";f.obj.removeClass("posAct")}css[l]="auto";f.obj.removeClass(l).addClass(i).css(css);return f},_getLimits:function(d,f,g){var j=a.gS,c=j.opts,h=f[g],k=h.css["min-"+c.WoH]||j.cssFloat(h.obj,"min-"+c.WoH),e=h.css["max-"+c.WoH]||j.cssFloat(h.obj,"max-"+c.WoH),b={max:!isNaN(e)?e:c.limits[g]&&!isNaN(c.limits[g].max)?c.limits[g].max:!isNaN(c.limits.max)?c.limits.max:undefined,min:!isNaN(k)?k:c.limits[g]&&!isNaN(c.limits[g].min)?c.limits[g].min:!isNaN(c.limits.min)?c.limits.min:undefined};if(k&&k>b.max){b.max=k}if(e&&e<b.min){b.min=e}return b},_getDCss:function(d,j,o){var m=a.gS,b=m.opts,p={},l=j.length,e=cS=a(d)[b.WoH](),k,f,h,n,g={};for(h=n=0;slide=j[h];h++){if(!slide.active){n+=slide.limits.min||0}g[h]={}}if(o>=0&&(isNaN(j[o].limits.max)||j[o].limits.max>cS-n)){for(h=0;h<j.length;h++){h==o?g[h][b.WoH]=cS-n:g[h][b.WoH]=j[h].limits.min||0}}else{k=e/l;for(h=0;limit=j[h];h++){limit=limit.limits;f=(limit.max<k);if(!p[h]&&(limit.min>k||f||h==o)){p[h]=true;l--;f||h==o?e-=g[h][b.WoH]=limit.max:e-=g[h][b.WoH]=limit.min;k=e/l;h=-1}}for(h=0;h<j.length;h++){if(!p[h]){g[h][b.WoH]=k}}}for(h=n=0;slide=j[h];h++){n+=g[h][b.WoH];if(b.resizable&&h==o){g[h]["margin-"+b.LoT]=n-g[h][b.WoH];g[h]["margin-"+b.RoB]=cS-n}else{if((h<o)||o<0||(slide.obj.hasClass(b.LoT)&&o==h)){g[h][b.LoT]=n-g[h][b.WoH]}else{g[h][b.RoB]=cS-n}}}return g},_getData:function(f){a.gS.timing("update","Start",true);var l=a.gS,c=l.opts,e=a(f).children(),g=e.filter("."+c.classes.slide+"."+c.classes.active),m=g.index(),k,d=f.data("cache")||{},j=d.dcss||{},b=d.limits||{},h=[];for(k=e.length-1;k>=0;k--){h[k]=h[k]||{obj:e.eq(k)};h=l._getCSS(f,h,k,m);!c.cache||!b[k]?h[k].limits=b[k]=l._getLimits(f,h,k):h[k].limits=b[k]}if(!c.cache||!j[m]){j[m]=l._getDCss(f,h,m)}if(c.cache){f.data("cache",{dcss:j,limits:b})}return{dcss:j[m],data:h,opts:c,cS:a(f)[c.WoH]()}},update:function(e,g){e=a(e).stop();var d=a.gS,f=a(e).children(),i,c,b,h;this.opts=g=g?d.setOpts(g):d.opts;i=f.filter("."+g.classes.slide+"."+g.classes.active);h=d._getData(e);e.data("animation",h);if(i.length<=0){d.hook("preDeactivateAnimation",i,h);b=function(){var j=a(this).find("."+g.classes.slide+".gSdeactivated");if(j.length>0){d.hook("postDeactivate",j);j.removeClass("gSdeactivated")}}}else{d.hook("preActivateAnimation",i,h);b=function(){var j=a(this).find("."+g.classes.slide+"."+g.classes.active);if(j.length>0){d.hook("postActivate",j)}}}e.dequeue("gSpreAnimation").css({textIndent:0}).animate({textIndent:100},{duration:g.transitionSpeed,easing:g.easing,complete:b,step:d.animationStep}).dequeue("gSpostAnimation");a.gS.timing("activation","done")},animationStep:function(c,h){a.gS.timing("step","start",true);var d=a(h.elem).dequeue("gSanimationStep").data("animation");if(!d){a(this).stop();return}var b=d.opts,j=d.data,l=d.dcss,n={},m,e,g,o,f=function(q,k){return Math.round(j[q].css[k]+((l[q][k]-j[q].css[k])*(c[q]||c)))},p=function(k,q){return n[k]?n[k][q]+n[k][b.WoH]:0};c/=100;for(g=j.length-1;m=j[g];g--){n[g]={};if(!m.active){m.css[m.align]=m.css[m.align]||0;n[g][m.align]=f(g,m.align)}else{o=g}}for(g=j.length-1;m=j[g];g--){if(!m.active){e=(m.align==b.LoT?g+1:g-1);if(j[e]){!j[e].active?n[g][b.WoH]=n[e][m.align]-n[g][m.align]:n[g][b.WoH]=f(g,b.WoH)}else{n[g][b.WoH]=d.cS-n[g][m.align]}m.obj.css(n[g])}}if(n[o]){if(b.resizable){n[o]["margin-"+b.LoT]=p(o-1,b.LoT);n[o]["margin-"+b.RoB]=p(o+1,b.RoB)}else{if(j[o].align==b.LoT){n[o][b.LoT]=p(o-1,b.LoT);n[o][b.WoH]=d.cS-n[o][b.LoT]-p(o+1,b.RoB)}else{n[o][b.RoB]=p(o+1,b.RoB);n[o][b.WoH]=d.cS-n[o][b.RoB]-p(o-1,b.LoT)}}j[o].obj.css(n[o])}a.gS.timing("step","end",true)},orientation:{horizontal:{WoH:"width",LoT:"left",RoB:"right"},vertical:{WoH:"height",LoT:"top",RoB:"bottom"}},css:{context:{zoom:1,listStyle:"none",margin:0,padding:0,border:0},gSSlide:{position:"absolute",margin:0,border:0,padding:0,display:"block",overflow:"hidden",textIndent:0},gSHorizontal:{marginBottom:"-100%",height:"100%",top:0},gSVertical:{marginRight:"-100%",width:"100%",left:0}}})})(jQuery);
/*! 
* Browser detection script from http://www.quirksmode.org/js/detect.html
* we're just doing this to prompt upgrades for browsers below the minium spec
* In all other cases we're using feature sniffing rather than browser sniffing.
* See http://www.quirksmode.org/js/support.html for why
*/
var BrowserDetect={init:function(){this.browser=this.searchString(this.dataBrowser)||"An unknown browser";this.version=this.searchVersion(navigator.userAgent)||this.searchVersion(navigator.appVersion)||"an unknown version";this.OS=this.searchString(this.dataOS)||"an unknown OS"},searchString:function(d){for(var a=0;a<d.length;a++){var b=d[a].string;var c=d[a].prop;this.versionSearchString=d[a].versionSearch||d[a].identity;if(b){if(b.indexOf(d[a].subString)!=-1){return d[a].identity}}else{if(c){return d[a].identity}}}},searchVersion:function(b){var a=b.indexOf(this.versionSearchString);if(a==-1){return}return parseFloat(b.substring(a+this.versionSearchString.length+1))},dataBrowser:[{string:navigator.userAgent,subString:"Chrome",identity:"Chrome"},{string:navigator.userAgent,subString:"OmniWeb",versionSearch:"OmniWeb/",identity:"OmniWeb"},{string:navigator.vendor,subString:"Apple",identity:"Safari",versionSearch:"Version"},{prop:window.opera,identity:"Opera"},{string:navigator.vendor,subString:"iCab",identity:"iCab"},{string:navigator.vendor,subString:"KDE",identity:"Konqueror"},{string:navigator.userAgent,subString:"Firefox",identity:"Firefox"},{string:navigator.vendor,subString:"Camino",identity:"Camino"},{string:navigator.userAgent,subString:"Netscape",identity:"Netscape"},{string:navigator.userAgent,subString:"MSIE",identity:"Explorer",versionSearch:"MSIE"},{string:navigator.userAgent,subString:"Gecko",identity:"Mozilla",versionSearch:"rv"},{string:navigator.userAgent,subString:"Mozilla",identity:"Netscape",versionSearch:"Mozilla"}],dataOS:[{string:navigator.platform,subString:"Win",identity:"Windows"},{string:navigator.platform,subString:"Mac",identity:"Mac"},{string:navigator.userAgent,subString:"iPhone",identity:"iPhone/iPod"},{string:navigator.platform,subString:"Linux",identity:"Linux"}]};