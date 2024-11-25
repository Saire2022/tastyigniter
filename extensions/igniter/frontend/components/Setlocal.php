<?php

namespace Igniter\Frontend\Components;

use Admin\Models\Locations_model;
use Admin\Models\Tables_model;
use Igniter\Flame\Cart\Facades\Cart;
use System\Classes\BaseComponent;
use Illuminate\Support\Facades\Log;

class Setlocal extends BaseComponent
{
    public function defineProperties()
    {
        return [];
    }

    public function onRun() {
        $this->addJs('/js/setlocation.js');
        $this->prepareVars();
        $this->page['locations'] = $this->getLocations();
    }

    protected function prepareVars(){
        Log::info('Generated Event Handler: ' . $this->getEventHandler('onSaveLocationId'));
        $this->page['locationEventHandler'] = $this->getEventHandler('onSaveLocationId');
        //Log::info('Event Handler: '.$this->page['locationEventHandler']);
    }
    public function initialize()
    {
    }

    public function onSaveLocationId()
    {
        Cart::destroy();
        $locationId = post('location_id');
        Log::info('Location ID Received: ' . $locationId);
        if (!$locationId) {
            throw new ApplicationException(lang('Location ID is required.'));
        }

        session()->put('local_info.id', $locationId);

        return ['message' => 'Location updated successfully!', 'location_id' => $locationId];
    }

    protected function getLocations(){
        return Locations_model::all();
    }

}
