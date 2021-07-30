<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\Livewire;

class RouteRegistrationTest extends TestCase
{
    /** @test */
    public function can_pass_parameters_to_a_layout_file()
    {
        Livewire::component(ComponentForRouteRegistration::class);

        Route::get('/foo', ComponentForRouteRegistration::class);

        $this->withoutExceptionHandling()->get('/foo')->assertSee('baz');
    }

    /** @test */
    public function component_uses_alias_instead_of_full_name_if_registered()
    {
        Livewire::component('component-alias', ComponentForRouteRegistration::class);

        Route::get('/foo', ComponentForRouteRegistration::class);

        $this->withoutExceptionHandling()->get('/foo')
            ->assertSee('component-alias');
    }
}

class ComponentForRouteRegistration extends Component
{
    public $name = 'bar';

    public function render()
    {
        return view('show-name')->layout('layouts.app-with-bar', [
            'bar' => 'baz',
        ]);
    }
}
