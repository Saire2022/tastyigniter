<?php namespace Acme\Extension\ApiResources;

use Acme\Extension\ApiResources\Transformers\TablesByLocationTransformer;
use Admin\Models\Tables_model;
use Igniter\Api\Classes\ApiController;

/**
 * Tablesbylocation API Controller
 */
class TablesByLocation extends ApiController
{
    public $implement = [
        'Igniter.Api.Actions.RestController'
    ];

    public $restConfig = [
        'actions' => [
            'getbylocation' => []
        ],
        'request' => \Admin\Requests\Table::class,
        'repository' => \Repositories\TablesByLocation::class,
        'transformer' => \Transformers\TablesByLocationTransformer::class,
    ];

    protected $requiredAbilities = ['tablesbylocation:*'];
    public function getbylocation($locationId)
    {
        // Fetch the tables based on the location ID
        $tables = Tables_model::whereHasLocation($locationId)->get();
        //$tables->toArray();

        // Return the response
        return response()->json($tables);
    }
}
