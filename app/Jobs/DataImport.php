<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DataImport implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable, SerializesModels;

    public string $modelClass;
    public string $fileContent;
    public string $extension;


    public function __construct(string $modelClass, string $fileContent, string $extension)
    {
        $this->modelClass  = $modelClass;
        $this->fileContent = $fileContent;
        $this->extension   = $extension;
    }


    public function handle(): void
    {
        $modelClass = $this->modelClass;

        logger("DataImport started", [
            'extension'   => $this->extension,
            'fileContent' => $this->fileContent,
        ]);

        if ($this->extension === 'csv') {
            if (!file_exists($this->fileContent)) {
                logger("DataImport ERROR: CSV file does not exist at path", [$this->fileContent]);
                return;
            }

            $result = $modelClass::importFromFile($this->fileContent);
        } else {
            $result = $modelClass::importFromString($this->fileContent, $this->extension);
        }

        logger("DataImport: Imported {$result['imported']} rows.");
        if (!empty($result['messages'])) {
            logger("DataImport messages:", $result['messages']);
        }
    }

}