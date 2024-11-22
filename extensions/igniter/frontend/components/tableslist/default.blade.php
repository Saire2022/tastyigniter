
<div class="container mt-5 ">
    <h2 class="mb-3"> Select Table for {{ $locationName }}</h2>
</div>

@partial('@tables')

@dump(session()->all())
