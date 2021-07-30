<?php

namespace Livewire\RenameMe;

use Livewire\Livewire;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SupportFileDownloads
{
    public static function init()
    {
        return new static;
    }

    protected $downloadsById = [];

    public function __construct()
    {
        Livewire::listen('action.returned', function ($component, $action, $returned) {
            if ($this->valueIsntAFileResponse($returned)) {
                return;
            }

            $response = $returned;

            $name = $this->getFilenameFromContentDispositionHeader(
                $response->headers->get('Content-Disposition')
            );

            $binary = $this->captureOutput(function () use ($response) {
                $response->sendContent();
            });

            $content = base64_encode($binary);

            $this->downloadsById[$component->id] = [
                'name' => $name,
                'content' => $content,
            ];

            $component->skipRender();
        });

        Livewire::listen('component.dehydrate.subsequent', function ($component, $response) {
            if (! $download = $this->downloadsById[$component->id] ?? false) {
                return;
            }

            $response->effects['download'] = $download;
        });
    }

    public function valueIsntAFileResponse($value)
    {
        return ! $value instanceof StreamedResponse
            && ! $value instanceof BinaryFileResponse;
    }

    public function captureOutput($callback)
    {
        ob_start();

        $callback();

        return ob_get_clean();
    }

    public function getFilenameFromContentDispositionHeader($header)
    {
        /**
         * The following conditionals are here to allow for quoted and
         * non quoted filenames in the Content-Disposition header.
         *
         * Both of these values should return the correct filename without quotes.
         *
         * Content-Disposition: attachment; filename=filename.jpg
         * Content-Disposition: attachment; filename="test file.jpg"
         */
        if (preg_match('/.*?filename="(.+?)"/', $header, $matches)) {
            return $matches[1];
        }

        if (preg_match('/.*?filename=([^; ]+)/', $header, $matches)) {
            return $matches[1];
        }

        return 'download';
    }
}
