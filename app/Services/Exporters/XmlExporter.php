<?php

namespace App\Services\Exporters;

class XmlExporter extends Exporter
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
        $xml = new \SimpleXMLElement('<root/>');

        foreach ($data as $item) {
            $child = $xml->addChild('item');
            foreach ($item as $key => $value) {
                $child->addChild($key, htmlspecialchars((string)$value));
            }
        }

        return $xml->asXML();
    }
}
