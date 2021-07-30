<?php

namespace Livewire\RenameMe;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Carbon as IlluminateCarbon;
use Livewire\Livewire;

class SupportDateTimes
{
    public static function init()
    {
        return new static;
    }

    public function __construct()
    {
        Livewire::listen('property.dehydrate', function ($name, $value, $component, $response) {
            if (! $value instanceof \DateTime) {
                return;
            }

            $component->{$name} = $value->format(\DateTimeInterface::ISO8601);

            data_fill($response->memo, 'dataMeta.dates', []);

            if ($value instanceof IlluminateCarbon) {
                $response->memo['dataMeta']['dates'][$name] = 'illuminate';
            } elseif ($value instanceof Carbon) {
                $response->memo['dataMeta']['dates'][$name] = 'carbon';
            } else {
                $response->memo['dataMeta']['dates'][$name] = 'native';
            }
        });

        Livewire::listen('property.hydrate', function ($name, $value, $component, $request) {
            $dates = data_get($request->memo, 'dataMeta.dates', []);

            $types = [
                'native' => DateTime::class,
                'carbon' => Carbon::class,
                'illuminate' => IlluminateCarbon::class,
            ];

            foreach ($dates as $name => $type) {
                data_set($component, $name, new $types[$type]($value));
            }
        });
    }
}
