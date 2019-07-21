<?php

use CFPropertyList\CFPropertyList;
use munkireport\processors\Processor;

class Munkireport_processor extends Processor
{
    public function run($plist)
    {
        if (! $plist) {
            throw new Exception(
                "Error Processing Request: No property list found", 1
            );
        }

        $parser = new CFPropertyList();
        $parser->parse($plist, CFPropertyList::FORMAT_XML);
        $mylist = $parser->toArray();
        $modelData = [
            'serial_number' => $this->serial_number,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // Translate plist keys to db keys
        $translate = [
            'ManagedInstallVersion' => 'version',
            'ManifestName' => 'manifestname',
            'RunType' => 'runtype',
            'StartTime' => 'starttime',
            'EndTime' => 'endtime',
        ];

        foreach ($translate as $key => $dbkey) {
            if (array_key_exists($key, $mylist)) {
                $modelData[$dbkey] = $mylist[$key];
            }
        }

        // Parse errors and warnings
        $errorsWarnings = ['Errors' => 'error_json', 'Warnings' => 'warning_json'];
        foreach ($errorsWarnings as $key => $json) {
            $dbkey = strtolower($key);
            if (isset($mylist[$key]) && is_array($mylist[$key])) {
                // Store count
                $modelData[$dbkey] = count($mylist[$key]);

                // Store json
                $modelData[$json] = json_encode($mylist[$key]);
            } else {
                // reset
                $modelData[$dbkey] = 0;
                $modelData[$json] = json_encode([]);
            }
        }
        
        $model = Munkireport_model::updateOrCreate(
            ['serial_number' => $this->serial_number], $modelData
        );

        $this->_storeEvents($modelData);

        return $this;
    }
        
    private function _storeEvents($modelData)
    {
        // Store apropriate event:
        if ($modelData['errors'] == 1) {
            $this->store_event(
                'danger',
                'munki.error',
                json_encode(
                    [
                        'error' => truncate_string(
                            json_decode($modelData['error_json'])[0]
                        )
                    ]
                )
            );
        } elseif ($modelData['errors'] > 1) {
            $this->store_event(
                'danger',
                'munki.error',
                json_encode(['count' => $modelData['errors']])
            );
        } elseif ($modelData['warnings'] == 1) {
            $this->store_event(
                'warning',
                'munki.warning',
                json_encode(
                    [
                        'warning' => truncate_string(
                            json_decode($modelData['warning_json'])[0]
                        )
                    ]
                )
            );
        } elseif ($modelData['warnings'] > 1) {
            $this->store_event(
                'warning',
                'munki.warning',
                json_encode(['count' => $modelData['warnings']])
            );
        } else {
            // Delete event
            $this->delete_event();
        }
    }
}
