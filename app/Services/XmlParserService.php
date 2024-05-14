<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\Log;

class XmlParserService
{
    private const VALID_COMPONENT_ID = '030-032-000-000';
    private const ERROR_BODY = 'error';

    /**
     * @throws \Exception
     */
    public function parse($file): bool
    {
        $xmlContent = file_get_contents($file->path());
        $xml = simplexml_load_string($xmlContent);

        if ($xml) {
            $components = $xml->xpath('//Component');

            foreach ($components as $component) {

                $componentId = (string)$component['Id'];
                if ($componentId !== self::VALID_COMPONENT_ID) {
                    continue;
                }

                $value = (string)$component->Value;
                $limit = (string)$component->Limit;
                $error = (string)$component->Error;
                if ($value !== '' || $limit !== '' || strtolower($error) !== self::ERROR_BODY) {
                    continue;
                }

                $fileName = uniqid('xml_').'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                File::create(['name' => $fileName]);
                return true;
            }
            return false;
        } else {
            throw new \Exception('Failed to parse XML file');
        }
    }

    public function xmlToJson(string $filename): array
    {
        $xmlContent = FileFacade::get(public_path('uploads/' . $filename));
        $xmlObject = simplexml_load_string($xmlContent);
        $xmlArray = json_decode(json_encode($xmlObject), true);
        return $xmlArray;
    }
}
