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
        //alert(`Location ${localStorage.getItem("restaurantLocationName")} was selected!`);
    }
});

function selectLocation(locationId, locationName) {
    localStorage.setItem("restaurantLocationId", locationId);
    localStorage.setItem("restaurantLocationName", locationName);
    fetchTables(locationId);

    //document.getElementById("select-location").style.display = "none";
    //document.getElementById("select-customer-attendance").style.display = "block";

    // Redirect or reload page
    //alert(`Location ${locationName} selected!`);
    location.reload(); // Reload or redirect to the appropriate page
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

        // Check if the response is successful (status code 200)
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

