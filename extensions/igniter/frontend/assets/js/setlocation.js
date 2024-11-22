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
    $.request('onSaveLocationId', {
        data: { location_id: locationId },
        success: function(response) {
            console.log('Location updated successfully!', response);
            //fetchTables(locationId); // Fetch tables after updating location
            location.reload(); // Reload the page to reflect the new location
        },
        error: function(error) {
            console.error('Error updating location:', error);
        }
    });
}

async function fetchTables(locationId) {

    const accessToken = 'EobZFn7Vw1KRlz6Peefy9FYfYprlRlA9Rp3njTYFakeHoGJgA56vaVsqM2H8gZdXMC9A8J1zjeQymgnx';

    if (!accessToken) {
        console.error("Access token is missing!");
        return;
    }
    console.log(accessToken)

    try {
        const response = await fetch(`https://mytasty.ddev.site/api/tables-by-location/${locationId}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${accessToken}`,
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Failed to fetch tables: ${response.statusText}`);
        }

        // Parse the JSON response
        const data = await response.json();
        console.log(data);

        // Store the tables data in localStorage
        localStorage.setItem("tables", JSON.stringify(data.tables));
    } catch (err) {
        console.error("Error fetching tables:", err);
    }
}

