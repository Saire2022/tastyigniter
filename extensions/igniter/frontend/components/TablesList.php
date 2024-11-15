<?php

namespace Igniter\FrontEnd\Components;

use Igniter\Flame\Cart\Facades\Cart;
use System\Classes\BaseComponent;
use Admin\Models\Tables_model;

use Admin\Models\Locations_model;
use Admin\Models\Orders_model;
use Illuminate\Support\Facades\Session;
use Igniter\Flame\Exception\ApplicationException;
use Igniter\FrontEnd\Classes\FrontendController;


class TablesList extends BaseComponent
{
    public $tables;

    public function defineProperties()
    {
        return [];
    }

    public function onRun() {
        Cart::destroy();
        $this->page['tables'] = $this->loadItems();
        $this->page['locationName'] = $this->getLocationName();
        $this->page['orders'] = $this->loadOrders();
    }
    protected function loadItems()
    {
        $locationId = Session::get('local_info.id');

        if (!$locationId) {
            throw new ApplicationException("No location selected.");
        }
        $tables = Tables_model::whereHasLocation($locationId)->get();

        return $tables;
    }

    protected function getLocationName()
    {
        $locationId = Session::get('local_info.id');
        $location = Locations_model::find($locationId);
        return $location ? $location->location_name : 'Default Location'; // Default fallback if no location is found
    }

    protected function loadOrders()
    {
        $locationId = Session::get('local_info.id');
        // Assuming you have a column `location_id` in orders table
        return Orders_model::where('location_id', $locationId)->get();
    }
}
