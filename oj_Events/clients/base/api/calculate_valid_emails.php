<?php
ini_set("display_errors",1);
require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');

class calculate_valid_emails extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API calls for Stay in touch process
     * @return type
     */
    public function registerApiRest() {
        return array(
            'calculate_Valid_Emails' => array(
                'reqType' => 'POST',
                'path' => array('oj_Events', 'calculate_valid_email'),
                'pathVars' => array('', ''),
                'method' => 'calculate_valid_email',
                'shortHelp' => 'Send Invitations',
                'longHelp' => '',
            ),
        );
    }

    public function calculate_valid_email($api, $args) {
        global $db;
        $eventid="'".$args['eventid']."'";
        $event_status_id=explode(",",$args['event_status_id']);
        $data=[];
        foreach($event_status_id as $id) {
            $data[]="'".$id."'";
        }
        $query="select email.email_address as email_address  FROM oj_events_cstm AS events 
                    INNER JOIN oj_events_oj_attendance_1_c AS rel 
                    ON
                    events.id_c=rel.oj_events_oj_attendance_1oj_events_ida
                    INNER JOIN oj_attendance AS at 
                    ON
                    at.id=rel.oj_events_oj_attendance_1oj_attendance_idb
                    INNER JOIN oj_attendance_cstm AS at_cstm
                    ON
                    at.id=at_cstm.id_c
                    INNER JOIN  contacts_oj_attendance_1_c as conrel
                    ON
                    conrel.	contacts_oj_attendance_1oj_attendance_idb=at.id
                    INNER JOIN contacts as con
                    ON
                    conrel.contacts_oj_attendance_1contacts_ida=con.id 
                    INNER JOIN contacts_cstm AS con_cstm
                    ON
                    con.id=con_cstm.id_c
                    INNER JOIN email_addr_bean_rel as rela
                    ON
                    con.id=rela.bean_id
                    INNER JOIN email_addresses as email
                    ON
                    rela.email_address_id=email.id
                    where rel.deleted='0' and conrel.deleted='0'and con.deleted='0' and email.deleted='0' and rela.deleted='0' and events.id_c=$eventid and at.id in (".implode(',',$data).")";
  //$GLOBALS['log']->fatal("$query"); and email.opt_out!='1' and email.invalid_email!='1' 
       $GLOBALS['log']->fatal("$query");
        $results = $db->query($query);
        //$Emails=[];
        
        $total=$results->num_rows;
        $GLOBALS['log']->fatal("asdsa",$total);
        
        
        
        $query1="select email.email_address as email_address  FROM oj_events_cstm AS events 
                    INNER JOIN oj_events_oj_attendance_1_c AS rel 
                    ON
                    events.id_c=rel.oj_events_oj_attendance_1oj_events_ida
                    INNER JOIN oj_attendance AS at 
                    ON
                    at.id=rel.oj_events_oj_attendance_1oj_attendance_idb
                    INNER JOIN oj_attendance_cstm AS at_cstm
                    ON
                    at.id=at_cstm.id_c
                    INNER JOIN  contacts_oj_attendance_1_c as conrel
                    ON
                    conrel.	contacts_oj_attendance_1oj_attendance_idb=at.id
                    INNER JOIN contacts as con
                    ON
                    conrel.contacts_oj_attendance_1contacts_ida=con.id 
                    INNER JOIN contacts_cstm AS con_cstm
                    ON
                    con.id=con_cstm.id_c
                    INNER JOIN email_addr_bean_rel as rela
                    ON
                    con.id=rela.bean_id
                    INNER JOIN email_addresses as email
                    ON
                    rela.email_address_id=email.id
                    where rel.deleted='0' and conrel.deleted='0'and con.deleted='0' and email.deleted='0' and rela.deleted='0'AND (email.opt_out = '1'
        OR at_cstm.event_status_c = 'NI') and events.id_c=$eventid and at.id in (".implode(',',$data).")";
  
        
        $results = $db->query($query1);
        $Optedout=$results->num_rows;
        
        
        $query2="select email.email_address as email_address  FROM oj_events_cstm AS events 
                    INNER JOIN oj_events_oj_attendance_1_c AS rel 
                    ON
                    events.id_c=rel.oj_events_oj_attendance_1oj_events_ida
                    INNER JOIN oj_attendance AS at 
                    ON
                    at.id=rel.oj_events_oj_attendance_1oj_attendance_idb
                    INNER JOIN oj_attendance_cstm AS at_cstm
                    ON
                    at.id=at_cstm.id_c
                    INNER JOIN  contacts_oj_attendance_1_c as conrel
                    ON
                    conrel.	contacts_oj_attendance_1oj_attendance_idb=at.id
                    INNER JOIN contacts as con
                    ON
                    conrel.contacts_oj_attendance_1contacts_ida=con.id 
                    INNER JOIN contacts_cstm AS con_cstm
                    ON
                    con.id=con_cstm.id_c
                    INNER JOIN email_addr_bean_rel as rela
                    ON
                    con.id=rela.bean_id
                    INNER JOIN email_addresses as email
                    ON
                    rela.email_address_id=email.id
                    where rel.deleted='0' and conrel.deleted='0'and con.deleted='0' and email.deleted='0' and rela.deleted='0'and email.invalid_email='1' and events.id_c=$eventid and at.id in (".implode(',',$data).")";
  
        
        $results = $db->query($query2);
        $invalid=$results->num_rows;
        
        
       $data['invalid']=$invalid;
       $data['total']=$total;
       $data['Optedout']=$Optedout;
return $data;
        
        
    }
}
