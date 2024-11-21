<?php

namespace Igniter\Api\ApiResources;

use Igniter\Api\Classes\ApiController;

/**
 * Tables API Controller
 */
class Tables extends ApiController
{
    public $implement = ['Igniter.Api.Actions.RestController'];

    public $restConfig = [
        'actions' => [
            'index' => [
                'pageLimit' => 20,
            ],
            'store' => [],
            'show' => [],
            'update' => [],
            'destroy' => [],
        ],
        'request' => \Admin\Requests\Table::class,
        'repository' => Repositories\TableRepository::class,
        'transformer' => Transformers\TableTransformer::class,
    ];

    protected $requiredAbilities = ['tables:*'];

    public function getbylocation()
    {
        dd('getbylocation action hit');
        $location = request()->input('location_id');
        //dd($location);

        if (!$location) {
            return $this->response->errorBadRequest('Location parameter is required.');
        }

        // Retrieve tables based on location
        $tables = $this->repository->getByLocation($location);

        // Return paginated data using transformer
        return $this->response->paginator($tables, $this->transformer);
    }
}
