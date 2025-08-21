export const attachDestinationHandlers = () => {
    document.querySelectorAll("#destinationModal li")?.forEach(item => {
        item.addEventListener("click", () => {
            document.body.style.overflow = "";
            let selectedPickupPlace = item.textContent;
            document.getElementById("destination-field").innerHTML = selectedPickupPlace;
            document.getElementById("destinationModal").style.display = "none";
        });
    });
}