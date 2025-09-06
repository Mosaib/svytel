<?php

namespace App\Services\Exporters;
use League\Csv\Writer;
use SplTempFileObject;

class CsvExporter extends Exporter
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
        $csv = Writer::createFromFileObject(new SplTempFileObject());

        if (count($data) === 0) return '';

        $csv->insertOne(array_keys($data[0]));

        foreach ($data as $row) {
            $csv->insertOne($row);
        }

        return $csv->getContent();
    }
}
