<?php

require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');
require_once 'custom/include/Helpers/SendEventInvitations.php';

class SendEventInvitationsApi extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API calls for Stay in touch process
     * @return type
     */
    public function registerApiRest() {
        return array(
            'sendEventInvitations' => array(
                'reqType' => 'POST',
                'path' => array('oj_Events', 'send_event_invitations'),
                'pathVars' => array('', ''),
                'method' => 'sendEventInvitations',
                'shortHelp' => 'Send Event Invitations',
                'longHelp' => '',
            ),
            'getQueueCount' => array(
                'reqType' => 'POST',
                'path' => array('oj_Events', 'get_queue_count'),
                'pathVars' => array('', ''),
                'method' => 'getQueueCount',
                'shortHelp' => 'Get the Count of the Queued Emails',
                'longHelp' => '',
            ),
        );
    }

    /**
     * Queue the job for sending Event Invitations
     *
     * @param $api ServiceBase The API class of the request
     * @param $args array The arguments array passed in from the API
     * @return array
     */
    public function sendEventInvitations($api, $args) {
        $this->requireArgs($args, array('record_id'));
        if (!empty($args['record_id'])) {
            $event_bean = BeanFactory::getBean("oj_Events", $args['record_id']);

            if ($event_bean->event_invitation_status_c == "1") {
                return array('success' => false, 'level' => 'info', 'message' => 'Event Invitations are already sent.');
            } else if ($event_bean->event_invitation_template_c == "") {
                return array('success' => false, 'level' => 'info', 'message' => 'Please select any template for Event Invitation.');
            } else {

                $sendEventInvitations = new clsSendEventInvitations();
                $scheduleId = $sendEventInvitations->queueJobForEmailSending($event_bean);
                
                if ($scheduleId) {
                    //update crm record
                    $selectQuery = "select id_c from oj_events_cstm where id_c = '{$args['record_id']}'";
                    $selectQueryResult = $GLOBALS['db']->query($selectQuery, true);
                    $selectQueryRow = $GLOBALS['db']->fetchByAssoc($selectQueryResult);

                    if (!empty($selectQueryRow['id_c'])) {
                        $query = "UPDATE oj_events_cstm SET event_invitation_status_c = 1 WHERE id_c = '{$args['record_id']}'";
                    } else {
                        $query = "INSERT INTO oj_events_cstm(id_c,event_invitation_status_c) VALUES ('{$args['record_id']}','1')";
                    }
                    $GLOBALS['db']->query($query);
                }
            }

            return array('success' => true, 'level' => 'success', 'message' => 'Event invitation process is started.');
        }
        return array('success' => false, 'level' => 'error', 'message' => "Couldn't Record.");
    }
    
    public function getQueueCount($api, $args) {
        
        $this->requireArgs($args, array('record_id'));
        if (!empty($args['record_id'])) {
            $event_bean = BeanFactory::getBean("oj_Events", $args['record_id']);
            if ($event_bean->event_invitation_status_c == "1") {
                return array('success' => false, 'level' => 'info', 'message' => 'Event Invitations are already sent.');
            } else if ($event_bean->event_invitation_template_c == "") {
                return array('success' => false, 'level' => 'info', 'message' => 'Please select any template for Event Invitation.');
            } else {

                $sendEventInvitations = new clsSendEventInvitations();
                $num_rows = $sendEventInvitations->getNumberOfRows($event_bean);
                if($num_rows > 0)
                {
                    return array('success' => true, 'level' => 'success', 'message' => 'The total number of emails queued will be '.$num_rows, "numrows" => $num_rows);
                }
                else
                {
                    return array('success' => false, 'level' => 'success', 'message' => 'No emails will be queued.', "numrows" => $num_rows);
                }
            }

            return array('success' => true, 'level' => 'success', 'message' => 'Event invitation process is started.');
        }
        return array('success' => false, 'level' => 'error', 'message' => "Couldn't Record.");
    }

}
