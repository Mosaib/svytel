<?php

namespace App\Services;
use Illuminate\Support\Collection;

class CsvImporter extends Importer
{
    public function import(string $file): Collection
    {
        $file = realpath($file);

        if ($file === false || !is_readable($file)) {
            throw new \RuntimeException("CSV file not readable: {$file}");
        }

        $handle = fopen($file, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Unable to open file: {$file}");
        }

        $header = null;
        $rows = [];


        $firstLine = fgets($handle);
        rewind($handle);
        $delimiter = str_contains($firstLine, "\t") ? "\t" : ",";

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if ($header === null) {
                $header = array_map(fn($h) => preg_replace('/^\xEF\xBB\xBF/', '', $h), $row);
                continue;
            }

            if (count($row) !== count($header)) {
                continue;
            }

            $rows[] = array_combine($header, $row);
        }

        fclose($handle);

        return collect($rows);
    }
}
