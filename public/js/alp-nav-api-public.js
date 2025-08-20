document.addEventListener('DOMContentLoaded', function() {
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

	updateAll();
	window.addEventListener('resize', updateAll);
	
	document.getElementById("departure-field").addEventListener("click", () => {
		document.getElementById("airportModal").style.display = "block";
	});

	document.querySelector(".modal .close").addEventListener("click", () => {
		document.getElementById("airportModal").style.display = "none";
	});

	document.querySelectorAll("#airportModal li").forEach(item => {
		item.addEventListener("click", function() {
			let selectedAirport = this.textContent;
			document.getElementById("departure-field").innerHTML = selectedAirport;
			document.getElementById("airportModal").style.display = "none";
		});
	});

	window.addEventListener("click", function(e) {
		if (e.target.id === "airportModal") {
			document.getElementById("airportModal").style.display = "none";
		}
	});
});
