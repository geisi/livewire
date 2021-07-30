<?php

namespace Tests\Browser\QueryString;

use Livewire\Component as BaseComponent;

class ComponentWithMethodInsteadOfProperty extends BaseComponent
{
    public $foo = 'bar';

    public function queryString()
    {
        return ['foo'];
    }

    public function render()
    {
        return '<div>{{ $foo }}</div>';
    }
}
