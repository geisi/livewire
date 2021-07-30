<?php

namespace Livewire\RenameMe;

use Livewire\Livewire;

class Placeholder
{
    public static function init()
    {
        return new static;
    }

    public function __construct()
    {
        Livewire::listen('component.hydrate', function ($component, $request) {
            //
        });

        Livewire::listen('component.dehydrate', function ($component, $response) {
            //
        });
    }
}
