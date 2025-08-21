export const destinationModalCollapse = () => {
    let countries = document.querySelectorAll('#destinationModal .country');
	    countries?.forEach(function(country) {
		let label = country.querySelector('.country-label');
		let airportList = country.querySelector('.airport-list');
		label.addEventListener('click', () => {
			country.classList.toggle('active');
			if (country.classList.contains('active')) {
				airportList.style.display = 'block';
			} else {
				airportList.style.display = 'none';
			}
		});
	});
}