<?php

namespace Livewire\HydrationMiddleware;

use Illuminate\Validation\ValidationException;
use Livewire\Livewire;

class PerformEventEmissions implements HydrationMiddleware
{
    public static function hydrate($unHydratedInstance, $request)
    {
        try {
            foreach ($request->updates as $update) {
                if ($update['type'] !== 'fireEvent') {
                    continue;
                }

                $event = $update['payload']['event'];
                $params = $update['payload']['params'];

                $unHydratedInstance->fireEvent($event, $params);
            }
        } catch (ValidationException $e) {
            Livewire::dispatch('failed-validation', $e->validator);

            $unHydratedInstance->setErrorBag($e->validator->errors());
        }
    }

    public static function dehydrate($instance, $response)
    {
        //
    }
}
