<?php

namespace Igniter\Api\ApiResources\Repositories;

use Admin\Models\Tables_model;
use Igniter\Api\Classes\AbstractRepository;

class TableRepository extends AbstractRepository
{
    protected $guarded = [];

    protected $modelClass = Tables_model::class;

    protected static $locationAwareConfig = [];

    public function getByLocation($locationId)
    {
        // Assuming you have a Table model with a `location_id` or similar column
        return Tables_model::whereHasLocation($locationId)->get()
            ->paginate(20); // Adjust this according to your pagination needs
    }
}
