<?php

namespace App\Services\Exporters;

abstract class Exporter
{
    /**
     * Create a new class instance.
     */
    abstract public function export(array $data): string;
    // public function __construct()
    // {
    //     //
    // }
}
