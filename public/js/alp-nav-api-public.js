(function( $ ) {
	'use strict';
	function setBg(section) {
	    var mobileUrl = section.getAttribute('data-mobile-url');
	    var wideUrl = section.getAttribute('data-wide-url');
	    var w = window.innerWidth;
	    var url = w > 768 && wideUrl ? wideUrl : mobileUrl;
	    section.style.backgroundImage = url ? "url('" + url + "')" : 'none';
	}
	function updateAll() {
	    var sections = document.querySelectorAll('.alpnav-banner-widget[data-mobile-url][data-wide-url]');
	    sections.forEach(setBg);
	}
	document.addEventListener('DOMContentLoaded', function() {
	    updateAll();
	    window.addEventListener('resize', updateAll);
	});
})( jQuery );
