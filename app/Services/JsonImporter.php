<?php

namespace App\Services;
use Illuminate\Support\Collection;


class JsonImporter extends Importer
{
    /**
     * Create a new class instance.
     */
    // public function __construct()
    // {
    //     //
    // }

    public function import(string $file): Collection
    {
        if (!is_readable($file)) {
            throw new \RuntimeException("JSON file not readable: {$file}");
        }

        $raw = file_get_contents($file);
        $decoded = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON: ' . json_last_error_msg());
        }

        if (!is_array($decoded)) {
            throw new \RuntimeException('JSON must decode to an array');
        }

        return collect($decoded)->map(fn ($row) => (array) $row);
    }
}
