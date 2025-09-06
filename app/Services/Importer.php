<?php

namespace App\Services;
use Illuminate\Support\Collection;

abstract class Importer
{
    /**
     * Create a new class instance.
     */
    // public function __construct()
    // {
    //     //
    // }
    abstract public function import(string $file): Collection;
}
