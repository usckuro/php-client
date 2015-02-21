<?php
	require_once 'vendor/autoload.php';
	require_once 'MyIQ.php';
        try {
            //
            //Create new Interqualitas API Resource
            //
            $iq = new MyIQ('lmros','testpass');

            //
            //Policyholder Fetch functions
            //
            
            //Queries
            $policyHolderApi = new \Interqualitas\PolicyHolder($iq);
            $result = $policyHolderApi->fetch(null, [
                'query'=>'aaron']);
            
            
            //Retrieve a specific policy holder
            $specificId = $result->body->_embedded->policy_holder[0]->id;
            $result = $policyHolderApi->fetch($specificId);
            
            
            //
            //Create a policy holder
            //
            
            //Insufficient Data
            $data = [
                'nameOne'=>'Steve'
            ];
            $result = $policyHolderApi->create($data);
            if($result->code != 201 && isset($result->body->validation_messages)) {
                foreach($result->body->validation_messages as $property=>$messages){
                    echo 'Property: ' . $property . ', Messages:' . PHP_EOL;
                    foreach($messages as $message) {
                        echo "\t" . $message . PHP_EOL;
                    }
                }
            }
            
            //Sufficient Data
            $data = [
                'nameOne'       => 'Steve',
                'nameThree'     => 'Young',
                'dateOfBirth'   => '1969-05-30',
                'type'          => 'Individual',
                'addressOne'    => '123 49er Way',
                'textCity'      => 'San Francisco',
                'state'         => 'CA',
                'postalCode'    => '92132'
            ];
            
            $result = $policyHolderApi->create($data);
            $specificId = null;
            if($result->code == 201) {
                $specificId = $result->body->id;
            }
            
            
            //
            //Vehicle
            //
            
            //Valuation
            $vehicleData = [
                'plateOrigin'   => 'USA',
                'VIN'           => '1HGCT1B35EA008017'
            ];
            $vehicleValuationApi = new \Interqualitas\VehicleValuation($iq);
            $result = $vehicleValuationApi->fetch(null, $vehicleData);

            //Creating Vehicle
            if($result->code == 200) {
                $valuation = $result->body->_embedded->vehicle_valuation[0];
                $valueID = $valuation->ID;
                unset($valuation->ID);
                $valuation->versionDisplay = $valuation->display;

                $vehicleData = array_merge($vehicleData, json_decode(json_encode($valuation), true));
                $vehicleData['version'] = $valueID;
                $vehicleData['policyHolder'] = $specificId;
                $vehicleData['plate'] = 'FDS432';
                $vehicleData['state'] = 'CA';
                $vehicleData['usage'] = 1;
                $vehicleApi = new \Interqualitas\Vehicle($iq);
                $rest = $vehicleApi->create($vehicleData);
                $vehicleId = $rest->body->id;
            }

            $policyApi = new \Interqualitas\Policy($iq);
            $date = new DateTime();
            $policyData = [
                'effectiveDate'     => $date->format('Y-m-d\TH:i:sP'),
                'expirationDate'    => $date->add(new DateInterval('P1Y'))->format('Y-m-d\TH:i:sP');
                'type'              => 1,
                'policyHolder'      => $specificId,
                'vehicle'           => $vehicleId,
                'package'           => 3,
                'status'            => 2
            ];
            $policyApi->create($data)


            $policyHolderApi->delete($specificId);
            $vehicleApi->delete($vehicleId);
        }
        catch (\Interqualitas\Exception\AuthenticationFailed $exc) {
            echo $exc->getMessage();
            echo $exc->getTraceAsString();
        }
