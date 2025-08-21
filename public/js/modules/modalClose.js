export const modalClose = () => {
    document.querySelector("#pickupModal .close")?.addEventListener("click", () => {
		document.getElementById("pickupModal").style.display = "none";
		document.body.style.overflow = "";
	});
	document.querySelector("#destinationModal .close")?.addEventListener("click", () => {
		document.getElementById("destinationModal").style.display = "none";
		document.body.style.overflow = "";
	});
}