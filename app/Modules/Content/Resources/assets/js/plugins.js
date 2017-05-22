/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright � 2008 George McGinley Smith
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

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		//alert(jQuery.easing.default);
		return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});

/*
 *
 * TERMS OF USE - EASING EQUATIONS
 * 
 * Open source under the BSD License. 
 * 
 * Copyright � 2001 Robert Penner
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
 
 
 
 
(function($){$.belowthefold=function(element,settings){var fold=$(window).height()+$(window).scrollTop();return fold<=$(element).offset().top-settings.threshold;};$.abovethetop=function(element,settings){var top=$(window).scrollTop();return top>=$(element).offset().top+$(element).height()-settings.threshold;};$.rightofscreen=function(element,settings){var fold=$(window).width()+$(window).scrollLeft();return fold<=$(element).offset().left-settings.threshold;};$.leftofscreen=function(element,settings){var left=$(window).scrollLeft();return left>=$(element).offset().left+$(element).width()-settings.threshold;};$.inviewport=function(element,settings){return!$.rightofscreen(element,settings)&&!$.leftofscreen(element,settings)&&!$.belowthefold(element,settings)&&!$.abovethetop(element,settings);};$.extend($.expr[':'],{"below-the-fold":function(a,i,m){return $.belowthefold(a,{threshold:0});},"above-the-top":function(a,i,m){return $.abovethetop(a,{threshold:0});},"left-of-screen":function(a,i,m){return $.leftofscreen(a,{threshold:0});},"right-of-screen":function(a,i,m){return $.rightofscreen(a,{threshold:0});},"in-viewport":function(a,i,m){return $.inviewport(a,{threshold:0});}});})(jQuery);




/*global jQuery */
/*!
* FitVids 1.0
*
* Copyright 2011, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
* Date: Thu Sept 01 18:00:00 2011 -0500
*/

(function(a){"use strict";a.fn.fitVids=function(b){var c={customSelector:null},d=document.createElement("div"),e=document.getElementsByTagName("base")[0]||document.getElementsByTagName("script")[0];return d.className="fit-vids-style",d.innerHTML="&shy;<style> .fluid-width-video-wrapper { width: 100%; position: relative; padding: 0; } .fluid-width-video-wrapper iframe, .fluid-width-video-wrapper object, .fluid-width-video-wrapper embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; } </style>",e.parentNode.insertBefore(d,e),b&&a.extend(c,b),this.each(function(){var b=["iframe[src*='player.vimeo.com']","iframe[src*='www.youtube.com']","iframe[src*='www.youtube-nocookie.com']","iframe[src*='www.kickstarter.com']","object","embed"];c.customSelector&&b.push(c.customSelector);var d=a(this).find(b.join(","));d.each(function(){var b=a(this);if(!("embed"===this.tagName.toLowerCase()&&b.parent("object").length||b.parent(".fluid-width-video-wrapper").length)){var c="object"===this.tagName.toLowerCase()||b.attr("height")&&!isNaN(parseInt(b.attr("height"),10))?parseInt(b.attr("height"),10):b.height(),d=isNaN(parseInt(b.attr("width"),10))?b.width():parseInt(b.attr("width"),10),e=c/d;if(!b.attr("id")){var f="fitvid"+Math.floor(999999*Math.random());b.attr("id",f)}b.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",100*e+"%"),b.removeAttr("height").removeAttr("width")}})})}})(jQuery);




/* jQuery Tipsy */

(function($) {

    function maybeCall(thing, ctx) {
        return (typeof thing == 'function') ? (thing.call(ctx)) : thing;
    };

    function Tipsy(element, options) {
        this.$element = $(element);
        this.options = options;
        this.enabled = true;
        this.fixTitle();
    };

    Tipsy.prototype = {
        show: function() {
            var title = this.getTitle();
            if (title && this.enabled) {
                var $tip = this.tip();

                $tip.find('.tipsy-inner')[this.options.html ? 'html' : 'text'](title);
                $tip[0].className = 'tipsy'; // reset classname in case of dynamic gravity
                $tip.remove().css({top: 0, left: 0, visibility: 'hidden', display: 'block'}).prependTo(document.body);

                var pos = $.extend({}, this.$element.offset(), {
                    width: this.$element[0].offsetWidth,
                    height: this.$element[0].offsetHeight
                });

                var actualWidth = $tip[0].offsetWidth,
                    actualHeight = $tip[0].offsetHeight,
                    gravity = maybeCall(this.options.gravity, this.$element[0]);

                var tp;
                switch (gravity.charAt(0)) {
                    case 'n':
                        tp = {top: pos.top + pos.height + this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2};
                        break;
                    case 's':
                        tp = {top: pos.top - actualHeight - this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2};
                        break;
                    case 'e':
                        tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth - this.options.offset};
                        break;
                    case 'w':
                        tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width + this.options.offset};
                        break;
                }

                if (gravity.length == 2) {
                    if (gravity.charAt(1) == 'w') {
                        tp.left = pos.left + pos.width / 2 - 15;
                    } else {
                        tp.left = pos.left + pos.width / 2 - actualWidth + 15;
                    }
                }

                $tip.css(tp).addClass('tipsy-' + gravity);
                $tip.find('.tipsy-arrow')[0].className = 'tipsy-arrow tipsy-arrow-' + gravity.charAt(0);
                if (this.options.className) {
                    $tip.addClass(maybeCall(this.options.className, this.$element[0]));
                }

                if (this.options.fade) {
                    $tip.stop().css({opacity: 0, display: 'block', visibility: 'visible'}).animate({opacity: this.options.opacity});
                } else {
                    $tip.css({visibility: 'visible', opacity: this.options.opacity});
                }
            }
        },

        hide: function() {
            if (this.options.fade) {
                this.tip().stop().fadeOut(function() { $(this).remove(); });
            } else {
                this.tip().remove();
            }
        },

        fixTitle: function() {
            var $e = this.$element;
            if ($e.attr('title') || typeof($e.attr('original-title')) != 'string') {
                $e.attr('original-title', $e.attr('title') || '').removeAttr('title');
            }
        },

        getTitle: function() {
            var title, $e = this.$element, o = this.options;
            this.fixTitle();
            var title, o = this.options;
            if (typeof o.title == 'string') {
                title = $e.attr(o.title == 'title' ? 'original-title' : o.title);
            } else if (typeof o.title == 'function') {
                title = o.title.call($e[0]);
            }
            title = ('' + title).replace(/(^\s*|\s*$)/, "");
            return title || o.fallback;
        },

        tip: function() {
            if (!this.$tip) {
                this.$tip = $('<div class="tipsy"></div>').html('<div class="tipsy-arrow"></div><div class="tipsy-inner"></div>');
            }
            return this.$tip;
        },

        validate: function() {
            if (!this.$element[0].parentNode) {
                this.hide();
                this.$element = null;
                this.options = null;
            }
        },

        enable: function() { this.enabled = true; },
        disable: function() { this.enabled = false; },
        toggleEnabled: function() { this.enabled = !this.enabled; }
    };

    $.fn.tipsy = function(options) {

        $.fn.tipsy.enable();

        if (options === true) {
            return this.data('tipsy');
        } else if (typeof options == 'string') {
            var tipsy = this.data('tipsy');
            if (tipsy) tipsy[options]();
            return this;
        }

        options = $.extend({}, $.fn.tipsy.defaults, options);

        function get(ele) {
            var tipsy = $.data(ele, 'tipsy');
            if (!tipsy) {
                tipsy = new Tipsy(ele, $.fn.tipsy.elementOptions(ele, options));
                $.data(ele, 'tipsy', tipsy);
            }
            return tipsy;
        }

        function enter() {
            if ($.fn.tipsy.enabled !== true) {
                return;
            }
            var tipsy = get(this);
            tipsy.hoverState = 'in';
            if (options.delayIn == 0) {
                tipsy.show();
            } else {
                tipsy.fixTitle();
                setTimeout(function() { if (tipsy.hoverState == 'in') tipsy.show(); }, options.delayIn);
            }
        };

        function leave() {
            var tipsy = get(this);
            tipsy.hoverState = 'out';
            if (options.delayOut == 0) {
                tipsy.hide();
            } else {
                setTimeout(function() { if (tipsy.hoverState == 'out') tipsy.hide(); }, options.delayOut);
            }
        };

        if (!options.live) this.each(function() { get(this); });

        if (options.trigger != 'manual') {
            var binder   = options.live ? 'live' : 'bind',
                eventIn  = options.trigger == 'hover' ? 'mouseenter' : 'focus',
                eventOut = options.trigger == 'hover' ? 'mouseleave' : 'blur';
            this[binder](eventIn, enter)[binder](eventOut, leave);
        }

        return this;

    };

    $.fn.tipsy.defaults = {
        className: null,
        delayIn: 0,
        delayOut: 0,
        fade: false,
        fallback: '',
        gravity: 'n',
        html: false,
        live: false,
        offset: 0,
        opacity: 0.8,
        title: 'title',
        trigger: 'hover'
    };

    $.fn.tipsy.enable = function() {
        $.fn.tipsy.enabled = true;
    }

    $.fn.tipsy.disable = function() {
        $.fn.tipsy.enabled = false;
    }

    // Overwrite this method to provide options on a per-element basis.
    // For example, you could store the gravity in a 'tipsy-gravity' attribute:
    // return $.extend({}, options, {gravity: $(ele).attr('tipsy-gravity') || 'n' });
    // (remember - do not modify 'options' in place!)
    $.fn.tipsy.elementOptions = function(ele, options) {
        return $.metadata ? $.extend({}, options, $(ele).metadata()) : options;
    };

    $.fn.tipsy.autoNS = function() {
        return $(this).offset().top > ($(document).scrollTop() + $(window).height() / 2) ? 's' : 'n';
    };

    $.fn.tipsy.autoWE = function() {
        return $(this).offset().left > ($(document).scrollLeft() + $(window).width() / 2) ? 'e' : 'w';
    };

    /**
     * yields a closure of the supplied parameters, producing a function that takes
     * no arguments and is suitable for use as an autogravity function like so:
     *
     * @param margin (int) - distance from the viewable region edge that an
     *        element should be before setting its tooltip's gravity to be away
     *        from that edge.
     * @param prefer (string, e.g. 'n', 'sw', 'w') - the direction to prefer
     *        if there are no viewable region edges effecting the tooltip's
     *        gravity. It will try to vary from this minimally, for example,
     *        if 'sw' is preferred and an element is near the right viewable
     *        region edge, but not the top edge, it will set the gravity for
     *        that element's tooltip to be 'se', preserving the southern
     *        component.
     */
     $.fn.tipsy.autoBounds = function(margin, prefer) {
        return function() {
            var dir = {ns: prefer[0], ew: (prefer.length > 1 ? prefer[1] : false)},
                boundTop = $(document).scrollTop() + margin,
                boundLeft = $(document).scrollLeft() + margin,
                $this = $(this);

            if ($this.offset().top < boundTop) dir.ns = 'n';
            if ($this.offset().left < boundLeft) dir.ew = 'w';
            if ($(window).width() + $(document).scrollLeft() - $this.offset().left < margin) dir.ew = 'e';
            if ($(window).height() + $(document).scrollTop() - $this.offset().top < margin) dir.ns = 's';

            return dir.ns + (dir.ew ? dir.ew : '');
        }
    };

})(jQuery);



/*
  AUTHOR: Osvaldas Valutis, www.osvaldas.info AudioPlayer CoDrops
*/

(function(e,t,n,r){var i="ontouchstart"in t,s=i?"touchstart":"mousedown",o=i?"touchmove":"mousemove",u=i?"touchend":"mouseup",a=i?"touchcancel":"mouseup",f=function(e){var t=Math.floor(e/3600),n=Math.floor(e%3600/60),r=Math.ceil(e%3600%60);return(t==0?"":t>0&&t.toString().length<2?"0"+t+":":t+":")+(n.toString().length<2?"0"+n:n)+":"+(r.toString().length<2?"0"+r:r)},l=function(e){var t=n.createElement("audio");return!!(t.canPlayType&&t.canPlayType("audio/"+e.split(".").pop().toLowerCase()+";").replace(/no/,""))};e.fn.audioPlayer=function(t){var t=e.extend({classPrefix:"audioplayer",strPlay:"Play",strPause:"Pause",strVolume:"Volume"},t),n={},r={playPause:"playpause",playing:"playing",time:"time",timeCurrent:"time-current",timeDuration:"time-duration",bar:"bar",barLoaded:"bar-loaded",barPlayed:"bar-played",volume:"volume",volumeButton:"volume-button",volumeAdjust:"volume-adjust",noVolume:"novolume",mute:"mute",mini:"mini"};for(var u in r)n[u]=t.classPrefix+"-"+r[u];this.each(function(){if(e(this).prop("tagName").toLowerCase()!="audio")return false;var r=e(this),u=r.attr("src"),c=r.get(0).getAttribute("autoplay"),c=c===""||c==="autoplay"?true:false,h=r.get(0).getAttribute("loop"),h=h===""||h==="loop"?true:false,p=false;if(typeof u==="undefined"){r.find("source").each(function(){u=e(this).attr("src");if(typeof u!=="undefined"&&l(u)){p=true;return false}})}else if(l(u))p=true;var d=e('<div class="'+t.classPrefix+'">'+(p?e("<div>").append(r.eq(0).clone()).html():'<embed src="'+u+'" width="0" height="0" volume="100" autostart="'+c.toString()+'" loop="'+h.toString()+'" />')+'<div class="'+n.playPause+'" title="'+t.strPlay+'"><a href="#">'+t.strPlay+"</a></div></div>"),v=p?d.find("audio"):d.find("embed"),v=v.get(0);if(p){d.find("audio").css({width:0,height:0,visibility:"hidden"});d.append('<div class="'+n.time+" "+n.timeCurrent+'"></div><div class="'+n.bar+'"><div class="'+n.barLoaded+'"></div><div class="'+n.barPlayed+'"></div></div><div class="'+n.time+" "+n.timeDuration+'"></div><div class="'+n.volume+'"><div class="'+n.volumeButton+'" title="'+t.strVolume+'"><a href="#">'+t.strVolume+'</a></div><div class="'+n.volumeAdjust+'"><div><div></div></div></div></div>');var m=d.find("."+n.bar),g=d.find("."+n.barPlayed),y=d.find("."+n.barLoaded),b=d.find("."+n.timeCurrent),w=d.find("."+n.timeDuration),E=d.find("."+n.volumeButton),S=d.find("."+n.volumeAdjust+" > div"),x=0,T=function(e){theRealEvent=i?e.originalEvent.touches[0]:e;v.currentTime=Math.round(v.duration*(theRealEvent.pageX-m.offset().left)/m.width())},N=function(e){theRealEvent=i?e.originalEvent.touches[0]:e;v.volume=Math.abs((theRealEvent.pageY-(S.offset().top+S.height()))/S.height())},C=setInterval(function(){y.width(v.buffered.end(0)/v.duration*100+"%");if(v.buffered.end(0)>=v.duration)clearInterval(C)},100);var k=v.volume,L=v.volume=.111;if(Math.round(v.volume*1e3)/1e3==L)v.volume=k;else d.addClass(n.noVolume);w.html("�");b.text(f(0));v.addEventListener("loadeddata",function(){w.text(f(v.duration));S.find("div").height(v.volume*100+"%");x=v.volume});v.addEventListener("timeupdate",function(){b.text(f(v.currentTime));g.width(v.currentTime/v.duration*100+"%")});v.addEventListener("volumechange",function(){S.find("div").height(v.volume*100+"%");if(v.volume>0&&d.hasClass(n.mute))d.removeClass(n.mute);if(v.volume<=0&&!d.hasClass(n.mute))d.addClass(n.mute)});v.addEventListener("ended",function(){d.removeClass(n.playing)});m.on(s,function(e){T(e);m.on(o,function(e){T(e)})}).on(a,function(){m.unbind(o)});E.on("click",function(){if(d.hasClass(n.mute)){d.removeClass(n.mute);v.volume=x}else{d.addClass(n.mute);x=v.volume;v.volume=0}return false});S.on(s,function(e){N(e);S.on(o,function(e){N(e)})}).on(a,function(){S.unbind(o)})}else d.addClass(n.mini);if(c)d.addClass(n.playing);d.find("."+n.playPause).on("click",function(){if(d.hasClass(n.playing)){e(this).attr("title",t.strPlay).find("a").html(t.strPlay);d.removeClass(n.playing);p?v.pause():v.Stop()}else{e(this).attr("title",t.strPause).find("a").html(t.strPause);d.addClass(n.playing);p?v.play():v.Play()}return false});r.replaceWith(d)});return this}})(jQuery,window,document);



/*
 * jQuery Superfish Menu Plugin
 * Copyright (c) 2013 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 *	http://www.opensource.org/licenses/mit-license.php
 *	http://www.gnu.org/licenses/gpl.html
 */

(function ($) {
	"use strict";

	var methods = (function () {
		// private properties and methods go here
		var c = {
				bcClass: 'sf-breadcrumb',
				menuClass: 'sf-js-enabled',
				anchorClass: 'sf-with-ul',
				menuArrowClass: 'sf-arrows'
			},
			ios = (function () {
				var ios = /iPhone|iPad|iPod/i.test(navigator.userAgent);
				if (ios) {
					// iOS clicks only bubble as far as body children
					$(window).load(function () {
						$('body').children().on('click', $.noop);
					});
				}
				return ios;
			})(),
			wp7 = (function () {
				var style = document.documentElement.style;
				return ('behavior' in style && 'fill' in style && /iemobile/i.test(navigator.userAgent));
			})(),
			toggleMenuClasses = function ($menu, o) {
				var classes = c.menuClass;
				if (o.cssArrows) {
					classes += ' ' + c.menuArrowClass;
				}
				$menu.toggleClass(classes);
			},
			setPathToCurrent = function ($menu, o) {
				return $menu.find('li.' + o.pathClass).slice(0, o.pathLevels)
					.addClass(o.hoverClass + ' ' + c.bcClass)
						.filter(function () {
							return ($(this).children(o.popUpSelector).hide().show().length);
						}).removeClass(o.pathClass);
			},
			toggleAnchorClass = function ($li) {
				$li.children('a').toggleClass(c.anchorClass);
			},
			toggleTouchAction = function ($menu) {
				var touchAction = $menu.css('ms-touch-action');
				touchAction = (touchAction === 'pan-y') ? 'auto' : 'pan-y';
				$menu.css('ms-touch-action', touchAction);
			},
			applyHandlers = function ($menu, o) {
				var targets = 'li:has(' + o.popUpSelector + ')';
				if ($.fn.hoverIntent && !o.disableHI) {
					$menu.hoverIntent(over, out, targets);
				}
				else {
					$menu
						.on('mouseenter.superfish', targets, over)
						.on('mouseleave.superfish', targets, out);
				}
				var touchevent = 'MSPointerDown.superfish';
				if (!ios) {
					touchevent += ' touchend.superfish';
				}
				if (wp7) {
					touchevent += ' mousedown.superfish';
				}
				$menu
					.on('focusin.superfish', 'li', over)
					.on('focusout.superfish', 'li', out)
					.on(touchevent, 'a', o, touchHandler);
			},
			touchHandler = function (e) {
				var $this = $(this),
					$ul = $this.siblings(e.data.popUpSelector);

				if ($ul.length > 0 && $ul.is(':hidden')) {
					$this.one('click.superfish', false);
					if (e.type === 'MSPointerDown') {
						$this.trigger('focus');
					} else {
						$.proxy(over, $this.parent('li'))();
					}
				}
			},
			over = function () {
				var $this = $(this),
					o = getOptions($this);
				clearTimeout(o.sfTimer);
				$this.siblings().superfish('hide').end().superfish('show');
			},
			out = function () {
				var $this = $(this),
					o = getOptions($this);
				if (ios) {
					$.proxy(close, $this, o)();
				}
				else {
					clearTimeout(o.sfTimer);
					o.sfTimer = setTimeout($.proxy(close, $this, o), o.delay);
				}
			},
			close = function (o) {
				o.retainPath = ($.inArray(this[0], o.$path) > -1);
				this.superfish('hide');

				if (!this.parents('.' + o.hoverClass).length) {
					o.onIdle.call(getMenu(this));
					if (o.$path.length) {
						$.proxy(over, o.$path)();
					}
				}
			},
			getMenu = function ($el) {
				return $el.closest('.' + c.menuClass);
			},
			getOptions = function ($el) {
				return getMenu($el).data('sf-options');
			};

		return {
			// public methods
			hide: function (instant) {
				if (this.length) {
					var $this = this,
						o = getOptions($this);
					if (!o) {
						return this;
					}
					var not = (o.retainPath === true) ? o.$path : '',
						$ul = $this.find('li.' + o.hoverClass).add(this).not(not).removeClass(o.hoverClass).children(o.popUpSelector),
						speed = o.speedOut;

					if (instant) {
						$ul.show();
						speed = 0;
					}
					o.retainPath = false;
					o.onBeforeHide.call($ul);
					$ul.stop(true, true).animate(o.animationOut, speed, function () {
						var $this = $(this);
						o.onHide.call($this);
					});
				}
				return this;
			},
			show: function () {
				var o = getOptions(this);
				if (!o) {
					return this;
				}
				var $this = this.addClass(o.hoverClass),
					$ul = $this.children(o.popUpSelector);

				o.onBeforeShow.call($ul);
				$ul.stop(true, true).animate(o.animation, o.speed, function () {
					o.onShow.call($ul);
				});
				return this;
			},
			destroy: function () {
				return this.each(function () {
					var $this = $(this),
						o = $this.data('sf-options'),
						$hasPopUp;
					if (!o) {
						return false;
					}
					$hasPopUp = $this.find(o.popUpSelector).parent('li');
					clearTimeout(o.sfTimer);
					toggleMenuClasses($this, o);
					toggleAnchorClass($hasPopUp);
					toggleTouchAction($this);
					// remove event handlers
					$this.off('.superfish').off('.hoverIntent');
					// clear animation's inline display style
					$hasPopUp.children(o.popUpSelector).attr('style', function (i, style) {
						return style.replace(/display[^;]+;?/g, '');
					});
					// reset 'current' path classes
					o.$path.removeClass(o.hoverClass + ' ' + c.bcClass).addClass(o.pathClass);
					$this.find('.' + o.hoverClass).removeClass(o.hoverClass);
					o.onDestroy.call($this);
					$this.removeData('sf-options');
				});
			},
			init: function (op) {
				return this.each(function () {
					var $this = $(this);
					if ($this.data('sf-options')) {
						return false;
					}
					var o = $.extend({}, $.fn.superfish.defaults, op),
						$hasPopUp = $this.find(o.popUpSelector).parent('li');
					o.$path = setPathToCurrent($this, o);

					$this.data('sf-options', o);

					toggleMenuClasses($this, o);
					toggleAnchorClass($hasPopUp);
					toggleTouchAction($this);
					applyHandlers($this, o);

					$hasPopUp.not('.' + c.bcClass).superfish('hide', true);

					o.onInit.call(this);
				});
			}
		};
	})();

	$.fn.superfish = function (method, args) {
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if (typeof method === 'object' || ! method) {
			return methods.init.apply(this, arguments);
		}
		else {
			return $.error('Method ' +  method + ' does not exist on jQuery.fn.superfish');
		}
	};

	$.fn.superfish.defaults = {
		popUpSelector: 'ul,.sf-mega', // within menu context
		hoverClass: 'sfHover',
		pathClass: 'overrideThisToUse',
		pathLevels: 1,
		delay: 800,
		animation: {opacity: 'show'},
		animationOut: {opacity: 'hide'},
		speed: 'normal',
		speedOut: 'fast',
		cssArrows: true,
		disableHI: false,
		onInit: $.noop,
		onBeforeShow: $.noop,
		onShow: $.noop,
		onBeforeHide: $.noop,
		onHide: $.noop,
		onIdle: $.noop,
		onDestroy: $.noop
	};

	// soon to be deprecated
	$.fn.extend({
		hideSuperfishUl: methods.hide,
		showSuperfishUl: methods.show
	});

})(jQuery);




/**
 * hoverIntent is similar to jQuery's built-in "hover" method except that
 * instead of firing the handlerIn function immediately, hoverIntent checks
 * to see if the user's mouse has slowed down (beneath the sensitivity
 * threshold) before firing the event. The handlerOut function is only
 * called after a matching handlerIn.
 *
 * hoverIntent r7 // 2013.03.11 // jQuery 1.9.1+
 * http://cherne.net/brian/resources/jquery.hoverIntent.html
 *
 * You may use hoverIntent under the terms of the MIT license. Basically that
 * means you are free to use hoverIntent as long as this header is left intact.
 * Copyright 2007, 2013 Brian Cherne
 *
 * // basic usage ... just like .hover()
 * .hoverIntent( handlerIn, handlerOut )
 * .hoverIntent( handlerInOut )
 *
 * // basic usage ... with event delegation!
 * .hoverIntent( handlerIn, handlerOut, selector )
 * .hoverIntent( handlerInOut, selector )
 *
 * // using a basic configuration object
 * .hoverIntent( config )
 *
 * @param  handlerIn   function OR configuration object
 * @param  handlerOut  function OR selector for delegation OR undefined
 * @param  selector    selector OR undefined
 * @author Brian Cherne <brian(at)cherne(dot)net>
 **/
(function($) {
    $.fn.hoverIntent = function(handlerIn,handlerOut,selector) {

        // default configuration values
        var cfg = {
            interval: 100,
            sensitivity: 7,
            timeout: 0
        };

        if ( typeof handlerIn === "object" ) {
            cfg = $.extend(cfg, handlerIn );
        } else if ($.isFunction(handlerOut)) {
            cfg = $.extend(cfg, { over: handlerIn, out: handlerOut, selector: selector } );
        } else {
            cfg = $.extend(cfg, { over: handlerIn, out: handlerIn, selector: handlerOut } );
        }

        // instantiate variables
        // cX, cY = current X and Y position of mouse, updated by mousemove event
        // pX, pY = previous X and Y position of mouse, set by mouseover and polling interval
        var cX, cY, pX, pY;

        // A private function for getting mouse position
        var track = function(ev) {
            cX = ev.pageX;
            cY = ev.pageY;
        };

        // A private function for comparing current and previous mouse position
        var compare = function(ev,ob) {
            ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
            // compare mouse positions to see if they've crossed the threshold
            if ( ( Math.abs(pX-cX) + Math.abs(pY-cY) ) < cfg.sensitivity ) {
                $(ob).off("mousemove.hoverIntent",track);
                // set hoverIntent state to true (so mouseOut can be called)
                ob.hoverIntent_s = 1;
                return cfg.over.apply(ob,[ev]);
            } else {
                // set previous coordinates for next time
                pX = cX; pY = cY;
                // use self-calling timeout, guarantees intervals are spaced out properly (avoids JavaScript timer bugs)
                ob.hoverIntent_t = setTimeout( function(){compare(ev, ob);} , cfg.interval );
            }
        };

        // A private function for delaying the mouseOut function
        var delay = function(ev,ob) {
            ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
            ob.hoverIntent_s = 0;
            return cfg.out.apply(ob,[ev]);
        };

        // A private function for handling mouse 'hovering'
        var handleHover = function(e) {
            // copy objects to be passed into t (required for event object to be passed in IE)
            var ev = jQuery.extend({},e);
            var ob = this;

            // cancel hoverIntent timer if it exists
            if (ob.hoverIntent_t) { ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t); }

            // if e.type == "mouseenter"
            if (e.type == "mouseenter") {
                // set "previous" X and Y position based on initial entry point
                pX = ev.pageX; pY = ev.pageY;
                // update "current" X and Y position based on mousemove
                $(ob).on("mousemove.hoverIntent",track);
                // start polling interval (self-calling timeout) to compare mouse coordinates over time
                if (ob.hoverIntent_s != 1) { ob.hoverIntent_t = setTimeout( function(){compare(ev,ob);} , cfg.interval );}

                // else e.type == "mouseleave"
            } else {
                // unbind expensive mousemove event
                $(ob).off("mousemove.hoverIntent",track);
                // if hoverIntent state is true, then call the mouseOut function after the specified delay
                if (ob.hoverIntent_s == 1) { ob.hoverIntent_t = setTimeout( function(){delay(ev,ob);} , cfg.timeout );}
            }
        };

        // listen for mouseenter and mouseleave
        return this.on({'mouseenter.hoverIntent':handleHover,'mouseleave.hoverIntent':handleHover}, cfg.selector);
    };
})(jQuery);




/**
 *
 * Twitter Feed Fetcher
 *
 */


function sm_format_twitter(twitters) {
  var statusHTML = [];
  for (var i=0; i<twitters.length; i++){
    var username = twitters[i].user.screen_name;
    var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
      return '<a href="'+url+'" target="_blank">'+url+'</a>';
    }).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
      return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'" target="_blank">'+reply.substring(1)+'</a>';
    });
    statusHTML.push('<li><span>'+status+'</span><small><a href="http://twitter.com/'+username+'/statuses/'+twitters[i].id_str+'" target="_blank">'+relative_time(twitters[i].created_at)+'</a></small></li>');
  }
  return statusHTML.join('');
}

function sm_format_twitter2(twitters) {
  var statusHTML = [];
  for (var i=0; i<twitters.length; i++){
    var username = twitters[i].user.screen_name;
    var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
      return '<a href="'+url+'" target="_blank">'+url+'</a>';
    }).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
      return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'" target="_blank">'+reply.substring(1)+'</a>';
    });
    statusHTML.push('<div class="slide"><span>'+status+'</span><small><a href="http://twitter.com/'+username+'/statuses/'+twitters[i].id_str+'" target="_blank">'+relative_time(twitters[i].created_at)+'</a></small></div>');
  }
  return statusHTML.join('');
}

function relative_time(time_value) {
  var values = time_value.split(" ");
  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
  var parsed_date = Date.parse(time_value);
  var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
  delta = delta + (relative_to.getTimezoneOffset() * 60);

  if (delta < 60) {
    return 'less than a minute ago';
  } else if(delta < 120) {
    return 'about a minute ago';
  } else if(delta < (60*60)) {
    return (parseInt(delta / 60)).toString() + ' minutes ago';
  } else if(delta < (120*60)) {
    return 'about an hour ago';
  } else if(delta < (24*60*60)) {
    return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
  } else if(delta < (48*60*60)) {
    return '1 day ago';
  } else {
    return (parseInt(delta / 86400)).toString() + ' days ago';
  }
}




/*
* Copyright (C) 2009 Joel Sutherland
* Licenced under the MIT license
* http://www.newmediacampaigns.com/page/jquery-flickr-plugin
*
* Available tags for templates:
* title, link, date_taken, description, published, author, author_id, tags, image*
*/
(function($){$.fn.jflickrfeed=function(settings,callback){settings=$.extend(true,{flickrbase:'http://api.flickr.com/services/feeds/',feedapi:'photos_public.gne',limit:20,qstrings:{lang:'en-us',format:'json',jsoncallback:'?'},cleanDescription:true,useTemplate:true,itemTemplate:'',itemCallback:function(){}},settings);var url=settings.flickrbase+settings.feedapi+'?';var first=true;for(var key in settings.qstrings){if(!first)
url+='&';url+=key+'='+settings.qstrings[key];first=false;}
return $(this).each(function(){var $container=$(this);var container=this;$.getJSON(url,function(data){$.each(data.items,function(i,item){if(i<settings.limit){if(settings.cleanDescription){var regex=/<p>(.*?)<\/p>/g;var input=item.description;if(regex.test(input)){item.description=input.match(regex)[2]
if(item.description!=undefined)
item.description=item.description.replace('<p>','').replace('</p>','');}}
item['image_s']=item.media.m.replace('_m','_s');item['image_t']=item.media.m.replace('_m','_t');item['image_m']=item.media.m.replace('_m','_m');item['image']=item.media.m.replace('_m','');item['image_b']=item.media.m.replace('_m','_b');delete item.media;if(settings.useTemplate){var template=settings.itemTemplate;for(var key in item){var rgx=new RegExp('{{'+key+'}}','g');template=template.replace(rgx,item[key]);}
$container.append(template)}
settings.itemCallback.call(container,item);}});if($.isFunction(callback)){callback.call(container,data);}});});}})(jQuery);




/*!
 * jQuery - Spectragram by Adrian Quevedo
 * http://adrianquevedo.com/  - http://lab.adrianquevedo.com/ - http://elnucleo.com.co/
 *
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * You are free to use this plugin in commercial projects as long as the copyright header is left intact.
 *
 * This plugin uses the Instagram(tm) API and is not endorsed or certified by Instagram or Burbn, inc. 
 * All Instagram(tm) logos and trademarks displayed on this plugin are property of Burbn, Inc.
 *
 * Date: Thu Jul 15 14:05:02 2012 -0500
 */
 
if(typeof Object.create!=='function'){Object.create=function(obj){function F(){};F.prototype=obj;return new F()}}(function($,window,document,undefined){var Instagram={init:function(options,elem){var self=this;self.elem=elem;self.$elem=$(elem);self.api='https://api.instagram.com/v1',self.accessData=$.fn.spectragram.accessData,self.options=$.extend({},$.fn.spectragram.options,options)},getRecentMedia:function(userID){var self=this,getData='/users/'+userID+'/media/recent/?'+self.accessData.clientID+'&access_token='+self.accessData.accessToken+'';self.fetch(getData).done(function(results){self.display(results)})},getUserFeed:function(){var self=this,getData='/users/search?q='+self.options.query+'&count='+self.options.max+'&client_id='+self.accessData.clientID+'';self.fetch(getData).done(function(results){if(results.data.length){self.getRecentMedia(results.data[0].id)}else{$.error('Spectagram.js - Error: the username '+self.options.query+' does not exist.')}})},getPopular:function(){var self=this,getData='/media/popular?client_id='+self.accessData.clientID+'&access_token='+self.accessData.accessToken+'';self.fetch(getData).done(function(results){self.display(results)})},getRecentTagged:function(){var self=this,getData='/tags/'+self.options.query+'/media/recent?client_id='+self.accessData.clientID+'&access_token='+self.accessData.accessToken+'';self.fetch(getData).done(function(results){if(results.data.length){self.display(results)}else{$.error('Spectagram.js - Error: the tag '+self.options.query+' does not have results.')}})},fetch:function(getData){var self=this,getUrl=self.api+getData;return $.ajax({type:"GET",dataType:"jsonp",cache:false,url:getUrl})},display:function(results){var self=this,setSize=self.options.size,size,max=(self.options.max>=results.data.length)?results.data.length:self.options.max;if(results.data.length===0){self.$elem.append($(self.options.wrapEachWith).append(self.options.notFoundMsg))}else{for(var i=0;i<max;i++){if(setSize=="small"){size=results.data[i].images.thumbnail.url}else if(setSize=="medium"){size=results.data[i].images.low_resolution.url}else{size=results.data[i].images.standard_resolution.url}self.$elem.append($(self.options.wrapEachWith).append("<a target='_blank' href='"+results.data[i].link+"'><img src='"+size+"'></img></a>"))}}}};jQuery.fn.spectragram=function(method,options){if(jQuery.fn.spectragram.accessData.clientID){this.each(function(){var instagram=Object.create(Instagram);instagram.init(options,this);if(instagram[method]){return instagram[method](this)}else{$.error('Method '+method+' does not exist on jQuery.spectragram')}})}else{$.error('You must define an accessToken and a clientID on jQuery.spectragram')}};jQuery.fn.spectragram.options={max:10,query:'coffee',size:'medium',wrapEachWith:'<li></li>'};jQuery.fn.spectragram.accessData={accessToken:null,clientID:null}})(jQuery,window,document);




/**
 * jQuery Plugin - Jribbble v@VERSION
 * A jQuery plugin to fetch shot and player data from the Dribbble API, 
 * http://dribbble.com/api
 * 
 * Copyright (c) 2011 Tyler Gaw
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 *
 * Date: @DATE
 *
 */

(function ($) {
	'use strict';

	// @return OBJ
	$.fn.jribbble = function () {

		// Make a request to the API
		// @param STRING url
		// @param FUNCTION callback
		// @param OBJ OPTIONAL options
		this.makeRequest = function (url, callback, options) {
			var successHandler = function (data) {
				if ($.isFunction(callback)) {
					callback(data);
				}
			},

				// Catch any double '/' that may be caused by our pathname storage
				cleanURL = url.replace('//', '/');

			$.ajax({
				data: options,
				dataType: 'jsonp',
				success: successHandler,
				type: 'GET',
				url: $.jribbble.baseUrl + cleanURL
			});
		};

        // Returning "this", allows this plugin to be chainable
		return this;
    };

	// Create a public object for jribbble
	$.jribbble = {};

	// Public Static Members
	// Can be set globally for all jquery.jribbble requests

	// @member STRING baseUrl - Will be prepended to all API requests
	$.jribbble.baseUrl = 'http://api.dribbble.com';

	// @member OBJ paths - Pathnames to resourses
	$.jribbble.paths = {
		shots:     '/shots/',
		rebounds:  '/rebounds/',
		following: '/following/',
		players:   '/players/',
		followers: '/followers/',
		draftees:  '/draftees/',
		comments:  '/comments/',
		likes:     '/likes/'
	};

	// Public Static Methods
	// These are available at any time, you do not have to
	// instantiate the jribbble plugin on an element to use

	// Retrieve the shot specified by shotId
	// @param INT shotId - The id of the shot you want.
	// @param FUNCTION callback - Function that will be called once the
	//                            request has successfully completed. The data
	//                            from the request will be passed to the callback
	$.jribbble.getShotById = function (shotId, callback) {
		var resource = $.jribbble.paths.shots + shotId;
		$.fn.jribbble().makeRequest(resource, callback);
	};

	// Retrieve the rebounds of a shot specified by shotId
	// @param INT shotId - The id of the shot you want rebounds for.
	// @param FUNCTION callback - Function that will be called once the
	//                            request has successfully completed. The data
	//                            from the request will be passed to the callback
	// @param OBJ OPTIONAL pagingOpts - { page: 1, per_page: 15 } 
	//                                  @see http://dribbble.com/api#pagination
	$.jribbble.getReboundsOfShot = function (shotId, callback, pagingOpts) {
		var resource = $.jribbble.paths.shots + shotId + $.jribbble.paths.rebounds;
		$.fn.jribbble().makeRequest(resource, callback, pagingOpts);
	};

	// @param STRING listName - One of the following: 'debuts', 'everyone', 'popular'
	// @param FUNCTION callback  - Function that will be called once the
	//                             request has successfully completed. The data
	//                             from the request will be passed to the callback
	// @param OBJ OPTIONAL pagingOpts - { page: 1, per_page: 15 } 
	//                                  @see http://dribbble.com/api#pagination
	$.jribbble.getShotsByList = function (listName, callback, pagingOpts) {
		var resource = $.jribbble.paths.shots + listName;
		$.fn.jribbble().makeRequest(resource, callback, pagingOpts);
	};

	// Retrieve the most recent shots for the player specified by playerId
	// @param STRING | INT playerId - Can be 'username' or 2318
	// @param FUNCTION callback - Function that will be called once the
	//                            request has successfully completed. The data
	//                            from the request will be passed to the callback.
	// @param OBJ OPTIONAL pagingOpts - { page: 1, per_page: 15 } 
	//                                  @see http://dribbble.com/api#pagination
	$.jribbble.getShotsByPlayerId = function (playerId, callback, pagingOpts) {	
		var resource = $.jribbble.paths.players + playerId + $.jribbble.paths.shots;
		$.fn.jribbble().makeRequest(resource, callback, pagingOpts);
	};

	// Retrieve the most recent shots published by those the player, specified by playerId, follows
	// @param STRING | INT playerId - Can be 'username' or 2318
	// @param FUNCTION callback - Function that will be called once the
	//                            request has successfully completed. The data
	//                            from the request will be passed to the callback.
	// @param OBJ OPTIONAL pagingOpts - { page: 1, per_page: 15 } 
	//                                  @see http://dribbble.com/api#pagination
	$.jribbble.getShotsThatPlayerFollows = function (playerId, callback, pagingOpts) {		
		var resource = $.jribbble.paths.players + playerId + $.jribbble.paths.shots + $.jribbble.paths.following;
		$.fn.jribbble().makeRequest(resource, callback, pagingOpts);
	};

	// Retrieve profile details for the player specified by playerId
	// @param STRING | INT playerId - Can be 'username' or 2318
	// @param FUNCTION callback - Function that will be called once the
	//                            request has successfully completed. The data
	//                            from the request will be passed to the callback.
	$.jribbble.getPlayerById = function (playerId, callback) {
		var resource = $.jribbble.paths.players + playerId;
		$.fn.jribbble().makeRequest(resource, callback);
	};

	// Retrieve followers of a player by the playerId
	// @param STRING | INT playerId - Can be 'username' or 2318
	// @param FUNCTION callback - Function that will be called once the
	//                            request has successfully completed. The data
	//                            from the request will be passed to the callback.
	$.jribbble.getPlayerFollowers = function (playerId, callback, pagingOpts) {
		var resource = $.jribbble.paths.players + playerId + $.jribbble.paths.followers;
		$.fn.jribbble().makeRequest(resource, callback, pagingOpts);
	};

	// Retrieve the list of players that are following playerId
	// @param STRING | INT playerId - Can be 'username' or 2318
	// @param FUNCTION callback - Function that will be called once the
	//                            request has successfully completed. The data
	//                            from the request will be passed to the callback.
	$.jribbble.getPlayerFollowing = function (playerId, callback, pagingOpts) {
		var resource = $.jribbble.paths.players + playerId + $.jribbble.paths.following;
		$.fn.jribbble().makeRequest(resource, callback, pagingOpts);
	};

	// Retrieve the list of players drafted by the playerId
	// @param STRING | INT playerId - Can be 'username' or 2318
	// @param FUNCTION callback - Function that will be called once the
	//                            request has successfully completed. The data
	//                            from the request will be passed to the callback.
	$.jribbble.getPlayerDraftees = function (playerId, callback, pagingOpts) {
		var resource = $.jribbble.paths.players + playerId + $.jribbble.paths.draftees;
		$.fn.jribbble().makeRequest(resource, callback, pagingOpts);
	};

	// Retrieve the set of comments for the shot specified by shotId
	// @param INT shotId - The id of the shot you want comments for.
	// @param FUNCTION callback - Function that will be called once the
	//                            request has successfully completed. The data
	//                            from the request will be passed to the callback.
	// @param OBJ OPTIONAL pagingOpts - { page: 1, per_page: 15 } 
	//                                  @see http://dribbble.com/api#pagination
	$.jribbble.getCommentsOfShot = function (shotId, callback, pagingOpts) {
		var resource = $.jribbble.paths.shots + shotId + $.jribbble.paths.comments;
		$.fn.jribbble().makeRequest(resource, callback, pagingOpts);
	};

	// Retrieve the set of likes of the player specified by playerID
	// @param INT playerId - The id of the player whose likes you want
	// @param FUNCTION callback - Function that will be called once the
	//                            request has successfully completed. The data
	//                            from the request will be passed to the callback.
	// @param OBJ OPTIONAL pagingOpts - { page: 1, per_page: 15 } 
	//                                  @see http://dribbble.com/api#pagination
	$.jribbble.getShotsThatPlayerLikes = function (playerId, callback, pagingOpts) {
		var resource = $.jribbble.paths.players + playerId + $.jribbble.paths.shots + $.jribbble.paths.likes;
		$.fn.jribbble().makeRequest(resource, callback, pagingOpts);
	};

}(jQuery));




/*
 * jQuery.appear
 * https://github.com/bas2k/jquery.appear/
 * http://code.google.com/p/jquery-appear/
 *
 * Copyright (c) 2009 Michael Hixson
 * Copyright (c) 2012 Alexander Brovikov
 * Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
 */
(function(e){e.fn.appear=function(t,n){var r=e.extend({data:undefined,one:true,accX:0,accY:0},n);return this.each(function(){var n=e(this);n.appeared=false;if(!t){n.trigger("appear",r.data);return}var i=e(window);var s=function(){if(!n.is(":visible")){n.appeared=false;return}var e=i.scrollLeft();var t=i.scrollTop();var s=n.offset();var o=s.left;var u=s.top;var a=r.accX;var f=r.accY;var l=n.height();var c=i.height();var h=n.width();var p=i.width();if(u+l+f>=t&&u<=t+c+f&&o+h+a>=e&&o<=e+p+a){if(!n.appeared)n.trigger("appear",r.data)}else{n.appeared=false}};var o=function(){n.appeared=true;if(r.one){i.unbind("scroll",s);var o=e.inArray(s,e.fn.appear.checks);if(o>=0)e.fn.appear.checks.splice(o,1)}t.apply(this,arguments)};if(r.one)n.one("appear",r.data,o);else n.bind("appear",r.data,o);i.scroll(s);e.fn.appear.checks.push(s);s()})};e.extend(e.fn.appear,{checks:[],timeout:null,checkAll:function(){var t=e.fn.appear.checks.length;if(t>0)while(t--)e.fn.appear.checks[t]()},run:function(){if(e.fn.appear.timeout)clearTimeout(e.fn.appear.timeout);e.fn.appear.timeout=setTimeout(e.fn.appear.checkAll,20)}});e.each(["append","prepend","after","before","attr","removeAttr","addClass","removeClass","toggleClass","remove","css","show","hide"],function(t,n){var r=e.fn[n];if(r){e.fn[n]=function(){var t=r.apply(this,arguments);e.fn.appear.run();return t}}})})(jQuery);




/**
 * Copyright (c) 2007-2012 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * @author Ariel Flesler
 * @version 1.4.5 BETA
 */
;(function($){var h=$.scrollTo=function(a,b,c){$(window).scrollTo(a,b,c)};h.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:true};h.window=function(a){return $(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(e,f,g){if(typeof f=='object'){g=f;f=0}if(typeof g=='function')g={onAfter:g};if(e=='max')e=9e9;g=$.extend({},h.defaults,g);f=f||g.duration;g.queue=g.queue&&g.axis.length>1;if(g.queue)f/=2;g.offset=both(g.offset);g.over=both(g.over);return this._scrollable().each(function(){if(e==null)return;var d=this,$elem=$(d),targ=e,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=?)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=$(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=$(targ)).offset()}$.each(g.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=h.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(g.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=g.offset[pos]||0;if(g.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*g.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(g.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&g.queue){if(old!=attr[key])animate(g.onAfterFirst);delete attr[key]}});animate(g.onAfter);function animate(a){$elem.animate(attr,f,g.easing,a&&function(){a.call(this,e,g)})}}).end()};h.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!$(a).is('html,body'))return a[scroll]-$(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);




/**
 * Isotope v1.5.25
 * An exquisite jQuery plugin for magical layouts
 * http://isotope.metafizzy.co
 *
 * Commercial use requires one-time purchase of a commercial license
 * http://isotope.metafizzy.co/docs/license.html
 *
 * Non-commercial use is licensed under the MIT License
 *
 * Copyright 2013 Metafizzy
 */
(function(a,b,c){"use strict";var d=a.document,e=a.Modernizr,f=function(a){return a.charAt(0).toUpperCase()+a.slice(1)},g="Moz Webkit O Ms".split(" "),h=function(a){var b=d.documentElement.style,c;if(typeof b[a]=="string")return a;a=f(a);for(var e=0,h=g.length;e<h;e++){c=g[e]+a;if(typeof b[c]=="string")return c}},i=h("transform"),j=h("transitionProperty"),k={csstransforms:function(){return!!i},csstransforms3d:function(){var a=!!h("perspective");if(a){var c=" -o- -moz- -ms- -webkit- -khtml- ".split(" "),d="@media ("+c.join("transform-3d),(")+"modernizr)",e=b("<style>"+d+"{#modernizr{height:3px}}"+"</style>").appendTo("head"),f=b('<div id="modernizr" />').appendTo("html");a=f.height()===3,f.remove(),e.remove()}return a},csstransitions:function(){return!!j}},l;if(e)for(l in k)e.hasOwnProperty(l)||e.addTest(l,k[l]);else{e=a.Modernizr={_version:"1.6ish: miniModernizr for Isotope"};var m=" ",n;for(l in k)n=k[l](),e[l]=n,m+=" "+(n?"":"no-")+l;b("html").addClass(m)}if(e.csstransforms){var o=e.csstransforms3d?{translate:function(a){return"translate3d("+a[0]+"px, "+a[1]+"px, 0) "},scale:function(a){return"scale3d("+a+", "+a+", 1) "}}:{translate:function(a){return"translate("+a[0]+"px, "+a[1]+"px) "},scale:function(a){return"scale("+a+") "}},p=function(a,c,d){var e=b.data(a,"isoTransform")||{},f={},g,h={},j;f[c]=d,b.extend(e,f);for(g in e)j=e[g],h[g]=o[g](j);var k=h.translate||"",l=h.scale||"",m=k+l;b.data(a,"isoTransform",e),a.style[i]=m};b.cssNumber.scale=!0,b.cssHooks.scale={set:function(a,b){p(a,"scale",b)},get:function(a,c){var d=b.data(a,"isoTransform");return d&&d.scale?d.scale:1}},b.fx.step.scale=function(a){b.cssHooks.scale.set(a.elem,a.now+a.unit)},b.cssNumber.translate=!0,b.cssHooks.translate={set:function(a,b){p(a,"translate",b)},get:function(a,c){var d=b.data(a,"isoTransform");return d&&d.translate?d.translate:[0,0]}}}var q,r;e.csstransitions&&(q={WebkitTransitionProperty:"webkitTransitionEnd",MozTransitionProperty:"transitionend",OTransitionProperty:"oTransitionEnd otransitionend",transitionProperty:"transitionend"}[j],r=h("transitionDuration"));var s=b.event,t=b.event.handle?"handle":"dispatch",u;s.special.smartresize={setup:function(){b(this).bind("resize",s.special.smartresize.handler)},teardown:function(){b(this).unbind("resize",s.special.smartresize.handler)},handler:function(a,b){var c=this,d=arguments;a.type="smartresize",u&&clearTimeout(u),u=setTimeout(function(){s[t].apply(c,d)},b==="execAsap"?0:100)}},b.fn.smartresize=function(a){return a?this.bind("smartresize",a):this.trigger("smartresize",["execAsap"])},b.Isotope=function(a,c,d){this.element=b(c),this._create(a),this._init(d)};var v=["width","height"],w=b(a);b.Isotope.settings={resizable:!0,layoutMode:"masonry",containerClass:"isotope",itemClass:"isotope-item",hiddenClass:"isotope-hidden",hiddenStyle:{opacity:0,scale:.001},visibleStyle:{opacity:1,scale:1},containerStyle:{position:"relative",overflow:"hidden"},animationEngine:"best-available",animationOptions:{queue:!1,duration:800},sortBy:"original-order",sortAscending:!0,resizesContainer:!0,transformsEnabled:!0,itemPositionDataEnabled:!1},b.Isotope.prototype={_create:function(a){this.options=b.extend({},b.Isotope.settings,a),this.styleQueue=[],this.elemCount=0;var c=this.element[0].style;this.originalStyle={};var d=v.slice(0);for(var e in this.options.containerStyle)d.push(e);for(var f=0,g=d.length;f<g;f++)e=d[f],this.originalStyle[e]=c[e]||"";this.element.css(this.options.containerStyle),this._updateAnimationEngine(),this._updateUsingTransforms();var h={"original-order":function(a,b){return b.elemCount++,b.elemCount},random:function(){return Math.random()}};this.options.getSortData=b.extend(this.options.getSortData,h),this.reloadItems(),this.offset={left:parseInt(this.element.css("padding-left")||0,10),top:parseInt(this.element.css("padding-top")||0,10)};var i=this;setTimeout(function(){i.element.addClass(i.options.containerClass)},0),this.options.resizable&&w.bind("smartresize.isotope",function(){i.resize()}),this.element.delegate("."+this.options.hiddenClass,"click",function(){return!1})},_getAtoms:function(a){var b=this.options.itemSelector,c=b?a.filter(b).add(a.find(b)):a,d={position:"absolute"};return c=c.filter(function(a,b){return b.nodeType===1}),this.usingTransforms&&(d.left=0,d.top=0),c.css(d).addClass(this.options.itemClass),this.updateSortData(c,!0),c},_init:function(a){this.$filteredAtoms=this._filter(this.$allAtoms),this._sort(),this.reLayout(a)},option:function(a){if(b.isPlainObject(a)){this.options=b.extend(!0,this.options,a);var c;for(var d in a)c="_update"+f(d),this[c]&&this[c]()}},_updateAnimationEngine:function(){var a=this.options.animationEngine.toLowerCase().replace(/[ _\-]/g,""),b;switch(a){case"css":case"none":b=!1;break;case"jquery":b=!0;break;default:b=!e.csstransitions}this.isUsingJQueryAnimation=b,this._updateUsingTransforms()},_updateTransformsEnabled:function(){this._updateUsingTransforms()},_updateUsingTransforms:function(){var a=this.usingTransforms=this.options.transformsEnabled&&e.csstransforms&&e.csstransitions&&!this.isUsingJQueryAnimation;a||(delete this.options.hiddenStyle.scale,delete this.options.visibleStyle.scale),this.getPositionStyles=a?this._translate:this._positionAbs},_filter:function(a){var b=this.options.filter===""?"*":this.options.filter;if(!b)return a;var c=this.options.hiddenClass,d="."+c,e=a.filter(d),f=e;if(b!=="*"){f=e.filter(b);var g=a.not(d).not(b).addClass(c);this.styleQueue.push({$el:g,style:this.options.hiddenStyle})}return this.styleQueue.push({$el:f,style:this.options.visibleStyle}),f.removeClass(c),a.filter(b)},updateSortData:function(a,c){var d=this,e=this.options.getSortData,f,g;a.each(function(){f=b(this),g={};for(var a in e)!c&&a==="original-order"?g[a]=b.data(this,"isotope-sort-data")[a]:g[a]=e[a](f,d);b.data(this,"isotope-sort-data",g)})},_sort:function(){var a=this.options.sortBy,b=this._getSorter,c=this.options.sortAscending?1:-1,d=function(d,e){var f=b(d,a),g=b(e,a);return f===g&&a!=="original-order"&&(f=b(d,"original-order"),g=b(e,"original-order")),(f>g?1:f<g?-1:0)*c};this.$filteredAtoms.sort(d)},_getSorter:function(a,c){return b.data(a,"isotope-sort-data")[c]},_translate:function(a,b){return{translate:[a,b]}},_positionAbs:function(a,b){return{left:a,top:b}},_pushPosition:function(a,b,c){b=Math.round(b+this.offset.left),c=Math.round(c+this.offset.top);var d=this.getPositionStyles(b,c);this.styleQueue.push({$el:a,style:d}),this.options.itemPositionDataEnabled&&a.data("isotope-item-position",{x:b,y:c})},layout:function(a,b){var c=this.options.layoutMode;this["_"+c+"Layout"](a);if(this.options.resizesContainer){var d=this["_"+c+"GetContainerSize"]();this.styleQueue.push({$el:this.element,style:d})}this._processStyleQueue(a,b),this.isLaidOut=!0},_processStyleQueue:function(a,c){var d=this.isLaidOut?this.isUsingJQueryAnimation?"animate":"css":"css",f=this.options.animationOptions,g=this.options.onLayout,h,i,j,k;i=function(a,b){b.$el[d](b.style,f)};if(this._isInserting&&this.isUsingJQueryAnimation)i=function(a,b){h=b.$el.hasClass("no-transition")?"css":d,b.$el[h](b.style,f)};else if(c||g||f.complete){var l=!1,m=[c,g,f.complete],n=this;j=!0,k=function(){if(l)return;var b;for(var c=0,d=m.length;c<d;c++)b=m[c],typeof b=="function"&&b.call(n.element,a,n);l=!0};if(this.isUsingJQueryAnimation&&d==="animate")f.complete=k,j=!1;else if(e.csstransitions){var o=0,p=this.styleQueue[0],s=p&&p.$el,t;while(!s||!s.length){t=this.styleQueue[o++];if(!t)return;s=t.$el}var u=parseFloat(getComputedStyle(s[0])[r]);u>0&&(i=function(a,b){b.$el[d](b.style,f).one(q,k)},j=!1)}}b.each(this.styleQueue,i),j&&k(),this.styleQueue=[]},resize:function(){this["_"+this.options.layoutMode+"ResizeChanged"]()&&this.reLayout()},reLayout:function(a){this["_"+this.options.layoutMode+"Reset"](),this.layout(this.$filteredAtoms,a)},addItems:function(a,b){var c=this._getAtoms(a);this.$allAtoms=this.$allAtoms.add(c),b&&b(c)},insert:function(a,b){this.element.append(a);var c=this;this.addItems(a,function(a){var d=c._filter(a);c._addHideAppended(d),c._sort(),c.reLayout(),c._revealAppended(d,b)})},appended:function(a,b){var c=this;this.addItems(a,function(a){c._addHideAppended(a),c.layout(a),c._revealAppended(a,b)})},_addHideAppended:function(a){this.$filteredAtoms=this.$filteredAtoms.add(a),a.addClass("no-transition"),this._isInserting=!0,this.styleQueue.push({$el:a,style:this.options.hiddenStyle})},_revealAppended:function(a,b){var c=this;setTimeout(function(){a.removeClass("no-transition"),c.styleQueue.push({$el:a,style:c.options.visibleStyle}),c._isInserting=!1,c._processStyleQueue(a,b)},10)},reloadItems:function(){this.$allAtoms=this._getAtoms(this.element.children())},remove:function(a,b){this.$allAtoms=this.$allAtoms.not(a),this.$filteredAtoms=this.$filteredAtoms.not(a);var c=this,d=function(){a.remove(),b&&b.call(c.element)};a.filter(":not(."+this.options.hiddenClass+")").length?(this.styleQueue.push({$el:a,style:this.options.hiddenStyle}),this._sort(),this.reLayout(d)):d()},shuffle:function(a){this.updateSortData(this.$allAtoms),this.options.sortBy="random",this._sort(),this.reLayout(a)},destroy:function(){var a=this.usingTransforms,b=this.options;this.$allAtoms.removeClass(b.hiddenClass+" "+b.itemClass).each(function(){var b=this.style;b.position="",b.top="",b.left="",b.opacity="",a&&(b[i]="")});var c=this.element[0].style;for(var d in this.originalStyle)c[d]=this.originalStyle[d];this.element.unbind(".isotope").undelegate("."+b.hiddenClass,"click").removeClass(b.containerClass).removeData("isotope"),w.unbind(".isotope")},_getSegments:function(a){var b=this.options.layoutMode,c=a?"rowHeight":"columnWidth",d=a?"height":"width",e=a?"rows":"cols",g=this.element[d](),h,i=this.options[b]&&this.options[b][c]||this.$filteredAtoms["outer"+f(d)](!0)||g;h=Math.floor(g/i),h=Math.max(h,1),this[b][e]=h,this[b][c]=i},_checkIfSegmentsChanged:function(a){var b=this.options.layoutMode,c=a?"rows":"cols",d=this[b][c];return this._getSegments(a),this[b][c]!==d},_masonryReset:function(){this.masonry={},this._getSegments();var a=this.masonry.cols;this.masonry.colYs=[];while(a--)this.masonry.colYs.push(0)},_masonryLayout:function(a){var c=this,d=c.masonry;a.each(function(){var a=b(this),e=Math.ceil(a.outerWidth(!0)/d.columnWidth);e=Math.min(e,d.cols);if(e===1)c._masonryPlaceBrick(a,d.colYs);else{var f=d.cols+1-e,g=[],h,i;for(i=0;i<f;i++)h=d.colYs.slice(i,i+e),g[i]=Math.max.apply(Math,h);c._masonryPlaceBrick(a,g)}})},_masonryPlaceBrick:function(a,b){var c=Math.min.apply(Math,b),d=0;for(var e=0,f=b.length;e<f;e++)if(b[e]===c){d=e;break}var g=this.masonry.columnWidth*d,h=c;this._pushPosition(a,g,h);var i=c+a.outerHeight(!0),j=this.masonry.cols+1-f;for(e=0;e<j;e++)this.masonry.colYs[d+e]=i},_masonryGetContainerSize:function(){var a=Math.max.apply(Math,this.masonry.colYs);return{height:a}},_masonryResizeChanged:function(){return this._checkIfSegmentsChanged()},_fitRowsReset:function(){this.fitRows={x:0,y:0,height:0}},_fitRowsLayout:function(a){var c=this,d=this.element.width(),e=this.fitRows;a.each(function(){var a=b(this),f=a.outerWidth(!0),g=a.outerHeight(!0);e.x!==0&&f+e.x>d&&(e.x=0,e.y=e.height),c._pushPosition(a,e.x,e.y),e.height=Math.max(e.y+g,e.height),e.x+=f})},_fitRowsGetContainerSize:function(){return{height:this.fitRows.height}},_fitRowsResizeChanged:function(){return!0},_cellsByRowReset:function(){this.cellsByRow={index:0},this._getSegments(),this._getSegments(!0)},_cellsByRowLayout:function(a){var c=this,d=this.cellsByRow;a.each(function(){var a=b(this),e=d.index%d.cols,f=Math.floor(d.index/d.cols),g=(e+.5)*d.columnWidth-a.outerWidth(!0)/2,h=(f+.5)*d.rowHeight-a.outerHeight(!0)/2;c._pushPosition(a,g,h),d.index++})},_cellsByRowGetContainerSize:function(){return{height:Math.ceil(this.$filteredAtoms.length/this.cellsByRow.cols)*this.cellsByRow.rowHeight+this.offset.top}},_cellsByRowResizeChanged:function(){return this._checkIfSegmentsChanged()},_straightDownReset:function(){this.straightDown={y:0}},_straightDownLayout:function(a){var c=this;a.each(function(a){var d=b(this);c._pushPosition(d,0,c.straightDown.y),c.straightDown.y+=d.outerHeight(!0)})},_straightDownGetContainerSize:function(){return{height:this.straightDown.y}},_straightDownResizeChanged:function(){return!0},_masonryHorizontalReset:function(){this.masonryHorizontal={},this._getSegments(!0);var a=this.masonryHorizontal.rows;this.masonryHorizontal.rowXs=[];while(a--)this.masonryHorizontal.rowXs.push(0)},_masonryHorizontalLayout:function(a){var c=this,d=c.masonryHorizontal;a.each(function(){var a=b(this),e=Math.ceil(a.outerHeight(!0)/d.rowHeight);e=Math.min(e,d.rows);if(e===1)c._masonryHorizontalPlaceBrick(a,d.rowXs);else{var f=d.rows+1-e,g=[],h,i;for(i=0;i<f;i++)h=d.rowXs.slice(i,i+e),g[i]=Math.max.apply(Math,h);c._masonryHorizontalPlaceBrick(a,g)}})},_masonryHorizontalPlaceBrick:function(a,b){var c=Math.min.apply(Math,b),d=0;for(var e=0,f=b.length;e<f;e++)if(b[e]===c){d=e;break}var g=c,h=this.masonryHorizontal.rowHeight*d;this._pushPosition(a,g,h);var i=c+a.outerWidth(!0),j=this.masonryHorizontal.rows+1-f;for(e=0;e<j;e++)this.masonryHorizontal.rowXs[d+e]=i},_masonryHorizontalGetContainerSize:function(){var a=Math.max.apply(Math,this.masonryHorizontal.rowXs);return{width:a}},_masonryHorizontalResizeChanged:function(){return this._checkIfSegmentsChanged(!0)},_fitColumnsReset:function(){this.fitColumns={x:0,y:0,width:0}},_fitColumnsLayout:function(a){var c=this,d=this.element.height(),e=this.fitColumns;a.each(function(){var a=b(this),f=a.outerWidth(!0),g=a.outerHeight(!0);e.y!==0&&g+e.y>d&&(e.x=e.width,e.y=0),c._pushPosition(a,e.x,e.y),e.width=Math.max(e.x+f,e.width),e.y+=g})},_fitColumnsGetContainerSize:function(){return{width:this.fitColumns.width}},_fitColumnsResizeChanged:function(){return!0},_cellsByColumnReset:function(){this.cellsByColumn={index:0},this._getSegments(),this._getSegments(!0)},_cellsByColumnLayout:function(a){var c=this,d=this.cellsByColumn;a.each(function(){var a=b(this),e=Math.floor(d.index/d.rows),f=d.index%d.rows,g=(e+.5)*d.columnWidth-a.outerWidth(!0)/2,h=(f+.5)*d.rowHeight-a.outerHeight(!0)/2;c._pushPosition(a,g,h),d.index++})},_cellsByColumnGetContainerSize:function(){return{width:Math.ceil(this.$filteredAtoms.length/this.cellsByColumn.rows)*this.cellsByColumn.columnWidth}},_cellsByColumnResizeChanged:function(){return this._checkIfSegmentsChanged(!0)},_straightAcrossReset:function(){this.straightAcross={x:0}},_straightAcrossLayout:function(a){var c=this;a.each(function(a){var d=b(this);c._pushPosition(d,c.straightAcross.x,0),c.straightAcross.x+=d.outerWidth(!0)})},_straightAcrossGetContainerSize:function(){return{width:this.straightAcross.x}},_straightAcrossResizeChanged:function(){return!0}},b.fn.imagesLoaded=function(a){function h(){a.call(c,d)}function i(a){var c=a.target;c.src!==f&&b.inArray(c,g)===-1&&(g.push(c),--e<=0&&(setTimeout(h),d.unbind(".imagesLoaded",i)))}var c=this,d=c.find("img").add(c.filter("img")),e=d.length,f="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==",g=[];return e||h(),d.bind("load.imagesLoaded error.imagesLoaded",i).each(function(){var a=this.src;this.src=f,this.src=a}),c};var x=function(b){a.console&&a.console.error(b)};b.fn.isotope=function(a,c){if(typeof a=="string"){var d=Array.prototype.slice.call(arguments,1);this.each(function(){var c=b.data(this,"isotope");if(!c){x("cannot call methods on isotope prior to initialization; attempted to call method '"+a+"'");return}if(!b.isFunction(c[a])||a.charAt(0)==="_"){x("no such method '"+a+"' for isotope instance");return}c[a].apply(c,d)})}else this.each(function(){var d=b.data(this,"isotope");d?(d.option(a),d._init(c)):b.data(this,"isotope",new b.Isotope(a,this,c))});return this}})(window,jQuery);




/*
 * jQuery BBQ: Back Button & Query Library - v1.2.1 - 2/17/2010
 * http://benalman.com/projects/jquery-bbq-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($,r){var h,n=Array.prototype.slice,t=decodeURIComponent,a=$.param,j,c,m,y,b=$.bbq=$.bbq||{},s,x,k,e=$.event.special,d="hashchange",B="querystring",F="fragment",z="elemUrlAttr",l="href",w="src",p=/^.*\?|#.*$/g,u,H,g,i,C,E={};function G(I){return typeof I==="string"}function D(J){var I=n.call(arguments,1);return function(){return J.apply(this,I.concat(n.call(arguments)))}}function o(I){return I.replace(H,"$2")}function q(I){return I.replace(/(?:^[^?#]*\?([^#]*).*$)?.*/,"$1")}function f(K,P,I,L,J){var R,O,N,Q,M;if(L!==h){N=I.match(K?H:/^([^#?]*)\??([^#]*)(#?.*)/);M=N[3]||"";if(J===2&&G(L)){O=L.replace(K?u:p,"")}else{Q=m(N[2]);L=G(L)?m[K?F:B](L):L;O=J===2?L:J===1?$.extend({},L,Q):$.extend({},Q,L);O=j(O);if(K){O=O.replace(g,t)}}R=N[1]+(K?C:O||!N[1]?"?":"")+O+M}else{R=P(I!==h?I:location.href)}return R}a[B]=D(f,0,q);a[F]=c=D(f,1,o);a.sorted=j=function(J,K){var I=[],L={};$.each(a(J,K).split("&"),function(P,M){var O=M.replace(/(?:%5B|=).*$/,""),N=L[O];if(!N){N=L[O]=[];I.push(O)}N.push(M)});return $.map(I.sort(),function(M){return L[M]}).join("&")};c.noEscape=function(J){J=J||"";var I=$.map(J.split(""),encodeURIComponent);g=new RegExp(I.join("|"),"g")};c.noEscape(",/");c.ajaxCrawlable=function(I){if(I!==h){if(I){u=/^.*(?:#!|#)/;H=/^([^#]*)(?:#!|#)?(.*)$/;C="#!"}else{u=/^.*#/;H=/^([^#]*)#?(.*)$/;C="#"}i=!!I}return i};c.ajaxCrawlable(0);$.deparam=m=function(L,I){var K={},J={"true":!0,"false":!1,"null":null};$.each(L.replace(/\+/g," ").split("&"),function(O,T){var N=T.split("="),S=t(N[0]),M,R=K,P=0,U=S.split("]["),Q=U.length-1;if(/\[/.test(U[0])&&/\]$/.test(U[Q])){U[Q]=U[Q].replace(/\]$/,"");U=U.shift().split("[").concat(U);Q=U.length-1}else{Q=0}if(N.length===2){M=t(N[1]);if(I){M=M&&!isNaN(M)?+M:M==="undefined"?h:J[M]!==h?J[M]:M}if(Q){for(;P<=Q;P++){S=U[P]===""?R.length:U[P];R=R[S]=P<Q?R[S]||(U[P+1]&&isNaN(U[P+1])?{}:[]):M}}else{if($.isArray(K[S])){K[S].push(M)}else{if(K[S]!==h){K[S]=[K[S],M]}else{K[S]=M}}}}else{if(S){K[S]=I?h:""}}});return K};function A(K,I,J){if(I===h||typeof I==="boolean"){J=I;I=a[K?F:B]()}else{I=G(I)?I.replace(K?u:p,""):I}return m(I,J)}m[B]=D(A,0);m[F]=y=D(A,1);$[z]||($[z]=function(I){return $.extend(E,I)})({a:l,base:l,iframe:w,img:w,input:w,form:"action",link:l,script:w});k=$[z];function v(L,J,K,I){if(!G(K)&&typeof K!=="object"){I=K;K=J;J=h}return this.each(function(){var O=$(this),M=J||k()[(this.nodeName||"").toLowerCase()]||"",N=M&&O.attr(M)||"";O.attr(M,a[L](N,K,I))})}$.fn[B]=D(v,B);$.fn[F]=D(v,F);b.pushState=s=function(L,I){if(G(L)&&/^#/.test(L)&&I===h){I=2}var K=L!==h,J=c(location.href,K?L:{},K?I:2);location.href=J};b.getState=x=function(I,J){return I===h||typeof I==="boolean"?y(I):y(J)[I]};b.removeState=function(I){var J={};if(I!==h){J=x();$.each($.isArray(I)?I:arguments,function(L,K){delete J[K]})}s(J,2)};e[d]=$.extend(e[d],{add:function(I){var K;function J(M){var L=M[F]=c();M.getState=function(N,O){return N===h||typeof N==="boolean"?m(L,N):m(L,O)[N]};K.apply(this,arguments)}if($.isFunction(I)){K=I;return J}else{K=I.handler;I.handler=J}}})})(jQuery,this);
/*
 * jQuery hashchange event - v1.3 - 7/21/2010
 * http://benalman.com/projects/jquery-hashchange-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function(e,t,n){"$:nomunge";function f(e){e=e||location.href;return"#"+e.replace(/^[^#]*#?(.*)$/,"$1")}var r="hashchange",i=document,s,o=e.event.special,u=i.documentMode,a="on"+r in t&&(u===n||u>7);e.fn[r]=function(e){return e?this.bind(r,e):this.trigger(r)};e.fn[r].delay=50;o[r]=e.extend(o[r],{setup:function(){if(a){return false}e(s.start)},teardown:function(){if(a){return false}e(s.stop)}});s=function(){function p(){var n=f(),i=h(u);if(n!==u){c(u=n,i);e(t).trigger(r)}else if(i!==u){location.href=location.href.replace(/#.*/,"")+i}o=setTimeout(p,e.fn[r].delay)}var s={},o,u=f(),l=function(e){return e},c=l,h=l;s.start=function(){o||p()};s.stop=function(){o&&clearTimeout(o);o=n};navigator.appName==="Microsoft Internet Explorer"&&!a&&function(){var t,n;s.start=function(){if(!t){n=e.fn[r].src;n=n&&n+f();t=e('<iframe tabindex="-1" title="empty"/>').hide().one("load",function(){n||c(f());p()}).attr("src",n||"javascript:0").insertAfter("body")[0].contentWindow;i.onpropertychange=function(){try{if(event.propertyName==="title"){t.document.title=i.title}}catch(e){}}}};s.stop=l;h=function(){return f(t.location.href)};c=function(n,s){var o=t.document,u=e.fn[r].domain;if(n!==s){o.title=i.title;o.open();u&&o.write('<script>document.domain="'+u+'"</script>');o.close();t.location.hash=n}}}();return s}()})(jQuery,this);




/*
* jQuery Form Plugin; v20131228
* http://jquery.malsup.com/form/
* Copyright (c) 2013 M. Alsup; Dual licensed: MIT/GPL
* https://github.com/malsup/form#copyright-and-license
*/
;!function(a){"use strict";"function"==typeof define&&define.amd?define(["jquery"],a):a("undefined"!=typeof jQuery?jQuery:window.Zepto)}(function(a){"use strict";function b(b){var c=b.data;b.isDefaultPrevented()||(b.preventDefault(),a(b.target).ajaxSubmit(c))}function c(b){var c=b.target,d=a(c);if(!d.is("[type=submit],[type=image]")){var e=d.closest("[type=submit]");if(0===e.length)return;c=e[0]}var f=this;if(f.clk=c,"image"==c.type)if(void 0!==b.offsetX)f.clk_x=b.offsetX,f.clk_y=b.offsetY;else if("function"==typeof a.fn.offset){var g=d.offset();f.clk_x=b.pageX-g.left,f.clk_y=b.pageY-g.top}else f.clk_x=b.pageX-c.offsetLeft,f.clk_y=b.pageY-c.offsetTop;setTimeout(function(){f.clk=f.clk_x=f.clk_y=null},100)}function d(){if(a.fn.ajaxSubmit.debug){var b="[jquery.form] "+Array.prototype.join.call(arguments,"");window.console&&window.console.log?window.console.log(b):window.opera&&window.opera.postError&&window.opera.postError(b)}}var e={};e.fileapi=void 0!==a("<input type='file'/>").get(0).files,e.formdata=void 0!==window.FormData;var f=!!a.fn.prop;a.fn.attr2=function(){if(!f)return this.attr.apply(this,arguments);var a=this.prop.apply(this,arguments);return a&&a.jquery||"string"==typeof a?a:this.attr.apply(this,arguments)},a.fn.ajaxSubmit=function(b){function c(c){var d,e,f=a.param(c,b.traditional).split("&"),g=f.length,h=[];for(d=0;g>d;d++)f[d]=f[d].replace(/\+/g," "),e=f[d].split("="),h.push([decodeURIComponent(e[0]),decodeURIComponent(e[1])]);return h}function g(d){for(var e=new FormData,f=0;f<d.length;f++)e.append(d[f].name,d[f].value);if(b.extraData){var g=c(b.extraData);for(f=0;f<g.length;f++)g[f]&&e.append(g[f][0],g[f][1])}b.data=null;var h=a.extend(!0,{},a.ajaxSettings,b,{contentType:!1,processData:!1,cache:!1,type:i||"POST"});b.uploadProgress&&(h.xhr=function(){var c=a.ajaxSettings.xhr();return c.upload&&c.upload.addEventListener("progress",function(a){var c=0,d=a.loaded||a.position,e=a.total;a.lengthComputable&&(c=Math.ceil(d/e*100)),b.uploadProgress(a,d,e,c)},!1),c}),h.data=null;var j=h.beforeSend;return h.beforeSend=function(a,c){c.data=b.formData?b.formData:e,j&&j.call(this,a,c)},a.ajax(h)}function h(c){function e(a){var b=null;try{a.contentWindow&&(b=a.contentWindow.document)}catch(c){d("cannot get iframe.contentWindow document: "+c)}if(b)return b;try{b=a.contentDocument?a.contentDocument:a.document}catch(c){d("cannot get iframe.contentDocument: "+c),b=a.document}return b}function g(){function b(){try{var a=e(r).readyState;d("state = "+a),a&&"uninitialized"==a.toLowerCase()&&setTimeout(b,50)}catch(c){d("Server abort: ",c," (",c.name,")"),h(A),w&&clearTimeout(w),w=void 0}}var c=l.attr2("target"),f=l.attr2("action"),g="multipart/form-data",j=l.attr("enctype")||l.attr("encoding")||g;x.setAttribute("target",o),(!i||/post/i.test(i))&&x.setAttribute("method","POST"),f!=m.url&&x.setAttribute("action",m.url),m.skipEncodingOverride||i&&!/post/i.test(i)||l.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"}),m.timeout&&(w=setTimeout(function(){v=!0,h(z)},m.timeout));var k=[];try{if(m.extraData)for(var n in m.extraData)m.extraData.hasOwnProperty(n)&&(a.isPlainObject(m.extraData[n])&&m.extraData[n].hasOwnProperty("name")&&m.extraData[n].hasOwnProperty("value")?k.push(a('<input type="hidden" name="'+m.extraData[n].name+'">').val(m.extraData[n].value).appendTo(x)[0]):k.push(a('<input type="hidden" name="'+n+'">').val(m.extraData[n]).appendTo(x)[0]));m.iframeTarget||q.appendTo("body"),r.attachEvent?r.attachEvent("onload",h):r.addEventListener("load",h,!1),setTimeout(b,15);try{x.submit()}catch(p){var s=document.createElement("form").submit;s.apply(x)}}finally{x.setAttribute("action",f),x.setAttribute("enctype",j),c?x.setAttribute("target",c):l.removeAttr("target"),a(k).remove()}}function h(b){if(!s.aborted&&!F){if(E=e(r),E||(d("cannot access response document"),b=A),b===z&&s)return s.abort("timeout"),y.reject(s,"timeout"),void 0;if(b==A&&s)return s.abort("server abort"),y.reject(s,"error","server abort"),void 0;if(E&&E.location.href!=m.iframeSrc||v){r.detachEvent?r.detachEvent("onload",h):r.removeEventListener("load",h,!1);var c,f="success";try{if(v)throw"timeout";var g="xml"==m.dataType||E.XMLDocument||a.isXMLDoc(E);if(d("isXml="+g),!g&&window.opera&&(null===E.body||!E.body.innerHTML)&&--G)return d("requeing onLoad callback, DOM not available"),setTimeout(h,250),void 0;var i=E.body?E.body:E.documentElement;s.responseText=i?i.innerHTML:null,s.responseXML=E.XMLDocument?E.XMLDocument:E,g&&(m.dataType="xml"),s.getResponseHeader=function(a){var b={"content-type":m.dataType};return b[a.toLowerCase()]},i&&(s.status=Number(i.getAttribute("status"))||s.status,s.statusText=i.getAttribute("statusText")||s.statusText);var j=(m.dataType||"").toLowerCase(),k=/(json|script|text)/.test(j);if(k||m.textarea){var l=E.getElementsByTagName("textarea")[0];if(l)s.responseText=l.value,s.status=Number(l.getAttribute("status"))||s.status,s.statusText=l.getAttribute("statusText")||s.statusText;else if(k){var o=E.getElementsByTagName("pre")[0],p=E.getElementsByTagName("body")[0];o?s.responseText=o.textContent?o.textContent:o.innerText:p&&(s.responseText=p.textContent?p.textContent:p.innerText)}}else"xml"==j&&!s.responseXML&&s.responseText&&(s.responseXML=H(s.responseText));try{D=J(s,j,m)}catch(t){f="parsererror",s.error=c=t||f}}catch(t){d("error caught: ",t),f="error",s.error=c=t||f}s.aborted&&(d("upload aborted"),f=null),s.status&&(f=s.status>=200&&s.status<300||304===s.status?"success":"error"),"success"===f?(m.success&&m.success.call(m.context,D,"success",s),y.resolve(s.responseText,"success",s),n&&a.event.trigger("ajaxSuccess",[s,m])):f&&(void 0===c&&(c=s.statusText),m.error&&m.error.call(m.context,s,f,c),y.reject(s,"error",c),n&&a.event.trigger("ajaxError",[s,m,c])),n&&a.event.trigger("ajaxComplete",[s,m]),n&&!--a.active&&a.event.trigger("ajaxStop"),m.complete&&m.complete.call(m.context,s,f),F=!0,m.timeout&&clearTimeout(w),setTimeout(function(){m.iframeTarget?q.attr("src",m.iframeSrc):q.remove(),s.responseXML=null},100)}}}var j,k,m,n,o,q,r,s,t,u,v,w,x=l[0],y=a.Deferred();if(y.abort=function(a){s.abort(a)},c)for(k=0;k<p.length;k++)j=a(p[k]),f?j.prop("disabled",!1):j.removeAttr("disabled");if(m=a.extend(!0,{},a.ajaxSettings,b),m.context=m.context||m,o="jqFormIO"+(new Date).getTime(),m.iframeTarget?(q=a(m.iframeTarget),u=q.attr2("name"),u?o=u:q.attr2("name",o)):(q=a('<iframe name="'+o+'" src="'+m.iframeSrc+'" />'),q.css({position:"absolute",top:"-1000px",left:"-1000px"})),r=q[0],s={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(b){var c="timeout"===b?"timeout":"aborted";d("aborting upload... "+c),this.aborted=1;try{r.contentWindow.document.execCommand&&r.contentWindow.document.execCommand("Stop")}catch(e){}q.attr("src",m.iframeSrc),s.error=c,m.error&&m.error.call(m.context,s,c,b),n&&a.event.trigger("ajaxError",[s,m,c]),m.complete&&m.complete.call(m.context,s,c)}},n=m.global,n&&0===a.active++&&a.event.trigger("ajaxStart"),n&&a.event.trigger("ajaxSend",[s,m]),m.beforeSend&&m.beforeSend.call(m.context,s,m)===!1)return m.global&&a.active--,y.reject(),y;if(s.aborted)return y.reject(),y;t=x.clk,t&&(u=t.name,u&&!t.disabled&&(m.extraData=m.extraData||{},m.extraData[u]=t.value,"image"==t.type&&(m.extraData[u+".x"]=x.clk_x,m.extraData[u+".y"]=x.clk_y)));var z=1,A=2,B=a("meta[name=csrf-token]").attr("content"),C=a("meta[name=csrf-param]").attr("content");C&&B&&(m.extraData=m.extraData||{},m.extraData[C]=B),m.forceSync?g():setTimeout(g,10);var D,E,F,G=50,H=a.parseXML||function(a,b){return window.ActiveXObject?(b=new ActiveXObject("Microsoft.XMLDOM"),b.async="false",b.loadXML(a)):b=(new DOMParser).parseFromString(a,"text/xml"),b&&b.documentElement&&"parsererror"!=b.documentElement.nodeName?b:null},I=a.parseJSON||function(a){return window.eval("("+a+")")},J=function(b,c,d){var e=b.getResponseHeader("content-type")||"",f="xml"===c||!c&&e.indexOf("xml")>=0,g=f?b.responseXML:b.responseText;return f&&"parsererror"===g.documentElement.nodeName&&a.error&&a.error("parsererror"),d&&d.dataFilter&&(g=d.dataFilter(g,c)),"string"==typeof g&&("json"===c||!c&&e.indexOf("json")>=0?g=I(g):("script"===c||!c&&e.indexOf("javascript")>=0)&&a.globalEval(g)),g};return y}if(!this.length)return d("ajaxSubmit: skipping submit process - no element selected"),this;var i,j,k,l=this;"function"==typeof b?b={success:b}:void 0===b&&(b={}),i=b.type||this.attr2("method"),j=b.url||this.attr2("action"),k="string"==typeof j?a.trim(j):"",k=k||window.location.href||"",k&&(k=(k.match(/^([^#]+)/)||[])[1]),b=a.extend(!0,{url:k,success:a.ajaxSettings.success,type:i||a.ajaxSettings.type,iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},b);var m={};if(this.trigger("form-pre-serialize",[this,b,m]),m.veto)return d("ajaxSubmit: submit vetoed via form-pre-serialize trigger"),this;if(b.beforeSerialize&&b.beforeSerialize(this,b)===!1)return d("ajaxSubmit: submit aborted via beforeSerialize callback"),this;var n=b.traditional;void 0===n&&(n=a.ajaxSettings.traditional);var o,p=[],q=this.formToArray(b.semantic,p);if(b.data&&(b.extraData=b.data,o=a.param(b.data,n)),b.beforeSubmit&&b.beforeSubmit(q,this,b)===!1)return d("ajaxSubmit: submit aborted via beforeSubmit callback"),this;if(this.trigger("form-submit-validate",[q,this,b,m]),m.veto)return d("ajaxSubmit: submit vetoed via form-submit-validate trigger"),this;var r=a.param(q,n);o&&(r=r?r+"&"+o:o),"GET"==b.type.toUpperCase()?(b.url+=(b.url.indexOf("?")>=0?"&":"?")+r,b.data=null):b.data=r;var s=[];if(b.resetForm&&s.push(function(){l.resetForm()}),b.clearForm&&s.push(function(){l.clearForm(b.includeHidden)}),!b.dataType&&b.target){var t=b.success||function(){};s.push(function(c){var d=b.replaceTarget?"replaceWith":"html";a(b.target)[d](c).each(t,arguments)})}else b.success&&s.push(b.success);if(b.success=function(a,c,d){for(var e=b.context||this,f=0,g=s.length;g>f;f++)s[f].apply(e,[a,c,d||l,l])},b.error){var u=b.error;b.error=function(a,c,d){var e=b.context||this;u.apply(e,[a,c,d,l])}}if(b.complete){var v=b.complete;b.complete=function(a,c){var d=b.context||this;v.apply(d,[a,c,l])}}var w=a("input[type=file]:enabled",this).filter(function(){return""!==a(this).val()}),x=w.length>0,y="multipart/form-data",z=l.attr("enctype")==y||l.attr("encoding")==y,A=e.fileapi&&e.formdata;d("fileAPI :"+A);var B,C=(x||z)&&!A;b.iframe!==!1&&(b.iframe||C)?b.closeKeepAlive?a.get(b.closeKeepAlive,function(){B=h(q)}):B=h(q):B=(x||z)&&A?g(q):a.ajax(b),l.removeData("jqxhr").data("jqxhr",B);for(var D=0;D<p.length;D++)p[D]=null;return this.trigger("form-submit-notify",[this,b]),this},a.fn.ajaxForm=function(e){if(e=e||{},e.delegation=e.delegation&&a.isFunction(a.fn.on),!e.delegation&&0===this.length){var f={s:this.selector,c:this.context};return!a.isReady&&f.s?(d("DOM not ready, queuing ajaxForm"),a(function(){a(f.s,f.c).ajaxForm(e)}),this):(d("terminating; zero elements found by selector"+(a.isReady?"":" (DOM not ready)")),this)}return e.delegation?(a(document).off("submit.form-plugin",this.selector,b).off("click.form-plugin",this.selector,c).on("submit.form-plugin",this.selector,e,b).on("click.form-plugin",this.selector,e,c),this):this.ajaxFormUnbind().bind("submit.form-plugin",e,b).bind("click.form-plugin",e,c)},a.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")},a.fn.formToArray=function(b,c){var d=[];if(0===this.length)return d;var f,g=this[0],h=this.attr("id"),i=b?g.getElementsByTagName("*"):g.elements;if(i&&(i=a(i).get()),h&&(f=a(":input[form="+h+"]").get(),f.length&&(i=(i||[]).concat(f))),!i||!i.length)return d;var j,k,l,m,n,o,p;for(j=0,o=i.length;o>j;j++)if(n=i[j],l=n.name,l&&!n.disabled)if(b&&g.clk&&"image"==n.type)g.clk==n&&(d.push({name:l,value:a(n).val(),type:n.type}),d.push({name:l+".x",value:g.clk_x},{name:l+".y",value:g.clk_y}));else if(m=a.fieldValue(n,!0),m&&m.constructor==Array)for(c&&c.push(n),k=0,p=m.length;p>k;k++)d.push({name:l,value:m[k]});else if(e.fileapi&&"file"==n.type){c&&c.push(n);var q=n.files;if(q.length)for(k=0;k<q.length;k++)d.push({name:l,value:q[k],type:n.type});else d.push({name:l,value:"",type:n.type})}else null!==m&&"undefined"!=typeof m&&(c&&c.push(n),d.push({name:l,value:m,type:n.type,required:n.required}));if(!b&&g.clk){var r=a(g.clk),s=r[0];l=s.name,l&&!s.disabled&&"image"==s.type&&(d.push({name:l,value:r.val()}),d.push({name:l+".x",value:g.clk_x},{name:l+".y",value:g.clk_y}))}return d},a.fn.formSerialize=function(b){return a.param(this.formToArray(b))},a.fn.fieldSerialize=function(b){var c=[];return this.each(function(){var d=this.name;if(d){var e=a.fieldValue(this,b);if(e&&e.constructor==Array)for(var f=0,g=e.length;g>f;f++)c.push({name:d,value:e[f]});else null!==e&&"undefined"!=typeof e&&c.push({name:this.name,value:e})}}),a.param(c)},a.fn.fieldValue=function(b){for(var c=[],d=0,e=this.length;e>d;d++){var f=this[d],g=a.fieldValue(f,b);null===g||"undefined"==typeof g||g.constructor==Array&&!g.length||(g.constructor==Array?a.merge(c,g):c.push(g))}return c},a.fieldValue=function(b,c){var d=b.name,e=b.type,f=b.tagName.toLowerCase();if(void 0===c&&(c=!0),c&&(!d||b.disabled||"reset"==e||"button"==e||("checkbox"==e||"radio"==e)&&!b.checked||("submit"==e||"image"==e)&&b.form&&b.form.clk!=b||"select"==f&&-1==b.selectedIndex))return null;if("select"==f){var g=b.selectedIndex;if(0>g)return null;for(var h=[],i=b.options,j="select-one"==e,k=j?g+1:i.length,l=j?g:0;k>l;l++){var m=i[l];if(m.selected){var n=m.value;if(n||(n=m.attributes&&m.attributes.value&&!m.attributes.value.specified?m.text:m.value),j)return n;h.push(n)}}return h}return a(b).val()},a.fn.clearForm=function(b){return this.each(function(){a("input,select,textarea",this).clearFields(b)})},a.fn.clearFields=a.fn.clearInputs=function(b){var c=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var d=this.type,e=this.tagName.toLowerCase();c.test(d)||"textarea"==e?this.value="":"checkbox"==d||"radio"==d?this.checked=!1:"select"==e?this.selectedIndex=-1:"file"==d?/MSIE/.test(navigator.userAgent)?a(this).replaceWith(a(this).clone(!0)):a(this).val(""):b&&(b===!0&&/hidden/.test(d)||"string"==typeof b&&a(this).is(b))&&(this.value="")})},a.fn.resetForm=function(){return this.each(function(){("function"==typeof this.reset||"object"==typeof this.reset&&!this.reset.nodeType)&&this.reset()})},a.fn.enable=function(a){return void 0===a&&(a=!0),this.each(function(){this.disabled=!a})},a.fn.selected=function(b){return void 0===b&&(b=!0),this.each(function(){var c=this.type;if("checkbox"==c||"radio"==c)this.checked=b;else if("option"==this.tagName.toLowerCase()){var d=a(this).parent("select");b&&d[0]&&"select-one"==d[0].type&&d.find("option").selected(!1),this.selected=b}})},a.fn.ajaxSubmit.debug=!1});




/*! Magnific Popup - v0.9.5 - 2013-08-21
* http://dimsemenov.com/plugins/magnific-popup/
* Copyright (c) 2013 Dmitry Semenov; */
(function(e){var t="Close",n="BeforeClose",r="AfterClose",i="BeforeAppend",s="MarkupParse",o="Open",u="Change",a="mfp",f="."+a,l="mfp-ready",c="mfp-removing",h="mfp-prevent-close";var p,d=function(){},v=!!window.jQuery,m,g=e(window),y,b,w,E,S;var x=function(e,t){p.ev.on(a+e+f,t)},T=function(t,n,r,i){var s=document.createElement("div");s.className="mfp-"+t;if(r){s.innerHTML=r}if(!i){s=e(s);if(n){s.appendTo(n)}}else if(n){n.appendChild(s)}return s},N=function(t,n){p.ev.triggerHandler(a+t,n);if(p.st.callbacks){t=t.charAt(0).toLowerCase()+t.slice(1);if(p.st.callbacks[t]){p.st.callbacks[t].apply(p,e.isArray(n)?n:[n])}}},C=function(){(p.st.focus?p.content.find(p.st.focus).eq(0):p.wrap).focus()},k=function(t){if(t!==S||!p.currTemplate.closeBtn){p.currTemplate.closeBtn=e(p.st.closeMarkup.replace("%title%",p.st.tClose));S=t}return p.currTemplate.closeBtn},L=function(){if(!e.magnificPopup.instance){p=new d;p.init();e.magnificPopup.instance=p}},A=function(t){if(e(t).hasClass(h)){return}var n=p.st.closeOnContentClick;var r=p.st.closeOnBgClick;if(n&&r){return true}else{if(!p.content||e(t).hasClass("mfp-close")||p.preloader&&t===p.preloader[0]){return true}if(t!==p.content[0]&&!e.contains(p.content[0],t)){if(r){if(e.contains(document,t)){return true}}}else if(n){return true}}return false},O=function(){var e=document.createElement("p").style,t=["ms","O","Moz","Webkit"];if(e["transition"]!==undefined){return true}while(t.length){if(t.pop()+"Transition"in e){return true}}return false};d.prototype={constructor:d,init:function(){var t=navigator.appVersion;p.isIE7=t.indexOf("MSIE 7.")!==-1;p.isIE8=t.indexOf("MSIE 8.")!==-1;p.isLowIE=p.isIE7||p.isIE8;p.isAndroid=/android/gi.test(t);p.isIOS=/iphone|ipad|ipod/gi.test(t);p.supportsTransition=O();p.probablyMobile=p.isAndroid||p.isIOS||/(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent);y=e(document.body);b=e(document);p.popupsCache={}},open:function(t){var n;if(t.isObj===false){p.items=t.items.toArray();p.index=0;var r=t.items,i;for(n=0;n<r.length;n++){i=r[n];if(i.parsed){i=i.el[0]}if(i===t.el[0]){p.index=n;break}}}else{p.items=e.isArray(t.items)?t.items:[t.items];p.index=t.index||0}if(p.isOpen){p.updateItemHTML();return}p.types=[];E="";if(t.mainEl&&t.mainEl.length){p.ev=t.mainEl.eq(0)}else{p.ev=b}if(t.key){if(!p.popupsCache[t.key]){p.popupsCache[t.key]={}}p.currTemplate=p.popupsCache[t.key]}else{p.currTemplate={}}p.st=e.extend(true,{},e.magnificPopup.defaults,t);p.fixedContentPos=p.st.fixedContentPos==="auto"?!p.probablyMobile:p.st.fixedContentPos;if(p.st.modal){p.st.closeOnContentClick=false;p.st.closeOnBgClick=false;p.st.showCloseBtn=false;p.st.enableEscapeKey=false}if(!p.bgOverlay){p.bgOverlay=T("bg").on("click"+f,function(){p.close()});p.wrap=T("wrap").attr("tabindex",-1).on("click"+f,function(e){if(A(e.target)){p.close()}});p.container=T("container",p.wrap)}p.contentContainer=T("content");if(p.st.preloader){p.preloader=T("preloader",p.container,p.st.tLoading)}var u=e.magnificPopup.modules;for(n=0;n<u.length;n++){var a=u[n];a=a.charAt(0).toUpperCase()+a.slice(1);p["init"+a].call(p)}N("BeforeOpen");if(p.st.showCloseBtn){if(!p.st.closeBtnInside){p.wrap.append(k())}else{x(s,function(e,t,n,r){n.close_replaceWith=k(r.type)});E+=" mfp-close-btn-in"}}if(p.st.alignTop){E+=" mfp-align-top"}if(p.fixedContentPos){p.wrap.css({overflow:p.st.overflowY,overflowX:"hidden",overflowY:p.st.overflowY})}else{p.wrap.css({top:g.scrollTop(),position:"absolute"})}if(p.st.fixedBgPos===false||p.st.fixedBgPos==="auto"&&!p.fixedContentPos){p.bgOverlay.css({height:b.height(),position:"absolute"})}if(p.st.enableEscapeKey){b.on("keyup"+f,function(e){if(e.keyCode===27){p.close()}})}g.on("resize"+f,function(){p.updateSize()});if(!p.st.closeOnContentClick){E+=" mfp-auto-cursor"}if(E)p.wrap.addClass(E);var c=p.wH=g.height();var h={};var d=p.st.mainClass;if(p.isIE7){d+=" mfp-ie7"}if(d){p._addClassToMFP(d)}p.updateItemHTML();N("BuildControls");e("html").css(h);p.bgOverlay.add(p.wrap).prependTo(document.body);p._lastFocusedEl=document.activeElement;setTimeout(function(){if(p.content){p._addClassToMFP(l);C()}else{p.bgOverlay.addClass(l)}b.on("focusin"+f,function(t){if(t.target!==p.wrap[0]&&!e.contains(p.wrap[0],t.target)){C();return false}})},16);p.isOpen=true;p.updateSize(c);N(o)},close:function(){if(!p.isOpen)return;N(n);p.isOpen=false;if(p.st.removalDelay&&!p.isLowIE&&p.supportsTransition){p._addClassToMFP(c);setTimeout(function(){p._close()},p.st.removalDelay)}else{p._close()}},_close:function(){N(t);var n=c+" "+l+" ";p.bgOverlay.detach();p.wrap.detach();p.container.empty();if(p.st.mainClass){n+=p.st.mainClass+" "}p._removeClassFromMFP(n);b.off("keyup"+f+" focusin"+f);p.ev.off(f);p.wrap.attr("class","mfp-wrap").removeAttr("style");p.bgOverlay.attr("class","mfp-bg");p.container.attr("class","mfp-container");if(p.st.showCloseBtn&&(!p.st.closeBtnInside||p.currTemplate[p.currItem.type]===true)){if(p.currTemplate.closeBtn)p.currTemplate.closeBtn.detach()}if(p._lastFocusedEl){e(p._lastFocusedEl).focus()}p.currItem=null;p.content=null;p.currTemplate=null;p.prevHeight=0;N(r)},updateSize:function(e){if(p.isIOS){var t=document.documentElement.clientWidth/window.innerWidth;var n=window.innerHeight*t;p.wrap.css("height",n);p.wH=n}else{p.wH=e||g.height()}if(!p.fixedContentPos){p.wrap.css("height",p.wH)}N("Resize")},updateItemHTML:function(){var t=p.items[p.index];p.contentContainer.detach();if(p.content)p.content.detach();if(!t.parsed){t=p.parseEl(p.index)}var n=t.type;N("BeforeChange",[p.currItem?p.currItem.type:"",n]);p.currItem=t;if(!p.currTemplate[n]){var r=p.st[n]?p.st[n].markup:false;N("FirstMarkupParse",r);if(r){p.currTemplate[n]=e(r)}else{p.currTemplate[n]=true}}if(w&&w!==t.type){p.container.removeClass("mfp-"+w+"-holder")}var i=p["get"+n.charAt(0).toUpperCase()+n.slice(1)](t,p.currTemplate[n]);p.appendContent(i,n);t.preloaded=true;N(u,t);w=t.type;p.container.prepend(p.contentContainer);N("AfterChange")},appendContent:function(e,t){p.content=e;if(e){if(p.st.showCloseBtn&&p.st.closeBtnInside&&p.currTemplate[t]===true){if(!p.content.find(".mfp-close").length){p.content.append(k())}}else{p.content=e}}else{p.content=""}N(i);p.container.addClass("mfp-"+t+"-holder");p.contentContainer.append(p.content)},parseEl:function(t){var n=p.items[t],r=n.type;if(n.tagName){n={el:e(n)}}else{n={data:n,src:n.src}}if(n.el){var i=p.types;for(var s=0;s<i.length;s++){if(n.el.hasClass("mfp-"+i[s])){r=i[s];break}}n.src=n.el.attr("data-mfp-src");if(!n.src){n.src=n.el.attr("href")}}n.type=r||p.st.type||"inline";n.index=t;n.parsed=true;p.items[t]=n;N("ElementParse",n);return p.items[t]},addGroup:function(e,t){var n=function(n){n.mfpEl=this;p._openClick(n,e,t)};if(!t){t={}}var r="click.magnificPopup";t.mainEl=e;if(t.items){t.isObj=true;e.off(r).on(r,n)}else{t.isObj=false;if(t.delegate){e.off(r).on(r,t.delegate,n)}else{t.items=e;e.off(r).on(r,n)}}},_openClick:function(t,n,r){var i=r.midClick!==undefined?r.midClick:e.magnificPopup.defaults.midClick;if(!i&&(t.which===2||t.ctrlKey||t.metaKey)){return}var s=r.disableOn!==undefined?r.disableOn:e.magnificPopup.defaults.disableOn;if(s){if(e.isFunction(s)){if(!s.call(p)){return true}}else{if(g.width()<s){return true}}}if(t.type){t.preventDefault();if(p.isOpen){t.stopPropagation()}}r.el=e(t.mfpEl);if(r.delegate){r.items=n.find(r.delegate)}p.open(r)},updateStatus:function(e,t){if(p.preloader){if(m!==e){p.container.removeClass("mfp-s-"+m)}if(!t&&e==="loading"){t=p.st.tLoading}var n={status:e,text:t};N("UpdateStatus",n);e=n.status;t=n.text;p.preloader.html(t);p.preloader.find("a").on("click",function(e){e.stopImmediatePropagation()});p.container.addClass("mfp-s-"+e);m=e}},_addClassToMFP:function(e){p.bgOverlay.addClass(e);p.wrap.addClass(e)},_removeClassFromMFP:function(e){this.bgOverlay.removeClass(e);p.wrap.removeClass(e)},_hasScrollBar:function(e){return(p.isIE7?b.height():document.body.scrollHeight)>(e||g.height())},_parseMarkup:function(t,n,r){var i;if(r.data){n=e.extend(r.data,n)}N(s,[t,n,r]);e.each(n,function(e,n){if(n===undefined||n===false){return true}i=e.split("_");if(i.length>1){var r=t.find(f+"-"+i[0]);if(r.length>0){var s=i[1];if(s==="replaceWith"){if(r[0]!==n[0]){r.replaceWith(n)}}else if(s==="img"){if(r.is("img")){r.attr("src",n)}else{r.replaceWith('<img src="'+n+'" class="'+r.attr("class")+'" />')}}else{r.attr(i[1],n)}}}else{t.find(f+"-"+e).html(n)}})},_getScrollbarSize:function(){if(p.scrollbarSize===undefined){var e=document.createElement("div");e.id="mfp-sbm";e.style.cssText="width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;";document.body.appendChild(e);p.scrollbarSize=e.offsetWidth-e.clientWidth;document.body.removeChild(e)}return p.scrollbarSize}};e.magnificPopup={instance:null,proto:d.prototype,modules:[],open:function(e,t){L();if(!e)e={};e.isObj=true;e.index=t||0;return this.instance.open(e)},close:function(){return e.magnificPopup.instance.close()},registerModule:function(t,n){if(n.options){e.magnificPopup.defaults[t]=n.options}e.extend(this.proto,n.proto);this.modules.push(t)},defaults:{disableOn:0,key:null,midClick:false,mainClass:"",preloader:true,focus:"",closeOnContentClick:false,closeOnBgClick:true,closeBtnInside:true,showCloseBtn:true,enableEscapeKey:true,modal:false,alignTop:false,removalDelay:0,fixedContentPos:"auto",fixedBgPos:"auto",overflowY:"auto",closeMarkup:'<button title="%title%" type="button" class="mfp-close">&times;</button>',tClose:"Close (Esc)",tLoading:"Loading..."}};e.fn.magnificPopup=function(t){L();var n=e(this);if(typeof t==="string"){if(t==="open"){var r,i=v?n.data("magnificPopup"):n[0].magnificPopup,s=parseInt(arguments[1],10)||0;if(i.items){r=i.items[s]}else{r=n;if(i.delegate){r=r.find(i.delegate)}r=r.eq(s)}p._openClick({mfpEl:r},n,i)}else{if(p.isOpen)p[t].apply(p,Array.prototype.slice.call(arguments,1))}}else{if(v){n.data("magnificPopup",t)}else{n[0].magnificPopup=t}p.addGroup(n,t)}return n};var M="inline",_,D,P,H=function(){if(P){D.after(P.addClass(_)).detach();P=null}};e.magnificPopup.registerModule(M,{options:{hiddenClass:"hide",markup:"",tNotFound:"Content not found"},proto:{initInline:function(){p.types.push(M);x(t+"."+M,function(){H()})},getInline:function(t,n){H();if(t.src){var r=p.st.inline,i=e(t.src);if(i.length){var s=i[0].parentNode;if(s&&s.tagName){if(!D){_=r.hiddenClass;D=T(_);_="mfp-"+_}P=i.after(D).detach().removeClass(_)}p.updateStatus("ready")}else{p.updateStatus("error",r.tNotFound);i=e("<div>")}t.inlineElement=i;return i}p.updateStatus("ready");p._parseMarkup(n,{},t);return n}}});var B="ajax",j,F=function(){if(j){y.removeClass(j)}};e.magnificPopup.registerModule(B,{options:{settings:null,cursor:"mfp-ajax-cur",tError:'<a href="%url%">The content</a> could not be loaded.'},proto:{initAjax:function(){p.types.push(B);j=p.st.ajax.cursor;x(t+"."+B,function(){F();if(p.req){p.req.abort()}})},getAjax:function(t){if(j)y.addClass(j);p.updateStatus("loading");var n=e.extend({url:t.src,success:function(n,r,i){var s={data:n,xhr:i};N("ParseAjax",s);p.appendContent(e(s.data),B);t.finished=true;F();C();setTimeout(function(){p.wrap.addClass(l)},16);p.updateStatus("ready");N("AjaxContentAdded")},error:function(){F();t.finished=t.loadError=true;p.updateStatus("error",p.st.ajax.tError.replace("%url%",t.src))}},p.st.ajax.settings);p.req=e.ajax(n);return""}}});var I,q=function(t){if(t.data&&t.data.title!==undefined)return t.data.title;var n=p.st.image.titleSrc;if(n){if(e.isFunction(n)){return n.call(p,t)}else if(t.el){return t.el.attr(n)||""}}return""};e.magnificPopup.registerModule("image",{options:{markup:'<div class="mfp-figure">'+'<div class="mfp-close"></div>'+'<div class="mfp-img"></div>'+'<div class="mfp-bottom-bar">'+'<div class="mfp-title"></div>'+'<div class="mfp-counter"></div>'+"</div>"+"</div>",cursor:"mfp-zoom-out-cur",titleSrc:"title",verticalFit:true,tError:'<a href="%url%">The image</a> could not be loaded.'},proto:{initImage:function(){var e=p.st.image,n=".image";p.types.push("image");x(o+n,function(){if(p.currItem.type==="image"&&e.cursor){y.addClass(e.cursor)}});x(t+n,function(){if(e.cursor){y.removeClass(e.cursor)}g.off("resize"+f)});x("Resize"+n,p.resizeImage);if(p.isLowIE){x("AfterChange",p.resizeImage)}},resizeImage:function(){var e=p.currItem;if(!e||!e.img)return;if(p.st.image.verticalFit){var t=0;if(p.isLowIE){t=parseInt(e.img.css("padding-top"),10)+parseInt(e.img.css("padding-bottom"),10)}e.img.css("max-height",p.wH-t)}},_onImageHasSize:function(e){if(e.img){e.hasSize=true;if(I){clearInterval(I)}e.isCheckingImgSize=false;N("ImageHasSize",e);if(e.imgHidden){if(p.content)p.content.removeClass("mfp-loading");e.imgHidden=false}}},findImageSize:function(e){var t=0,n=e.img[0],r=function(i){if(I){clearInterval(I)}I=setInterval(function(){if(n.naturalWidth>0){p._onImageHasSize(e);return}if(t>200){clearInterval(I)}t++;if(t===3){r(10)}else if(t===40){r(50)}else if(t===100){r(500)}},i)};r(1)},getImage:function(t,n){var r=0,i=function(){if(t){if(t.img[0].complete){t.img.off(".mfploader");if(t===p.currItem){p._onImageHasSize(t);p.updateStatus("ready")}t.hasSize=true;t.loaded=true;N("ImageLoadComplete")}else{r++;if(r<200){setTimeout(i,100)}else{s()}}}},s=function(){if(t){t.img.off(".mfploader");if(t===p.currItem){p._onImageHasSize(t);p.updateStatus("error",o.tError.replace("%url%",t.src))}t.hasSize=true;t.loaded=true;t.loadError=true}},o=p.st.image;var u=n.find(".mfp-img");if(u.length){var a=document.createElement("img");a.className="mfp-img";t.img=e(a).on("load.mfploader",i).on("error.mfploader",s);a.src=t.src;if(u.is("img")){t.img=t.img.clone()}if(t.img[0].naturalWidth>0){t.hasSize=true}}p._parseMarkup(n,{title:q(t),img_replaceWith:t.img},t);p.resizeImage();if(t.hasSize){if(I)clearInterval(I);if(t.loadError){n.addClass("mfp-loading");p.updateStatus("error",o.tError.replace("%url%",t.src))}else{n.removeClass("mfp-loading");p.updateStatus("ready")}return n}p.updateStatus("loading");t.loading=true;if(!t.hasSize){t.imgHidden=true;n.addClass("mfp-loading");p.findImageSize(t)}return n}}});var R,U=function(){if(R===undefined){R=document.createElement("p").style.MozTransform!==undefined}return R};e.magnificPopup.registerModule("zoom",{options:{enabled:false,easing:"ease-in-out",duration:300,opener:function(e){return e.is("img")?e:e.find("img")}},proto:{initZoom:function(){var e=p.st.zoom,r=".zoom";if(!e.enabled||!p.supportsTransition){return}var i=e.duration,s=function(t){var n=t.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"),r="all "+e.duration/1e3+"s "+e.easing,i={position:"fixed",zIndex:9999,left:0,top:0,"-webkit-backface-visibility":"hidden"},s="transition";i["-webkit-"+s]=i["-moz-"+s]=i["-o-"+s]=i[s]=r;n.css(i);return n},o=function(){p.content.css("visibility","visible")},u,a;x("BuildControls"+r,function(){if(p._allowZoom()){clearTimeout(u);p.content.css("visibility","hidden");image=p._getItemToZoom();if(!image){o();return}a=s(image);a.css(p._getOffset());p.wrap.append(a);u=setTimeout(function(){a.css(p._getOffset(true));u=setTimeout(function(){o();setTimeout(function(){a.remove();image=a=null;N("ZoomAnimationEnded")},16)},i)},16)}});x(n+r,function(){if(p._allowZoom()){clearTimeout(u);p.st.removalDelay=i;if(!image){image=p._getItemToZoom();if(!image){return}a=s(image)}a.css(p._getOffset(true));p.wrap.append(a);p.content.css("visibility","hidden");setTimeout(function(){a.css(p._getOffset())},16)}});x(t+r,function(){if(p._allowZoom()){o();if(a){a.remove()}}})},_allowZoom:function(){return p.currItem.type==="image"},_getItemToZoom:function(){if(p.currItem.hasSize){return p.currItem.img}else{return false}},_getOffset:function(t){var n;if(t){n=p.currItem.img}else{n=p.st.zoom.opener(p.currItem.el||p.currItem)}var r=n.offset();var i=parseInt(n.css("padding-top"),10);var s=parseInt(n.css("padding-bottom"),10);r.top-=e(window).scrollTop()-i;var o={width:n.width(),height:(v?n.innerHeight():n[0].offsetHeight)-s-i};if(U()){o["-moz-transform"]=o["transform"]="translate("+r.left+"px,"+r.top+"px)"}else{o.left=r.left;o.top=r.top}return o}}});var z="iframe",W="//about:blank",X=function(e){if(p.currTemplate[z]){var t=p.currTemplate[z].find("iframe");if(t.length){if(!e){t[0].src=W}if(p.isIE8){t.css("display",e?"block":"none")}}}};e.magnificPopup.registerModule(z,{options:{markup:'<div class="mfp-iframe-scaler">'+'<div class="mfp-close"></div>'+'<iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe>'+"</div>",srcAction:"iframe_src",patterns:{youtube:{index:"youtube.com",id:"v=",src:"//www.youtube.com/embed/%id%?autoplay=1"},vimeo:{index:"vimeo.com/",id:"/",src:"//player.vimeo.com/video/%id%?autoplay=1"},gmaps:{index:"//maps.google.",src:"%id%&output=embed"}}},proto:{initIframe:function(){p.types.push(z);x("BeforeChange",function(e,t,n){if(t!==n){if(t===z){X()}else if(n===z){X(true)}}});x(t+"."+z,function(){X()})},getIframe:function(t,n){var r=t.src;var i=p.st.iframe;e.each(i.patterns,function(){if(r.indexOf(this.index)>-1){if(this.id){if(typeof this.id==="string"){r=r.substr(r.lastIndexOf(this.id)+this.id.length,r.length)}else{r=this.id.call(this,r)}}r=this.src.replace("%id%",r);return false}});var s={};if(i.srcAction){s[i.srcAction]=r}p._parseMarkup(n,s,t);p.updateStatus("ready");return n}}});var V=function(e){var t=p.items.length;if(e>t-1){return e-t}else if(e<0){return t+e}return e},$=function(e,t,n){return e.replace("%curr%",t+1).replace("%total%",n)};e.magnificPopup.registerModule("gallery",{options:{enabled:false,arrowMarkup:'<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',preload:[0,2],navigateByImgClick:true,arrows:true,tPrev:"Previous (Left arrow key)",tNext:"Next (Right arrow key)",tCounter:"%curr% of %total%"},proto:{initGallery:function(){var n=p.st.gallery,r=".mfp-gallery",i=Boolean(e.fn.mfpFastClick);p.direction=true;if(!n||!n.enabled)return false;E+=" mfp-gallery";x(o+r,function(){if(n.navigateByImgClick){p.wrap.on("click"+r,".mfp-img",function(){if(p.items.length>1){p.next();return false}})}b.on("keydown"+r,function(e){if(e.keyCode===37){p.prev()}else if(e.keyCode===39){p.next()}})});x("UpdateStatus"+r,function(e,t){if(t.text){t.text=$(t.text,p.currItem.index,p.items.length)}});x(s+r,function(e,t,r,i){var s=p.items.length;r.counter=s>1?$(n.tCounter,i.index,s):""});x("BuildControls"+r,function(){if(p.items.length>1&&n.arrows&&!p.arrowLeft){var t=n.arrowMarkup,r=p.arrowLeft=e(t.replace("%title%",n.tPrev).replace("%dir%","left")).addClass(h),s=p.arrowRight=e(t.replace("%title%",n.tNext).replace("%dir%","right")).addClass(h);var o=i?"mfpFastClick":"click";r[o](function(){p.prev()});s[o](function(){p.next()});if(p.isIE7){T("b",r[0],false,true);T("a",r[0],false,true);T("b",s[0],false,true);T("a",s[0],false,true)}p.container.append(r.add(s))}});x(u+r,function(){if(p._preloadTimeout)clearTimeout(p._preloadTimeout);p._preloadTimeout=setTimeout(function(){p.preloadNearbyImages();p._preloadTimeout=null},16)});x(t+r,function(){b.off(r);p.wrap.off("click"+r);if(p.arrowLeft&&i){p.arrowLeft.add(p.arrowRight).destroyMfpFastClick()}p.arrowRight=p.arrowLeft=null})},next:function(){p.direction=true;p.index=V(p.index+1);p.updateItemHTML()},prev:function(){p.direction=false;p.index=V(p.index-1);p.updateItemHTML()},goTo:function(e){p.direction=e>=p.index;p.index=e;p.updateItemHTML()},preloadNearbyImages:function(){var e=p.st.gallery.preload,t=Math.min(e[0],p.items.length),n=Math.min(e[1],p.items.length),r;for(r=1;r<=(p.direction?n:t);r++){p._preloadItem(p.index+r)}for(r=1;r<=(p.direction?t:n);r++){p._preloadItem(p.index-r)}},_preloadItem:function(t){t=V(t);if(p.items[t].preloaded){return}var n=p.items[t];if(!n.parsed){n=p.parseEl(t)}N("LazyLoad",n);if(n.type==="image"){n.img=e('<img class="mfp-img" />').on("load.mfploader",function(){n.hasSize=true}).on("error.mfploader",function(){n.hasSize=true;n.loadError=true;N("LazyLoadError",n)}).attr("src",n.src)}n.preloaded=true}}});var J="retina";e.magnificPopup.registerModule(J,{options:{replaceSrc:function(e){return e.src.replace(/\.\w+$/,function(e){return"@2x"+e})},ratio:1},proto:{initRetina:function(){if(window.devicePixelRatio>1){var e=p.st.retina,t=e.ratio;t=!isNaN(t)?t:t();if(t>1){x("ImageHasSize"+"."+J,function(e,n){n.img.css({"max-width":n.img[0].naturalWidth/t,width:"100%"})});x("ElementParse"+"."+J,function(n,r){r.src=e.replaceSrc(r,t)})}}}}});(function(){var t=1e3,n="ontouchstart"in window,r=function(){g.off("touchmove"+s+" touchend"+s)},i="mfpFastClick",s="."+i;e.fn.mfpFastClick=function(i){return e(this).each(function(){var o=e(this),u;if(n){var a,f,l,c,h,p;o.on("touchstart"+s,function(e){c=false;p=1;h=e.originalEvent?e.originalEvent.touches[0]:e.touches[0];f=h.clientX;l=h.clientY;g.on("touchmove"+s,function(e){h=e.originalEvent?e.originalEvent.touches:e.touches;p=h.length;h=h[0];if(Math.abs(h.clientX-f)>10||Math.abs(h.clientY-l)>10){c=true;r()}}).on("touchend"+s,function(e){r();if(c||p>1){return}u=true;e.preventDefault();clearTimeout(a);a=setTimeout(function(){u=false},t);i()})})}o.on("click"+s,function(){if(!u){i()}})})};e.fn.destroyMfpFastClick=function(){e(this).off("touchstart"+s+" click"+s);if(n)g.off("touchmove"+s+" touchend"+s)}})()})(window.jQuery||window.Zepto);




/*
 * jQuery FlexSlider v2.1
 * Copyright 2012 WooThemes
 * Contributing Author: Tyler Smith
 */
;(function(d){d.flexslider=function(i,k){var a=d(i),c=d.extend({},d.flexslider.defaults,k),e=c.namespace,p="ontouchstart"in window||window.DocumentTouch&&document instanceof DocumentTouch,t=p?"touchend":"click",l="vertical"===c.direction,m=c.reverse,h=0<c.itemWidth,r="fade"===c.animation,s=""!==c.asNavFor,f={};d.data(i,"flexslider",a);f={init:function(){a.animating=!1;a.currentSlide=c.startAt;a.animatingTo=a.currentSlide;a.atEnd=0===a.currentSlide||a.currentSlide===a.last;a.containerSelector=c.selector.substr(0,
 c.selector.search(" "));a.slides=d(c.selector,a);a.container=d(a.containerSelector,a);a.count=a.slides.length;a.syncExists=0<d(c.sync).length;"slide"===c.animation&&(c.animation="swing");a.prop=l?"top":"marginLeft";a.args={};a.manualPause=!1;var b=a,g;if(g=!c.video)if(g=!r)if(g=c.useCSS)a:{g=document.createElement("div");var n=["perspectiveProperty","WebkitPerspective","MozPerspective","OPerspective","msPerspective"],e;for(e in n)if(void 0!==g.style[n[e]]){a.pfx=n[e].replace("Perspective","").toLowerCase();
 a.prop="-"+a.pfx+"-transform";g=!0;break a}g=!1}b.transitions=g;""!==c.controlsContainer&&(a.controlsContainer=0<d(c.controlsContainer).length&&d(c.controlsContainer));""!==c.manualControls&&(a.manualControls=0<d(c.manualControls).length&&d(c.manualControls));c.randomize&&(a.slides.sort(function(){return Math.round(Math.random())-0.5}),a.container.empty().append(a.slides));a.doMath();s&&f.asNav.setup();a.setup("init");c.controlNav&&f.controlNav.setup();c.directionNav&&f.directionNav.setup();c.keyboard&&
 (1===d(a.containerSelector).length||c.multipleKeyboard)&&d(document).bind("keyup",function(b){b=b.keyCode;if(!a.animating&&(39===b||37===b))b=39===b?a.getTarget("next"):37===b?a.getTarget("prev"):!1,a.flexAnimate(b,c.pauseOnAction)});c.mousewheel&&a.bind("mousewheel",function(b,g){b.preventDefault();var d=0>g?a.getTarget("next"):a.getTarget("prev");a.flexAnimate(d,c.pauseOnAction)});c.pausePlay&&f.pausePlay.setup();c.slideshow&&(c.pauseOnHover&&a.hover(function(){!a.manualPlay&&!a.manualPause&&a.pause()},
 function(){!a.manualPause&&!a.manualPlay&&a.play()}),0<c.initDelay?setTimeout(a.play,c.initDelay):a.play());p&&c.touch&&f.touch();(!r||r&&c.smoothHeight)&&d(window).bind("resize focus",f.resize);setTimeout(function(){c.start(a)},200)},asNav:{setup:function(){a.asNav=!0;a.animatingTo=Math.floor(a.currentSlide/a.move);a.currentItem=a.currentSlide;a.slides.removeClass(e+"active-slide").eq(a.currentItem).addClass(e+"active-slide");a.slides.click(function(b){b.preventDefault();var b=d(this),g=b.index();
 !d(c.asNavFor).data("flexslider").animating&&!b.hasClass("active")&&(a.direction=a.currentItem<g?"next":"prev",a.flexAnimate(g,c.pauseOnAction,!1,!0,!0))})}},controlNav:{setup:function(){a.manualControls?f.controlNav.setupManual():f.controlNav.setupPaging()},setupPaging:function(){var b=1,g;a.controlNavScaffold=d('<ol class="'+e+"control-nav "+e+("thumbnails"===c.controlNav?"control-thumbs":"control-paging")+'"></ol>');if(1<a.pagingCount)for(var n=0;n<a.pagingCount;n++)g="thumbnails"===c.controlNav?
 '<img src="'+a.slides.eq(n).attr("data-thumb")+'"/>':"<a>"+b+"</a>",a.controlNavScaffold.append("<li>"+g+"</li>"),b++;a.controlsContainer?d(a.controlsContainer).append(a.controlNavScaffold):a.append(a.controlNavScaffold);f.controlNav.set();f.controlNav.active();a.controlNavScaffold.delegate("a, img",t,function(b){b.preventDefault();var b=d(this),g=a.controlNav.index(b);b.hasClass(e+"active")||(a.direction=g>a.currentSlide?"next":"prev",a.flexAnimate(g,c.pauseOnAction))});p&&a.controlNavScaffold.delegate("a",
 "click touchstart",function(a){a.preventDefault()})},setupManual:function(){a.controlNav=a.manualControls;f.controlNav.active();a.controlNav.live(t,function(b){b.preventDefault();var b=d(this),g=a.controlNav.index(b);b.hasClass(e+"active")||(g>a.currentSlide?a.direction="next":a.direction="prev",a.flexAnimate(g,c.pauseOnAction))});p&&a.controlNav.live("click touchstart",function(a){a.preventDefault()})},set:function(){a.controlNav=d("."+e+"control-nav li "+("thumbnails"===c.controlNav?"img":"a"),
 a.controlsContainer?a.controlsContainer:a)},active:function(){a.controlNav.removeClass(e+"active").eq(a.animatingTo).addClass(e+"active")},update:function(b,c){1<a.pagingCount&&"add"===b?a.controlNavScaffold.append(d("<li><a>"+a.count+"</a></li>")):1===a.pagingCount?a.controlNavScaffold.find("li").remove():a.controlNav.eq(c).closest("li").remove();f.controlNav.set();1<a.pagingCount&&a.pagingCount!==a.controlNav.length?a.update(c,b):f.controlNav.active()}},directionNav:{setup:function(){var b=d('<ul class="'+
 e+'direction-nav"><li><a class="'+e+'prev" href="#">'+c.prevText+'</a></li><li><a class="'+e+'next" href="#">'+c.nextText+"</a></li></ul>");a.controlsContainer?(d(a.controlsContainer).append(b),a.directionNav=d("."+e+"direction-nav li a",a.controlsContainer)):(a.append(b),a.directionNav=d("."+e+"direction-nav li a",a));f.directionNav.update();a.directionNav.bind(t,function(b){b.preventDefault();b=d(this).hasClass(e+"next")?a.getTarget("next"):a.getTarget("prev");a.flexAnimate(b,c.pauseOnAction)});
 p&&a.directionNav.bind("click touchstart",function(a){a.preventDefault()})},update:function(){var b=e+"disabled";1===a.pagingCount?a.directionNav.addClass(b):c.animationLoop?a.directionNav.removeClass(b):0===a.animatingTo?a.directionNav.removeClass(b).filter("."+e+"prev").addClass(b):a.animatingTo===a.last?a.directionNav.removeClass(b).filter("."+e+"next").addClass(b):a.directionNav.removeClass(b)}},pausePlay:{setup:function(){var b=d('<div class="'+e+'pauseplay"><a></a></div>');a.controlsContainer?
 (a.controlsContainer.append(b),a.pausePlay=d("."+e+"pauseplay a",a.controlsContainer)):(a.append(b),a.pausePlay=d("."+e+"pauseplay a",a));f.pausePlay.update(c.slideshow?e+"pause":e+"play");a.pausePlay.bind(t,function(b){b.preventDefault();d(this).hasClass(e+"pause")?(a.manualPause=!0,a.manualPlay=!1,a.pause()):(a.manualPause=!1,a.manualPlay=!0,a.play())});p&&a.pausePlay.bind("click touchstart",function(a){a.preventDefault()})},update:function(b){"play"===b?a.pausePlay.removeClass(e+"pause").addClass(e+
 "play").text(c.playText):a.pausePlay.removeClass(e+"play").addClass(e+"pause").text(c.pauseText)}},touch:function(){function b(b){j=l?d-b.touches[0].pageY:d-b.touches[0].pageX;p=l?Math.abs(j)<Math.abs(b.touches[0].pageX-e):Math.abs(j)<Math.abs(b.touches[0].pageY-e);if(!p||500<Number(new Date)-k)b.preventDefault(),!r&&a.transitions&&(c.animationLoop||(j/=0===a.currentSlide&&0>j||a.currentSlide===a.last&&0<j?Math.abs(j)/q+2:1),a.setProps(f+j,"setTouch"))}function g(){i.removeEventListener("touchmove",
 b,!1);if(a.animatingTo===a.currentSlide&&!p&&null!==j){var h=m?-j:j,l=0<h?a.getTarget("next"):a.getTarget("prev");a.canAdvance(l)&&(550>Number(new Date)-k&&50<Math.abs(h)||Math.abs(h)>q/2)?a.flexAnimate(l,c.pauseOnAction):r||a.flexAnimate(a.currentSlide,c.pauseOnAction,!0)}i.removeEventListener("touchend",g,!1);f=j=e=d=null}var d,e,f,q,j,k,p=!1;i.addEventListener("touchstart",function(j){a.animating?j.preventDefault():1===j.touches.length&&(a.pause(),q=l?a.h:a.w,k=Number(new Date),f=h&&m&&a.animatingTo===
 a.last?0:h&&m?a.limit-(a.itemW+c.itemMargin)*a.move*a.animatingTo:h&&a.currentSlide===a.last?a.limit:h?(a.itemW+c.itemMargin)*a.move*a.currentSlide:m?(a.last-a.currentSlide+a.cloneOffset)*q:(a.currentSlide+a.cloneOffset)*q,d=l?j.touches[0].pageY:j.touches[0].pageX,e=l?j.touches[0].pageX:j.touches[0].pageY,i.addEventListener("touchmove",b,!1),i.addEventListener("touchend",g,!1))},!1)},resize:function(){!a.animating&&a.is(":visible")&&(h||a.doMath(),r?f.smoothHeight():h?(a.slides.width(a.computedW),
 a.update(a.pagingCount),a.setProps()):l?(a.viewport.height(a.h),a.setProps(a.h,"setTotal")):(c.smoothHeight&&f.smoothHeight(),a.newSlides.width(a.computedW),a.setProps(a.computedW,"setTotal")))},smoothHeight:function(b){if(!l||r){var c=r?a:a.viewport;b?c.animate({height:a.slides.eq(a.animatingTo).height()},b):c.height(a.slides.eq(a.animatingTo).height())}},sync:function(b){var g=d(c.sync).data("flexslider"),e=a.animatingTo;switch(b){case "animate":g.flexAnimate(e,c.pauseOnAction,!1,!0);break;case "play":!g.playing&&
 !g.asNav&&g.play();break;case "pause":g.pause()}}};a.flexAnimate=function(b,g,n,i,k){s&&1===a.pagingCount&&(a.direction=a.currentItem<b?"next":"prev");if(!a.animating&&(a.canAdvance(b,k)||n)&&a.is(":visible")){if(s&&i)if(n=d(c.asNavFor).data("flexslider"),a.atEnd=0===b||b===a.count-1,n.flexAnimate(b,!0,!1,!0,k),a.direction=a.currentItem<b?"next":"prev",n.direction=a.direction,Math.ceil((b+1)/a.visible)-1!==a.currentSlide&&0!==b)a.currentItem=b,a.slides.removeClass(e+"active-slide").eq(b).addClass(e+
 "active-slide"),b=Math.floor(b/a.visible);else return a.currentItem=b,a.slides.removeClass(e+"active-slide").eq(b).addClass(e+"active-slide"),!1;a.animating=!0;a.animatingTo=b;c.before(a);g&&a.pause();a.syncExists&&!k&&f.sync("animate");c.controlNav&&f.controlNav.active();h||a.slides.removeClass(e+"active-slide").eq(b).addClass(e+"active-slide");a.atEnd=0===b||b===a.last;c.directionNav&&f.directionNav.update();b===a.last&&(c.end(a),c.animationLoop||a.pause());if(r)p?(a.slides.eq(a.currentSlide).css({opacity:0,
 zIndex:1}),a.slides.eq(b).css({opacity:1,zIndex:2}),a.slides.unbind("webkitTransitionEnd transitionend"),a.slides.eq(a.currentSlide).bind("webkitTransitionEnd transitionend",function(){c.after(a)}),a.animating=!1,a.currentSlide=a.animatingTo):(a.slides.eq(a.currentSlide).fadeOut(c.animationSpeed,c.easing),a.slides.eq(b).fadeIn(c.animationSpeed,c.easing,a.wrapup));else{var q=l?a.slides.filter(":first").height():a.computedW;h?(b=c.itemWidth>a.w?2*c.itemMargin:c.itemMargin,b=(a.itemW+b)*a.move*a.animatingTo,
 b=b>a.limit&&1!==a.visible?a.limit:b):b=0===a.currentSlide&&b===a.count-1&&c.animationLoop&&"next"!==a.direction?m?(a.count+a.cloneOffset)*q:0:a.currentSlide===a.last&&0===b&&c.animationLoop&&"prev"!==a.direction?m?0:(a.count+1)*q:m?(a.count-1-b+a.cloneOffset)*q:(b+a.cloneOffset)*q;a.setProps(b,"",c.animationSpeed);if(a.transitions){if(!c.animationLoop||!a.atEnd)a.animating=!1,a.currentSlide=a.animatingTo;a.container.unbind("webkitTransitionEnd transitionend");a.container.bind("webkitTransitionEnd transitionend",
 function(){a.wrapup(q)})}else a.container.animate(a.args,c.animationSpeed,c.easing,function(){a.wrapup(q)})}c.smoothHeight&&f.smoothHeight(c.animationSpeed)}};a.wrapup=function(b){!r&&!h&&(0===a.currentSlide&&a.animatingTo===a.last&&c.animationLoop?a.setProps(b,"jumpEnd"):a.currentSlide===a.last&&(0===a.animatingTo&&c.animationLoop)&&a.setProps(b,"jumpStart"));a.animating=!1;a.currentSlide=a.animatingTo;c.after(a)};a.animateSlides=function(){a.animating||a.flexAnimate(a.getTarget("next"))};a.pause=
 function(){clearInterval(a.animatedSlides);a.playing=!1;c.pausePlay&&f.pausePlay.update("play");a.syncExists&&f.sync("pause")};a.play=function(){a.animatedSlides=setInterval(a.animateSlides,c.slideshowSpeed);a.playing=!0;c.pausePlay&&f.pausePlay.update("pause");a.syncExists&&f.sync("play")};a.canAdvance=function(b,g){var d=s?a.pagingCount-1:a.last;return g?!0:s&&a.currentItem===a.count-1&&0===b&&"prev"===a.direction?!0:s&&0===a.currentItem&&b===a.pagingCount-1&&"next"!==a.direction?!1:b===a.currentSlide&&
 !s?!1:c.animationLoop?!0:a.atEnd&&0===a.currentSlide&&b===d&&"next"!==a.direction?!1:a.atEnd&&a.currentSlide===d&&0===b&&"next"===a.direction?!1:!0};a.getTarget=function(b){a.direction=b;return"next"===b?a.currentSlide===a.last?0:a.currentSlide+1:0===a.currentSlide?a.last:a.currentSlide-1};a.setProps=function(b,g,d){var e,f=b?b:(a.itemW+c.itemMargin)*a.move*a.animatingTo;e=-1*function(){if(h)return"setTouch"===g?b:m&&a.animatingTo===a.last?0:m?a.limit-(a.itemW+c.itemMargin)*a.move*a.animatingTo:a.animatingTo===
 a.last?a.limit:f;switch(g){case "setTotal":return m?(a.count-1-a.currentSlide+a.cloneOffset)*b:(a.currentSlide+a.cloneOffset)*b;case "setTouch":return b;case "jumpEnd":return m?b:a.count*b;case "jumpStart":return m?a.count*b:b;default:return b}}()+"px";a.transitions&&(e=l?"translate3d(0,"+e+",0)":"translate3d("+e+",0,0)",d=void 0!==d?d/1E3+"s":"0s",a.container.css("-"+a.pfx+"-transition-duration",d));a.args[a.prop]=e;(a.transitions||void 0===d)&&a.container.css(a.args)};a.setup=function(b){if(r)a.slides.css({width:"100%",
 "float":"left",marginRight:"-100%",position:"relative"}),"init"===b&&(p?a.slides.css({opacity:0,display:"block",webkitTransition:"opacity "+c.animationSpeed/1E3+"s ease",zIndex:1}).eq(a.currentSlide).css({opacity:1,zIndex:2}):a.slides.eq(a.currentSlide).fadeIn(c.animationSpeed,c.easing)),c.smoothHeight&&f.smoothHeight();else{var g,n;"init"===b&&(a.viewport=d('<div class="'+e+'viewport"></div>').css({overflow:"hidden",position:"relative"}).appendTo(a).append(a.container),a.cloneCount=0,a.cloneOffset=
 0,m&&(n=d.makeArray(a.slides).reverse(),a.slides=d(n),a.container.empty().append(a.slides)));c.animationLoop&&!h&&(a.cloneCount=2,a.cloneOffset=1,"init"!==b&&a.container.find(".clone").remove(),a.container.append(a.slides.first().clone().addClass("clone")).prepend(a.slides.last().clone().addClass("clone")));a.newSlides=d(c.selector,a);g=m?a.count-1-a.currentSlide+a.cloneOffset:a.currentSlide+a.cloneOffset;l&&!h?(a.container.height(200*(a.count+a.cloneCount)+"%").css("position","absolute").width("100%"),
 setTimeout(function(){a.newSlides.css({display:"block"});a.doMath();a.viewport.height(a.h);a.setProps(g*a.h,"init")},"init"===b?100:0)):(a.container.width(200*(a.count+a.cloneCount)+"%"),a.setProps(g*a.computedW,"init"),setTimeout(function(){a.doMath();a.newSlides.css({width:a.computedW,"float":"left",display:"block"});c.smoothHeight&&f.smoothHeight()},"init"===b?100:0))}h||a.slides.removeClass(e+"active-slide").eq(a.currentSlide).addClass(e+"active-slide")};a.doMath=function(){var b=a.slides.first(),
 d=c.itemMargin,e=c.minItems,f=c.maxItems;a.w=a.width();a.h=b.height();a.boxPadding=b.outerWidth()-b.width();h?(a.itemT=c.itemWidth+d,a.minW=e?e*a.itemT:a.w,a.maxW=f?f*a.itemT:a.w,a.itemW=a.minW>a.w?(a.w-d*e)/e:a.maxW<a.w?(a.w-d*f)/f:c.itemWidth>a.w?a.w:c.itemWidth,a.visible=Math.floor(a.w/(a.itemW+d)),a.move=0<c.move&&c.move<a.visible?c.move:a.visible,a.pagingCount=Math.ceil((a.count-a.visible)/a.move+1),a.last=a.pagingCount-1,a.limit=1===a.pagingCount?0:c.itemWidth>a.w?(a.itemW+2*d)*a.count-a.w-
 d:(a.itemW+d)*a.count-a.w-d):(a.itemW=a.w,a.pagingCount=a.count,a.last=a.count-1);a.computedW=a.itemW-a.boxPadding};a.update=function(b,d){a.doMath();h||(b<a.currentSlide?a.currentSlide+=1:b<=a.currentSlide&&0!==b&&(a.currentSlide-=1),a.animatingTo=a.currentSlide);if(c.controlNav&&!a.manualControls)if("add"===d&&!h||a.pagingCount>a.controlNav.length)f.controlNav.update("add");else if("remove"===d&&!h||a.pagingCount<a.controlNav.length)h&&a.currentSlide>a.last&&(a.currentSlide-=1,a.animatingTo-=1),
 f.controlNav.update("remove",a.last);c.directionNav&&f.directionNav.update()};a.addSlide=function(b,e){var f=d(b);a.count+=1;a.last=a.count-1;l&&m?void 0!==e?a.slides.eq(a.count-e).after(f):a.container.prepend(f):void 0!==e?a.slides.eq(e).before(f):a.container.append(f);a.update(e,"add");a.slides=d(c.selector+":not(.clone)",a);a.setup();c.added(a)};a.removeSlide=function(b){var e=isNaN(b)?a.slides.index(d(b)):b;a.count-=1;a.last=a.count-1;isNaN(b)?d(b,a.slides).remove():l&&m?a.slides.eq(a.last).remove():
 a.slides.eq(b).remove();a.doMath();a.update(e,"remove");a.slides=d(c.selector+":not(.clone)",a);a.setup();c.removed(a)};f.init()};d.flexslider.defaults={namespace:"flex-",selector:".slides > li",animation:"fade",easing:"swing",direction:"horizontal",reverse:!1,animationLoop:!0,smoothHeight:!1,startAt:0,slideshow:!0,slideshowSpeed:7E3,animationSpeed:600,initDelay:0,randomize:!1,pauseOnAction:!0,pauseOnHover:!1,useCSS:!0,touch:!0,video:!1,controlNav:!0,directionNav:!0,prevText:"Previous",nextText:"Next",
 keyboard:!0,multipleKeyboard:!1,mousewheel:!1,pausePlay:!1,pauseText:"Pause",playText:"Play",controlsContainer:"",manualControls:"",sync:"",asNavFor:"",itemWidth:0,itemMargin:0,minItems:0,maxItems:0,move:0,start:function(){},before:function(){},after:function(){},end:function(){},added:function(){},removed:function(){}};d.fn.flexslider=function(i){void 0===i&&(i={});if("object"===typeof i)return this.each(function(){var a=d(this),c=a.find(i.selector?i.selector:".slides > li");1===c.length?(c.fadeIn(400),
 i.start&&i.start(a)):void 0==a.data("flexslider")&&new d.flexslider(this,i)});var k=d(this).data("flexslider");switch(i){case "play":k.play();break;case "pause":k.pause();break;case "next":k.flexAnimate(k.getTarget("next"),!0);break;case "prev":case "previous":k.flexAnimate(k.getTarget("prev"),!0);break;default:"number"===typeof i&&k.flexAnimate(i,!0)}}})(jQuery);
 
 
 

// jQuery Paginate v0.4
;(function($){$.fn.pajinate=function(options){var current_page="current_page";var items_per_page="items_per_page";var meta;var defaults={item_container_id:".content",items_per_page:10,nav_panel_id:".page_navigation",nav_info_id:".info_text",num_page_links_to_display:20,start_page:0,wrap_around:false,nav_label_first:"First",nav_label_prev:"Prev",nav_label_next:"Next",nav_label_last:"Last",nav_order:["first","prev","num","next","last"],nav_label_info:"Showing {0}-{1} of {2} results",show_first_last:true,abort_on_small_lists:false,jquery_ui:false,jquery_ui_active:"ui-state-highlight",jquery_ui_default:"ui-state-default",jquery_ui_disabled:"ui-state-disabled"};var options=$.extend(defaults,options);var $item_container;var $page_container;var $items;var $nav_panels;var total_page_no_links;var jquery_ui_default_class=options.jquery_ui?options.jquery_ui_default:"";var jquery_ui_active_class=options.jquery_ui?options.jquery_ui_active:"";var jquery_ui_disabled_class=options.jquery_ui?options.jquery_ui_disabled:"";return this.each(function(){$page_container=$(this);$item_container=$(this).find(options.item_container_id);$items=$page_container.find(options.item_container_id).children();if(options.abort_on_small_lists&&options.items_per_page>=$items.size()){return $page_container}meta=$page_container;meta.data(current_page,0);meta.data(items_per_page,options.items_per_page);var total_items=$item_container.children().size();var number_of_pages=Math.ceil(total_items/options.items_per_page);var more='<li class="disabled ellipse more"><span>...</span></li>';var less='<li class="disabled ellipse less"><span>...</span></li>';var first=!options.show_first_last?"":'<li class="first_link '+jquery_ui_default_class+'"><a href="#">'+options.nav_label_first+"</a></li>";var last=!options.show_first_last?"":'<li class="last_link '+jquery_ui_default_class+'"><a href="#">'+options.nav_label_last+"</a></li>";var navigation_html="";for(var i=0;i<options.nav_order.length;i++){switch(options.nav_order[i]){case"first":navigation_html+=first;break;case"last":navigation_html+=last;break;case"next":navigation_html+='<li class="next_link '+jquery_ui_default_class+'"><a href="#">'+options.nav_label_next+"</a></li>";break;case"prev":navigation_html+='<li class="previous_link '+jquery_ui_default_class+'"><a href="#">'+options.nav_label_prev+"</a></li>";break;case"num":navigation_html+=less;var current_link=0;while(number_of_pages>current_link){navigation_html+='<li class="page_link '+jquery_ui_default_class+'" longdesc="'+current_link+'"><a href="#">'+(current_link+1)+"</a></li>";current_link++}navigation_html+=more;break;default:break}}$nav_panels=$page_container.find(options.nav_panel_id);$nav_panels.html(navigation_html).each(function(){$(this).find(".page_link:first").addClass("first");$(this).find(".page_link:last").addClass("last")});$nav_panels.children(".ellipse").hide();$nav_panels.find(".previous_link").next().next().addClass("active "+jquery_ui_active_class);$items.hide();$items.slice(0,meta.data(items_per_page)).show();total_page_no_links=$page_container.find(options.nav_panel_id+":first").children(".page_link").size();options.num_page_links_to_display=Math.min(options.num_page_links_to_display,total_page_no_links);$nav_panels.children(".page_link").hide();$nav_panels.each(function(){$(this).children(".page_link").slice(0,options.num_page_links_to_display).show()});$page_container.find(".first_link").click(function(e){e.preventDefault();movePageNumbersRight($(this),0);gotopage(0)});$page_container.find(".last_link").click(function(e){e.preventDefault();var lastPage=total_page_no_links-1;movePageNumbersLeft($(this),lastPage);gotopage(lastPage)});$page_container.find(".previous_link").click(function(e){e.preventDefault();showPrevPage($(this))});$page_container.find(".next_link").click(function(e){e.preventDefault();showNextPage($(this))});$page_container.find(".page_link").click(function(e){e.preventDefault();gotopage($(this).attr("longdesc"))});gotopage(parseInt(options.start_page));toggleMoreLess();if(!options.wrap_around){tagNextPrev()}});function showPrevPage(e){new_page=parseInt(meta.data(current_page))-1;if($(e).siblings(".active").prev(".page_link").length==true){movePageNumbersRight(e,new_page);gotopage(new_page)}else{if(options.wrap_around){gotopage(total_page_no_links-1)}}}function showNextPage(e){new_page=parseInt(meta.data(current_page))+1;if($(e).siblings(".active").next(".page_link").length==true){movePageNumbersLeft(e,new_page);gotopage(new_page)}else{if(options.wrap_around){gotopage(0)}}}function gotopage(page_num){page_num=parseInt(page_num,10);var ipp=parseInt(meta.data(items_per_page));start_from=page_num*ipp;end_on=start_from+ipp;var items=$items.hide().slice(start_from,end_on);items.fadeIn(700);$page_container.find(options.nav_panel_id).children(".page_link[longdesc="+page_num+"]").addClass("active "+jquery_ui_active_class).siblings(".active").removeClass("active "+jquery_ui_active_class);meta.data(current_page,page_num);var $current_page=parseInt(meta.data(current_page)+1);var total_items=$item_container.children().size();var $number_of_pages=Math.ceil(total_items/options.items_per_page);$page_container.find(options.nav_info_id).html(options.nav_label_info.replace("{0}",start_from+1).replace("{1}",start_from+items.length).replace("{2}",$items.length).replace("{3}",$current_page).replace("{4}",$number_of_pages));toggleMoreLess();tagNextPrev();if(typeof(options.onPageDisplayed)!=="undefined"){options.onPageDisplayed.call(this,page_num+1)}}function movePageNumbersLeft(e,new_p){var new_page=new_p;var $current_active_link=$(e).siblings(".active");if($current_active_link.siblings(".page_link[longdesc="+new_page+"]").css("display")=="none"){$nav_panels.each(function(){$(this).children(".page_link").hide().slice(parseInt(new_page-options.num_page_links_to_display+1),new_page+1).show()})}}function movePageNumbersRight(e,new_p){var new_page=new_p;var $current_active_link=$(e).siblings(".active");if($current_active_link.siblings(".page_link[longdesc="+new_page+"]").css("display")=="none"){$nav_panels.each(function(){$(this).children(".page_link").hide().slice(new_page,new_page+parseInt(options.num_page_links_to_display)).show()})}}function toggleMoreLess(){}function tagNextPrev(){if($nav_panels.children(".last").hasClass("active")){$nav_panels.children(".next_link").add(".last_link").addClass("no_more "+jquery_ui_disabled_class)}else{$nav_panels.children(".next_link").add(".last_link").removeClass("no_more "+jquery_ui_disabled_class)}if($nav_panels.children(".first").hasClass("active")){$nav_panels.children(".previous_link").add(".first_link").addClass("no_more "+jquery_ui_disabled_class)}else{$nav_panels.children(".previous_link").add(".first_link").removeClass("no_more "+jquery_ui_disabled_class)}}}})(jQuery);
 
 
 
 
 /*
 *	jQuery carouFredSel 6.1.0
 *	Demo's and documentation:
 *	caroufredsel.frebsite.nl
 *
 *	Copyright (c) 2012 Fred Heusschen
 *	www.frebsite.nl
 *
 *	Dual licensed under the MIT and GPL licenses.
 *	http://en.wikipedia.org/wiki/MIT_License
 *	http://en.wikipedia.org/wiki/GNU_General_Public_License
 */


eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(C($){8($.1r.1v){G}$.1r.6s=$.1r.1v=C(u,w){8(1k.R==0){17(I,\'6t 57 6u 1j "\'+1k.4p+\'".\');G 1k}8(1k.R>1){G 1k.1W(C(){$(1k).1v(u,w)})}E y=1k,$13=1k[0],59=K;8(y.1m(\'5a\')){59=y.1Q(\'3p\',\'4q\');y.S(\'3p\',[\'4r\',I])}y.5b=C(o,a,b){o=3T($13,o);o.D=6v($13,o.D);o.1M=6w($13,o.1M);o.M=6x($13,o.M);o.V=5c($13,o.V);o.Z=5c($13,o.Z);o.1a=6y($13,o.1a);o.1q=6z($13,o.1q);o.1h=6A($13,o.1h);8(a){34=$.1N(I,{},$.1r.1v.5d,o)}7=$.1N(I,{},$.1r.1v.5d,o);7.d=6B(7);z.2b=(7.2b==\'4s\'||7.2b==\'1n\')?\'Z\':\'V\';E c=y.14(),2n=5e($1s,7,\'N\');8(3q(7.23)){7.23=\'7T\'+F.3U}7.3V=5f(7,2n);7.D=6C(7.D,7,c,b);7[7.d[\'N\']]=6D(7[7.d[\'N\']],7,c);7[7.d[\'1d\']]=6E(7[7.d[\'1d\']],7,c);8(7.2o){8(!3W(7[7.d[\'N\']])){7[7.d[\'N\']]=\'2J%\'}}8(3W(7[7.d[\'N\']])){z.6F=I;z.4t=7[7.d[\'N\']];7[7.d[\'N\']]=4u(2n,z.4t);8(!7.D.L){7.D.T.1c=I}}8(7.2o){7.1R=K;7.1i=[0,0,0,0];7.1A=K;7.D.T.1c=K}O{8(!7.D.L){7=6G(7,2n)}8(!7[7.d[\'N\']]){8(!7.D.T.1c&&11(7.D[7.d[\'N\']])&&7.D.1t==\'*\'){7[7.d[\'N\']]=7.D.L*7.D[7.d[\'N\']];7.1A=K}O{7[7.d[\'N\']]=\'1c\'}}8(1E(7.1A)){7.1A=(11(7[7.d[\'N\']]))?\'5g\':K}8(7.D.T.1c){7.D.L=35(c,7,0)}}8(7.D.1t!=\'*\'&&!7.D.T.1c){7.D.T.4v=7.D.L;7.D.L=3X(c,7,0)}7.D.L=2z(7.D.L,7,7.D.T.2c,$13);7.D.T.1Z=7.D.L;8(7.2o){8(!7.D.T.36){7.D.T.36=7.D.L}8(!7.D.T.1X){7.D.T.1X=7.D.L}7=5h(7,c,2n)}O{7.1i=6H(7.1i);8(7.1A==\'3r\'){7.1A=\'1n\'}O 8(7.1A==\'5i\'){7.1A=\'3a\'}1B(7.1A){Q\'5g\':Q\'1n\':Q\'3a\':8(7[7.d[\'N\']]!=\'1c\'){7=5j(7,c);7.1R=I}16;2A:7.1A=K;7.1R=(7.1i[0]==0&&7.1i[1]==0&&7.1i[2]==0&&7.1i[3]==0)?K:I;16}}8(!11(7.1M.1F)){7.1M.1F=6I}8(1E(7.1M.D)){7.1M.D=(7.2o||7.D.T.1c||7.D.1t!=\'*\')?\'L\':7.D.L}7.M=$.1N(I,{},7.1M,7.M);7.V=$.1N(I,{},7.1M,7.V);7.Z=$.1N(I,{},7.1M,7.Z);7.1a=$.1N(I,{},7.1M,7.1a);7.M=6J($13,7.M);7.V=5k($13,7.V);7.Z=5k($13,7.Z);7.1a=6K($13,7.1a);7.1q=6L($13,7.1q);7.1h=6M($13,7.1h);8(7.2p){7.2p=5l(7.2p)}8(7.M.5m){7.M.4w=7.M.5m;2K(\'M.5m\',\'M.4w\')}8(7.M.5n){7.M.4x=7.M.5n;2K(\'M.5n\',\'M.4x\')}8(7.M.5o){7.M.4y=7.M.5o;2K(\'M.5o\',\'M.4y\')}8(7.M.5p){7.M.2L=7.M.5p;2K(\'M.5p\',\'M.2L\')}};y.6N=C(){y.1m(\'5a\',I);E a=y.14(),3Y=5q(y,[\'6O\',\'6P\',\'3s\',\'3r\',\'3a\',\'5i\',\'1n\',\'3Z\',\'N\',\'1d\',\'6Q\',\'1S\',\'5r\',\'6R\']),5s=\'7U\';1B(3Y.3s){Q\'6S\':Q\'7V\':5s=3Y.3s;16}$1s.X(3Y).X({\'7W\':\'3t\',\'3s\':5s});y.1m(\'5t\',3Y).X({\'6O\':\'1n\',\'6P\':\'41\',\'3s\':\'6S\',\'3r\':0,\'3a\':\'M\',\'5i\':\'M\',\'1n\':0,\'6Q\':0,\'1S\':0,\'5r\':0,\'6R\':0});4z(a,7);5u(a,7);8(7.2o){5v(7,a)}};y.6T=C(){y.5w();y.12(H(\'5x\',F),C(e,a){e.1f();8(!z.2d){8(7.M.W){7.M.W.3b(2B(\'4A\',F))}}z.2d=I;8(7.M.1G){7.M.1G=K;y.S(H(\'3c\',F),a)}G I});y.12(H(\'5y\',F),C(e){e.1f();8(z.25){42(U)}G I});y.12(H(\'3c\',F),C(e,a,b){e.1f();1u=3u(1u);8(a&&z.25){U.2d=I;E c=2q()-U.2M;U.1F-=c;8(U.3v){U.3v.1F-=c}8(U.3w){U.3w.1F-=c}42(U,K)}8(!z.26&&!z.25){8(b){1u.3x+=2q()-1u.2M}}8(!z.26){8(7.M.W){7.M.W.3b(2B(\'6U\',F))}}z.26=I;8(7.M.4x){E d=7.M.2L-1u.3x,3d=2J-1H.2C(d*2J/7.M.2L);7.M.4x.1g($13,3d,d)}G I});y.12(H(\'1G\',F),C(e,b,c,d){e.1f();1u=3u(1u);E v=[b,c,d],t=[\'2N\',\'27\',\'3e\'],a=3f(v,t);b=a[0];c=a[1];d=a[2];8(b!=\'V\'&&b!=\'Z\'){b=z.2b}8(!11(c)){c=0}8(!1l(d)){d=K}8(d){z.2d=K;7.M.1G=I}8(!7.M.1G){e.2e();G 17(F,\'3y 4A: 2r 3g.\')}8(z.26){8(7.M.W){7.M.W.2O(2B(\'4A\',F));7.M.W.2O(2B(\'6U\',F))}}z.26=K;1u.2M=2q();E f=7.M.2L+c;43=f-1u.3x;3d=2J-1H.2C(43*2J/f);8(7.M.1e){1u.1e=7X(C(){E a=2q()-1u.2M+1u.3x,3d=1H.2C(a*2J/f);7.M.1e.4B.1g(7.M.1e.2s[0],3d)},7.M.1e.5z)}1u.M=7Y(C(){8(7.M.1e){7.M.1e.4B.1g(7.M.1e.2s[0],2J)}8(7.M.4y){7.M.4y.1g($13,3d,43)}8(z.25){y.S(H(\'1G\',F),b)}O{y.S(H(b,F),7.M)}},43);8(7.M.4w){7.M.4w.1g($13,3d,43)}G I});y.12(H(\'3h\',F),C(e){e.1f();8(U.2d){U.2d=K;z.26=K;z.25=I;U.2M=2q();2P(U)}O{y.S(H(\'1G\',F))}G I});y.12(H(\'V\',F)+\' \'+H(\'Z\',F),C(e,b,f,g,h){e.1f();8(z.2d||y.2f(\':3t\')){e.2e();G 17(F,\'3y 4A 7Z 3t: 2r 3g.\')}E i=(11(7.D.4C))?7.D.4C:7.D.L+1;8(i>J.P){e.2e();G 17(F,\'2r 6V D (\'+J.P+\' P, \'+i+\' 6W): 2r 3g.\')}E v=[b,f,g,h],t=[\'2g\',\'27/2N\',\'C\',\'3e\'],a=3f(v,t);b=a[0];f=a[1];g=a[2];h=a[3];E k=e.5A.18(F.3z.44.R);8(!1I(b)){b={}}8(1o(g)){b.3i=g}8(1l(h)){b.2Q=h}b=$.1N(I,{},7[k],b);8(b.5B&&!b.5B.1g($13,k)){e.2e();G 17(F,\'80 "5B" 81 K.\')}8(!11(f)){8(7.D.1t!=\'*\'){f=\'L\'}O{E m=[f,b.D,7[k].D];1j(E a=0,l=m.R;a<l;a++){8(11(m[a])||m[a]==\'6X\'||m[a]==\'L\'){f=m[a];16}}}1B(f){Q\'6X\':e.2e();G y.1Q(H(k+\'82\',F),[b,g]);16;Q\'L\':8(!7.D.T.1c&&7.D.1t==\'*\'){f=7.D.L}16}}8(U.2d){y.S(H(\'3h\',F));y.S(H(\'2Q\',F),[k,[b,f,g]]);e.2e();G 17(F,\'3y 83 3g.\')}8(b.1F>0){8(z.25){8(b.2Q){8(b.2Q==\'2R\'){2h=[]}8(b.2Q!=\'Y\'||2h.R==0){y.S(H(\'2Q\',F),[k,[b,f,g]])}}e.2e();G 17(F,\'3y 84 3g.\')}}1u.3x=0;y.S(H(\'6Y\'+k,F),[b,f]);8(7.2p){E s=7.2p,c=[b,f];1j(E j=0,l=s.R;j<l;j++){E d=k;8(!s[j][2]){d=(d==\'V\')?\'Z\':\'V\'}8(!s[j][1]){c[0]=s[j][0].1Q(\'3p\',[\'4D\',d])}c[1]=f+s[j][3];s[j][0].S(\'3p\',[\'6Y\'+d,c])}}G I});y.12(H(\'85\',F),C(e,b,c){e.1f();E d=y.14();8(!7.1T){8(J.Y==0){8(7.3A){y.S(H(\'Z\',F),J.P-1)}G e.2e()}}1U(d,7);8(!11(c)){8(7.D.T.1c){c=4E(d,7,J.P-1)}O 8(7.D.1t!=\'*\'){E f=(11(b.D))?b.D:5C(y,7);c=6Z(d,7,J.P-1,f)}O{c=7.D.L}c=4F(c,7,b.D,$13)}8(!7.1T){8(J.P-c<J.Y){c=J.P-J.Y}}7.D.T.1Z=7.D.L;8(7.D.T.1c){E g=2z(35(d,7,J.P-c),7,7.D.T.2c,$13);8(7.D.L+c<=g&&c<J.P){c++;g=2z(35(d,7,J.P-c),7,7.D.T.2c,$13)}7.D.L=g}O 8(7.D.1t!=\'*\'){E g=3X(d,7,J.P-c);7.D.L=2z(g,7,7.D.T.2c,$13)}1U(d,7,I);8(c==0){e.2e();G 17(F,\'0 D 45 1M: 2r 3g.\')}17(F,\'70 \'+c+\' D 5D.\');J.Y+=c;2i(J.Y>=J.P){J.Y-=J.P}8(!7.1T){8(J.Y==0&&b.4G){b.4G.1g($13,\'V\')}8(!7.3A){3B(7,J.Y,F)}}y.14().18(J.P-c,J.P).86(y);8(J.P<7.D.L+c){y.14().18(0,(7.D.L+c)-J.P).4H(I).46(y)}E d=y.14(),3j=71(d,7,c),2j=72(d,7),1Y=d.1O(c-1),20=3j.2R(),2t=2j.2R();1U(d,7);E h=0,2D=0;8(7.1A){E p=4I(2j,7);h=p[0];2D=p[1]}E i=(h<0)?7.1i[7.d[3]]:0;E j=K,2S=$();8(7.D.L<c){2S=d.18(7.D.T.1Z,c);8(b.1V==\'73\'){E k=7.D[7.d[\'N\']];j=2S;1Y=2t;5E(j);7.D[7.d[\'N\']]=\'1c\'}}E l=K,3C=2T(d.18(0,c),7,\'N\'),2k=4J(4K(2j,7,I),7,!7.1R),3D=0,28={},4L={},2u={},2U={},4M={},2V={},5F={},2W=5G(b,7,c,3C);1B(b.1V){Q\'1J\':Q\'1J-1w\':3D=2T(d.18(0,7.D.L),7,\'N\');16}8(j){7.D[7.d[\'N\']]=k}1U(d,7,I);8(2D>=0){1U(20,7,7.1i[7.d[1]])}8(h>=0){1U(1Y,7,7.1i[7.d[3]])}8(7.1A){7.1i[7.d[1]]=2D;7.1i[7.d[3]]=h}2V[7.d[\'1n\']]=-(3C-i);5F[7.d[\'1n\']]=-(3D-i);4L[7.d[\'1n\']]=2k[7.d[\'N\']];E m=C(){},1P=C(){},1C=C(){},3E=C(){},2E=C(){},5H=C(){},1D=C(){},3F=C(){},1x=C(){},1y=C(){},1K=C(){};1B(b.1V){Q\'3k\':Q\'1J\':Q\'1J-1w\':Q\'21\':Q\'21-1w\':l=y.4H(I).46($1s);16}1B(b.1V){Q\'3k\':Q\'21\':Q\'21-1w\':l.14().18(0,c).2v();l.14().18(7.D.T.1Z).2v();16;Q\'1J\':Q\'1J-1w\':l.14().18(7.D.L).2v();l.X(5F);16}y.X(2V);U=47(2W,b.2l);28[7.d[\'1n\']]=(7.1R)?7.1i[7.d[3]]:0;8(7[7.d[\'N\']]==\'1c\'||7[7.d[\'1d\']]==\'1c\'){m=C(){$1s.X(2k)};1P=C(){U.19.1b([$1s,2k])}}8(7.1R){8(2t.4N(1Y).R){2u[7.d[\'1S\']]=1Y.1m(\'29\');8(h<0){1Y.X(2u)}O{1D=C(){1Y.X(2u)};3F=C(){U.19.1b([1Y,2u])}}}1B(b.1V){Q\'1J\':Q\'1J-1w\':l.14().1O(c-1).X(2u);16}8(2t.4N(20).R){2U[7.d[\'1S\']]=20.1m(\'29\');1C=C(){20.X(2U)};3E=C(){U.19.1b([20,2U])}}8(2D>=0){4M[7.d[\'1S\']]=2t.1m(\'29\')+7.1i[7.d[1]];2E=C(){2t.X(4M)};5H=C(){U.19.1b([2t,4M])}}}1K=C(){y.X(28)};E n=7.D.L+c-J.P;1y=C(){8(n>0){y.14().18(J.P).2v();3j=$(y.14().18(J.P-(7.D.L-n)).3G().74(y.14().18(0,n).3G()))}5I(j);8(7.1R){E a=y.14().1O(7.D.L+c-1);a.X(7.d[\'1S\'],a.1m(\'29\'))}};E o=5J(3j,2S,2j,c,\'V\',2W,2k);1x=C(){5K(y,l,b);z.25=K;2a.3i=48($13,b,\'3i\',o,2a);2h=5L(y,2h,F);8(!z.26){y.S(H(\'1G\',F))}};z.25=I;1u=3u(1u);2a.3H=48($13,b,\'3H\',o,2a);1B(b.1V){Q\'41\':y.X(28);m();1C();2E();1D();1K();1y();1x();16;Q\'1w\':U.19.1b([y,{\'1L\':0},C(){m();1C();2E();1D();1K();1y();U=47(2W,b.2l);U.19.1b([y,{\'1L\':1},1x]);2P(U)}]);16;Q\'3k\':y.X({\'1L\':0});U.19.1b([l,{\'1L\':0}]);U.19.1b([y,{\'1L\':1},1x]);1P();1C();2E();1D();1K();1y();16;Q\'1J\':U.19.1b([l,28,C(){1C();2E();1D();1K();1y();1x()}]);1P();16;Q\'1J-1w\':U.19.1b([y,{\'1L\':0}]);U.19.1b([l,28,C(){y.X({\'1L\':1});1C();2E();1D();1K();1y();1x()}]);1P();16;Q\'21\':U.19.1b([l,4L,1x]);1P();1C();2E();1D();1K();1y();16;Q\'21-1w\':y.X({\'1L\':0});U.19.1b([y,{\'1L\':1}]);U.19.1b([l,4L,1x]);1P();1C();2E();1D();1K();1y();16;2A:U.19.1b([y,28,C(){1y();1x()}]);1P();3E();5H();3F();16}2P(U);5M(7.23,y,F);y.S(H(\'3I\',F),[K,2k]);G I});y.12(H(\'87\',F),C(e,c,d){e.1f();E f=y.14();8(!7.1T){8(J.Y==7.D.L){8(7.3A){y.S(H(\'V\',F),J.P-1)}G e.2e()}}1U(f,7);8(!11(d)){8(7.D.1t!=\'*\'){E g=(11(c.D))?c.D:5C(y,7);d=75(f,7,0,g)}O{d=7.D.L}d=4F(d,7,c.D,$13)}E h=(J.Y==0)?J.P:J.Y;8(!7.1T){8(7.D.T.1c){E i=35(f,7,d),g=4E(f,7,h-1)}O{E i=7.D.L,g=7.D.L}8(d+i>h){d=h-g}}7.D.T.1Z=7.D.L;8(7.D.T.1c){E i=2z(5N(f,7,d,h),7,7.D.T.2c,$13);2i(7.D.L-d>=i&&d<J.P){d++;i=2z(5N(f,7,d,h),7,7.D.T.2c,$13)}7.D.L=i}O 8(7.D.1t!=\'*\'){E i=3X(f,7,d);7.D.L=2z(i,7,7.D.T.2c,$13)}1U(f,7,I);8(d==0){e.2e();G 17(F,\'0 D 45 1M: 2r 3g.\')}17(F,\'70 \'+d+\' D 76.\');J.Y-=d;2i(J.Y<0){J.Y+=J.P}8(!7.1T){8(J.Y==7.D.L&&c.4G){c.4G.1g($13,\'Z\')}8(!7.3A){3B(7,J.Y,F)}}8(J.P<7.D.L+d){y.14().18(0,(7.D.L+d)-J.P).4H(I).46(y)}E f=y.14(),3j=77(f,7),2j=78(f,7,d),1Y=f.1O(d-1),20=3j.2R(),2t=2j.2R();1U(f,7);E j=0,2D=0;8(7.1A){E p=4I(2j,7);j=p[0];2D=p[1]}E k=K,2S=$();8(7.D.T.1Z<d){2S=f.18(7.D.T.1Z,d);8(c.1V==\'73\'){E l=7.D[7.d[\'N\']];k=2S;1Y=20;5E(k);7.D[7.d[\'N\']]=\'1c\'}}E m=K,3C=2T(f.18(0,d),7,\'N\'),2k=4J(4K(2j,7,I),7,!7.1R),3D=0,28={},4O={},2u={},2U={},2V={},2W=5G(c,7,d,3C);1B(c.1V){Q\'21\':Q\'21-1w\':3D=2T(f.18(0,7.D.T.1Z),7,\'N\');16}8(k){7.D[7.d[\'N\']]=l}8(7.1A){8(7.1i[7.d[1]]<0){7.1i[7.d[1]]=0}}1U(f,7,I);1U(20,7,7.1i[7.d[1]]);8(7.1A){7.1i[7.d[1]]=2D;7.1i[7.d[3]]=j}2V[7.d[\'1n\']]=(7.1R)?7.1i[7.d[3]]:0;E n=C(){},1P=C(){},1C=C(){},3E=C(){},1D=C(){},3F=C(){},1x=C(){},1y=C(){},1K=C(){};1B(c.1V){Q\'3k\':Q\'1J\':Q\'1J-1w\':Q\'21\':Q\'21-1w\':m=y.4H(I).46($1s);m.14().18(7.D.T.1Z).2v();16}1B(c.1V){Q\'3k\':Q\'1J\':Q\'1J-1w\':y.X(\'3Z\',1);m.X(\'3Z\',0);16}U=47(2W,c.2l);28[7.d[\'1n\']]=-3C;4O[7.d[\'1n\']]=-3D;8(j<0){28[7.d[\'1n\']]+=j}8(7[7.d[\'N\']]==\'1c\'||7[7.d[\'1d\']]==\'1c\'){n=C(){$1s.X(2k)};1P=C(){U.19.1b([$1s,2k])}}8(7.1R){E o=2t.1m(\'29\');8(2D>=0){o+=7.1i[7.d[1]]}2t.X(7.d[\'1S\'],o);8(1Y.4N(20).R){2U[7.d[\'1S\']]=20.1m(\'29\')}1C=C(){20.X(2U)};3E=C(){U.19.1b([20,2U])};E q=1Y.1m(\'29\');8(j>0){q+=7.1i[7.d[3]]}2u[7.d[\'1S\']]=q;1D=C(){1Y.X(2u)};3F=C(){U.19.1b([1Y,2u])}}1K=C(){y.X(2V)};E r=7.D.L+d-J.P;1y=C(){8(r>0){y.14().18(J.P).2v()}E a=y.14().18(0,d).46(y).2R();8(r>0){2j=3J(f,7)}5I(k);8(7.1R){8(J.P<7.D.L+d){E b=y.14().1O(7.D.L-1);b.X(7.d[\'1S\'],b.1m(\'29\')+7.1i[7.d[3]])}a.X(7.d[\'1S\'],a.1m(\'29\'))}};E s=5J(3j,2S,2j,d,\'Z\',2W,2k);1x=C(){y.X(\'3Z\',y.1m(\'5t\').3Z);5K(y,m,c);z.25=K;2a.3i=48($13,c,\'3i\',s,2a);2h=5L(y,2h,F);8(!z.26){y.S(H(\'1G\',F))}};z.25=I;1u=3u(1u);2a.3H=48($13,c,\'3H\',s,2a);1B(c.1V){Q\'41\':y.X(28);n();1C();1D();1K();1y();1x();16;Q\'1w\':U.19.1b([y,{\'1L\':0},C(){n();1C();1D();1K();1y();U=47(2W,c.2l);U.19.1b([y,{\'1L\':1},1x]);2P(U)}]);16;Q\'3k\':y.X({\'1L\':0});U.19.1b([m,{\'1L\':0}]);U.19.1b([y,{\'1L\':1},1x]);1P();1C();1D();1K();1y();16;Q\'1J\':y.X(7.d[\'1n\'],$1s[7.d[\'N\']]());U.19.1b([y,2V,1x]);1P();1C();1D();1y();16;Q\'1J-1w\':y.X(7.d[\'1n\'],$1s[7.d[\'N\']]());U.19.1b([m,{\'1L\':0}]);U.19.1b([y,2V,1x]);1P();1C();1D();1y();16;Q\'21\':U.19.1b([m,4O,1x]);1P();1C();1D();1K();1y();16;Q\'21-1w\':y.X({\'1L\':0});U.19.1b([y,{\'1L\':1}]);U.19.1b([m,4O,1x]);1P();1C();1D();1K();1y();16;2A:U.19.1b([y,28,C(){1K();1y();1x()}]);1P();3E();3F();16}2P(U);5M(7.23,y,F);y.S(H(\'3I\',F),[K,2k]);G I});y.12(H(\'3l\',F),C(e,b,c,d,f,g,h){e.1f();E v=[b,c,d,f,g,h],t=[\'2N/27/2g\',\'27\',\'3e\',\'2g\',\'2N\',\'C\'],a=3f(v,t);f=a[3];g=a[4];h=a[5];b=3K(a[0],a[1],a[2],J,y);8(b==0){G K}8(!1I(f)){f=K}8(g!=\'V\'&&g!=\'Z\'){8(7.1T){g=(b<=J.P/2)?\'Z\':\'V\'}O{g=(J.Y==0||J.Y>b)?\'Z\':\'V\'}}8(g==\'V\'){b=J.P-b}y.S(H(g,F),[f,b,h]);G I});y.12(H(\'88\',F),C(e,a,b){e.1f();E c=y.1Q(H(\'4a\',F));G y.1Q(H(\'5O\',F),[c-1,a,\'V\',b])});y.12(H(\'89\',F),C(e,a,b){e.1f();E c=y.1Q(H(\'4a\',F));G y.1Q(H(\'5O\',F),[c+1,a,\'Z\',b])});y.12(H(\'5O\',F),C(e,a,b,c,d){e.1f();8(!11(a)){a=y.1Q(H(\'4a\',F))}E f=7.1a.D||7.D.L,1X=1H.2C(J.P/f)-1;8(a<0){a=1X}8(a>1X){a=0}G y.1Q(H(\'3l\',F),[a*f,0,I,b,c,d])});y.12(H(\'79\',F),C(e,s){e.1f();8(s){s=3K(s,0,I,J,y)}O{s=0}s+=J.Y;8(s!=0){8(J.P>0){2i(s>J.P){s-=J.P}}y.8a(y.14().18(s,J.P))}G I});y.12(H(\'2p\',F),C(e,s){e.1f();8(s){s=5l(s)}O 8(7.2p){s=7.2p}O{G 17(F,\'6t 8b 45 2p.\')}E n=y.1Q(H(\'4q\',F)),x=I;1j(E j=0,l=s.R;j<l;j++){8(!s[j][0].1Q(H(\'3l\',F),[n,s[j][3],I])){x=K}}G x});y.12(H(\'2Q\',F),C(e,a,b){e.1f();8(1o(a)){a.1g($13,2h)}O 8(2X(a)){2h=a}O 8(!1E(a)){2h.1b([a,b])}G 2h});y.12(H(\'8c\',F),C(e,b,c,d,f){e.1f();E v=[b,c,d,f],t=[\'2N/2g\',\'2N/27/2g\',\'3e\',\'27\'],a=3f(v,t);b=a[0];c=a[1];d=a[2];f=a[3];8(1I(b)&&!2w(b)){b=$(b)}O 8(1p(b)){b=$(b)}8(!2w(b)||b.R==0){G 17(F,\'2r a 5P 2g.\')}8(1E(c)){c=\'4b\'}4z(b,7);5u(b,7);E g=c,4c=\'4c\';8(c==\'4b\'){8(d){8(J.Y==0){c=J.P-1;4c=\'7a\'}O{c=J.Y;J.Y+=b.R}8(c<0){c=0}}O{c=J.P-1;4c=\'7a\'}}O{c=3K(c,f,d,J,y)}E h=y.14().1O(c);8(h.R){h[4c](b)}O{17(F,\'8d 8e-3s 4N 6u! 8f 8g 45 3L 4b.\');y.7b(b)}8(g!=\'4b\'&&!d){8(c<J.Y){J.Y+=b.R}}J.P=y.14().R;8(J.Y>=J.P){J.Y-=J.P}y.S(H(\'4P\',F));y.S(H(\'5Q\',F));G I});y.12(H(\'7c\',F),C(e,c,d,f){e.1f();E v=[c,d,f],t=[\'2N/27/2g\',\'3e\',\'27\'],a=3f(v,t);c=a[0];d=a[1];f=a[2];E g=K;8(c 2Y $&&c.R>1){h=$();c.1W(C(i,a){E b=y.S(H(\'7c\',F),[$(1k),d,f]);8(b)h=h.8h(b)});G h}8(1E(c)||c==\'4b\'){h=y.14().2R()}O{c=3K(c,f,d,J,y);E h=y.14().1O(c);8(h.R){8(c<J.Y)J.Y-=h.R}}8(h&&h.R){h.8i();J.P=y.14().R;y.S(H(\'4P\',F))}G h});y.12(H(\'3H\',F)+\' \'+H(\'3i\',F),C(e,a){e.1f();E b=e.5A.18(F.3z.44.R);8(2X(a)){2a[b]=a}8(1o(a)){2a[b].1b(a)}G 2a[b]});y.12(H(\'4q\',F),C(e,a){e.1f();8(J.Y==0){E b=0}O{E b=J.P-J.Y}8(1o(a)){a.1g($13,b)}G b});y.12(H(\'4a\',F),C(e,a){e.1f();E b=7.1a.D||7.D.L,1X=1H.2C(J.P/b-1),2m;8(J.Y==0){2m=0}O 8(J.Y<J.P%b){2m=0}O 8(J.Y==b&&!7.1T){2m=1X}O{2m=1H.7d((J.P-J.Y)/b)}8(2m<0){2m=0}8(2m>1X){2m=1X}8(1o(a)){a.1g($13,2m)}G 2m});y.12(H(\'8j\',F),C(e,a){e.1f();E b=3J(y.14(),7);8(1o(a)){a.1g($13,b)}G b});y.12(H(\'18\',F),C(e,f,l,b){e.1f();8(J.P==0){G K}E v=[f,l,b],t=[\'27\',\'27\',\'C\'],a=3f(v,t);f=(11(a[0]))?a[0]:0;l=(11(a[1]))?a[1]:J.P;b=a[2];f+=J.Y;l+=J.Y;8(D.P>0){2i(f>J.P){f-=J.P}2i(l>J.P){l-=J.P}2i(f<0){f+=J.P}2i(l<0){l+=J.P}}E c=y.14(),$i;8(l>f){$i=c.18(f,l)}O{$i=$(c.18(f,J.P).3G().74(c.18(0,l).3G()))}8(1o(b)){b.1g($13,$i)}G $i});y.12(H(\'26\',F)+\' \'+H(\'2d\',F)+\' \'+H(\'25\',F),C(e,a){e.1f();E b=e.5A.18(F.3z.44.R),5R=z[b];8(1o(a)){a.1g($13,5R)}G 5R});y.12(H(\'4D\',F),C(e,a,b,c){e.1f();E d=K;8(1o(a)){a.1g($13,7)}O 8(1I(a)){34=$.1N(I,{},34,a);8(b!==K)d=I;O 7=$.1N(I,{},7,a)}O 8(!1E(a)){8(1o(b)){E f=4Q(\'7.\'+a);8(1E(f)){f=\'\'}b.1g($13,f)}O 8(!1E(b)){8(2Z c!==\'3e\')c=I;4Q(\'34.\'+a+\' = b\');8(c!==K)d=I;O 4Q(\'7.\'+a+\' = b\')}O{G 4Q(\'7.\'+a)}}8(d){1U(y.14(),7);y.5b(34);y.5S();E g=4R(y,7);y.S(H(\'3I\',F),[I,g])}G 7});y.12(H(\'5Q\',F),C(e,a,b){e.1f();8(1E(a)){a=$(\'8k\')}O 8(1p(a)){a=$(a)}8(!2w(a)||a.R==0){G 17(F,\'2r a 5P 2g.\')}8(!1p(b)){b=\'a.6s\'}a.8l(b).1W(C(){E h=1k.7e||\'\';8(h.R>0&&y.14().7f($(h))!=-1){$(1k).22(\'5T\').5T(C(e){e.2F();y.S(H(\'3l\',F),h)})}});G I});y.12(H(\'3I\',F),C(e,b,c){e.1f();8(!7.1a.1z){G}E d=7.1a.D||7.D.L,4S=1H.2C(J.P/d);8(b){8(7.1a.3M){7.1a.1z.14().2v();7.1a.1z.1W(C(){1j(E a=0;a<4S;a++){E i=y.14().1O(3K(a*d,0,I,J,y));$(1k).7b(7.1a.3M.1g(i[0],a+1))}})}7.1a.1z.1W(C(){$(1k).14().22(7.1a.3N).1W(C(a){$(1k).12(7.1a.3N,C(e){e.2F();y.S(H(\'3l\',F),[a*d,-7.1a.4T,I,7.1a])})})})}E f=y.1Q(H(\'4a\',F))+7.1a.4T;8(f>=4S){f=0}8(f<0){f=4S-1}7.1a.1z.1W(C(){$(1k).14().2O(2B(\'7g\',F)).1O(f).3b(2B(\'7g\',F))});G I});y.12(H(\'4P\',F),C(e){E a=7.D.L,2G=y.14(),2n=5e($1s,7,\'N\');J.P=2G.R;8(z.4t){7.3V=2n;7[7.d[\'N\']]=4u(2n,z.4t)}O{7.3V=5f(7,2n)}8(7.2o){7.D.N=7.D.3O.N;7.D.1d=7.D.3O.1d;7=5h(7,2G,2n);a=7.D.L;5v(7,2G)}O 8(7.D.T.1c){a=35(2G,7,0)}O 8(7.D.1t!=\'*\'){a=3X(2G,7,0)}8(!7.1T&&J.Y!=0&&a>J.Y){8(7.D.T.1c){E b=4E(2G,7,J.Y)-J.Y}O 8(7.D.1t!=\'*\'){E b=7h(2G,7,J.Y)-J.Y}O{E b=7.D.L-J.Y}17(F,\'8m 8n-1T: 8o \'+b+\' D 5D.\');y.S(H(\'V\',F),b)}7.D.L=2z(a,7,7.D.T.2c,$13);7.D.T.1Z=7.D.L;7=5j(7,2G);E c=4R(y,7);y.S(H(\'3I\',F),[I,c]);4U(7,J.P,F);3B(7,J.Y,F);G c});y.12(H(\'4r\',F),C(e,a){e.1f();1u=3u(1u);y.1m(\'5a\',K);y.S(H(\'5y\',F));8(a){y.S(H(\'79\',F))}1U(y.14(),7);8(7.2o){y.14().1W(C(){$(1k).X($(1k).1m(\'7i\'))})}y.X(y.1m(\'5t\'));y.5w();y.5U();$1s.8p(y);G I});y.12(H(\'17\',F),C(e){17(F,\'3y N: \'+7.N);17(F,\'3y 1d: \'+7.1d);17(F,\'7j 8q: \'+7.D.N);17(F,\'7j 8r: \'+7.D.1d);17(F,\'4d 4e D L: \'+7.D.L);8(7.M.1G){17(F,\'4d 4e D 5V 8s: \'+7.M.D)}8(7.V.W){17(F,\'4d 4e D 5V 5D: \'+7.V.D)}8(7.Z.W){17(F,\'4d 4e D 5V 76: \'+7.Z.D)}G F.17});y.12(\'3p\',C(e,n,o){e.1f();G y.1Q(H(n,F),o)})};y.5w=C(){y.22(H(\'\',F));y.22(H(\'\',F,K));y.22(\'3p\')};y.5S=C(){y.5U();4U(7,J.P,F);3B(7,J.Y,F);8(7.M.2H){E b=3P(7.M.2H);$1s.12(H(\'4V\',F,K),C(){y.S(H(\'3c\',F),b)}).12(H(\'4W\',F,K),C(){y.S(H(\'3h\',F))})}8(7.M.W){7.M.W.12(H(7.M.3N,F,K),C(e){e.2F();E a=K,b=2x;8(z.26){a=\'1G\'}O 8(7.M.4X){a=\'3c\';b=3P(7.M.4X)}8(a){y.S(H(a,F),b)}})}8(7.V.W){7.V.W.12(H(7.V.3N,F,K),C(e){e.2F();y.S(H(\'V\',F))});8(7.V.2H){E b=3P(7.V.2H);7.V.W.12(H(\'4V\',F,K),C(){y.S(H(\'3c\',F),b)}).12(H(\'4W\',F,K),C(){y.S(H(\'3h\',F))})}}8(7.Z.W){7.Z.W.12(H(7.Z.3N,F,K),C(e){e.2F();y.S(H(\'Z\',F))});8(7.Z.2H){E b=3P(7.Z.2H);7.Z.W.12(H(\'4V\',F,K),C(){y.S(H(\'3c\',F),b)}).12(H(\'4W\',F,K),C(){y.S(H(\'3h\',F))})}}8(7.1a.1z){8(7.1a.2H){E b=3P(7.1a.2H);7.1a.1z.12(H(\'4V\',F,K),C(){y.S(H(\'3c\',F),b)}).12(H(\'4W\',F,K),C(){y.S(H(\'3h\',F))})}}8(7.V.31||7.Z.31){$(4f).12(H(\'7k\',F,K,I,I),C(e){E k=e.7l;8(k==7.Z.31){e.2F();y.S(H(\'Z\',F))}8(k==7.V.31){e.2F();y.S(H(\'V\',F))}})}8(7.1a.4Y){$(4f).12(H(\'7k\',F,K,I,I),C(e){E k=e.7l;8(k>=49&&k<58){k=(k-49)*7.D.L;8(k<=J.P){e.2F();y.S(H(\'3l\',F),[k,0,I,7.1a])}}})}8(7.V.4Z||7.Z.4Z){2K(\'3L 4g-7m\',\'3L 8t-7m\');8($.1r.4g){E c=(7.V.4Z)?C(){y.S(H(\'V\',F))}:2x,4h=(7.Z.4Z)?C(){y.S(H(\'Z\',F))}:2x;8(4h||4h){8(!z.4g){z.4g=I;E d={\'8u\':30,\'8v\':30,\'8w\':I};1B(7.2b){Q\'4s\':Q\'5W\':d.8x=c;d.8y=4h;16;2A:d.8z=4h;d.8A=c}$1s.4g(d)}}}}8($.1r.1q){E f=\'8B\'8C 3m;8((f&&7.1q.4i)||(!f&&7.1q.5X)){E g=$.1N(I,{},7.V,7.1q),7n=$.1N(I,{},7.Z,7.1q),5Y=C(){y.S(H(\'V\',F),[g])},5Z=C(){y.S(H(\'Z\',F),[7n])};1B(7.2b){Q\'4s\':Q\'5W\':7.1q.2I.8D=5Z;7.1q.2I.8E=5Y;16;2A:7.1q.2I.8F=5Z;7.1q.2I.8G=5Y}8(z.1q){y.1q(\'4r\')}$1s.1q(7.1q.2I);$1s.X(\'7o\',\'8H\');z.1q=I}}8($.1r.1h){8(7.V.1h){2K(\'7p V.1h 7q\',\'3L 1h 4D 2g\');7.V.1h=2x;7.1h={D:61(7.V.1h)}}8(7.Z.1h){2K(\'7p Z.1h 7q\',\'3L 1h 4D 2g\');7.Z.1h=2x;7.1h={D:61(7.Z.1h)}}8(7.1h){E h=$.1N(I,{},7.V,7.1h),7r=$.1N(I,{},7.Z,7.1h);8(z.1h){$1s.22(H(\'1h\',F,K))}$1s.12(H(\'1h\',F,K),C(e,a){e.2F();8(a>0){y.S(H(\'V\',F),[h])}O{y.S(H(\'Z\',F),[7r])}});z.1h=I}}8(7.M.1G){y.S(H(\'1G\',F),7.M.62)}8(z.6F){E i=C(e){y.S(H(\'5y\',F));8(7.M.63&&!z.26){y.S(H(\'1G\',F))}1U(y.14(),7);y.S(H(\'4P\',F))};E j=$(3m),4j=2x;8($.64&&F.65==\'64\'){4j=$.64(8I,i)}O 8($.51&&F.65==\'51\'){4j=$.51(8J,i)}O{E l=0,66=0;4j=C(){E a=j.N(),68=j.1d();8(a!=l||68!=66){i();l=a;66=68}}}j.12(H(\'8K\',F,K,I,I),4j)}};y.5U=C(){E a=H(\'\',F),3Q=H(\'\',F,K);69=H(\'\',F,K,I,I);$(4f).22(69);$(3m).22(69);$1s.22(3Q);8(7.M.W){7.M.W.22(3Q)}8(7.V.W){7.V.W.22(3Q)}8(7.Z.W){7.Z.W.22(3Q)}8(7.1a.1z){7.1a.1z.22(3Q);8(7.1a.3M){7.1a.1z.14().2v()}}8(z.1q){y.1q(\'4r\');$1s.X(\'7o\',\'2A\');z.1q=K}8(z.1h){z.1h=K}4U(7,\'4k\',F);3B(7,\'2O\',F)};8(1l(w)){w={\'17\':w}}E z={\'2b\':\'Z\',\'26\':I,\'25\':K,\'2d\':K,\'1h\':K,\'1q\':K},J={\'P\':y.14().R,\'Y\':0},1u={\'M\':2x,\'1e\':2x,\'2M\':2q(),\'3x\':0},U={\'2d\':K,\'1F\':0,\'2M\':0,\'2l\':\'\',\'19\':[]},2a={\'3H\':[],\'3i\':[]},2h=[],F=$.1N(I,{},$.1r.1v.7s,w),7={},34=$.1N(I,{},u),$1s=y.8L(\'<\'+F.6a.57+\' 8M="\'+F.6a.7t+\'" />\').6b();F.4p=y.4p;F.3U=$.1r.1v.3U++;y.5b(34,I,59);y.6N();y.6T();y.5S();8(2X(7.D.3n)){E A=7.D.3n}O{E A=[];8(7.D.3n!=0){A.1b(7.D.3n)}}8(7.23){A.8N(4l(7u(7.23),10))}8(A.R>0){1j(E a=0,l=A.R;a<l;a++){E s=A[a];8(s==0){6c}8(s===I){s=3m.8O.7e;8(s.R<1){6c}}O 8(s===\'7v\'){s=1H.4m(1H.7v()*J.P)}8(y.1Q(H(\'3l\',F),[s,0,I,{1V:\'41\'}])){16}}}E B=4R(y,7),7w=3J(y.14(),7);8(7.7x){7.7x.1g($13,{\'N\':B.N,\'1d\':B.1d,\'D\':7w})}y.S(H(\'3I\',F),[I,B]);y.S(H(\'5Q\',F));8(F.17){y.S(H(\'17\',F))}G y};$.1r.1v.3U=1;$.1r.1v.5d={\'2p\':K,\'3A\':I,\'1T\':I,\'2o\':K,\'2b\':\'1n\',\'D\':{\'3n\':0},\'1M\':{\'2l\':\'8P\',\'1F\':6I,\'2H\':K,\'3N\':\'5T\',\'2Q\':K}};$.1r.1v.7s={\'17\':K,\'65\':\'51\',\'3z\':{\'44\':\'\',\'7y\':\'8Q\'},\'6a\':{\'57\':\'8R\',\'7t\':\'8S\'},\'6d\':{}};$.1r.1v.7z=C(a){G\'<a 8T="#"><7A>\'+a+\'</7A></a>\'};$.1r.1v.7B=C(a){$(1k).X(\'N\',a+\'%\')};$.1r.1v.23={3G:C(n){n+=\'=\';E b=4f.23.3R(\';\');1j(E a=0,l=b.R;a<l;a++){E c=b[a];2i(c.8U(0)==\' \'){c=c.18(1)}8(c.3S(n)==0){G c.18(n.R)}}G 0},6e:C(n,v,d){E e="";8(d){E a=6f 7C();a.8V(a.2q()+(d*24*60*60*8W));e="; 8X="+a.8Y()}4f.23=n+\'=\'+v+e+\'; 8Z=/\'},2v:C(n){$.1r.1v.23.6e(n,"",-1)}};C 47(d,e){G{19:[],1F:d,90:d,2l:e,2M:2q()}}C 2P(s){8(1I(s.3v)){2P(s.3v)}1j(E a=0,l=s.19.R;a<l;a++){E b=s.19[a];8(!b){6c}8(b[3]){b[0].5x()}b[0].91(b[1],{92:b[2],1F:s.1F,2l:s.2l})}8(1I(s.3w)){2P(s.3w)}}C 42(s,c){8(!1l(c)){c=I}8(1I(s.3v)){42(s.3v,c)}1j(E a=0,l=s.19.R;a<l;a++){E b=s.19[a];b[0].5x(I);8(c){b[0].X(b[1]);8(1o(b[2])){b[2]()}}}8(1I(s.3w)){42(s.3w,c)}}C 5K(a,b,o){8(b){b.2v()}1B(o.1V){Q\'1w\':Q\'3k\':Q\'1J-1w\':Q\'21-1w\':a.X(\'1t\',\'\');16}}C 48(d,o,b,a,c){8(o[b]){o[b].1g(d,a)}8(c[b].R){1j(E i=0,l=c[b].R;i<l;i++){c[b][i].1g(d,a)}}G[]}C 5L(a,q,c){8(q.R){a.S(H(q[0][0],c),q[0][1]);q.93()}G q}C 5E(b){b.1W(C(){E a=$(1k);a.1m(\'7D\',a.2f(\':3t\')).4k()})}C 5I(b){8(b){b.1W(C(){E a=$(1k);8(!a.1m(\'7D\')){a.4n()}})}}C 3u(t){8(t.M){94(t.M)}8(t.1e){95(t.1e)}G t}C 5J(a,b,c,d,e,f,g){G{\'N\':g.N,\'1d\':g.1d,\'D\':{\'1Z\':a,\'96\':b,\'L\':c,\'6f\':c},\'1M\':{\'D\':d,\'2b\':e,\'1F\':f}}}C 5G(a,o,b,c){E d=a.1F;8(a.1V==\'41\'){G 0}8(d==\'M\'){d=o.1M.1F/o.1M.D*b}O 8(d<10){d=c/d}8(d<1){G 0}8(a.1V==\'1w\'){d=d/2}G 1H.7d(d)}C 4U(o,t,c){E a=(11(o.D.4C))?o.D.4C:o.D.L+1;8(t==\'4n\'||t==\'4k\'){E f=t}O 8(a>t){17(c,\'2r 6V D (\'+t+\' P, \'+a+\' 6W): 97 98.\');E f=\'4k\'}O{E f=\'4n\'}E s=(f==\'4n\')?\'2O\':\'3b\',h=2B(\'3t\',c);8(o.M.W){o.M.W[f]()[s](h)}8(o.V.W){o.V.W[f]()[s](h)}8(o.Z.W){o.Z.W[f]()[s](h)}8(o.1a.1z){o.1a.1z[f]()[s](h)}}C 3B(o,f,c){8(o.1T||o.3A)G;E a=(f==\'2O\'||f==\'3b\')?f:K,52=2B(\'99\',c);8(o.M.W&&a){o.M.W[a](52)}8(o.V.W){E b=a||(f==0)?\'3b\':\'2O\';o.V.W[b](52)}8(o.Z.W){E b=a||(f==o.D.L)?\'3b\':\'2O\';o.Z.W[b](52)}}C 3T(a,b){8(1o(b)){b=b.1g(a)}O 8(1E(b)){b={}}G b}C 6v(a,b){b=3T(a,b);8(11(b)){b={\'L\':b}}O 8(b==\'1c\'){b={\'L\':b,\'N\':b,\'1d\':b}}O 8(!1I(b)){b={}}G b}C 6w(a,b){b=3T(a,b);8(11(b)){8(b<=50){b={\'D\':b}}O{b={\'1F\':b}}}O 8(1p(b)){b={\'2l\':b}}O 8(!1I(b)){b={}}G b}C 53(a,b){b=3T(a,b);8(1p(b)){E c=6g(b);8(c==-1){b=$(b)}O{b=c}}G b}C 6x(a,b){b=53(a,b);8(2w(b)){b={\'W\':b}}O 8(1l(b)){b={\'1G\':b}}O 8(11(b)){b={\'2L\':b}}8(b.1e){8(1p(b.1e)||2w(b.1e)){b.1e={\'2s\':b.1e}}}G b}C 6J(a,b){8(1o(b.W)){b.W=b.W.1g(a)}8(1p(b.W)){b.W=$(b.W)}8(!1l(b.1G)){b.1G=I}8(!11(b.62)){b.62=0}8(1E(b.4X)){b.4X=I}8(!1l(b.63)){b.63=I}8(!11(b.2L)){b.2L=(b.1F<10)?9a:b.1F*5}8(b.1e){8(1o(b.1e.2s)){b.1e.2s=b.1e.2s.1g(a)}8(1p(b.1e.2s)){b.1e.2s=$(b.1e.2s)}8(b.1e.2s){8(!1o(b.1e.4B)){b.1e.4B=$.1r.1v.7B}8(!11(b.1e.5z)){b.1e.5z=50}}O{b.1e=K}}G b}C 5c(a,b){b=53(a,b);8(2w(b)){b={\'W\':b}}O 8(11(b)){b={\'31\':b}}G b}C 5k(a,b){8(1o(b.W)){b.W=b.W.1g(a)}8(1p(b.W)){b.W=$(b.W)}8(1p(b.31)){b.31=6g(b.31)}G b}C 6y(a,b){b=53(a,b);8(2w(b)){b={\'1z\':b}}O 8(1l(b)){b={\'4Y\':b}}G b}C 6K(a,b){8(1o(b.1z)){b.1z=b.1z.1g(a)}8(1p(b.1z)){b.1z=$(b.1z)}8(!11(b.D)){b.D=K}8(!1l(b.4Y)){b.4Y=K}8(!1o(b.3M)&&!54(b.3M)){b.3M=$.1r.1v.7z}8(!11(b.4T)){b.4T=0}G b}C 6z(a,b){8(1o(b)){b=b.1g(a)}8(1E(b)){b={\'4i\':K}}8(3q(b)){b={\'4i\':b}}O 8(11(b)){b={\'D\':b}}G b}C 6L(a,b){8(!1l(b.4i)){b.4i=I}8(!1l(b.5X)){b.5X=K}8(!1I(b.2I)){b.2I={}}8(!1l(b.2I.7E)){b.2I.7E=K}G b}C 6A(a,b){8(1o(b)){b=b.1g(a)}8(3q(b)){b={}}O 8(11(b)){b={\'D\':b}}O 8(1E(b)){b=K}G b}C 6M(a,b){G b}C 3K(a,b,c,d,e){8(1p(a)){a=$(a,e)}8(1I(a)){a=$(a,e)}8(2w(a)){a=e.14().7f(a);8(!1l(c)){c=K}}O{8(!1l(c)){c=I}}8(!11(a)){a=0}8(!11(b)){b=0}8(c){a+=d.Y}a+=b;8(d.P>0){2i(a>=d.P){a-=d.P}2i(a<0){a+=d.P}}G a}C 4E(i,o,s){E t=0,x=0;1j(E a=s;a>=0;a--){E j=i.1O(a);t+=(j.2f(\':L\'))?j[o.d[\'2y\']](I):0;8(t>o.3V){G x}8(a==0){a=i.R}x++}}C 7h(i,o,s){G 6h(i,o.D.1t,o.D.T.4v,s)}C 6Z(i,o,s,m){G 6h(i,o.D.1t,m,s)}C 6h(i,f,m,s){E t=0,x=0;1j(E a=s,l=i.R;a>=0;a--){x++;8(x==l){G x}E j=i.1O(a);8(j.2f(f)){t++;8(t==m){G x}}8(a==0){a=l}}}C 5C(a,o){G o.D.T.4v||a.14().18(0,o.D.L).1t(o.D.1t).R}C 35(i,o,s){E t=0,x=0;1j(E a=s,l=i.R-1;a<=l;a++){E j=i.1O(a);t+=(j.2f(\':L\'))?j[o.d[\'2y\']](I):0;8(t>o.3V){G x}x++;8(x==l+1){G x}8(a==l){a=-1}}}C 5N(i,o,s,l){E v=35(i,o,s);8(!o.1T){8(s+v>l){v=l-s}}G v}C 3X(i,o,s){G 6i(i,o.D.1t,o.D.T.4v,s,o.1T)}C 75(i,o,s,m){G 6i(i,o.D.1t,m+1,s,o.1T)-1}C 6i(i,f,m,s,c){E t=0,x=0;1j(E a=s,l=i.R-1;a<=l;a++){x++;8(x>=l){G x}E j=i.1O(a);8(j.2f(f)){t++;8(t==m){G x}}8(a==l){a=-1}}}C 3J(i,o){G i.18(0,o.D.L)}C 71(i,o,n){G i.18(n,o.D.T.1Z+n)}C 72(i,o){G i.18(0,o.D.L)}C 77(i,o){G i.18(0,o.D.T.1Z)}C 78(i,o,n){G i.18(n,o.D.L+n)}C 4z(i,o,d){8(o.1R){8(!1p(d)){d=\'29\'}i.1W(C(){E j=$(1k),m=4l(j.X(o.d[\'1S\']),10);8(!11(m)){m=0}j.1m(d,m)})}}C 1U(i,o,m){8(o.1R){E x=(1l(m))?m:K;8(!11(m)){m=0}4z(i,o,\'7F\');i.1W(C(){E j=$(1k);j.X(o.d[\'1S\'],((x)?j.1m(\'7F\'):m+j.1m(\'29\')))})}}C 5u(i,o){8(o.2o){i.1W(C(){E j=$(1k),s=5q(j,[\'N\',\'1d\']);j.1m(\'7i\',s)})}}C 5v(o,b){E c=o.D.L,7G=o.D[o.d[\'N\']],6j=o[o.d[\'1d\']],7H=3W(6j);b.1W(C(){E a=$(1k),6k=7G-7I(a,o,\'9b\');a[o.d[\'N\']](6k);8(7H){a[o.d[\'1d\']](4u(6k,6j))}})}C 4R(a,o){E b=a.6b(),$i=a.14(),$v=3J($i,o),55=4J(4K($v,o,I),o,K);b.X(55);8(o.1R){E p=o.1i,r=p[o.d[1]];8(o.1A&&r<0){r=0}E c=$v.2R();c.X(o.d[\'1S\'],c.1m(\'29\')+r);a.X(o.d[\'3r\'],p[o.d[0]]);a.X(o.d[\'1n\'],p[o.d[3]])}a.X(o.d[\'N\'],55[o.d[\'N\']]+(2T($i,o,\'N\')*2));a.X(o.d[\'1d\'],6l($i,o,\'1d\'));G 55}C 4K(i,o,a){G[2T(i,o,\'N\',a),6l(i,o,\'1d\',a)]}C 6l(i,o,a,b){8(!1l(b)){b=K}8(11(o[o.d[a]])&&b){G o[o.d[a]]}8(11(o.D[o.d[a]])){G o.D[o.d[a]]}a=(a.6m().3S(\'N\')>-1)?\'2y\':\'3o\';G 4o(i,o,a)}C 4o(i,o,b){E s=0;1j(E a=0,l=i.R;a<l;a++){E j=i.1O(a);E m=(j.2f(\':L\'))?j[o.d[b]](I):0;8(s<m){s=m}}G s}C 2T(i,o,b,c){8(!1l(c)){c=K}8(11(o[o.d[b]])&&c){G o[o.d[b]]}8(11(o.D[o.d[b]])){G o.D[o.d[b]]*i.R}E d=(b.6m().3S(\'N\')>-1)?\'2y\':\'3o\',s=0;1j(E a=0,l=i.R;a<l;a++){E j=i.1O(a);s+=(j.2f(\':L\'))?j[o.d[d]](I):0}G s}C 5e(a,o,d){E b=a.2f(\':L\');8(b){a.4k()}E s=a.6b()[o.d[d]]();8(b){a.4n()}G s}C 5f(o,a){G(11(o[o.d[\'N\']]))?o[o.d[\'N\']]:a}C 6n(i,o,b){E s=K,v=K;1j(E a=0,l=i.R;a<l;a++){E j=i.1O(a);E c=(j.2f(\':L\'))?j[o.d[b]](I):0;8(s===K){s=c}O 8(s!=c){v=I}8(s==0){v=I}}G v}C 7I(i,o,d){G i[o.d[\'9c\'+d]](I)-i[o.d[d.6m()]]()}C 4u(s,o){8(3W(o)){o=4l(o.18(0,-1),10);8(!11(o)){G s}s*=o/2J}G s}C H(n,c,a,b,d){8(!1l(a)){a=I}8(!1l(b)){b=I}8(!1l(d)){d=K}8(a){n=c.3z.44+n}8(b){n=n+\'.\'+c.3z.7y}8(b&&d){n+=c.3U}G n}C 2B(n,c){G(1p(c.6d[n]))?c.6d[n]:n}C 4J(a,o,p){8(!1l(p)){p=I}E b=(o.1R&&p)?o.1i:[0,0,0,0];E c={};c[o.d[\'N\']]=a[0]+b[1]+b[3];c[o.d[\'1d\']]=a[1]+b[0]+b[2];G c}C 3f(c,d){E e=[];1j(E a=0,7J=c.R;a<7J;a++){1j(E b=0,7K=d.R;b<7K;b++){8(d[b].3S(2Z c[a])>-1&&1E(e[b])){e[b]=c[a];16}}}G e}C 6H(p){8(1E(p)){G[0,0,0,0]}8(11(p)){G[p,p,p,p]}8(1p(p)){p=p.3R(\'9d\').7L(\'\').3R(\'9e\').7L(\'\').3R(\' \')}8(!2X(p)){G[0,0,0,0]}1j(E i=0;i<4;i++){p[i]=4l(p[i],10)}1B(p.R){Q 0:G[0,0,0,0];Q 1:G[p[0],p[0],p[0],p[0]];Q 2:G[p[0],p[1],p[0],p[1]];Q 3:G[p[0],p[1],p[2],p[1]];2A:G[p[0],p[1],p[2],p[3]]}}C 4I(a,o){E x=(11(o[o.d[\'N\']]))?1H.2C(o[o.d[\'N\']]-2T(a,o,\'N\')):0;1B(o.1A){Q\'1n\':G[0,x];Q\'3a\':G[x,0];Q\'5g\':2A:G[1H.2C(x/2),1H.4m(x/2)]}}C 6B(o){E a=[[\'N\',\'7M\',\'2y\',\'1d\',\'7N\',\'3o\',\'1n\',\'3r\',\'1S\',0,1,2,3],[\'1d\',\'7N\',\'3o\',\'N\',\'7M\',\'2y\',\'3r\',\'1n\',\'5r\',3,2,1,0]];E b=a[0].R,7O=(o.2b==\'3a\'||o.2b==\'1n\')?0:1;E c={};1j(E d=0;d<b;d++){c[a[0][d]]=a[7O][d]}G c}C 4F(x,o,a,b){E v=x;8(1o(a)){v=a.1g(b,v)}O 8(1p(a)){E p=a.3R(\'+\'),m=a.3R(\'-\');8(m.R>p.R){E c=I,6o=m[0],32=m[1]}O{E c=K,6o=p[0],32=p[1]}1B(6o){Q\'9f\':v=(x%2==1)?x-1:x;16;Q\'9g\':v=(x%2==0)?x-1:x;16;2A:v=x;16}32=4l(32,10);8(11(32)){8(c){32=-32}v+=32}}8(!11(v)||v<1){v=1}G v}C 2z(x,o,a,b){G 6p(4F(x,o,a,b),o.D.T)}C 6p(v,i){8(11(i.36)&&v<i.36){v=i.36}8(11(i.1X)&&v>i.1X){v=i.1X}8(v<1){v=1}G v}C 5l(s){8(!2X(s)){s=[[s]]}8(!2X(s[0])){s=[s]}1j(E j=0,l=s.R;j<l;j++){8(1p(s[j][0])){s[j][0]=$(s[j][0])}8(!1l(s[j][1])){s[j][1]=I}8(!1l(s[j][2])){s[j][2]=I}8(!11(s[j][3])){s[j][3]=0}}G s}C 6g(k){8(k==\'3a\'){G 39}8(k==\'1n\'){G 37}8(k==\'4s\'){G 38}8(k==\'5W\'){G 40}G-1}C 5M(n,a,c){8(n){E v=a.1Q(H(\'4q\',c));$.1r.1v.23.6e(n,v)}}C 7u(n){E c=$.1r.1v.23.3G(n);G(c==\'\')?0:c}C 5q(a,b){E c={},56;1j(E p=0,l=b.R;p<l;p++){56=b[p];c[56]=a.X(56)}G c}C 6C(a,b,c,d){8(!1I(a.T)){a.T={}}8(!1I(a.3O)){a.3O={}}8(a.3n==0&&11(d)){a.3n=d}8(1I(a.L)){a.T.36=a.L.36;a.T.1X=a.L.1X;a.L=K}O 8(1p(a.L)){8(a.L==\'1c\'){a.T.1c=I}O{a.T.2c=a.L}a.L=K}O 8(1o(a.L)){a.T.2c=a.L;a.L=K}8(!1p(a.1t)){a.1t=(c.1t(\':3t\').R>0)?\':L\':\'*\'}8(!a[b.d[\'N\']]){8(b.2o){17(I,\'7P a \'+b.d[\'N\']+\' 1j 3L D!\');a[b.d[\'N\']]=4o(c,b,\'2y\')}O{a[b.d[\'N\']]=(6n(c,b,\'2y\'))?\'1c\':c[b.d[\'2y\']](I)}}8(!a[b.d[\'1d\']]){a[b.d[\'1d\']]=(6n(c,b,\'3o\'))?\'1c\':c[b.d[\'3o\']](I)}a.3O.N=a.N;a.3O.1d=a.1d;G a}C 6G(a,b){8(a.D[a.d[\'N\']]==\'1c\'){a.D.T.1c=I}8(!a.D.T.1c){8(11(a[a.d[\'N\']])){a.D.L=1H.4m(a[a.d[\'N\']]/a.D[a.d[\'N\']])}O{a.D.L=1H.4m(b/a.D[a.d[\'N\']]);a[a.d[\'N\']]=a.D.L*a.D[a.d[\'N\']];8(!a.D.T.2c){a.1A=K}}8(a.D.L==\'9h\'||a.D.L<1){17(I,\'2r a 5P 27 4e L D: 7P 45 "1c".\');a.D.T.1c=I}}G a}C 6D(a,b,c){8(a==\'M\'){a=4o(c,b,\'2y\')}G a}C 6E(a,b,c){8(a==\'M\'){a=4o(c,b,\'3o\')}8(!a){a=b.D[b.d[\'1d\']]}G a}C 5j(o,a){E p=4I(3J(a,o),o);o.1i[o.d[1]]=p[1];o.1i[o.d[3]]=p[0];G o}C 5h(o,a,b){E c=6p(1H.2C(o[o.d[\'N\']]/o.D[o.d[\'N\']]),o.D.T);8(c>a.R){c=a.R}E d=1H.4m(o[o.d[\'N\']]/c);o.D.L=c;o.D[o.d[\'N\']]=d;o[o.d[\'N\']]=c*d;G o}C 3P(p){8(1p(p)){E i=(p.3S(\'9i\')>-1)?I:K,r=(p.3S(\'3h\')>-1)?I:K}O{E i=r=K}G[i,r]}C 61(a){G(11(a))?a:2x}C 6q(a){G(a===2x)}C 1E(a){G(6q(a)||2Z a==\'7Q\'||a===\'\'||a===\'7Q\')}C 2X(a){G(a 2Y 9j)}C 2w(a){G(a 2Y 7R)}C 1I(a){G((a 2Y 9k||2Z a==\'2g\')&&!6q(a)&&!2w(a)&&!2X(a))}C 11(a){G((a 2Y 4d||2Z a==\'27\')&&!9l(a))}C 1p(a){G((a 2Y 9m||2Z a==\'2N\')&&!1E(a)&&!3q(a)&&!54(a))}C 1o(a){G(a 2Y 9n||2Z a==\'C\')}C 1l(a){G(a 2Y 9o||2Z a==\'3e\'||3q(a)||54(a))}C 3q(a){G(a===I||a===\'I\')}C 54(a){G(a===K||a===\'K\')}C 3W(x){G(1p(x)&&x.18(-1)==\'%\')}C 2q(){G 6f 7C().2q()}C 2K(o,n){17(I,o+\' 2f 9p, 9q 1j 9r 9s 9t 9u. 9v \'+n+\' 9w.\')}C 17(d,m){8(1I(d)){E s=\' (\'+d.4p+\')\';d=d.17}O{E s=\'\'}8(!d){G K}8(1p(m)){m=\'1v\'+s+\': \'+m}O{m=[\'1v\'+s+\':\',m]}8(3m.6r&&3m.6r.7S){3m.6r.7S(m)}G K}$.1N($.2l,{\'9x\':C(t){E a=t*t;G t*(-a*t+4*a-6*t+4)},\'9y\':C(t){G t*(4*t*t-9*t+6)},\'9z\':C(t){E a=t*t;G t*(33*a*a-9A*a*t+9B*a-67*t+15)}})})(7R);',62,596,'|||||||opts|if||||||||||||||||||||||||||||||function|items|var|conf|return|cf_e|true|itms|false|visible|auto|width|else|total|case|length|trigger|visibleConf|scrl|prev|button|css|first|next||is_number|bind|tt0|children||break|debug|slice|anims|pagination|push|variable|height|progress|stopPropagation|call|mousewheel|padding|for|this|is_boolean|data|left|is_function|is_string|swipe|fn|wrp|filter|tmrs|carouFredSel|fade|_onafter|_moveitems|container|align|switch|_s_paddingold|_s_paddingcur|is_undefined|duration|play|Math|is_object|cover|_position|opacity|scroll|extend|eq|_a_wrapper|triggerHandler|usePadding|marginRight|circular|sz_resetMargin|fx|each|max|i_cur_l|old|i_old_l|uncover|unbind|cookie||isScrolling|isPaused|number|a_cfs|_cfs_origCssMargin|clbk|direction|adjust|isStopped|stopImmediatePropagation|is|object|queu|while|i_new|w_siz|easing|nr|avail_primary|responsive|synchronise|getTime|Not|bar|i_new_l|a_cur|remove|is_jquery|null|outerWidth|cf_getItemsAdjust|default|cf_c|ceil|pR|_s_paddingnew|preventDefault|a_itm|pauseOnHover|options|100|deprecated|timeoutDuration|startTime|string|removeClass|sc_startScroll|queue|last|i_skp|ms_getTotalSize|a_old|a_lef|a_dur|is_array|instanceof|typeof||key|adj||opts_orig|gn_getVisibleItemsNext|min||||right|addClass|pause|perc|boolean|cf_sortParams|scrolling|resume|onAfter|i_old|crossfade|slideTo|window|start|outerHeight|_cfs_triggerEvent|is_true|top|position|hidden|sc_clearTimers|pre|post|timePassed|Carousel|events|infinite|nv_enableNavi|i_siz|i_siz_vis|_a_paddingold|_a_paddingcur|get|onBefore|updatePageStatus|gi_getCurrentItems|gn_getItemIndex|the|anchorBuilder|event|sizesConf|bt_pauseOnHoverConfig|ns2|split|indexOf|go_getObject|serialNumber|maxDimension|is_percentage|gn_getVisibleItemsNextFilter|orgCSS|zIndex||none|sc_stopScroll|dur2|prefix|to|appendTo|sc_setScroll|sc_fireCallbacks||currentPage|end|before|Number|of|document|touchwipe|wN|onTouch|onResize|hide|parseInt|floor|show|ms_getTrueLargestSize|selector|currentPosition|destroy|up|primarySizePercentage|ms_getPercentage|org|onTimeoutStart|onTimeoutPause|onTimeoutEnd|sz_storeMargin|stopped|updater|minimum|configuration|gn_getVisibleItemsPrev|cf_getAdjust|onEnd|clone|cf_getAlignPadding|cf_mapWrapperSizes|ms_getSizes|a_wsz|a_new|not|a_cfs_vis|updateSizes|eval|sz_setSizes|pgs|deviation|nv_showNavi|mouseenter|mouseleave|pauseOnEvent|keys|wipe||throttle|di|go_getNaviObject|is_false|sz|prop|element||starting_position|_cfs_isCarousel|_cfs_init|go_getPrevNextObject|defaults|ms_getParentSize|ms_getMaxDimension|center|in_getResponsiveValues|bottom|in_getAlignPadding|go_complementPrevNextObject|cf_getSynchArr|onPauseStart|onPausePause|onPauseEnd|pauseDuration|in_mapCss|marginBottom|newPosition|_cfs_origCss|sz_storeSizes|sz_setResponsiveSizes|_cfs_unbind_events|stop|finish|interval|type|conditions|gn_getVisibleOrg|backward|sc_hideHiddenItems|a_lef_vis|sc_getDuration|_a_paddingnew|sc_showHiddenItems|sc_mapCallbackArguments|sc_afterScroll|sc_fireQueue|cf_setCookie|gn_getVisibleItemsNextTestCircular|slideToPage|valid|linkAnchors|value|_cfs_bind_buttons|click|_cfs_unbind_buttons|scrolled|down|onMouse|swP|swN||bt_mousesheelNumber|delay|pauseOnResize|debounce|onWindowResize|_windowHeight||nh|ns3|wrapper|parent|continue|classnames|set|new|cf_getKeyCode|gn_getItemsPrevFilter|gn_getItemsNextFilter|seco|nw|ms_getLargestSize|toLowerCase|ms_hasVariableSizes|sta|cf_getItemAdjustMinMax|is_null|console|caroufredsel|No|found|go_getItemsObject|go_getScrollObject|go_getAutoObject|go_getPaginationObject|go_getSwipeObject|go_getMousewheelObject|cf_getDimensions|in_complementItems|in_complementPrimarySize|in_complementSecondarySize|upDateOnWindowResize|in_complementVisibleItems|cf_getPadding|500|go_complementAutoObject|go_complementPaginationObject|go_complementSwipeObject|go_complementMousewheelObject|_cfs_build|textAlign|float|marginTop|marginLeft|absolute|_cfs_bind_events|paused|enough|needed|page|slide_|gn_getScrollItemsPrevFilter|Scrolling|gi_getOldItemsPrev|gi_getNewItemsPrev|directscroll|concat|gn_getScrollItemsNextFilter|forward|gi_getOldItemsNext|gi_getNewItemsNext|jumpToStart|after|append|removeItem|round|hash|index|selected|gn_getVisibleItemsPrevFilter|_cfs_origCssSizes|Item|keyup|keyCode|plugin|scN|cursor|The|option|mcN|configs|classname|cf_getCookie|random|itm|onCreate|namespace|pageAnchorBuilder|span|progressbarUpdater|Date|_cfs_isHidden|triggerOnTouchEnd|_cfs_tempCssMargin|newS|secp|ms_getPaddingBorderMargin|l1|l2|join|innerWidth|innerHeight|dx|Set|undefined|jQuery|log|caroufredsel_cookie_|relative|fixed|overflow|setInterval|setTimeout|or|Callback|returned|Page|resumed|currently|slide_prev|prependTo|slide_next|prevPage|nextPage|prepend|carousel|insertItem|Correct|insert|Appending|item|add|detach|currentVisible|body|find|Preventing|non|sliding|replaceWith|widths|heights|automatically|touchSwipe|min_move_x|min_move_y|preventDefaultEvents|wipeUp|wipeDown|wipeLeft|wipeRight|ontouchstart|in|swipeUp|swipeDown|swipeLeft|swipeRight|move|200|300|resize|wrap|class|unshift|location|swing|cfs|div|caroufredsel_wrapper|href|charAt|setTime|1000|expires|toGMTString|path|orgDuration|animate|complete|shift|clearTimeout|clearInterval|skipped|Hiding|navigation|disabled|2500|Width|outer|px|em|even|odd|Infinity|immediate|Array|Object|isNaN|String|Function|Boolean|DEPRECATED|support|it|will|be|removed|Use|instead|quadratic|cubic|elastic|106|126'.split('|'),0,{}));




(function($){$.fn.touchwipe=function(settings){var config={min_move_x:20,min_move_y:20,wipeLeft:function(){},wipeRight:function(){},wipeUp:function(){},wipeDown:function(){},preventDefaultEvents:true};if(settings)$.extend(config,settings);this.each(function(){var startX;var startY;var isMoving=false;function cancelTouch(){this.removeEventListener('touchmove',onTouchMove);startX=null;isMoving=false}function onTouchMove(e){if(config.preventDefaultEvents){e.preventDefault()}if(isMoving){var x=e.touches[0].pageX;var y=e.touches[0].pageY;var dx=startX-x;var dy=startY-y;if(Math.abs(dx)>=config.min_move_x){cancelTouch();if(dx>0){config.wipeLeft()}else{config.wipeRight()}}else if(Math.abs(dy)>=config.min_move_y){cancelTouch();if(dy>0){config.wipeDown()}else{config.wipeUp()}}}}function onTouchStart(e){if(e.touches.length==1){startX=e.touches[0].pageX;startY=e.touches[0].pageY;isMoving=true;this.addEventListener('touchmove',onTouchMove,false)}}if('ontouchstart'in document.documentElement){this.addEventListener('touchstart',onTouchStart,false)}});return this}})(jQuery);




/*!
 * Bootstrap v3.0.2 by @fat and @mdo
 * Copyright 2013 Twitter, Inc.
 * Licensed under http://www.apache.org/licenses/LICENSE-2.0
 *
 * Designed and built with all the love in the world by @mdo and @fat.
 */
//TODO:: bootstrap js is removed from here, as included in gulp file

/****************************************************************************/
/*! jQuery Validation Plugin - v1.15.1 - 7/22/2016
 * http://jqueryvalidation.org/
 * Copyright (c) 2016 Jörn Zaefferer; Licensed MIT */
//TODO: jquery validation is removed from here, as included in gulp file
/****************************************************************************/

/*! iCheck v1.0.2 by Damir Sultanov, http://git.io/arlzeA, MIT Licensed */
(function(f){function A(a,b,d){var c=a[0],g=/er/.test(d)?_indeterminate:/bl/.test(d)?n:k,e=d==_update?{checked:c[k],disabled:c[n],indeterminate:"true"==a.attr(_indeterminate)||"false"==a.attr(_determinate)}:c[g];if(/^(ch|di|in)/.test(d)&&!e)x(a,g);else if(/^(un|en|de)/.test(d)&&e)q(a,g);else if(d==_update)for(var f in e)e[f]?x(a,f,!0):q(a,f,!0);else if(!b||"toggle"==d){if(!b)a[_callback]("ifClicked");e?c[_type]!==r&&q(a,g):x(a,g)}}function x(a,b,d){var c=a[0],g=a.parent(),e=b==k,u=b==_indeterminate,
v=b==n,s=u?_determinate:e?y:"enabled",F=l(a,s+t(c[_type])),B=l(a,b+t(c[_type]));if(!0!==c[b]){if(!d&&b==k&&c[_type]==r&&c.name){var w=a.closest("form"),p='input[name="'+c.name+'"]',p=w.length?w.find(p):f(p);p.each(function(){this!==c&&f(this).data(m)&&q(f(this),b)})}u?(c[b]=!0,c[k]&&q(a,k,"force")):(d||(c[b]=!0),e&&c[_indeterminate]&&q(a,_indeterminate,!1));D(a,e,b,d)}c[n]&&l(a,_cursor,!0)&&g.find("."+C).css(_cursor,"default");g[_add](B||l(a,b)||"");g.attr("role")&&!u&&g.attr("aria-"+(v?n:k),"true");
g[_remove](F||l(a,s)||"")}function q(a,b,d){var c=a[0],g=a.parent(),e=b==k,f=b==_indeterminate,m=b==n,s=f?_determinate:e?y:"enabled",q=l(a,s+t(c[_type])),r=l(a,b+t(c[_type]));if(!1!==c[b]){if(f||!d||"force"==d)c[b]=!1;D(a,e,s,d)}!c[n]&&l(a,_cursor,!0)&&g.find("."+C).css(_cursor,"pointer");g[_remove](r||l(a,b)||"");g.attr("role")&&!f&&g.attr("aria-"+(m?n:k),"false");g[_add](q||l(a,s)||"")}function E(a,b){if(a.data(m)){a.parent().html(a.attr("style",a.data(m).s||""));if(b)a[_callback](b);a.off(".i").unwrap();
f(_label+'[for="'+a[0].id+'"]').add(a.closest(_label)).off(".i")}}function l(a,b,f){if(a.data(m))return a.data(m).o[b+(f?"":"Class")]}function t(a){return a.charAt(0).toUpperCase()+a.slice(1)}function D(a,b,f,c){if(!c){if(b)a[_callback]("ifToggled");a[_callback]("ifChanged")[_callback]("if"+t(f))}}var m="iCheck",C=m+"-helper",r="radio",k="checked",y="un"+k,n="disabled";_determinate="determinate";_indeterminate="in"+_determinate;_update="update";_type="type";_click="click";_touch="touchbegin.i touchend.i";
_add="addClass";_remove="removeClass";_callback="trigger";_label="label";_cursor="cursor";_mobile=/ipad|iphone|ipod|android|blackberry|windows phone|opera mini|silk/i.test(navigator.userAgent);f.fn[m]=function(a,b){var d='input[type="checkbox"], input[type="'+r+'"]',c=f(),g=function(a){a.each(function(){var a=f(this);c=a.is(d)?c.add(a):c.add(a.find(d))})};if(/^(check|uncheck|toggle|indeterminate|determinate|disable|enable|update|destroy)$/i.test(a))return a=a.toLowerCase(),g(this),c.each(function(){var c=
f(this);"destroy"==a?E(c,"ifDestroyed"):A(c,!0,a);f.isFunction(b)&&b()});if("object"!=typeof a&&a)return this;var e=f.extend({checkedClass:k,disabledClass:n,indeterminateClass:_indeterminate,labelHover:!0},a),l=e.handle,v=e.hoverClass||"hover",s=e.focusClass||"focus",t=e.activeClass||"active",B=!!e.labelHover,w=e.labelHoverClass||"hover",p=(""+e.increaseArea).replace("%","")|0;if("checkbox"==l||l==r)d='input[type="'+l+'"]';-50>p&&(p=-50);g(this);return c.each(function(){var a=f(this);E(a);var c=this,
b=c.id,g=-p+"%",d=100+2*p+"%",d={position:"absolute",top:g,left:g,display:"block",width:d,height:d,margin:0,padding:0,background:"#fff",border:0,opacity:0},g=_mobile?{position:"absolute",visibility:"hidden"}:p?d:{position:"absolute",opacity:0},l="checkbox"==c[_type]?e.checkboxClass||"icheckbox":e.radioClass||"i"+r,z=f(_label+'[for="'+b+'"]').add(a.closest(_label)),u=!!e.aria,y=m+"-"+Math.random().toString(36).substr(2,6),h='<div class="'+l+'" '+(u?'role="'+c[_type]+'" ':"");u&&z.each(function(){h+=
'aria-labelledby="';this.id?h+=this.id:(this.id=y,h+=y);h+='"'});h=a.wrap(h+"/>")[_callback]("ifCreated").parent().append(e.insert);d=f('<ins class="'+C+'"/>').css(d).appendTo(h);a.data(m,{o:e,s:a.attr("style")}).css(g);e.inheritClass&&h[_add](c.className||"");e.inheritID&&b&&h.attr("id",m+"-"+b);"static"==h.css("position")&&h.css("position","relative");A(a,!0,_update);if(z.length)z.on(_click+".i mouseover.i mouseout.i "+_touch,function(b){var d=b[_type],e=f(this);if(!c[n]){if(d==_click){if(f(b.target).is("a"))return;
A(a,!1,!0)}else B&&(/ut|nd/.test(d)?(h[_remove](v),e[_remove](w)):(h[_add](v),e[_add](w)));if(_mobile)b.stopPropagation();else return!1}});a.on(_click+".i focus.i blur.i keyup.i keydown.i keypress.i",function(b){var d=b[_type];b=b.keyCode;if(d==_click)return!1;if("keydown"==d&&32==b)return c[_type]==r&&c[k]||(c[k]?q(a,k):x(a,k)),!1;if("keyup"==d&&c[_type]==r)!c[k]&&x(a,k);else if(/us|ur/.test(d))h["blur"==d?_remove:_add](s)});d.on(_click+" mousedown mouseup mouseover mouseout "+_touch,function(b){var d=
b[_type],e=/wn|up/.test(d)?t:v;if(!c[n]){if(d==_click)A(a,!1,!0);else{if(/wn|er|in/.test(d))h[_add](e);else h[_remove](e+" "+t);if(z.length&&B&&e==v)z[/ut|nd/.test(d)?_remove:_add](w)}if(_mobile)b.stopPropagation();else return!1}})})}})(window.jQuery||window.Zepto);

/*! URI.js v1.17.1 http://medialize.github.io/URI.js/ */
/* build contains: URI.js */
(function(p,w){"object"===typeof exports?module.exports=w(require("./punycode"),require("./IPv6"),require("./SecondLevelDomains")):"function"===typeof define&&define.amd?define(["./punycode","./IPv6","./SecondLevelDomains"],w):p.URI=w(p.punycode,p.IPv6,p.SecondLevelDomains,p)})(this,function(p,w,u,m){function d(a,b){var c=1<=arguments.length,f=2<=arguments.length;if(!(this instanceof d))return c?f?new d(a,b):new d(a):new d;if(void 0===a){if(c)throw new TypeError("undefined is not a valid argument for URI");
	a="undefined"!==typeof location?location.href+"":""}this.href(a);return void 0!==b?this.absoluteTo(b):this}function r(a){return a.replace(/([.*+?^=!:${}()|[\]\/\\])/g,"\\$1")}function v(a){return void 0===a?"Undefined":String(Object.prototype.toString.call(a)).slice(8,-1)}function h(a){return"Array"===v(a)}function D(a,b){var c={},d,g;if("RegExp"===v(b))c=null;else if(h(b))for(d=0,g=b.length;d<g;d++)c[b[d]]=!0;else c[b]=!0;d=0;for(g=a.length;d<g;d++)if(c&&void 0!==c[a[d]]||!c&&b.test(a[d]))a.splice(d,
	1),g--,d--;return a}function z(a,b){var c,d;if(h(b)){c=0;for(d=b.length;c<d;c++)if(!z(a,b[c]))return!1;return!0}var g=v(b);c=0;for(d=a.length;c<d;c++)if("RegExp"===g){if("string"===typeof a[c]&&a[c].match(b))return!0}else if(a[c]===b)return!0;return!1}function E(a,b){if(!h(a)||!h(b)||a.length!==b.length)return!1;a.sort();b.sort();for(var c=0,d=a.length;c<d;c++)if(a[c]!==b[c])return!1;return!0}function A(a){return a.replace(/^\/+|\/+$/g,"")}function G(a){return escape(a)}function B(a){return encodeURIComponent(a).replace(/[!'()*]/g,
	G).replace(/\*/g,"%2A")}function x(a){return function(b,c){if(void 0===b)return this._parts[a]||"";this._parts[a]=b||null;this.build(!c);return this}}function F(a,b){return function(c,d){if(void 0===c)return this._parts[a]||"";null!==c&&(c+="",c.charAt(0)===b&&(c=c.substring(1)));this._parts[a]=c;this.build(!d);return this}}var H=m&&m.URI;d.version="1.17.1";var e=d.prototype,q=Object.prototype.hasOwnProperty;d._parts=function(){return{protocol:null,username:null,password:null,hostname:null,urn:null,
	port:null,path:null,query:null,fragment:null,duplicateQueryParameters:d.duplicateQueryParameters,escapeQuerySpace:d.escapeQuerySpace}};d.duplicateQueryParameters=!1;d.escapeQuerySpace=!0;d.protocol_expression=/^[a-z][a-z0-9.+-]*$/i;d.idn_expression=/[^a-z0-9\.-]/i;d.punycode_expression=/(xn--)/i;d.ip4_expression=/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/;d.ip6_expression=/^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/;
	d.find_uri_expression=/\b((?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'".,<>?\u00ab\u00bb\u201c\u201d\u2018\u2019]))/ig;d.findUri={start:/\b(?:([a-z][a-z0-9.+-]*:\/\/)|www\.)/gi,end:/[\s\r\n]|$/,trim:/[`!()\[\]{};:'".,<>?\u00ab\u00bb\u201c\u201d\u201e\u2018\u2019]+$/};d.defaultPorts={http:"80",https:"443",ftp:"21",gopher:"70",ws:"80",wss:"443"};d.invalid_hostname_characters=
		/[^a-zA-Z0-9\.-]/;d.domAttributes={a:"href",blockquote:"cite",link:"href",base:"href",script:"src",form:"action",img:"src",area:"href",iframe:"src",embed:"src",source:"src",track:"src",input:"src",audio:"src",video:"src"};d.getDomAttribute=function(a){if(a&&a.nodeName){var b=a.nodeName.toLowerCase();return"input"===b&&"image"!==a.type?void 0:d.domAttributes[b]}};d.encode=B;d.decode=decodeURIComponent;d.iso8859=function(){d.encode=escape;d.decode=unescape};d.unicode=function(){d.encode=B;d.decode=
		decodeURIComponent};d.characters={pathname:{encode:{expression:/%(24|26|2B|2C|3B|3D|3A|40)/ig,map:{"%24":"$","%26":"&","%2B":"+","%2C":",","%3B":";","%3D":"=","%3A":":","%40":"@"}},decode:{expression:/[\/\?#]/g,map:{"/":"%2F","?":"%3F","#":"%23"}}},reserved:{encode:{expression:/%(21|23|24|26|27|28|29|2A|2B|2C|2F|3A|3B|3D|3F|40|5B|5D)/ig,map:{"%3A":":","%2F":"/","%3F":"?","%23":"#","%5B":"[","%5D":"]","%40":"@","%21":"!","%24":"$","%26":"&","%27":"'","%28":"(","%29":")","%2A":"*","%2B":"+","%2C":",",
		"%3B":";","%3D":"="}}},urnpath:{encode:{expression:/%(21|24|27|28|29|2A|2B|2C|3B|3D|40)/ig,map:{"%21":"!","%24":"$","%27":"'","%28":"(","%29":")","%2A":"*","%2B":"+","%2C":",","%3B":";","%3D":"=","%40":"@"}},decode:{expression:/[\/\?#:]/g,map:{"/":"%2F","?":"%3F","#":"%23",":":"%3A"}}}};d.encodeQuery=function(a,b){var c=d.encode(a+"");void 0===b&&(b=d.escapeQuerySpace);return b?c.replace(/%20/g,"+"):c};d.decodeQuery=function(a,b){a+="";void 0===b&&(b=d.escapeQuerySpace);try{return d.decode(b?a.replace(/\+/g,
		"%20"):a)}catch(c){return a}};var t={encode:"encode",decode:"decode"},y,C=function(a,b){return function(c){try{return d[b](c+"").replace(d.characters[a][b].expression,function(c){return d.characters[a][b].map[c]})}catch(f){return c}}};for(y in t)d[y+"PathSegment"]=C("pathname",t[y]),d[y+"UrnPathSegment"]=C("urnpath",t[y]);t=function(a,b,c){return function(f){var g;g=c?function(a){return d[b](d[c](a))}:d[b];f=(f+"").split(a);for(var e=0,l=f.length;e<l;e++)f[e]=g(f[e]);return f.join(a)}};d.decodePath=
		t("/","decodePathSegment");d.decodeUrnPath=t(":","decodeUrnPathSegment");d.recodePath=t("/","encodePathSegment","decode");d.recodeUrnPath=t(":","encodeUrnPathSegment","decode");d.encodeReserved=C("reserved","encode");d.parse=function(a,b){var c;b||(b={});c=a.indexOf("#");-1<c&&(b.fragment=a.substring(c+1)||null,a=a.substring(0,c));c=a.indexOf("?");-1<c&&(b.query=a.substring(c+1)||null,a=a.substring(0,c));"//"===a.substring(0,2)?(b.protocol=null,a=a.substring(2),a=d.parseAuthority(a,b)):(c=a.indexOf(":"),
	-1<c&&(b.protocol=a.substring(0,c)||null,b.protocol&&!b.protocol.match(d.protocol_expression)?b.protocol=void 0:"//"===a.substring(c+1,c+3)?(a=a.substring(c+3),a=d.parseAuthority(a,b)):(a=a.substring(c+1),b.urn=!0)));b.path=a;return b};d.parseHost=function(a,b){a=a.replace(/\\/g,"/");var c=a.indexOf("/"),d;-1===c&&(c=a.length);if("["===a.charAt(0))d=a.indexOf("]"),b.hostname=a.substring(1,d)||null,b.port=a.substring(d+2,c)||null,"/"===b.port&&(b.port=null);else{var g=a.indexOf(":");d=a.indexOf("/");
		g=a.indexOf(":",g+1);-1!==g&&(-1===d||g<d)?(b.hostname=a.substring(0,c)||null,b.port=null):(d=a.substring(0,c).split(":"),b.hostname=d[0]||null,b.port=d[1]||null)}b.hostname&&"/"!==a.substring(c).charAt(0)&&(c++,a="/"+a);return a.substring(c)||"/"};d.parseAuthority=function(a,b){a=d.parseUserinfo(a,b);return d.parseHost(a,b)};d.parseUserinfo=function(a,b){var c=a.indexOf("/"),f=a.lastIndexOf("@",-1<c?c:a.length-1);-1<f&&(-1===c||f<c)?(c=a.substring(0,f).split(":"),b.username=c[0]?d.decode(c[0]):null,
		c.shift(),b.password=c[0]?d.decode(c.join(":")):null,a=a.substring(f+1)):(b.username=null,b.password=null);return a};d.parseQuery=function(a,b){if(!a)return{};a=a.replace(/&+/g,"&").replace(/^\?*&*|&+$/g,"");if(!a)return{};for(var c={},f=a.split("&"),g=f.length,e,l,n=0;n<g;n++)if(e=f[n].split("="),l=d.decodeQuery(e.shift(),b),e=e.length?d.decodeQuery(e.join("="),b):null,q.call(c,l)){if("string"===typeof c[l]||null===c[l])c[l]=[c[l]];c[l].push(e)}else c[l]=e;return c};d.build=function(a){var b="";
		a.protocol&&(b+=a.protocol+":");a.urn||!b&&!a.hostname||(b+="//");b+=d.buildAuthority(a)||"";"string"===typeof a.path&&("/"!==a.path.charAt(0)&&"string"===typeof a.hostname&&(b+="/"),b+=a.path);"string"===typeof a.query&&a.query&&(b+="?"+a.query);"string"===typeof a.fragment&&a.fragment&&(b+="#"+a.fragment);return b};d.buildHost=function(a){var b="";if(a.hostname)b=d.ip6_expression.test(a.hostname)?b+("["+a.hostname+"]"):b+a.hostname;else return"";a.port&&(b+=":"+a.port);return b};d.buildAuthority=
		function(a){return d.buildUserinfo(a)+d.buildHost(a)};d.buildUserinfo=function(a){var b="";a.username&&(b+=d.encode(a.username),a.password&&(b+=":"+d.encode(a.password)),b+="@");return b};d.buildQuery=function(a,b,c){var f="",g,e,l,n;for(e in a)if(q.call(a,e)&&e)if(h(a[e]))for(g={},l=0,n=a[e].length;l<n;l++)void 0!==a[e][l]&&void 0===g[a[e][l]+""]&&(f+="&"+d.buildQueryParameter(e,a[e][l],c),!0!==b&&(g[a[e][l]+""]=!0));else void 0!==a[e]&&(f+="&"+d.buildQueryParameter(e,a[e],c));return f.substring(1)};
	d.buildQueryParameter=function(a,b,c){return d.encodeQuery(a,c)+(null!==b?"="+d.encodeQuery(b,c):"")};d.addQuery=function(a,b,c){if("object"===typeof b)for(var f in b)q.call(b,f)&&d.addQuery(a,f,b[f]);else if("string"===typeof b)void 0===a[b]?a[b]=c:("string"===typeof a[b]&&(a[b]=[a[b]]),h(c)||(c=[c]),a[b]=(a[b]||[]).concat(c));else throw new TypeError("URI.addQuery() accepts an object, string as the name parameter");};d.removeQuery=function(a,b,c){var f;if(h(b))for(c=0,f=b.length;c<f;c++)a[b[c]]=
		void 0;else if("RegExp"===v(b))for(f in a)b.test(f)&&(a[f]=void 0);else if("object"===typeof b)for(f in b)q.call(b,f)&&d.removeQuery(a,f,b[f]);else if("string"===typeof b)void 0!==c?"RegExp"===v(c)?!h(a[b])&&c.test(a[b])?a[b]=void 0:a[b]=D(a[b],c):a[b]!==String(c)||h(c)&&1!==c.length?h(a[b])&&(a[b]=D(a[b],c)):a[b]=void 0:a[b]=void 0;else throw new TypeError("URI.removeQuery() accepts an object, string, RegExp as the first parameter");};d.hasQuery=function(a,b,c,f){switch(v(b)){case "String":break;
		case "RegExp":for(var e in a)if(q.call(a,e)&&b.test(e)&&(void 0===c||d.hasQuery(a,e,c)))return!0;return!1;case "Object":for(var k in b)if(q.call(b,k)&&!d.hasQuery(a,k,b[k]))return!1;return!0;default:throw new TypeError("URI.hasQuery() accepts a string, regular expression or object as the name parameter");}switch(v(c)){case "Undefined":return b in a;case "Boolean":return a=!(h(a[b])?!a[b].length:!a[b]),c===a;case "Function":return!!c(a[b],b,a);case "Array":return h(a[b])?(f?z:E)(a[b],c):!1;case "RegExp":return h(a[b])?
		f?z(a[b],c):!1:!(!a[b]||!a[b].match(c));case "Number":c=String(c);case "String":return h(a[b])?f?z(a[b],c):!1:a[b]===c;default:throw new TypeError("URI.hasQuery() accepts undefined, boolean, string, number, RegExp, Function as the value parameter");}};d.commonPath=function(a,b){var c=Math.min(a.length,b.length),d;for(d=0;d<c;d++)if(a.charAt(d)!==b.charAt(d)){d--;break}if(1>d)return a.charAt(0)===b.charAt(0)&&"/"===a.charAt(0)?"/":"";if("/"!==a.charAt(d)||"/"!==b.charAt(d))d=a.substring(0,d).lastIndexOf("/");
		return a.substring(0,d+1)};d.withinString=function(a,b,c){c||(c={});var f=c.start||d.findUri.start,e=c.end||d.findUri.end,k=c.trim||d.findUri.trim,l=/[a-z0-9-]=["']?$/i;for(f.lastIndex=0;;){var n=f.exec(a);if(!n)break;n=n.index;if(c.ignoreHtml){var h=a.slice(Math.max(n-3,0),n);if(h&&l.test(h))continue}var h=n+a.slice(n).search(e),m=a.slice(n,h).replace(k,"");c.ignore&&c.ignore.test(m)||(h=n+m.length,m=b(m,n,h,a),a=a.slice(0,n)+m+a.slice(h),f.lastIndex=n+m.length)}f.lastIndex=0;return a};d.ensureValidHostname=
		function(a){if(a.match(d.invalid_hostname_characters)){if(!p)throw new TypeError('Hostname "'+a+'" contains characters other than [A-Z0-9.-] and Punycode.js is not available');if(p.toASCII(a).match(d.invalid_hostname_characters))throw new TypeError('Hostname "'+a+'" contains characters other than [A-Z0-9.-]');}};d.noConflict=function(a){if(a)return a={URI:this.noConflict()},m.URITemplate&&"function"===typeof m.URITemplate.noConflict&&(a.URITemplate=m.URITemplate.noConflict()),m.IPv6&&"function"===
	typeof m.IPv6.noConflict&&(a.IPv6=m.IPv6.noConflict()),m.SecondLevelDomains&&"function"===typeof m.SecondLevelDomains.noConflict&&(a.SecondLevelDomains=m.SecondLevelDomains.noConflict()),a;m.URI===this&&(m.URI=H);return this};e.build=function(a){if(!0===a)this._deferred_build=!0;else if(void 0===a||this._deferred_build)this._string=d.build(this._parts),this._deferred_build=!1;return this};e.clone=function(){return new d(this)};e.valueOf=e.toString=function(){return this.build(!1)._string};e.protocol=
		x("protocol");e.username=x("username");e.password=x("password");e.hostname=x("hostname");e.port=x("port");e.query=F("query","?");e.fragment=F("fragment","#");e.search=function(a,b){var c=this.query(a,b);return"string"===typeof c&&c.length?"?"+c:c};e.hash=function(a,b){var c=this.fragment(a,b);return"string"===typeof c&&c.length?"#"+c:c};e.pathname=function(a,b){if(void 0===a||!0===a){var c=this._parts.path||(this._parts.hostname?"/":"");return a?(this._parts.urn?d.decodeUrnPath:d.decodePath)(c):c}this._parts.path=
		this._parts.urn?a?d.recodeUrnPath(a):"":a?d.recodePath(a):"/";this.build(!b);return this};e.path=e.pathname;e.href=function(a,b){var c;if(void 0===a)return this.toString();this._string="";this._parts=d._parts();var f=a instanceof d,e="object"===typeof a&&(a.hostname||a.path||a.pathname);a.nodeName&&(e=d.getDomAttribute(a),a=a[e]||"",e=!1);!f&&e&&void 0!==a.pathname&&(a=a.toString());if("string"===typeof a||a instanceof String)this._parts=d.parse(String(a),this._parts);else if(f||e)for(c in f=f?a._parts:
		a,f)q.call(this._parts,c)&&(this._parts[c]=f[c]);else throw new TypeError("invalid input");this.build(!b);return this};e.is=function(a){var b=!1,c=!1,f=!1,e=!1,k=!1,l=!1,h=!1,m=!this._parts.urn;this._parts.hostname&&(m=!1,c=d.ip4_expression.test(this._parts.hostname),f=d.ip6_expression.test(this._parts.hostname),b=c||f,k=(e=!b)&&u&&u.has(this._parts.hostname),l=e&&d.idn_expression.test(this._parts.hostname),h=e&&d.punycode_expression.test(this._parts.hostname));switch(a.toLowerCase()){case "relative":return m;
		case "absolute":return!m;case "domain":case "name":return e;case "sld":return k;case "ip":return b;case "ip4":case "ipv4":case "inet4":return c;case "ip6":case "ipv6":case "inet6":return f;case "idn":return l;case "url":return!this._parts.urn;case "urn":return!!this._parts.urn;case "punycode":return h}return null};var I=e.protocol,J=e.port,K=e.hostname;e.protocol=function(a,b){if(void 0!==a&&a&&(a=a.replace(/:(\/\/)?$/,""),!a.match(d.protocol_expression)))throw new TypeError('Protocol "'+a+"\" contains characters other than [A-Z0-9.+-] or doesn't start with [A-Z]");
		return I.call(this,a,b)};e.scheme=e.protocol;e.port=function(a,b){if(this._parts.urn)return void 0===a?"":this;if(void 0!==a&&(0===a&&(a=null),a&&(a+="",":"===a.charAt(0)&&(a=a.substring(1)),a.match(/[^0-9]/))))throw new TypeError('Port "'+a+'" contains characters other than [0-9]');return J.call(this,a,b)};e.hostname=function(a,b){if(this._parts.urn)return void 0===a?"":this;if(void 0!==a){var c={};if("/"!==d.parseHost(a,c))throw new TypeError('Hostname "'+a+'" contains characters other than [A-Z0-9.-]');
		a=c.hostname}return K.call(this,a,b)};e.origin=function(a,b){if(this._parts.urn)return void 0===a?"":this;if(void 0===a){var c=this.protocol();return this.authority()?(c?c+"://":"")+this.authority():""}c=d(a);this.protocol(c.protocol()).authority(c.authority()).build(!b);return this};e.host=function(a,b){if(this._parts.urn)return void 0===a?"":this;if(void 0===a)return this._parts.hostname?d.buildHost(this._parts):"";if("/"!==d.parseHost(a,this._parts))throw new TypeError('Hostname "'+a+'" contains characters other than [A-Z0-9.-]');
		this.build(!b);return this};e.authority=function(a,b){if(this._parts.urn)return void 0===a?"":this;if(void 0===a)return this._parts.hostname?d.buildAuthority(this._parts):"";if("/"!==d.parseAuthority(a,this._parts))throw new TypeError('Hostname "'+a+'" contains characters other than [A-Z0-9.-]');this.build(!b);return this};e.userinfo=function(a,b){if(this._parts.urn)return void 0===a?"":this;if(void 0===a){if(!this._parts.username)return"";var c=d.buildUserinfo(this._parts);return c.substring(0,c.length-
		1)}"@"!==a[a.length-1]&&(a+="@");d.parseUserinfo(a,this._parts);this.build(!b);return this};e.resource=function(a,b){var c;if(void 0===a)return this.path()+this.search()+this.hash();c=d.parse(a);this._parts.path=c.path;this._parts.query=c.query;this._parts.fragment=c.fragment;this.build(!b);return this};e.subdomain=function(a,b){if(this._parts.urn)return void 0===a?"":this;if(void 0===a){if(!this._parts.hostname||this.is("IP"))return"";var c=this._parts.hostname.length-this.domain().length-1;return this._parts.hostname.substring(0,
			c)||""}c=this._parts.hostname.length-this.domain().length;c=this._parts.hostname.substring(0,c);c=new RegExp("^"+r(c));a&&"."!==a.charAt(a.length-1)&&(a+=".");a&&d.ensureValidHostname(a);this._parts.hostname=this._parts.hostname.replace(c,a);this.build(!b);return this};e.domain=function(a,b){if(this._parts.urn)return void 0===a?"":this;"boolean"===typeof a&&(b=a,a=void 0);if(void 0===a){if(!this._parts.hostname||this.is("IP"))return"";var c=this._parts.hostname.match(/\./g);if(c&&2>c.length)return this._parts.hostname;
		c=this._parts.hostname.length-this.tld(b).length-1;c=this._parts.hostname.lastIndexOf(".",c-1)+1;return this._parts.hostname.substring(c)||""}if(!a)throw new TypeError("cannot set domain empty");d.ensureValidHostname(a);!this._parts.hostname||this.is("IP")?this._parts.hostname=a:(c=new RegExp(r(this.domain())+"$"),this._parts.hostname=this._parts.hostname.replace(c,a));this.build(!b);return this};e.tld=function(a,b){if(this._parts.urn)return void 0===a?"":this;"boolean"===typeof a&&(b=a,a=void 0);
		if(void 0===a){if(!this._parts.hostname||this.is("IP"))return"";var c=this._parts.hostname.lastIndexOf("."),c=this._parts.hostname.substring(c+1);return!0!==b&&u&&u.list[c.toLowerCase()]?u.get(this._parts.hostname)||c:c}if(a)if(a.match(/[^a-zA-Z0-9-]/))if(u&&u.is(a))c=new RegExp(r(this.tld())+"$"),this._parts.hostname=this._parts.hostname.replace(c,a);else throw new TypeError('TLD "'+a+'" contains characters other than [A-Z0-9]');else{if(!this._parts.hostname||this.is("IP"))throw new ReferenceError("cannot set TLD on non-domain host");
			c=new RegExp(r(this.tld())+"$");this._parts.hostname=this._parts.hostname.replace(c,a)}else throw new TypeError("cannot set TLD empty");this.build(!b);return this};e.directory=function(a,b){if(this._parts.urn)return void 0===a?"":this;if(void 0===a||!0===a){if(!this._parts.path&&!this._parts.hostname)return"";if("/"===this._parts.path)return"/";var c=this._parts.path.length-this.filename().length-1,c=this._parts.path.substring(0,c)||(this._parts.hostname?"/":"");return a?d.decodePath(c):c}c=this._parts.path.length-
		this.filename().length;c=this._parts.path.substring(0,c);c=new RegExp("^"+r(c));this.is("relative")||(a||(a="/"),"/"!==a.charAt(0)&&(a="/"+a));a&&"/"!==a.charAt(a.length-1)&&(a+="/");a=d.recodePath(a);this._parts.path=this._parts.path.replace(c,a);this.build(!b);return this};e.filename=function(a,b){if(this._parts.urn)return void 0===a?"":this;if(void 0===a||!0===a){if(!this._parts.path||"/"===this._parts.path)return"";var c=this._parts.path.lastIndexOf("/"),c=this._parts.path.substring(c+1);return a?
		d.decodePathSegment(c):c}c=!1;"/"===a.charAt(0)&&(a=a.substring(1));a.match(/\.?\//)&&(c=!0);var f=new RegExp(r(this.filename())+"$");a=d.recodePath(a);this._parts.path=this._parts.path.replace(f,a);c?this.normalizePath(b):this.build(!b);return this};e.suffix=function(a,b){if(this._parts.urn)return void 0===a?"":this;if(void 0===a||!0===a){if(!this._parts.path||"/"===this._parts.path)return"";var c=this.filename(),f=c.lastIndexOf(".");if(-1===f)return"";c=c.substring(f+1);c=/^[a-z0-9%]+$/i.test(c)?
		c:"";return a?d.decodePathSegment(c):c}"."===a.charAt(0)&&(a=a.substring(1));if(c=this.suffix())f=a?new RegExp(r(c)+"$"):new RegExp(r("."+c)+"$");else{if(!a)return this;this._parts.path+="."+d.recodePath(a)}f&&(a=d.recodePath(a),this._parts.path=this._parts.path.replace(f,a));this.build(!b);return this};e.segment=function(a,b,c){var d=this._parts.urn?":":"/",e=this.path(),k="/"===e.substring(0,1),e=e.split(d);void 0!==a&&"number"!==typeof a&&(c=b,b=a,a=void 0);if(void 0!==a&&"number"!==typeof a)throw Error('Bad segment "'+
		a+'", must be 0-based integer');k&&e.shift();0>a&&(a=Math.max(e.length+a,0));if(void 0===b)return void 0===a?e:e[a];if(null===a||void 0===e[a])if(h(b)){e=[];a=0;for(var l=b.length;a<l;a++)if(b[a].length||e.length&&e[e.length-1].length)e.length&&!e[e.length-1].length&&e.pop(),e.push(A(b[a]))}else{if(b||"string"===typeof b)b=A(b),""===e[e.length-1]?e[e.length-1]=b:e.push(b)}else b?e[a]=A(b):e.splice(a,1);k&&e.unshift("");return this.path(e.join(d),c)};e.segmentCoded=function(a,b,c){var e,g;"number"!==
	typeof a&&(c=b,b=a,a=void 0);if(void 0===b){a=this.segment(a,b,c);if(h(a))for(e=0,g=a.length;e<g;e++)a[e]=d.decode(a[e]);else a=void 0!==a?d.decode(a):void 0;return a}if(h(b))for(e=0,g=b.length;e<g;e++)b[e]=d.encode(b[e]);else b="string"===typeof b||b instanceof String?d.encode(b):b;return this.segment(a,b,c)};var L=e.query;e.query=function(a,b){if(!0===a)return d.parseQuery(this._parts.query,this._parts.escapeQuerySpace);if("function"===typeof a){var c=d.parseQuery(this._parts.query,this._parts.escapeQuerySpace),
		e=a.call(this,c);this._parts.query=d.buildQuery(e||c,this._parts.duplicateQueryParameters,this._parts.escapeQuerySpace);this.build(!b);return this}return void 0!==a&&"string"!==typeof a?(this._parts.query=d.buildQuery(a,this._parts.duplicateQueryParameters,this._parts.escapeQuerySpace),this.build(!b),this):L.call(this,a,b)};e.setQuery=function(a,b,c){var e=d.parseQuery(this._parts.query,this._parts.escapeQuerySpace);if("string"===typeof a||a instanceof String)e[a]=void 0!==b?b:null;else if("object"===
		typeof a)for(var g in a)q.call(a,g)&&(e[g]=a[g]);else throw new TypeError("URI.addQuery() accepts an object, string as the name parameter");this._parts.query=d.buildQuery(e,this._parts.duplicateQueryParameters,this._parts.escapeQuerySpace);"string"!==typeof a&&(c=b);this.build(!c);return this};e.addQuery=function(a,b,c){var e=d.parseQuery(this._parts.query,this._parts.escapeQuerySpace);d.addQuery(e,a,void 0===b?null:b);this._parts.query=d.buildQuery(e,this._parts.duplicateQueryParameters,this._parts.escapeQuerySpace);
		"string"!==typeof a&&(c=b);this.build(!c);return this};e.removeQuery=function(a,b,c){var e=d.parseQuery(this._parts.query,this._parts.escapeQuerySpace);d.removeQuery(e,a,b);this._parts.query=d.buildQuery(e,this._parts.duplicateQueryParameters,this._parts.escapeQuerySpace);"string"!==typeof a&&(c=b);this.build(!c);return this};e.hasQuery=function(a,b,c){var e=d.parseQuery(this._parts.query,this._parts.escapeQuerySpace);return d.hasQuery(e,a,b,c)};e.setSearch=e.setQuery;e.addSearch=e.addQuery;e.removeSearch=
		e.removeQuery;e.hasSearch=e.hasQuery;e.normalize=function(){return this._parts.urn?this.normalizeProtocol(!1).normalizePath(!1).normalizeQuery(!1).normalizeFragment(!1).build():this.normalizeProtocol(!1).normalizeHostname(!1).normalizePort(!1).normalizePath(!1).normalizeQuery(!1).normalizeFragment(!1).build()};e.normalizeProtocol=function(a){"string"===typeof this._parts.protocol&&(this._parts.protocol=this._parts.protocol.toLowerCase(),this.build(!a));return this};e.normalizeHostname=function(a){this._parts.hostname&&
	(this.is("IDN")&&p?this._parts.hostname=p.toASCII(this._parts.hostname):this.is("IPv6")&&w&&(this._parts.hostname=w.best(this._parts.hostname)),this._parts.hostname=this._parts.hostname.toLowerCase(),this.build(!a));return this};e.normalizePort=function(a){"string"===typeof this._parts.protocol&&this._parts.port===d.defaultPorts[this._parts.protocol]&&(this._parts.port=null,this.build(!a));return this};e.normalizePath=function(a){var b=this._parts.path;if(!b)return this;if(this._parts.urn)return this._parts.path=
		d.recodeUrnPath(this._parts.path),this.build(!a),this;if("/"===this._parts.path)return this;var b=d.recodePath(b),c,e="",g,k;"/"!==b.charAt(0)&&(c=!0,b="/"+b);if("/.."===b.slice(-3)||"/."===b.slice(-2))b+="/";b=b.replace(/(\/(\.\/)+)|(\/\.$)/g,"/").replace(/\/{2,}/g,"/");c&&(e=b.substring(1).match(/^(\.\.\/)+/)||"")&&(e=e[0]);for(;;){g=b.search(/\/\.\.(\/|$)/);if(-1===g)break;else if(0===g){b=b.substring(3);continue}k=b.substring(0,g).lastIndexOf("/");-1===k&&(k=g);b=b.substring(0,k)+b.substring(g+
			3)}c&&this.is("relative")&&(b=e+b.substring(1));this._parts.path=b;this.build(!a);return this};e.normalizePathname=e.normalizePath;e.normalizeQuery=function(a){"string"===typeof this._parts.query&&(this._parts.query.length?this.query(d.parseQuery(this._parts.query,this._parts.escapeQuerySpace)):this._parts.query=null,this.build(!a));return this};e.normalizeFragment=function(a){this._parts.fragment||(this._parts.fragment=null,this.build(!a));return this};e.normalizeSearch=e.normalizeQuery;e.normalizeHash=
		e.normalizeFragment;e.iso8859=function(){var a=d.encode,b=d.decode;d.encode=escape;d.decode=decodeURIComponent;try{this.normalize()}finally{d.encode=a,d.decode=b}return this};e.unicode=function(){var a=d.encode,b=d.decode;d.encode=B;d.decode=unescape;try{this.normalize()}finally{d.encode=a,d.decode=b}return this};e.readable=function(){var a=this.clone();a.username("").password("").normalize();var b="";a._parts.protocol&&(b+=a._parts.protocol+"://");a._parts.hostname&&(a.is("punycode")&&p?(b+=p.toUnicode(a._parts.hostname),
	a._parts.port&&(b+=":"+a._parts.port)):b+=a.host());a._parts.hostname&&a._parts.path&&"/"!==a._parts.path.charAt(0)&&(b+="/");b+=a.path(!0);if(a._parts.query){for(var c="",e=0,g=a._parts.query.split("&"),k=g.length;e<k;e++){var h=(g[e]||"").split("="),c=c+("&"+d.decodeQuery(h[0],this._parts.escapeQuerySpace).replace(/&/g,"%26"));void 0!==h[1]&&(c+="="+d.decodeQuery(h[1],this._parts.escapeQuerySpace).replace(/&/g,"%26"))}b+="?"+c.substring(1)}return b+=d.decodeQuery(a.hash(),!0)};e.absoluteTo=function(a){var b=
		this.clone(),c=["protocol","username","password","hostname","port"],e,g;if(this._parts.urn)throw Error("URNs do not have any generally defined hierarchical components");a instanceof d||(a=new d(a));b._parts.protocol||(b._parts.protocol=a._parts.protocol);if(this._parts.hostname)return b;for(e=0;g=c[e];e++)b._parts[g]=a._parts[g];b._parts.path?".."===b._parts.path.substring(-2)&&(b._parts.path+="/"):(b._parts.path=a._parts.path,b._parts.query||(b._parts.query=a._parts.query));"/"!==b.path().charAt(0)&&
	(c=(c=a.directory())?c:0===a.path().indexOf("/")?"/":"",b._parts.path=(c?c+"/":"")+b._parts.path,b.normalizePath());b.build();return b};e.relativeTo=function(a){var b=this.clone().normalize(),c,e,g;if(b._parts.urn)throw Error("URNs do not have any generally defined hierarchical components");a=(new d(a)).normalize();c=b._parts;e=a._parts;g=b.path();a=a.path();if("/"!==g.charAt(0))throw Error("URI is already relative");if("/"!==a.charAt(0))throw Error("Cannot calculate a URI relative to another relative URI");
		c.protocol===e.protocol&&(c.protocol=null);if(c.username===e.username&&c.password===e.password&&null===c.protocol&&null===c.username&&null===c.password&&c.hostname===e.hostname&&c.port===e.port)c.hostname=null,c.port=null;else return b.build();if(g===a)return c.path="",b.build();g=d.commonPath(g,a);if(!g)return b.build();e=e.path.substring(g.length).replace(/[^\/]*$/,"").replace(/.*?\//g,"../");c.path=e+c.path.substring(g.length)||"./";return b.build()};e.equals=function(a){var b=this.clone();a=new d(a);
		var c={},e={},g={},k;b.normalize();a.normalize();if(b.toString()===a.toString())return!0;c=b.query();e=a.query();b.query("");a.query("");if(b.toString()!==a.toString()||c.length!==e.length)return!1;c=d.parseQuery(c,this._parts.escapeQuerySpace);e=d.parseQuery(e,this._parts.escapeQuerySpace);for(k in c)if(q.call(c,k)){if(!h(c[k])){if(c[k]!==e[k])return!1}else if(!E(c[k],e[k]))return!1;g[k]=!0}for(k in e)if(q.call(e,k)&&!g[k])return!1;return!0};e.duplicateQueryParameters=function(a){this._parts.duplicateQueryParameters=
		!!a;return this};e.escapeQuerySpace=function(a){this._parts.escapeQuerySpace=!!a;return this};return d});


/**
 * bootbox.js v4.4.0
 *
 * http://bootboxjs.com/license.txt
 */
//TODO:: bootbox is removed from here, as included in gulp.

                                                                        /*
PNotify 2.0.1 sciactive.com/pnotify/
(C) 2014 Hunter Perrin
license GPL/LGPL/MPL
*/
(function(c){"function"===typeof define&&define.amd?define("pnotify",["jquery"],c):c(jQuery)})(function(c){var p={dir1:"down",dir2:"left",push:"bottom",spacing1:25,spacing2:25,context:c("body")},f,g,h=c(window),m=function(){g=c("body");PNotify.prototype.options.stack.context=g;h=c(window);h.bind("resize",function(){f&&clearTimeout(f);f=setTimeout(function(){PNotify.positionAll(!0)},10)})};PNotify=function(b){this.parseOptions(b);this.init()};c.extend(PNotify.prototype,{version:"2.0.1",options:{title:!1,
title_escape:!1,text:!1,text_escape:!1,styling:"bootstrap3",addclass:"",cornerclass:"",auto_display:!0,width:"300px",min_height:"16px",type:"notice",icon:!0,opacity:1,animation:"fade",animate_speed:"slow",position_animate_speed:500,shadow:!0,hide:!0,delay:8E3,mouse_reset:!0,remove:!0,insert_brs:!0,destroy:!0,stack:p},modules:{},runModules:function(b,a){var c,e;for(e in this.modules)if(c="object"===typeof a&&e in a?a[e]:a,"function"===typeof this.modules[e][b])this.modules[e][b](this,"object"===typeof this.options[e]?
this.options[e]:{},c)},state:"initializing",timer:null,styles:null,elem:null,container:null,title_container:null,text_container:null,animating:!1,timerHide:!1,init:function(){var b=this;this.modules={};c.extend(!0,this.modules,PNotify.prototype.modules);this.styles="object"===typeof this.options.styling?this.options.styling:PNotify.styling[this.options.styling];this.elem=c("<div />",{"class":"ui-pnotify "+this.options.addclass,css:{display:"none"},mouseenter:function(a){if(b.options.mouse_reset&&
"out"===b.animating){if(!b.timerHide)return;b.cancelRemove()}b.options.hide&&b.options.mouse_reset&&b.cancelRemove()},mouseleave:function(a){b.options.hide&&b.options.mouse_reset&&b.queueRemove();PNotify.positionAll()}});this.container=c("<div />",{"class":this.styles.container+" ui-pnotify-container "+("error"===this.options.type?this.styles.error:"info"===this.options.type?this.styles.info:"success"===this.options.type?this.styles.success:this.styles.notice)}).appendTo(this.elem);""!==this.options.cornerclass&&
this.container.removeClass("ui-corner-all").addClass(this.options.cornerclass);this.options.shadow&&this.container.addClass("ui-pnotify-shadow");!1!==this.options.icon&&c("<div />",{"class":"ui-pnotify-icon"}).append(c("<span />",{"class":!0===this.options.icon?"error"===this.options.type?this.styles.error_icon:"info"===this.options.type?this.styles.info_icon:"success"===this.options.type?this.styles.success_icon:this.styles.notice_icon:this.options.icon})).prependTo(this.container);this.title_container=
c("<h4 />",{"class":"ui-pnotify-title"}).appendTo(this.container);!1===this.options.title?this.title_container.hide():this.options.title_escape?this.title_container.text(this.options.title):this.title_container.html(this.options.title);this.text_container=c("<div />",{"class":"ui-pnotify-text"}).appendTo(this.container);!1===this.options.text?this.text_container.hide():this.options.text_escape?this.text_container.text(this.options.text):this.text_container.html(this.options.insert_brs?String(this.options.text).replace(/\n/g,
"<br />"):this.options.text);"string"===typeof this.options.width&&this.elem.css("width",this.options.width);"string"===typeof this.options.min_height&&this.container.css("min-height",this.options.min_height);PNotify.notices="top"===this.options.stack.push?c.merge([this],PNotify.notices):c.merge(PNotify.notices,[this]);"top"===this.options.stack.push&&this.queuePosition(!1,1);this.options.stack.animation=!1;this.runModules("init");this.options.auto_display&&this.open();return this},update:function(b){var a=
this.options;this.parseOptions(a,b);this.options.cornerclass!==a.cornerclass&&this.container.removeClass("ui-corner-all "+a.cornerclass).addClass(this.options.cornerclass);this.options.shadow!==a.shadow&&(this.options.shadow?this.container.addClass("ui-pnotify-shadow"):this.container.removeClass("ui-pnotify-shadow"));!1===this.options.addclass?this.elem.removeClass(a.addclass):this.options.addclass!==a.addclass&&this.elem.removeClass(a.addclass).addClass(this.options.addclass);!1===this.options.title?
this.title_container.slideUp("fast"):this.options.title!==a.title&&(this.options.title_escape?this.title_container.text(this.options.title):this.title_container.html(this.options.title),!1===a.title&&this.title_container.slideDown(200));!1===this.options.text?this.text_container.slideUp("fast"):this.options.text!==a.text&&(this.options.text_escape?this.text_container.text(this.options.text):this.text_container.html(this.options.insert_brs?String(this.options.text).replace(/\n/g,"<br />"):this.options.text),
!1===a.text&&this.text_container.slideDown(200));this.options.type!==a.type&&this.container.removeClass(this.styles.error+" "+this.styles.notice+" "+this.styles.success+" "+this.styles.info).addClass("error"===this.options.type?this.styles.error:"info"===this.options.type?this.styles.info:"success"===this.options.type?this.styles.success:this.styles.notice);if(this.options.icon!==a.icon||!0===this.options.icon&&this.options.type!==a.type)this.container.find("div.ui-pnotify-icon").remove(),!1!==this.options.icon&&
c("<div />",{"class":"ui-pnotify-icon"}).append(c("<span />",{"class":!0===this.options.icon?"error"===this.options.type?this.styles.error_icon:"info"===this.options.type?this.styles.info_icon:"success"===this.options.type?this.styles.success_icon:this.styles.notice_icon:this.options.icon})).prependTo(this.container);this.options.width!==a.width&&this.elem.animate({width:this.options.width});this.options.min_height!==a.min_height&&this.container.animate({minHeight:this.options.min_height});this.options.opacity!==
a.opacity&&this.elem.fadeTo(this.options.animate_speed,this.options.opacity);this.options.hide?a.hide||this.queueRemove():this.cancelRemove();this.queuePosition(!0);this.runModules("update",a);return this},open:function(){this.state="opening";this.runModules("beforeOpen");var b=this;this.elem.parent().length||this.elem.appendTo(this.options.stack.context?this.options.stack.context:g);"top"!==this.options.stack.push&&this.position(!0);"fade"===this.options.animation||"fade"===this.options.animation.effect_in?
this.elem.show().fadeTo(0,0).hide():1!==this.options.opacity&&this.elem.show().fadeTo(0,this.options.opacity).hide();this.animateIn(function(){b.queuePosition(!0);b.options.hide&&b.queueRemove();b.state="open";b.runModules("afterOpen")});return this},remove:function(b){this.state="closing";this.timerHide=!!b;this.runModules("beforeClose");var a=this;this.timer&&(window.clearTimeout(this.timer),this.timer=null);this.animateOut(function(){a.state="closed";a.runModules("afterClose");a.queuePosition(!0);
a.options.remove&&a.elem.detach();a.runModules("beforeDestroy");if(a.options.destroy&&null!==PNotify.notices){var b=c.inArray(a,PNotify.notices);-1!==b&&PNotify.notices.splice(b,1)}a.runModules("afterDestroy")});return this},get:function(){return this.elem},parseOptions:function(b,a){this.options=c.extend(!0,{},PNotify.prototype.options);this.options.stack=PNotify.prototype.options.stack;var n=[b,a],e,f;for(f in n){e=n[f];if("undefined"==typeof e)break;if("object"!==typeof e)this.options.text=e;else for(var d in e)this.modules[d]?
c.extend(!0,this.options[d],e[d]):this.options[d]=e[d]}},animateIn:function(b){this.animating="in";var a;a="undefined"!==typeof this.options.animation.effect_in?this.options.animation.effect_in:this.options.animation;"none"===a?(this.elem.show(),b()):"show"===a?this.elem.show(this.options.animate_speed,b):"fade"===a?this.elem.show().fadeTo(this.options.animate_speed,this.options.opacity,b):"slide"===a?this.elem.slideDown(this.options.animate_speed,b):"function"===typeof a?a("in",b,this.elem):this.elem.show(a,
"object"===typeof this.options.animation.options_in?this.options.animation.options_in:{},this.options.animate_speed,b);this.elem.parent().hasClass("ui-effects-wrapper")&&this.elem.parent().css({position:"fixed",overflow:"visible"});"slide"!==a&&this.elem.css("overflow","visible");this.container.css("overflow","hidden")},animateOut:function(b){this.animating="out";var a;a="undefined"!==typeof this.options.animation.effect_out?this.options.animation.effect_out:this.options.animation;"none"===a?(this.elem.hide(),
b()):"show"===a?this.elem.hide(this.options.animate_speed,b):"fade"===a?this.elem.fadeOut(this.options.animate_speed,b):"slide"===a?this.elem.slideUp(this.options.animate_speed,b):"function"===typeof a?a("out",b,this.elem):this.elem.hide(a,"object"===typeof this.options.animation.options_out?this.options.animation.options_out:{},this.options.animate_speed,b);this.elem.parent().hasClass("ui-effects-wrapper")&&this.elem.parent().css({position:"fixed",overflow:"visible"});"slide"!==a&&this.elem.css("overflow",
"visible");this.container.css("overflow","hidden")},position:function(b){var a=this.options.stack,c=this.elem;c.parent().hasClass("ui-effects-wrapper")&&(c=this.elem.css({left:"0",top:"0",right:"0",bottom:"0"}).parent());"undefined"===typeof a.context&&(a.context=g);if(a){"number"!==typeof a.nextpos1&&(a.nextpos1=a.firstpos1);"number"!==typeof a.nextpos2&&(a.nextpos2=a.firstpos2);"number"!==typeof a.addpos2&&(a.addpos2=0);var e="none"===c.css("display");if(!e||b){var f,d={},k;switch(a.dir1){case "down":k=
"top";break;case "up":k="bottom";break;case "left":k="right";break;case "right":k="left"}b=parseInt(c.css(k).replace(/(?:\..*|[^0-9.])/g,""));isNaN(b)&&(b=0);"undefined"!==typeof a.firstpos1||e||(a.firstpos1=b,a.nextpos1=a.firstpos1);var l;switch(a.dir2){case "down":l="top";break;case "up":l="bottom";break;case "left":l="right";break;case "right":l="left"}f=parseInt(c.css(l).replace(/(?:\..*|[^0-9.])/g,""));isNaN(f)&&(f=0);"undefined"!==typeof a.firstpos2||e||(a.firstpos2=f,a.nextpos2=a.firstpos2);
if("down"===a.dir1&&a.nextpos1+c.height()>(a.context.is(g)?h.height():a.context.prop("scrollHeight"))||"up"===a.dir1&&a.nextpos1+c.height()>(a.context.is(g)?h.height():a.context.prop("scrollHeight"))||"left"===a.dir1&&a.nextpos1+c.width()>(a.context.is(g)?h.width():a.context.prop("scrollWidth"))||"right"===a.dir1&&a.nextpos1+c.width()>(a.context.is(g)?h.width():a.context.prop("scrollWidth")))a.nextpos1=a.firstpos1,a.nextpos2+=a.addpos2+("undefined"===typeof a.spacing2?25:a.spacing2),a.addpos2=0;if(a.animation&&
a.nextpos2<f)switch(a.dir2){case "down":d.top=a.nextpos2+"px";break;case "up":d.bottom=a.nextpos2+"px";break;case "left":d.right=a.nextpos2+"px";break;case "right":d.left=a.nextpos2+"px"}else"number"===typeof a.nextpos2&&c.css(l,a.nextpos2+"px");switch(a.dir2){case "down":case "up":c.outerHeight(!0)>a.addpos2&&(a.addpos2=c.height());break;case "left":case "right":c.outerWidth(!0)>a.addpos2&&(a.addpos2=c.width())}if("number"===typeof a.nextpos1)if(a.animation&&(b>a.nextpos1||d.top||d.bottom||d.right||
d.left))switch(a.dir1){case "down":d.top=a.nextpos1+"px";break;case "up":d.bottom=a.nextpos1+"px";break;case "left":d.right=a.nextpos1+"px";break;case "right":d.left=a.nextpos1+"px"}else c.css(k,a.nextpos1+"px");(d.top||d.bottom||d.right||d.left)&&c.animate(d,{duration:this.options.position_animate_speed,queue:!1});switch(a.dir1){case "down":case "up":a.nextpos1+=c.height()+("undefined"===typeof a.spacing1?25:a.spacing1);break;case "left":case "right":a.nextpos1+=c.width()+("undefined"===typeof a.spacing1?
25:a.spacing1)}}return this}},queuePosition:function(b,a){f&&clearTimeout(f);a||(a=10);f=setTimeout(function(){PNotify.positionAll(b)},a);return this},cancelRemove:function(){this.timer&&window.clearTimeout(this.timer);"closing"===this.state&&(this.elem.stop(!0),this.state="open",this.animating="in",this.elem.css("height","auto").animate({width:this.options.width,opacity:this.options.opacity},"fast"));return this},queueRemove:function(){var b=this;this.cancelRemove();this.timer=window.setTimeout(function(){b.remove(!0)},
isNaN(this.options.delay)?0:this.options.delay);return this}});c.extend(PNotify,{notices:[],removeAll:function(){c.each(PNotify.notices,function(){this.remove&&this.remove()})},positionAll:function(b){f&&clearTimeout(f);f=null;c.each(PNotify.notices,function(){var a=this.options.stack;a&&(a.nextpos1=a.firstpos1,a.nextpos2=a.firstpos2,a.addpos2=0,a.animation=b)});c.each(PNotify.notices,function(){this.position()})},styling:{jqueryui:{container:"ui-widget ui-widget-content ui-corner-all",notice:"ui-state-highlight",
notice_icon:"ui-icon ui-icon-info",info:"",info_icon:"ui-icon ui-icon-info",success:"ui-state-default",success_icon:"ui-icon ui-icon-circle-check",error:"ui-state-error",error_icon:"ui-icon ui-icon-alert"},bootstrap2:{container:"alert",notice:"",notice_icon:"icon-exclamation-sign",info:"alert-info",info_icon:"icon-info-sign",success:"alert-success",success_icon:"icon-ok-sign",error:"alert-error",error_icon:"icon-warning-sign"},bootstrap3:{container:"alert",notice:"alert-warning",notice_icon:"fa fa-exclamation-triangle",
info:"alert-info",info_icon:"fa fa-info",success:"alert-success",success_icon:"fa fa-check",error:"alert-danger",error_icon:"fa fa-warning"}}});PNotify.styling.fontawesome=c.extend({},PNotify.styling.bootstrap3);c.extend(PNotify.styling.fontawesome,{notice_icon:"fa fa-exclamation-circle",info_icon:"fa fa-info",success_icon:"fa fa-check",error_icon:"fa fa-warning"});document.body?m():c(m);return PNotify});
(function(c){"function"===typeof define&&define.amd?define("pnotify.buttons",["jquery","pnotify"],c):c(jQuery,PNotify)})(function(c,e){e.prototype.options.buttons={closer:!0,closer_hover:!0,sticker:!0,sticker_hover:!0,labels:{close:"Close",stick:"Stick"}};e.prototype.modules.buttons={myOptions:null,closer:null,sticker:null,init:function(a,b){var d=this;this.myOptions=b;a.elem.on({mouseenter:function(b){!d.myOptions.sticker||a.options.nonblock&&a.options.nonblock.nonblock||d.sticker.trigger("pnotify_icon").css("visibility",
"visible");!d.myOptions.closer||a.options.nonblock&&a.options.nonblock.nonblock||d.closer.css("visibility","visible")},mouseleave:function(a){d.myOptions.sticker_hover&&d.sticker.css("visibility","hidden");d.myOptions.closer_hover&&d.closer.css("visibility","hidden")}});this.sticker=c("<div />",{"class":"ui-pnotify-sticker",css:{cursor:"pointer",visibility:b.sticker_hover?"hidden":"visible"},click:function(){a.options.hide=!a.options.hide;a.options.hide?a.queueRemove():a.cancelRemove();c(this).trigger("pnotify_icon")}}).bind("pnotify_icon",
function(){c(this).children().removeClass(a.styles.pin_up+" "+a.styles.pin_down).addClass(a.options.hide?a.styles.pin_up:a.styles.pin_down)}).append(c("<span />",{"class":a.styles.pin_up,title:b.labels.stick})).prependTo(a.container);(!b.sticker||a.options.nonblock&&a.options.nonblock.nonblock)&&this.sticker.css("display","none");this.closer=c("<div />",{"class":"ui-pnotify-closer",css:{cursor:"pointer",visibility:b.closer_hover?"hidden":"visible"},click:function(){a.remove(!1);d.sticker.css("visibility",
"hidden");d.closer.css("visibility","hidden")}}).append(c("<span />",{"class":a.styles.closer,title:b.labels.close})).prependTo(a.container);(!b.closer||a.options.nonblock&&a.options.nonblock.nonblock)&&this.closer.css("display","none")},update:function(a,b){this.myOptions=b;!b.closer||a.options.nonblock&&a.options.nonblock.nonblock?this.closer.css("display","none"):b.closer&&this.closer.css("display","block");!b.sticker||a.options.nonblock&&a.options.nonblock.nonblock?this.sticker.css("display",
"none"):b.sticker&&this.sticker.css("display","block");this.sticker.trigger("pnotify_icon");b.sticker_hover?this.sticker.css("visibility","hidden"):a.options.nonblock&&a.options.nonblock.nonblock||this.sticker.css("visibility","visible");b.closer_hover?this.closer.css("visibility","hidden"):a.options.nonblock&&a.options.nonblock.nonblock||this.closer.css("visibility","visible")}};c.extend(e.styling.jqueryui,{closer:"ui-icon ui-icon-close",pin_up:"ui-icon ui-icon-pin-w",pin_down:"ui-icon ui-icon-pin-s"});
c.extend(e.styling.bootstrap2,{closer:"icon-remove",pin_up:"icon-pause",pin_down:"icon-play"});c.extend(e.styling.bootstrap3,{closer:"fa fa-times",pin_up:"fa fa-pause",pin_down:"fa fa-play"});c.extend(e.styling.fontawesome,{closer:"fa fa-times",pin_up:"fa fa-pause",pin_down:"fa fa-play"})});
(function(b){"function"===typeof define&&define.amd?define("pnotify.callbacks",["jquery","pnotify"],b):b(jQuery,PNotify)})(function(b,a){var c=a.prototype.init,d=a.prototype.open,e=a.prototype.remove;a.prototype.init=function(){this.options.before_init&&this.options.before_init(this.options);c.apply(this,arguments);this.options.after_init&&this.options.after_init(this)};a.prototype.open=function(){var a;this.options.before_open&&(a=this.options.before_open(this));!1!==a&&(d.apply(this,arguments),
this.options.after_open&&this.options.after_open(this))};a.prototype.remove=function(a){var b;this.options.before_close&&(b=this.options.before_close(this,a));!1!==b&&(e.apply(this,arguments),this.options.after_close&&this.options.after_close(this,a))}});
(function(d){"function"===typeof define&&define.amd?define("pnotify.confirm",["jquery","pnotify"],d):d(jQuery,PNotify)})(function(d,e){e.prototype.options.confirm={confirm:!1,prompt:!1,prompt_class:"",prompt_default:"",prompt_multi_line:!1,align:"right",buttons:[{text:"Ok",addClass:"",promptTrigger:!0,click:function(b,a){b.remove();b.get().trigger("pnotify.confirm",[b,a])}},{text:"Cancel",addClass:"",click:function(b){b.remove();b.get().trigger("pnotify.cancel",b)}}]};e.prototype.modules.confirm=
{container:null,prompt:null,init:function(b,a){this.container=d('<div style="margin-top:5px;clear:both;" />').css("text-align",a.align).appendTo(b.container);a.confirm||a.prompt?this.makeDialog(b,a):this.container.hide()},update:function(b,a){a.confirm?(this.makeDialog(b,a),this.container.show()):this.container.hide().empty()},afterOpen:function(b,a){a.prompt&&this.prompt.focus()},makeDialog:function(b,a){var e=!1,h=this,f,c;this.container.empty();a.prompt&&(this.prompt=d("<"+(a.prompt_multi_line?
'textarea rows="5"':'input type="text"')+' style="margin-bottom:5px;clear:both;" />').addClass(b.styles.input+" "+a.prompt_class).val(a.prompt_default).appendTo(this.container));for(var k in a.buttons){f=a.buttons[k];e?this.container.append(" "):e=!0;c=d('<button type="button" />').addClass(b.styles.btn+" "+f.addClass).text(f.text).appendTo(this.container).on("click",function(g){return function(){"function"==typeof g.click&&g.click(b,a.prompt?h.prompt.val():null)}}(f));a.prompt&&!a.prompt_multi_line&&
f.promptTrigger&&this.prompt.keypress(function(b){return function(a){13==a.keyCode&&b.click()}}(c));b.styles.text&&c.wrapInner('<span class="'+b.styles.text+'"></span>');b.styles.btnhover&&c.hover(function(a){return function(){a.addClass(b.styles.btnhover)}}(c),function(a){return function(){a.removeClass(b.styles.btnhover)}}(c));if(b.styles.btnactive)c.on("mousedown",function(a){return function(){a.addClass(b.styles.btnactive)}}(c)).on("mouseup",function(a){return function(){a.removeClass(b.styles.btnactive)}}(c));
if(b.styles.btnfocus)c.on("focus",function(a){return function(){a.addClass(b.styles.btnfocus)}}(c)).on("blur",function(a){return function(){a.removeClass(b.styles.btnfocus)}}(c))}}};d.extend(e.styling.jqueryui,{btn:"ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only",btnhover:"ui-state-hover",btnactive:"ui-state-active",btnfocus:"ui-state-focus",input:"",text:"ui-button-text"});d.extend(e.styling.bootstrap2,{btn:"btn",input:""});d.extend(e.styling.bootstrap3,{btn:"btn btn-default",
input:"form-control"});d.extend(e.styling.fontawesome,{btn:"btn btn-default",input:"form-control"})});
(function(e){"function"===typeof define&&define.amd?define("pnotify.desktop",["jquery","pnotify"],e):e(jQuery,PNotify)})(function(e,d){var c,f=function(a,b){f="Notification"in window?function(a,b){return new Notification(a,b)}:"mozNotification"in navigator?function(a,b){return navigator.mozNotification.createNotification(a,b.body,b.icon).show()}:"webkitNotifications"in window?function(a,b){return window.webkitNotifications.createNotification(b.icon,a,b.body)}:function(a,b){return null};return f(a,
b)};d.prototype.options.desktop={desktop:!1,icon:null,tag:null};d.prototype.modules.desktop={tag:null,icon:null,genNotice:function(a,b){this.icon=null===b.icon?"http://sciactive.com/pnotify/includes/desktop/"+a.options.type+".png":!1===b.icon?null:b.icon;if(null===this.tag||null!==b.tag)this.tag=null===b.tag?"PNotify-"+Math.round(1E6*Math.random()):b.tag;a.desktop=f(a.options.title,{icon:this.icon,body:a.options.text,tag:this.tag});"close"in a.desktop||(a.desktop.close=function(){a.desktop.cancel()});
a.desktop.onclick=function(){a.elem.trigger("click")};a.desktop.onclose=function(){"closing"!==a.state&&"closed"!==a.state&&a.remove()}},init:function(a,b){b.desktop&&(c=d.desktop.checkPermission(),0==c&&this.genNotice(a,b))},update:function(a,b,d){0==c&&b.desktop&&this.genNotice(a,b)},beforeOpen:function(a,b){0==c&&b.desktop&&a.elem.css({left:"-10000px",display:"none"})},afterOpen:function(a,b){0==c&&b.desktop&&(a.elem.css({left:"-10000px",display:"none"}),"show"in a.desktop&&a.desktop.show())},
beforeClose:function(a,b){0==c&&b.desktop&&a.elem.css({left:"-10000px",display:"none"})},afterClose:function(a,b){0==c&&b.desktop&&(a.elem.css({left:"-10000px",display:"none"}),a.desktop.close())}};d.desktop={permission:function(){"undefined"!==typeof Notification&&"requestPermission"in Notification?Notification.requestPermission():"webkitNotifications"in window&&window.webkitNotifications.requestPermission()},checkPermission:function(){return"undefined"!==typeof Notification&&"permission"in Notification?
"granted"==Notification.permission?0:1:"webkitNotifications"in window?window.webkitNotifications.checkPermission():1}};c=d.desktop.checkPermission()});
(function(a){"function"===typeof define&&define.amd?define("pnotify.history",["jquery","pnotify"],a):a(jQuery,PNotify)})(function(a,b){var d,f;a(function(){a("body").on("pnotify.history-all",function(){a.each(b.notices,function(){this.modules.history.inHistory&&(this.elem.is(":visible")?this.options.hide&&this.queueRemove():this.open&&this.open())})}).on("pnotify.history-last",function(){var a="top"===b.prototype.options.stack.push,c=a?0:-1,e;do{e=-1===c?b.notices.slice(c):b.notices.slice(c,c+1);
if(!e[0])return!1;c=a?c+1:c-1}while(!e[0].modules.history.inHistory||e[0].elem.is(":visible"));e[0].open&&e[0].open()})});b.prototype.options.history={history:!0,menu:!1,fixed:!0,maxonscreen:Infinity,labels:{redisplay:"Redisplay",all:"All",last:"Last"}};b.prototype.modules.history={inHistory:!1,init:function(b,c){b.options.destroy=!1;this.inHistory=c.history;if(c.menu&&"undefined"===typeof d){d=a("<div />",{"class":"ui-pnotify-history-container "+b.styles.hi_menu,mouseleave:function(){d.animate({top:"-"+
f+"px"},{duration:100,queue:!1})}}).append(a("<div />",{"class":"ui-pnotify-history-header",text:c.labels.redisplay})).append(a("<button />",{"class":"ui-pnotify-history-all "+b.styles.hi_btn,text:c.labels.all,mouseenter:function(){a(this).addClass(b.styles.hi_btnhov)},mouseleave:function(){a(this).removeClass(b.styles.hi_btnhov)},click:function(){a(this).trigger("pnotify.history-all");return!1}})).append(a("<button />",{"class":"ui-pnotify-history-last "+b.styles.hi_btn,text:c.labels.last,mouseenter:function(){a(this).addClass(b.styles.hi_btnhov)},
mouseleave:function(){a(this).removeClass(b.styles.hi_btnhov)},click:function(){a(this).trigger("pnotify.history-last");return!1}})).appendTo("body");var e=a("<span />",{"class":"ui-pnotify-history-pulldown "+b.styles.hi_hnd,mouseenter:function(){d.animate({top:"0"},{duration:100,queue:!1})}}).appendTo(d);console.log(e.offset());f=e.offset().top+2;d.css({top:"-"+f+"px"});c.fixed&&d.addClass("ui-pnotify-history-fixed")}},update:function(b,a){this.inHistory=a.history;a.fixed&&d?d.addClass("ui-pnotify-history-fixed"):
d&&d.removeClass("ui-pnotify-history-fixed")},beforeOpen:function(d,c){if(b.notices&&b.notices.length>c.maxonscreen){var e;e="top"!==d.options.stack.push?b.notices.slice(0,b.notices.length-c.maxonscreen):b.notices.slice(c.maxonscreen,b.notices.length);a.each(e,function(){this.remove&&this.remove()})}}};a.extend(b.styling.jqueryui,{hi_menu:"ui-state-default ui-corner-bottom",hi_btn:"ui-state-default ui-corner-all",hi_btnhov:"ui-state-hover",hi_hnd:"ui-icon ui-icon-grip-dotted-horizontal"});a.extend(b.styling.bootstrap2,
{hi_menu:"well",hi_btn:"btn",hi_btnhov:"",hi_hnd:"icon-chevron-down"});a.extend(b.styling.bootstrap3,{hi_menu:"well",hi_btn:"btn btn-default",hi_btnhov:"",hi_hnd:"fa fa-chevron-down"});a.extend(b.styling.fontawesome,{hi_menu:"well",hi_btn:"btn btn-default",hi_btnhov:"",hi_hnd:"fa fa-chevron-down"})});
(function(f){"function"===typeof define&&define.amd?define("pnotify.nonblock",["jquery","pnotify"],f):f(jQuery,PNotify)})(function(f,g){var l=/^on/,m=/^(dbl)?click$|^mouse(move|down|up|over|out|enter|leave)$|^contextmenu$/,n=/^(focus|blur|select|change|reset)$|^key(press|down|up)$/,p=/^(scroll|resize|(un)?load|abort|error)$/,h=function(b,a){var c;b=b.toLowerCase();document.createEvent&&this.dispatchEvent?(b=b.replace(l,""),b.match(m)?(f(this).offset(),c=document.createEvent("MouseEvents"),c.initMouseEvent(b,
a.bubbles,a.cancelable,a.view,a.detail,a.screenX,a.screenY,a.clientX,a.clientY,a.ctrlKey,a.altKey,a.shiftKey,a.metaKey,a.button,a.relatedTarget)):b.match(n)?(c=document.createEvent("UIEvents"),c.initUIEvent(b,a.bubbles,a.cancelable,a.view,a.detail)):b.match(p)&&(c=document.createEvent("HTMLEvents"),c.initEvent(b,a.bubbles,a.cancelable)),c&&this.dispatchEvent(c)):(b.match(l)||(b="on"+b),c=document.createEventObject(a),this.fireEvent(b,c))},e,d=function(b,a,c){b.elem.css("display","none");var k=document.elementFromPoint(a.clientX,
a.clientY);b.elem.css("display","block");var d=f(k),g=d.css("cursor");b.elem.css("cursor","auto"!==g?g:"default");e&&e.get(0)==k||(e&&(h.call(e.get(0),"mouseleave",a.originalEvent),h.call(e.get(0),"mouseout",a.originalEvent)),h.call(k,"mouseenter",a.originalEvent),h.call(k,"mouseover",a.originalEvent));h.call(k,c,a.originalEvent);e=d};g.prototype.options.nonblock={nonblock:!1,nonblock_opacity:0.2};g.prototype.modules.nonblock={myOptions:null,init:function(b,a){var c=this;this.myOptions=a;b.elem.on({mouseenter:function(a){c.myOptions.nonblock&&
a.stopPropagation();c.myOptions.nonblock&&b.elem.stop().animate({opacity:c.myOptions.nonblock_opacity},"fast")},mouseleave:function(a){c.myOptions.nonblock&&a.stopPropagation();e=null;b.elem.css("cursor","auto");c.myOptions.nonblock&&"out"!==b.animating&&b.elem.stop().animate({opacity:b.options.opacity},"fast")},mouseover:function(a){c.myOptions.nonblock&&a.stopPropagation()},mouseout:function(a){c.myOptions.nonblock&&a.stopPropagation()},mousemove:function(a){c.myOptions.nonblock&&(a.stopPropagation(),
d(b,a,"onmousemove"))},mousedown:function(a){c.myOptions.nonblock&&(a.stopPropagation(),a.preventDefault(),d(b,a,"onmousedown"))},mouseup:function(a){c.myOptions.nonblock&&(a.stopPropagation(),a.preventDefault(),d(b,a,"onmouseup"))},click:function(a){c.myOptions.nonblock&&(a.stopPropagation(),d(b,a,"onclick"))},dblclick:function(a){c.myOptions.nonblock&&(a.stopPropagation(),d(b,a,"ondblclick"))}})},update:function(b,a){this.myOptions=a}}});
(function(d){"function"===typeof define&&define.amd?define("pnotify.reference",["jquery","pnotify"],d):d(jQuery,PNotify)})(function(d,c){c.prototype.options.reference={putThing:!1,labels:{text:"Spin Around"}};c.prototype.modules.reference={thingElem:null,init:function(b,a){var c=this;a.putThing&&(this.thingElem=d('<button style="float:right;" class="btn btn-default" type="button" disabled><i class="'+b.styles.athing+'" />&nbsp;'+a.labels.text+"</button>").appendTo(b.container),b.container.append('<div style="clear: right; line-height: 0;" />'),
b.elem.on({mouseenter:function(b){c.thingElem.prop("disabled",!1)},mouseleave:function(b){c.thingElem.prop("disabled",!0)}}),this.thingElem.on("click",function(){var a=0,c=setInterval(function(){a+=10;360==a&&(a=0,clearInterval(c));b.elem.css({"-moz-transform":"rotate("+a+"deg)","-webkit-transform":"rotate("+a+"deg)","-o-transform":"rotate("+a+"deg)","-ms-transform":"rotate("+a+"deg)",filter:"progid:DXImageTransform.Microsoft.BasicImage(rotation="+a/360*4+")"})},20)}))},update:function(b,a,c){a.putThing&&
this.thingElem?this.thingElem.show():!a.putThing&&this.thingElem&&this.thingElem.hide();this.thingElem&&this.thingElem.find("i").attr("class",b.styles.athing)},beforeOpen:function(b,a){},afterOpen:function(b,a){},beforeClose:function(b,a){},afterClose:function(b,a){},beforeDestroy:function(b,a){},afterDestroy:function(b,a){}};d.extend(c.styling.jqueryui,{athing:"ui-icon ui-icon-refresh"});d.extend(c.styling.bootstrap2,{athing:"icon-refresh"});d.extend(c.styling.bootstrap3,{athing:"fa fa-refresh"});
d.extend(c.styling.fontawesome,{athing:"fa fa-refresh"})});

/*! modernizr 3.3.1 (Custom Build) | MIT *
 * http://modernizr.com/download/?-audio-canvas-emoji-flash-ie8compat-svg-video-addtest-atrule-domprefixes-hasevent-mq-prefixed-prefixedcss-prefixedcssvalue-prefixes-printshiv-setclasses-testallprops-testprop-teststyles !*/
!function(e,t,n){function r(e,t){return typeof e===t}function o(){var e,t,n,o,a,i,s;for(var c in C)if(C.hasOwnProperty(c)){if(e=[],t=C[c],t.name&&(e.push(t.name.toLowerCase()),t.options&&t.options.aliases&&t.options.aliases.length))for(n=0;n<t.options.aliases.length;n++)e.push(t.options.aliases[n].toLowerCase());for(o=r(t.fn,"function")?t.fn():t.fn,a=0;a<e.length;a++)i=e[a],s=i.split("."),1===s.length?Modernizr[s[0]]=o:(!Modernizr[s[0]]||Modernizr[s[0]]instanceof Boolean||(Modernizr[s[0]]=new Boolean(Modernizr[s[0]])),Modernizr[s[0]][s[1]]=o),b.push((o?"":"no-")+s.join("-"))}}function a(e){var t=w.className,n=Modernizr._config.classPrefix||"";if(x&&(t=t.baseVal),Modernizr._config.enableJSClass){var r=new RegExp("(^|\\s)"+n+"no-js(\\s|$)");t=t.replace(r,"$1"+n+"js$2")}Modernizr._config.enableClasses&&(t+=" "+n+e.join(" "+n),x?w.className.baseVal=t:w.className=t)}function i(e,t){if("object"==typeof e)for(var n in e)P(e,n)&&i(n,e[n]);else{e=e.toLowerCase();var r=e.split("."),o=Modernizr[r[0]];if(2==r.length&&(o=o[r[1]]),"undefined"!=typeof o)return Modernizr;t="function"==typeof t?t():t,1==r.length?Modernizr[r[0]]=t:(!Modernizr[r[0]]||Modernizr[r[0]]instanceof Boolean||(Modernizr[r[0]]=new Boolean(Modernizr[r[0]])),Modernizr[r[0]][r[1]]=t),a([(t&&0!=t?"":"no-")+r.join("-")]),Modernizr._trigger(e,t)}return Modernizr}function s(){return"function"!=typeof t.createElement?t.createElement(arguments[0]):x?t.createElementNS.call(t,"http://www.w3.org/2000/svg",arguments[0]):t.createElement.apply(t,arguments)}function c(e){return e.replace(/([a-z])-([a-z])/g,function(e,t,n){return t+n.toUpperCase()}).replace(/^-/,"")}function l(e){return e.replace(/([A-Z])/g,function(e,t){return"-"+t.toLowerCase()}).replace(/^ms-/,"-ms-")}function u(){var e=t.body;return e||(e=s(x?"svg":"body"),e.fake=!0),e}function f(e,n,r,o){var a,i,c,l,f="modernizr",d=s("div"),p=u();if(parseInt(r,10))for(;r--;)c=s("div"),c.id=o?o[r]:f+(r+1),d.appendChild(c);return a=s("style"),a.type="text/css",a.id="s"+f,(p.fake?p:d).appendChild(a),p.appendChild(d),a.styleSheet?a.styleSheet.cssText=e:a.appendChild(t.createTextNode(e)),d.id=f,p.fake&&(p.style.background="",p.style.overflow="hidden",l=w.style.overflow,w.style.overflow="hidden",w.appendChild(p)),i=n(d,e),p.fake?(p.parentNode.removeChild(p),w.style.overflow=l,w.offsetHeight):d.parentNode.removeChild(d),!!i}function d(e,t){return!!~(""+e).indexOf(t)}function p(t,r){var o=t.length;if("CSS"in e&&"supports"in e.CSS){for(;o--;)if(e.CSS.supports(l(t[o]),r))return!0;return!1}if("CSSSupportsRule"in e){for(var a=[];o--;)a.push("("+l(t[o])+":"+r+")");return a=a.join(" or "),f("@supports ("+a+") { #modernizr { position: absolute; } }",function(e){return"absolute"==getComputedStyle(e,null).position})}return n}function m(e,t){return function(){return e.apply(t,arguments)}}function v(e,t,n){var o;for(var a in e)if(e[a]in t)return n===!1?e[a]:(o=t[e[a]],r(o,"function")?m(o,n||t):o);return!1}function h(e,t,o,a){function i(){u&&(delete z.style,delete z.modElem)}if(a=r(a,"undefined")?!1:a,!r(o,"undefined")){var l=p(e,o);if(!r(l,"undefined"))return l}for(var u,f,m,v,h,y=["modernizr","tspan"];!z.style;)u=!0,z.modElem=s(y.shift()),z.style=z.modElem.style;for(m=e.length,f=0;m>f;f++)if(v=e[f],h=z.style[v],d(v,"-")&&(v=c(v)),z.style[v]!==n){if(a||r(o,"undefined"))return i(),"pfx"==t?v:!0;try{z.style[v]=o}catch(g){}if(z.style[v]!=h)return i(),"pfx"==t?v:!0}return i(),!1}function y(e,t,n,o,a){var i=e.charAt(0).toUpperCase()+e.slice(1),s=(e+" "+j.join(i+" ")+i).split(" ");return r(t,"string")||r(t,"undefined")?h(s,t,o,a):(s=(e+" "+_.join(i+" ")+i).split(" "),v(s,t,n))}function g(e,t,r){return y(e,n,n,t,r)}var b=[],C=[],E={_version:"3.3.1",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,t){var n=this;setTimeout(function(){t(n[e])},0)},addTest:function(e,t,n){C.push({name:e,fn:t,options:n})},addAsyncTest:function(e){C.push({name:null,fn:e})}},Modernizr=function(){};Modernizr.prototype=E,Modernizr=new Modernizr,Modernizr.addTest("ie8compat",!e.addEventListener&&!!t.documentMode&&7===t.documentMode),Modernizr.addTest("svg",!!t.createElementNS&&!!t.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect);var T=E._config.usePrefixes?" -webkit- -moz- -o- -ms- ".split(" "):["",""];E._prefixes=T;var w=t.documentElement,x="svg"===w.nodeName.toLowerCase();x||!function(e,t){function n(e,t){var n=e.createElement("p"),r=e.getElementsByTagName("head")[0]||e.documentElement;return n.innerHTML="x<style>"+t+"</style>",r.insertBefore(n.lastChild,r.firstChild)}function r(){var e=x.elements;return"string"==typeof e?e.split(" "):e}function o(e,t){var n=x.elements;"string"!=typeof n&&(n=n.join(" ")),"string"!=typeof e&&(e=e.join(" ")),x.elements=n+" "+e,l(t)}function a(e){var t=w[e[E]];return t||(t={},T++,e[E]=T,w[T]=t),t}function i(e,n,r){if(n||(n=t),h)return n.createElement(e);r||(r=a(n));var o;return o=r.cache[e]?r.cache[e].cloneNode():C.test(e)?(r.cache[e]=r.createElem(e)).cloneNode():r.createElem(e),!o.canHaveChildren||b.test(e)||o.tagUrn?o:r.frag.appendChild(o)}function s(e,n){if(e||(e=t),h)return e.createDocumentFragment();n=n||a(e);for(var o=n.frag.cloneNode(),i=0,s=r(),c=s.length;c>i;i++)o.createElement(s[i]);return o}function c(e,t){t.cache||(t.cache={},t.createElem=e.createElement,t.createFrag=e.createDocumentFragment,t.frag=t.createFrag()),e.createElement=function(n){return x.shivMethods?i(n,e,t):t.createElem(n)},e.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+r().join().replace(/[\w\-:]+/g,function(e){return t.createElem(e),t.frag.createElement(e),'c("'+e+'")'})+");return n}")(x,t.frag)}function l(e){e||(e=t);var r=a(e);return!x.shivCSS||v||r.hasCSS||(r.hasCSS=!!n(e,"article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")),h||c(e,r),e}function u(e){for(var t,n=e.getElementsByTagName("*"),o=n.length,a=RegExp("^(?:"+r().join("|")+")$","i"),i=[];o--;)t=n[o],a.test(t.nodeName)&&i.push(t.applyElement(f(t)));return i}function f(e){for(var t,n=e.attributes,r=n.length,o=e.ownerDocument.createElement(_+":"+e.nodeName);r--;)t=n[r],t.specified&&o.setAttribute(t.nodeName,t.nodeValue);return o.style.cssText=e.style.cssText,o}function d(e){for(var t,n=e.split("{"),o=n.length,a=RegExp("(^|[\\s,>+~])("+r().join("|")+")(?=[[\\s,>+~#.:]|$)","gi"),i="$1"+_+"\\:$2";o--;)t=n[o]=n[o].split("}"),t[t.length-1]=t[t.length-1].replace(a,i),n[o]=t.join("}");return n.join("{")}function p(e){for(var t=e.length;t--;)e[t].removeNode()}function m(e){function t(){clearTimeout(i._removeSheetTimer),r&&r.removeNode(!0),r=null}var r,o,i=a(e),s=e.namespaces,c=e.parentWindow;return!P||e.printShived?e:("undefined"==typeof s[_]&&s.add(_),c.attachEvent("onbeforeprint",function(){t();for(var a,i,s,c=e.styleSheets,l=[],f=c.length,p=Array(f);f--;)p[f]=c[f];for(;s=p.pop();)if(!s.disabled&&S.test(s.media)){try{a=s.imports,i=a.length}catch(m){i=0}for(f=0;i>f;f++)p.push(a[f]);try{l.push(s.cssText)}catch(m){}}l=d(l.reverse().join("")),o=u(e),r=n(e,l)}),c.attachEvent("onafterprint",function(){p(o),clearTimeout(i._removeSheetTimer),i._removeSheetTimer=setTimeout(t,500)}),e.printShived=!0,e)}var v,h,y="3.7.3",g=e.html5||{},b=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,C=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,E="_html5shiv",T=0,w={};!function(){try{var e=t.createElement("a");e.innerHTML="<xyz></xyz>",v="hidden"in e,h=1==e.childNodes.length||function(){t.createElement("a");var e=t.createDocumentFragment();return"undefined"==typeof e.cloneNode||"undefined"==typeof e.createDocumentFragment||"undefined"==typeof e.createElement}()}catch(n){v=!0,h=!0}}();var x={elements:g.elements||"abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output picture progress section summary template time video",version:y,shivCSS:g.shivCSS!==!1,supportsUnknownElements:h,shivMethods:g.shivMethods!==!1,type:"default",shivDocument:l,createElement:i,createDocumentFragment:s,addElements:o};e.html5=x,l(t);var S=/^$|\b(?:all|print)\b/,_="html5shiv",P=!h&&function(){var n=t.documentElement;return!("undefined"==typeof t.namespaces||"undefined"==typeof t.parentWindow||"undefined"==typeof n.applyElement||"undefined"==typeof n.removeNode||"undefined"==typeof e.attachEvent)}();x.type+=" print",x.shivPrint=m,m(t),"object"==typeof module&&module.exports&&(module.exports=x)}("undefined"!=typeof e?e:this,t);var S="Moz O ms Webkit",_=E._config.usePrefixes?S.toLowerCase().split(" "):[];E._domPrefixes=_;var P;!function(){var e={}.hasOwnProperty;P=r(e,"undefined")||r(e.call,"undefined")?function(e,t){return t in e&&r(e.constructor.prototype[t],"undefined")}:function(t,n){return e.call(t,n)}}(),E._l={},E.on=function(e,t){this._l[e]||(this._l[e]=[]),this._l[e].push(t),Modernizr.hasOwnProperty(e)&&setTimeout(function(){Modernizr._trigger(e,Modernizr[e])},0)},E._trigger=function(e,t){if(this._l[e]){var n=this._l[e];setTimeout(function(){var e,r;for(e=0;e<n.length;e++)(r=n[e])(t)},0),delete this._l[e]}},Modernizr._q.push(function(){E.addTest=i});var N=function(){function e(e,t){var o;return e?(t&&"string"!=typeof t||(t=s(t||"div")),e="on"+e,o=e in t,!o&&r&&(t.setAttribute||(t=s("div")),t.setAttribute(e,""),o="function"==typeof t[e],t[e]!==n&&(t[e]=n),t.removeAttribute(e)),o):!1}var r=!("onblur"in t.documentElement);return e}();E.hasEvent=N;var k=function(e,t){var n=!1,r=s("div"),o=r.style;if(e in o){var a=_.length;for(o[e]=t,n=o[e];a--&&!n;)o[e]="-"+_[a]+"-"+t,n=o[e]}return""===n&&(n=!1),n};E.prefixedCSSValue=k,Modernizr.addTest("audio",function(){var e=s("audio"),t=!1;try{(t=!!e.canPlayType)&&(t=new Boolean(t),t.ogg=e.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/,""),t.mp3=e.canPlayType('audio/mpeg; codecs="mp3"').replace(/^no$/,""),t.opus=e.canPlayType('audio/ogg; codecs="opus"')||e.canPlayType('audio/webm; codecs="opus"').replace(/^no$/,""),t.wav=e.canPlayType('audio/wav; codecs="1"').replace(/^no$/,""),t.m4a=(e.canPlayType("audio/x-m4a;")||e.canPlayType("audio/aac;")).replace(/^no$/,""))}catch(n){}return t}),Modernizr.addTest("canvas",function(){var e=s("canvas");return!(!e.getContext||!e.getContext("2d"))}),Modernizr.addTest("video",function(){var e=s("video"),t=!1;try{(t=!!e.canPlayType)&&(t=new Boolean(t),t.ogg=e.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),t.h264=e.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),t.webm=e.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,""),t.vp9=e.canPlayType('video/webm; codecs="vp9"').replace(/^no$/,""),t.hls=e.canPlayType('application/x-mpegURL; codecs="avc1.42E01E"').replace(/^no$/,""))}catch(n){}return t});var j=E._config.usePrefixes?S.split(" "):[];E._cssomPrefixes=j;var $=function(t){var r,o=T.length,a=e.CSSRule;if("undefined"==typeof a)return n;if(!t)return!1;if(t=t.replace(/^@/,""),r=t.replace(/-/g,"_").toUpperCase()+"_RULE",r in a)return"@"+t;for(var i=0;o>i;i++){var s=T[i],c=s.toUpperCase()+"_"+r;if(c in a)return"@-"+s.toLowerCase()+"-"+t}return!1};E.atRule=$,Modernizr.addTest("canvastext",function(){return Modernizr.canvas===!1?!1:"function"==typeof s("canvas").getContext("2d").fillText}),Modernizr.addTest("emoji",function(){if(!Modernizr.canvastext)return!1;var t=e.devicePixelRatio||1,n=12*t,r=s("canvas"),o=r.getContext("2d");return o.fillStyle="#f00",o.textBaseline="top",o.font="32px Arial",o.fillText("🐨",0,0),0!==o.getImageData(n,n,1,1).data[0]}),Modernizr.addAsyncTest(function(){var n,r,o=function(e){w.contains(e)||w.appendChild(e)},a=function(e){e.fake&&e.parentNode&&e.parentNode.removeChild(e)},c=function(e,t){var n=!!e;if(n&&(n=new Boolean(n),n.blocked="blocked"===e),i("flash",function(){return n}),t&&m.contains(t)){for(;t.parentNode!==m;)t=t.parentNode;m.removeChild(t)}};try{r="ActiveXObject"in e&&"Pan"in new e.ActiveXObject("ShockwaveFlash.ShockwaveFlash")}catch(l){}if(n=!("plugins"in navigator&&"Shockwave Flash"in navigator.plugins||r),n||x)c(!1);else{var f,d,p=s("embed"),m=u();if(p.type="application/x-shockwave-flash",m.appendChild(p),!("Pan"in p||r))return o(m),c("blocked",p),void a(m);f=function(){return o(m),w.contains(m)?(w.contains(p)?(d=p.style.cssText,""!==d?c("blocked",p):c(!0,p)):c("blocked"),void a(m)):(m=t.body||m,p=s("embed"),p.type="application/x-shockwave-flash",m.appendChild(p),setTimeout(f,1e3))},setTimeout(f,10)}});var A=function(){var t=e.matchMedia||e.msMatchMedia;return t?function(e){var n=t(e);return n&&n.matches||!1}:function(t){var n=!1;return f("@media "+t+" { #modernizr { position: absolute; } }",function(t){n="absolute"==(e.getComputedStyle?e.getComputedStyle(t,null):t.currentStyle).position}),n}}();E.mq=A;var F=(E.testStyles=f,{elem:s("modernizr")});Modernizr._q.push(function(){delete F.elem});var z={style:F.elem.style};Modernizr._q.unshift(function(){delete z.style});E.testProp=function(e,t,r){return h([e],n,t,r)};E.testAllProps=y;var M=E.prefixed=function(e,t,n){return 0===e.indexOf("@")?$(e):(-1!=e.indexOf("-")&&(e=c(e)),t?y(e,t,n):y(e,"pfx"))};E.prefixedCSS=function(e){var t=M(e);return t&&l(t)};E.testAllProps=g,o(),a(b),delete E.addTest,delete E.addAsyncTest;for(var L=0;L<Modernizr._q.length;L++)Modernizr._q[L]();e.Modernizr=Modernizr}(window,document);

// http://spin.js.org/#v2.3.2
!function(a,b){"object"==typeof module&&module.exports?module.exports=b():"function"==typeof define&&define.amd?define(b):a.Spinner=b()}(this,function(){"use strict";function a(a,b){var c,d=document.createElement(a||"div");for(c in b)d[c]=b[c];return d}function b(a){for(var b=1,c=arguments.length;c>b;b++)a.appendChild(arguments[b]);return a}function c(a,b,c,d){var e=["opacity",b,~~(100*a),c,d].join("-"),f=.01+c/d*100,g=Math.max(1-(1-a)/b*(100-f),a),h=j.substring(0,j.indexOf("Animation")).toLowerCase(),i=h&&"-"+h+"-"||"";return m[e]||(k.insertRule("@"+i+"keyframes "+e+"{0%{opacity:"+g+"}"+f+"%{opacity:"+a+"}"+(f+.01)+"%{opacity:1}"+(f+b)%100+"%{opacity:"+a+"}100%{opacity:"+g+"}}",k.cssRules.length),m[e]=1),e}function d(a,b){var c,d,e=a.style;if(b=b.charAt(0).toUpperCase()+b.slice(1),void 0!==e[b])return b;for(d=0;d<l.length;d++)if(c=l[d]+b,void 0!==e[c])return c}function e(a,b){for(var c in b)a.style[d(a,c)||c]=b[c];return a}function f(a){for(var b=1;b<arguments.length;b++){var c=arguments[b];for(var d in c)void 0===a[d]&&(a[d]=c[d])}return a}function g(a,b){return"string"==typeof a?a:a[b%a.length]}function h(a){this.opts=f(a||{},h.defaults,n)}function i(){function c(b,c){return a("<"+b+' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">',c)}k.addRule(".spin-vml","behavior:url(#default#VML)"),h.prototype.lines=function(a,d){function f(){return e(c("group",{coordsize:k+" "+k,coordorigin:-j+" "+-j}),{width:k,height:k})}function h(a,h,i){b(m,b(e(f(),{rotation:360/d.lines*a+"deg",left:~~h}),b(e(c("roundrect",{arcsize:d.corners}),{width:j,height:d.scale*d.width,left:d.scale*d.radius,top:-d.scale*d.width>>1,filter:i}),c("fill",{color:g(d.color,a),opacity:d.opacity}),c("stroke",{opacity:0}))))}var i,j=d.scale*(d.length+d.width),k=2*d.scale*j,l=-(d.width+d.length)*d.scale*2+"px",m=e(f(),{position:"absolute",top:l,left:l});if(d.shadow)for(i=1;i<=d.lines;i++)h(i,-2,"progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");for(i=1;i<=d.lines;i++)h(i);return b(a,m)},h.prototype.opacity=function(a,b,c,d){var e=a.firstChild;d=d.shadow&&d.lines||0,e&&b+d<e.childNodes.length&&(e=e.childNodes[b+d],e=e&&e.firstChild,e=e&&e.firstChild,e&&(e.opacity=c))}}var j,k,l=["webkit","Moz","ms","O"],m={},n={lines:12,length:7,width:5,radius:10,scale:1,corners:1,color:"#000",opacity:.25,rotate:0,direction:1,speed:1,trail:100,fps:20,zIndex:2e9,className:"spinner",top:"50%",left:"50%",shadow:!1,hwaccel:!1,position:"absolute"};if(h.defaults={},f(h.prototype,{spin:function(b){this.stop();var c=this,d=c.opts,f=c.el=a(null,{className:d.className});if(e(f,{position:d.position,width:0,zIndex:d.zIndex,left:d.left,top:d.top}),b&&b.insertBefore(f,b.firstChild||null),f.setAttribute("role","progressbar"),c.lines(f,c.opts),!j){var g,h=0,i=(d.lines-1)*(1-d.direction)/2,k=d.fps,l=k/d.speed,m=(1-d.opacity)/(l*d.trail/100),n=l/d.lines;!function o(){h++;for(var a=0;a<d.lines;a++)g=Math.max(1-(h+(d.lines-a)*n)%l*m,d.opacity),c.opacity(f,a*d.direction+i,g,d);c.timeout=c.el&&setTimeout(o,~~(1e3/k))}()}return c},stop:function(){var a=this.el;return a&&(clearTimeout(this.timeout),a.parentNode&&a.parentNode.removeChild(a),this.el=void 0),this},lines:function(d,f){function h(b,c){return e(a(),{position:"absolute",width:f.scale*(f.length+f.width)+"px",height:f.scale*f.width+"px",background:b,boxShadow:c,transformOrigin:"left",transform:"rotate("+~~(360/f.lines*k+f.rotate)+"deg) translate("+f.scale*f.radius+"px,0)",borderRadius:(f.corners*f.scale*f.width>>1)+"px"})}for(var i,k=0,l=(f.lines-1)*(1-f.direction)/2;k<f.lines;k++)i=e(a(),{position:"absolute",top:1+~(f.scale*f.width/2)+"px",transform:f.hwaccel?"translate3d(0,0,0)":"",opacity:f.opacity,animation:j&&c(f.opacity,f.trail,l+k*f.direction,f.lines)+" "+1/f.speed+"s linear infinite"}),f.shadow&&b(i,e(h("#000","0 0 4px #000"),{top:"2px"})),b(d,b(i,h(g(f.color,k),"0 0 1px rgba(0,0,0,.1)")));return d},opacity:function(a,b,c){b<a.childNodes.length&&(a.childNodes[b].style.opacity=c)}}),"undefined"!=typeof document){k=function(){var c=a("style",{type:"text/css"});return b(document.getElementsByTagName("head")[0],c),c.sheet||c.styleSheet}();var o=e(a("group"),{behavior:"url(#default#VML)"});!d(o,"transform")&&o.adj?i():j=d(o,"animation")}return h});
!function(n){if("object"==typeof exports)n(require("jquery"),require("spin.js"));else if("function"==typeof define&&define.amd)define(["jquery","spin"],n);else{if(!window.Spinner)throw new Error("Spin.js not present");n(window.jQuery,window.Spinner)}}(function(n,e){n.fn.spin=function(i,s){return this.each(function(){var r=n(this),t=r.data();t.spinner&&(t.spinner.stop(),delete t.spinner),i!==!1&&(i=n.extend({color:s||r.css("color")},n.fn.spin.presets[i]||i),t.spinner=new e(i).spin(this))})},n.fn.spin.presets={tiny:{lines:8,length:2,width:2,radius:3},small:{lines:8,length:4,width:3,radius:5},large:{lines:10,length:8,width:4,radius:8}}});

/*! sprintf-js | Alexandru Marasteanu <hello@alexei.ro> (http://alexei.ro/) | BSD-3-Clause */

!function(a){function b(){var a=arguments[0],c=b.cache;return c[a]&&c.hasOwnProperty(a)||(c[a]=b.parse(a)),b.format.call(null,c[a],arguments)}function c(a){return Object.prototype.toString.call(a).slice(8,-1).toLowerCase()}function d(a,b){return Array(b+1).join(a)}var e={not_string:/[^s]/,number:/[diefg]/,json:/[j]/,not_json:/[^j]/,text:/^[^\x25]+/,modulo:/^\x25{2}/,placeholder:/^\x25(?:([1-9]\d*)\$|\(([^\)]+)\))?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-gijosuxX])/,key:/^([a-z_][a-z_\d]*)/i,key_access:/^\.([a-z_][a-z_\d]*)/i,index_access:/^\[(\d+)\]/,sign:/^[\+\-]/};b.format=function(a,f){var g,h,i,j,k,l,m,n=1,o=a.length,p="",q=[],r=!0,s="";for(h=0;o>h;h++)if(p=c(a[h]),"string"===p)q[q.length]=a[h];else if("array"===p){if(j=a[h],j[2])for(g=f[n],i=0;i<j[2].length;i++){if(!g.hasOwnProperty(j[2][i]))throw new Error(b("[sprintf] property '%s' does not exist",j[2][i]));g=g[j[2][i]]}else g=j[1]?f[j[1]]:f[n++];if("function"==c(g)&&(g=g()),e.not_string.test(j[8])&&e.not_json.test(j[8])&&"number"!=c(g)&&isNaN(g))throw new TypeError(b("[sprintf] expecting number but found %s",c(g)));switch(e.number.test(j[8])&&(r=g>=0),j[8]){case"b":g=g.toString(2);break;case"c":g=String.fromCharCode(g);break;case"d":case"i":g=parseInt(g,10);break;case"j":g=JSON.stringify(g,null,j[6]?parseInt(j[6]):0);break;case"e":g=j[7]?g.toExponential(j[7]):g.toExponential();break;case"f":g=j[7]?parseFloat(g).toFixed(j[7]):parseFloat(g);break;case"g":g=j[7]?parseFloat(g).toPrecision(j[7]):parseFloat(g);break;case"o":g=g.toString(8);break;case"s":g=(g=String(g))&&j[7]?g.substring(0,j[7]):g;break;case"u":g>>>=0;break;case"x":g=g.toString(16);break;case"X":g=g.toString(16).toUpperCase()}e.json.test(j[8])?q[q.length]=g:(!e.number.test(j[8])||r&&!j[3]?s="":(s=r?"+":"-",g=g.toString().replace(e.sign,"")),l=j[4]?"0"===j[4]?"0":j[4].charAt(1):" ",m=j[6]-(s+g).length,k=j[6]&&m>0?d(l,m):"",q[q.length]=j[5]?s+g+k:"0"===l?s+k+g:k+s+g)}return q.join("")},b.cache={},b.parse=function(a){for(var b=a,c=[],d=[],f=0;b;){if(null!==(c=e.text.exec(b)))d[d.length]=c[0];else if(null!==(c=e.modulo.exec(b)))d[d.length]="%";else{if(null===(c=e.placeholder.exec(b)))throw new SyntaxError("[sprintf] unexpected placeholder");if(c[2]){f|=1;var g=[],h=c[2],i=[];if(null===(i=e.key.exec(h)))throw new SyntaxError("[sprintf] failed to parse named argument key");for(g[g.length]=i[1];""!==(h=h.substring(i[0].length));)if(null!==(i=e.key_access.exec(h)))g[g.length]=i[1];else{if(null===(i=e.index_access.exec(h)))throw new SyntaxError("[sprintf] failed to parse named argument key");g[g.length]=i[1]}c[2]=g}else f|=2;if(3===f)throw new Error("[sprintf] mixing positional and named placeholders is not (yet) supported");d[d.length]=c}b=b.substring(c[0].length)}return d};var f=function(a,c,d){return d=(c||[]).slice(0),d.splice(0,0,a),b.apply(null,d)};"undefined"!=typeof exports?(exports.sprintf=b,exports.vsprintf=f):(a.sprintf=b,a.vsprintf=f,"function"==typeof define&&define.amd&&define(function(){return{sprintf:b,vsprintf:f}}))}("undefined"==typeof window?this:window);
//# sourceMappingURL=sprintf.min.map