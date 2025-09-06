<?php

namespace App;

use App\Services\CsvImporter;
use App\Services\JsonImporter;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait Importable
{
    public static function importFromFile(string $file): array
    {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        $importer = match ($ext) {
            'csv' => new CsvImporter(),
            'json' => new JsonImporter(),
            default => throw new \InvalidArgumentException("Unsupported format: {$ext}"),
        };

        $rows = $importer->import($file);
        return static::processRows($rows);
    }

    public static function importFromString(string $content, string $extension): array
    {
        $ext = strtolower($extension);

        $importer = match ($ext) {
            'csv' => new CsvImporter(),
            'json' => new JsonImporter(),
            default => throw new \InvalidArgumentException("Unsupported format: {$ext}"),
        };

        if ($ext === 'csv') {
            $tempFile = tempnam(sys_get_temp_dir(), 'import_csv_');
            file_put_contents($tempFile, $content);
            $rows = $importer->import($tempFile);
            unlink($tempFile);
        } else {
            $tempFile = tempnam(sys_get_temp_dir(), 'import_json_');
            file_put_contents($tempFile, $content);
            $rows = $importer->import($tempFile);
            unlink($tempFile);
        }

        return static::processRows($rows);
    }

    protected static function processRows(array|Collection $rows): array
    {
        $count = 0;
        $messages = [];

        foreach ($rows as $index => $row) {
            $data = Arr::only($row, (new static)->getFillable());

            if (empty($data['email']) || empty($data['password'])) {
                $messages[] = "Row #{$index} skipped: missing required fields";
                continue;
            }

            try {
                if (static::where('email', $data['email'])->exists()) {
                    $messages[] = "Row #{$index} skipped: duplicate email ({$data['email']})";
                    continue;
                }

                static::create($data);
                $count++;
            } catch (\Exception $e) {
                $messages[] = "Row #{$index} failed: {$e->getMessage()}";
            }
        }

        return [
            'imported' => $count,
            'messages' => $messages,
        ];
    }
}