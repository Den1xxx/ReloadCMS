/*
 * IE PNG Fix v1.4
 * Copyright (c) 2006 Takashi Aida http://www.isella.com/aod2/
 */

// IE5.5+ PNG Alpha Fix v1.0RC4
// (c) 2004-2005 Angus Turnbull http://www.twinhelix.com

// This is licensed under the CC-GNU LGPL, version 2.1 or later.
// For details, see: http://creativecommons.org/licenses/LGPL/2.1/

if (typeof IEPNGFIX == 'undefined') {
//--============================================================================

var IEPNGFIX = {
	blank:  'images/blank.gif',
	filter: 'DXImageTransform.Microsoft.AlphaImageLoader',

	fixit: function (elem, src, method) {
		if (elem.filters[this.filter]) {
			var filter = elem.filters[this.filter];
			filter.enabled = true;
			filter.src = src;
			filter.sizingMethod = method;
		}
		else {
			elem.style.filter = 'progid:' + this.filter +
				'(src="' + src + '",sizingMethod="' + method + '")';
		}
	},

	fixwidth: function(elem) {
		if (elem.currentStyle.width == 'auto' &&
			elem.currentStyle.height == 'auto') {
			elem.style.width = elem.offsetWidth + 'px';
		}
	},

	fixchild: function(elem, recursive) {
		if (!/MSIE (5\.5|6\.|7\.)/.test(navigator.userAgent)) return;

		for (var i = 0, n = elem.childNodes.length; i < n; i++) {
			var childNode = elem.childNodes[i];
			if (childNode.style) {
				if (childNode.style.position) {
					childNode.style.position = childNode.style.position;
				}
				else {
					childNode.style.position = 'relative';
				}
			}
			if (recursive && childNode.hasChildNodes()) {
				this.fixchild(childNode, recursive);
			}
		}
	},

	fix: function(elem) {
		if (!/MSIE (5\.5|6\.|7\.)/.test(navigator.userAgent)) return;

		var bgImg =
			elem.currentStyle.backgroundImage || elem.style.backgroundImage;

		if (elem.tagName == 'IMG') {
			if ((/\.png$/i).test(elem.src)) {
				this.fixwidth(elem);
				this.fixit(elem, elem.src, 'scale');
				elem.src = this.blank;
				elem.runtimeStyle.behavior = 'none';
			}
		}
		else if (bgImg && bgImg != 'none') {
			if (bgImg.match(/^url[("']+(.*\.png)[)"']+$/i)) {
				var s = RegExp.$1;
				this.fixwidth(elem);
				elem.style.backgroundImage = 'none';
				this.fixit(elem, s, 'scale'); // crop | image | scale

				if (elem.tagName == 'A' && elem.style) {
					if (!elem.style.cursor) {
						elem.style.cursor = 'pointer';
					}
				}

				this.fixchild(elem);
				elem.runtimeStyle.behavior = 'none';
			}
		}
	},

	hover: function(elem, hvImg) {
		var bgImg = elem.style.backgroundImage;

		if (!bgImg && elem.currentStyle) bgImg = elem.currentStyle.backgroundImage;

		if (elem.tagName == 'IMG' && hvImg) {
			var image = elem.src;
			elem.onmouseover = function() {
				elem.src = hvImg;
				IEPNGFIX.fix(elem);
			};
			elem.onmouseout = function() {
				elem.src = image;
				IEPNGFIX.fix(elem);
			};
		}
		else if (bgImg && bgImg != 'none' && hvImg) {
			elem.onmouseover = function() {
				elem.style.backgroundImage = 'url(' + hvImg + ')';
				IEPNGFIX.fix(elem);
			};
			elem.onmouseout = function() {
				elem.style.backgroundImage = bgImg;
				IEPNGFIX.fix(elem);
			};
		}

		IEPNGFIX.fix(elem);
	}
};

//--============================================================================
} // end if (typeof IEPNGFIX == 'undefined')
