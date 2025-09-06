<?php

namespace App\Services\Exporters;

class JsonExporter extends Exporter
{
    /**
     * Create a new class instance.
     */
    // public function __construct()
    // {
    //     //
    // }

    public function export(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
