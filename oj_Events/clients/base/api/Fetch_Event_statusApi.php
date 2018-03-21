<?php 
ini_set("display_errors",1);
require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');

class Fetch_Event_statusApi extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API calls for Stay in touch process
     * @return type
     */
    public function registerApiRest() {
        return array(
            'Fetch_Event_status' => array(
                'reqType' => 'POST',
                'path' => array('oj_Events', 'fetch_event_status'),
                'pathVars' => array('', ''),
                'method' => 'fetch_event_status',
                'shortHelp' => 'Send Invitations',
                'longHelp' => '',
            ),
        );
    }

    public function fetch_event_status($api, $args) {
        $events = explode(',', $args['events']);
        foreach($events as $i => $event) {
           $events[$i] = "'".$event."'";
       }
     //  $GLOBALS['log']->fatal('RRRRRRRRRRRRRRRRRRRRR'.$args['templateID']);
        $event_id = $args['event_id'];
        $job = new SchedulersJob();
        $job->name = "Send Event Status Email Job";
        $data['events'] = $events;
        $data['event_id'] = $event_id;
        $data['template_id']=$args['templateID'];
        // $GLOBALS['log']->fatal("EVENTID",$data['event_id']);
        $data['is_send_all'] = 1;
        $job->data = base64_encode(serialize($data));
        $job->target = "function::sendEventStatusEmailAll";
        $job->assigned_user_id = $GLOBALS['current_user']->id;

        $jq = new SugarJobQueue();
        return $jq->submitJob($job);
    }

}
