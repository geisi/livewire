<?php

namespace Tests\Unit;

use Livewire\Component;
use Livewire\Livewire;

class LivewireThisKeywordInBladeTest extends TestCase
{
    /** @test */
    public function this_keyword_will_reference_the_livewire_component_class()
    {
        Livewire::test(ComponentForTestingThisKeyword::class)
            ->assertSee(ComponentForTestingThisKeyword::class);
    }
}

class ComponentForTestingThisKeyword extends Component
{
    public function render()
    {
        return view('this-keyword');
    }
}
