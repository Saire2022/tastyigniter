document.addEventListener("DOMContentLoaded", function () {
    const locationData = localStorage.getItem("restaurantLocationId");
    const tablesData = localStorage.getItem("tables");

    const selectLocationSection = document.getElementById("select-location");
    const selectCustomerAttendanceSection = document.getElementById("select-customer-attendance");

    if (!locationData) {
        selectLocationSection.style.display = "block";
        selectCustomerAttendanceSection.style.display = "none";
    } else {
        selectLocationSection.style.display = "none";
        selectCustomerAttendanceSection.style.display = "block";
        //window.location.href = '/login';
    }
});

function selectLocation(locationId, locationName) {
    // Save location data to localStorage
    localStorage.setItem("restaurantLocationId", locationId);
    localStorage.setItem("restaurantLocationName", locationName);

    // Send an AJAX request to the server to update the session
    $.request('setlocal::onSaveLocationId', {
        data: { location_id: locationId },
        success: function(response) {
            console.log('Location updated successfully!', response);
            location.reload();
        },
        error: function(error) {
            console.error('Error updating location:', error);
        }
    });
}
