export const destinationSearchModal = () => {
    let searchInput = document.querySelector('#destinationModal input[name="search"]');
	if (searchInput) {
		searchInput.addEventListener('input', function() {
			let query = this.value.trim().toLowerCase();
			let countries = document.querySelectorAll('#destinationModal .country');
			countries.forEach(function(country) {
				let anyVisible = false;
				let airportList = country.querySelector('.airport-list');
				let items = airportList.querySelectorAll('li');
				items.forEach(function(li) {
					let name = li.textContent.trim().toLowerCase();
					if (name.includes(query)) {
						li.style.display = '';
						li.parentElement.style.display = 'block';
						anyVisible = true;
					}
					else {
						li.style.display = 'none';
					}
				});
				country.style.display = anyVisible ? '' : 'none';
				document.getElementById('destination-form').style.display = 'none';
			});
			// if query is empty, hide locations of countries
			if (query === '') {
				countries.forEach(function(country) {
					let pickupList = country.querySelector('.airport-list');
					pickupList.style.display = 'none';
				});
				document.getElementById('destination-form').style.display = 'block';
			}

		});
	}
}