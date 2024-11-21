<?php

$api = app('api.router');

$api->version('v1', function ($api) {
    // Define the routes to issue tokens.
    $api->post('/token', [\Igniter\Api\Controllers\Tokens::class, 'create']);
    // Custom route for fetching tables by location
    $api->get('/tables/location', [\Igniter\Api\ApiResources\Tables::class, 'getbylocation']);
    //dd('Routes file loaded');
    $api->get('/tables-by-location/{locationId}', [Acme\Extension\ApiResources\TablesByLocation::class, 'getbylocation']);

});
