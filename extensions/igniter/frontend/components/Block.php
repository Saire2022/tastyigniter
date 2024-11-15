<?php

namespace Igniter\Frontend\Components;

use System\Classes\BaseComponent;

class Block extends BaseComponent
{
    public function defineProperties()
    {
        return [
            $message = [
                'code' => 'helloBlock',
                'name' => 'Name of the hello block component',
                'description' => 'Description of the hello block component',
            ],
        ];
    }

    public function onRun() {
        // Do something when the component is loaded by a page or layout

    }
}
