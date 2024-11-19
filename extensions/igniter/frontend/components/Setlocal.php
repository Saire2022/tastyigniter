<?php

namespace Igniter\Frontend\Components;

use Admin\Models\Locations_model;
use Admin\Models\Tables_model;
use System\Classes\BaseComponent;

class Setlocal extends BaseComponent
{
    public function defineProperties()
    {
        return [];
    }

    public function onRun() {
        $this->page['tables'] = $this->loadTables();
        $this->page['locations'] = $this->getLocations();
    }
    public function initialize()
    {
        $this->addJs('js/setlocal.js');
    }

    protected function loadTables(){
        $locationId = input('location_id');
        $tables = Tables_model::whereHasLocation($locationId)->get();
        //return response()->json(['tables' => $tables]);
        return $tables;
    }

    protected function getLocations(){
        return Locations_model::all();
    }

}
