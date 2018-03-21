<?php 
ini_set("display_errors",1);
require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');
require_once('custom/include/Helpers/CrowdCompass/CrowdCompassSugarHelper.php');

class Send_1_by_1 extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API calls for Stay in touch process
     * @return type
     */
    public function registerApiRest() {
        return array(
            'send_1_by_1' => array(
                'reqType' => 'GET',
                'path' => array('oj_Events', 'send_1_by_1_email'),
                'pathVars' => array('', ''),
                'method' => 'send_1_by_1_email',
                'shortHelp' => 'Send email one by one',
                'longHelp' => '',
            ),
        );
    }


    /**
     * Update the email count sent to the invitee and its status accordingly
     * @param $api
     * @param $args
     */
    public function send_1_by_1_email($api, $args) {
       global $db;
      $event_invitation_count=0;  
      $ev_stat_id="'".$args['eve_stat_ids']."'";
        if (!empty($ev_stat_id)) {
            $query = "SELECT count_email_sent_c FROM oj_attendance_cstm where id_c=$ev_stat_id";
            $result = $db->query($query);
            $event_invitation_count = $db->fetchByAssoc($result);
            $event_invitation_count = $event_invitation_count['count_email_sent_c'];
            $event_invitation_count++;
            $query_update = "UPDATE oj_attendance_cstm SET count_email_sent_c=$event_invitation_count WHERE id_c= $ev_stat_id";
            $db->query($query_update);
            $current_status = CrowdCompassSugarHelper::getCurrentStatus($args['eve_stat_ids']);
            if ($current_status == 'AI') {
                $Status = "'I'";
                $query_count = "UPDATE oj_attendance_cstm SET event_status_c =$Status WHERE id_c=$ev_stat_id";
                $db->query($query_count);
            }
        }
    }
}
