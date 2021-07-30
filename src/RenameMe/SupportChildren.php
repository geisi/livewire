<?php

namespace Livewire\RenameMe;

use Livewire\Livewire;

class SupportChildren
{
    public static function init()
    {
        return new static;
    }

    public function __construct()
    {
        Livewire::listen('component.dehydrate', function ($component, $response) {
            $response->memo['children'] = $component->getRenderedChildren();
        });

        Livewire::listen('component.hydrate.subsequent', function ($component, $request) {
            $component->setPreviouslyRenderedChildren($request->memo['children']);
        });
    }
}
