<?php

namespace Acme\Extension\ApiResources\repositories;

use Admin\Models\Tables_model;
use Igniter\Api\Classes\AbstractRepository;

class TablesByLocation extends AbstractRepository
{
    protected $guarded = [];

    protected $modelClass = Tables_model::class;

    protected static $locationAwareConfig = [];
}
