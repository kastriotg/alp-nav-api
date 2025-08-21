export const pickupModalCollapse = () => {
    let countries = document.querySelectorAll('#pickupModal .country');
	    countries?.forEach(function(country) {
		let label = country.querySelector('.country-label');
		let airportList = country.querySelector('.airport-list');
		label.addEventListener('click', function() {
			country.classList.toggle('active');
			if (country.classList.contains('active')) {
				airportList.style.display = 'block';
			} else {
				airportList.style.display = 'none';
			}
		});
	});
}