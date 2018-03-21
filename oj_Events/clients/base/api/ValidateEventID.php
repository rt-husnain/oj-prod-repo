<?php

require_once('custom/include/Helpers/CrowdCompass/CrowdCompassIntegration.php');

class ValidateEventID extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API calls for Stay in touch process
     * @return type
     */
    public function registerApiRest() {
        return array(
            'validateEventID' => array(
                'reqType' => 'GET',
                'path' => array('oj_Events', 'validateEventID'),
                'pathVars' => array('', ''),
                'method' => 'validateEventID',
                'shortHelp' => '',
                'longHelp' => '',
            ),
        );
    }

    /**
     * validate the event ID from crowdCompass
     *
     * @param $api ServiceBase The API class of the request
     * @param $args array The arguments array passed in from the API
     * @return array
     */
    public function validateEventID($api, $args) {
        return CrowdCompassIntegration::pingEvent($args['event_id']);
    }

}
