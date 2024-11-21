<?php namespace Acme\Extension\ApiResources\Transformers;

use Admin\Models\Tables_model;
use League\Fractal\TransformerAbstract;

class TablesByLocationTransformer extends TransformerAbstract
{
    public function transform(Tables_model $table)
    {
        return $table->toArray();
    }
}
