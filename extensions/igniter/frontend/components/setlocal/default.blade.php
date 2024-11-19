<div id="featured-menu-box" class="module-box py-5">
    <div class="container text-center">
        <h2 class="mb-3">{{ $featuredTitle }}</h2>
        <div class="locations-container">
            @foreach($locations as $location)
                <div class="location-card">
                    <h3>{{$location->location_name}}</h3>
                    <p><strong>Location ID:</strong> {{$location->location_id}}</p>
                    <button onclick="selectLocation({{$location->location_id}}, '{{$location->location_name}}')">
                        Select
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function selectLocation(locationId, locationName) {
        // Store location in localStorage
        localStorage.setItem("restaurantLocationId", locationId);
        localStorage.setItem("restaurantLocationName", locationName);

        // Redirect or reload page
        alert(`Location ${locationName} selected!`);
        location.reload(); // Reload or redirect to the appropriate page
    }
</script>

