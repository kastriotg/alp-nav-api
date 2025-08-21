export const attachPickupHandlers = () => {
    document.querySelectorAll("#pickupModal li")?.forEach(item => {
        item.addEventListener("click", () => {
            document.body.style.overflow = "";
            let selectedPickupPlace = item.textContent;
            document.getElementById("departure-field").innerHTML = selectedPickupPlace;
            document.getElementById("pickupModal").style.display = "none";
        });
    });
}