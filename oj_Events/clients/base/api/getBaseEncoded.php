<?php 
ini_set("display_errors",1);
require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');

class getBaseEncoded extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API calls for Stay in touch process
     * @return type
     */
    public function registerApiRest() {
        return array(
            'getbaseencoded' => array(
                'reqType' => 'GET',
                'path' => array('oj_Events', 'getbaseencodedform'),
                'pathVars' => array('', ''),
                'method' => 'getbaseencodedform',
                'shortHelp' => 'Get base encoded',
                'longHelp' => '',
            ),
        );
    }

    public function getbaseencodedform($api, $args) {
       
      
      return base64_encode($args['eve_status']);
      
    }

}
