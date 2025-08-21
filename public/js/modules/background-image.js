export const backgroundImage = () => {
    	function setBg(section) {
	    var mobileUrl = section.getAttribute('data-mobile-url');
	    var wideUrl = section.getAttribute('data-wide-url');
	    var w = window.innerWidth;
	    var url = w > 768 && wideUrl ? wideUrl : mobileUrl;
	    section.style.backgroundImage = url ? "url('" + url + "')" : 'none';
	}
	const updateAll = () => {
	    var sections = document.querySelectorAll('.alpnav-banner-widget[data-mobile-url][data-wide-url]');
	    sections.forEach(setBg);
	}

	updateAll();
	window.addEventListener('resize', updateAll);
}