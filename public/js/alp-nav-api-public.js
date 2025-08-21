import {backgroundImage} from '../modules/background-image.js';
import { pickupSearchModal } from '../modules/pickupSearchModal.js';
import { destinationSearchModal } from '../modules/destinationSearchModal.js';
import { attachPickupHandlers } from '../modules/attachPickupHandlers.js';
import { pickupModalCollapse } from '../modules/pickupModalCollapse.js';
import { modalClose } from '../modules/modalClose.js';
import { destinationModalCollapse } from '../modules/destinationModalCollapse.js';
import { attachDestinationHandlers } from '../modules/attachDestinationHandlers.js';
document.addEventListener('DOMContentLoaded', function() {
	'use strict';
	backgroundImage();

	document.getElementById("departure-field")?.addEventListener("click", () => {
		document.getElementById("pickupModal").style.display = "block";
		document.body.style.overflow = "hidden";
		attachPickupHandlers();
	});

	document.getElementById("destination-field")?.addEventListener("click", () => {
		document.getElementById("destinationModal").style.display = "block";
		document.body.style.overflow = "hidden";
		attachDestinationHandlers();
	});

	window.addEventListener("click", function(e) {
		if (e.target.id === "pickupModal") {
			document.getElementById("pickupModal").style.display = "none";
			document.body.style.overflow = "";
		}
	});

	window.addEventListener("click", function(e) {
		if (e.target.id === "destinationModal") {
			document.getElementById("destinationModal").style.display = "none";
			document.body.style.overflow = "";
		}
	});

	modalClose();
	pickupModalCollapse();
	destinationModalCollapse();
	pickupSearchModal();
	destinationSearchModal();
});