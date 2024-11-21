<div id="select-location" class="module-box py-5">
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

<div id="select-customer-attendance" class="module-box py-5">
    <div class="container text-center">
        <div class="locations-container">
            <div class="locations-container">
                <a
                    href="{{ page_url('account/register') }}"
                    class="location-card">
                    <p>I am a new Customer</p>
                </a>
                <a
                    href="{{ site_url('/login') }}"
                    class="location-card">
                    <p> I am already registered</p>
                </a>
            </div>
        </div>
    </div>
</div>


@php
    $localInfo = session('local_info', []);
    $localInfo['id'] = 3;
    session(['local_info' => $localInfo]);
@endphp
@dump(session()->all())
