<?php

//ini_set("display_errors", 1);
require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');

class Fetch_ContactsApi extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API calls for Stay in touch process
     * @return type
     */
    public function registerApiRest() {
        return array(
            'fetch_Contacts' => array(
                'reqType' => 'POST',
                'path' => array('oj_Events', 'fetchContacts'),
                'pathVars' => array('', ''),
                'method' => 'fetchContacts',
                'shortHelp' => 'Send Invitations',
                'longHelp' => '',
            ),
        );
    }

    public function fetchContacts($api, $args) {
        $i = 0;
        global $db, $sugar_config;
        $events = array();
        $events_ids = explode(',', $args['events']);
        foreach ($events_ids as $event) {
            array_push($events, "'" . $event . "'");
        }
        $events = implode(',', $events);
        $event_id = $args['event_id'];
//      die(print_r($events));
        $query = "SELECT 
    IFNULL(con_cstm.pa_email_c, '') AS pa,
    is_pa_c AS email_flag,
    add_admin_in_cc,
    at.id AS event_status,
    con.id AS id,
    CONCAT(con.first_name, ' ', con.last_name) AS name,
    email.email_address AS email
FROM
    oj_events_cstm AS events
        INNER JOIN
    oj_events_oj_attendance_1_c AS rel ON events.id_c = rel.oj_events_oj_attendance_1oj_events_ida
        INNER JOIN
    oj_attendance AS at ON at.id = rel.oj_events_oj_attendance_1oj_attendance_idb
        LEFT JOIN
    oj_attendance_cstm AS at_cstm ON at.id = at_cstm.id_c
        INNER JOIN
    contacts_oj_attendance_1_c AS conrel ON conrel.contacts_oj_attendance_1oj_attendance_idb = at.id
        INNER JOIN
    contacts AS con ON conrel.contacts_oj_attendance_1contacts_ida = con.id
        INNER JOIN
    contacts_cstm AS con_cstm ON con_cstm.id_c = con.id
        INNER JOIN
    email_addr_bean_rel AS rela ON con.id = rela.bean_id
        INNER JOIN
    email_addresses AS email ON rela.email_address_id = email.id
WHERE
    rel.deleted = '0' AND rela.deleted = '0'
        AND conrel.deleted = '0'
        AND con.deleted = '0'
        AND email.deleted = '0'
        AND email.opt_out != '1'
        AND at_cstm.event_status_c != 'NI'
        AND email.invalid_email != '1'
        AND rela.deleted = '0'
        AND events.id_c = '$event_id'
        AND at.id IN ($events)";
        
        $result = $db->query($query);
        $data = [];
        $status = 'decline';
        require_once('modules/EmailTemplates/EmailTemplate.php');
        $templateObj = new EmailTemplate();
        $templateObj->retrieve($args['templateID']);
        $urlStripePayment = trim($sugar_config['site_url'], '/') . '/index.php?entryPoint=terms_conditions&eventStatusId={{{EVENT_STATUS}}}'.'&randid='.time();
        $declinePayment = trim($sugar_config['site_url'], '/') . '/index.php?entryPoint=paymentResponse&status='.$status.'&event_status_id={{{EVENT_STATUS}}}'.'&randid='.time();
        $optedout = trim($sugar_config['site_url'], '/') . '/index.php?entryPoint=paymentResponse&status=optedout&event_status_id={{{EVENT_STATUS}}}'.'&randid='.time();
        $button_html = '<div style="text-align:center; margin:auto;">
                    <table border="0" width="270px" cellspacing="2" cellpadding="2" style="margin:auto;"><tbody><tr><td> <a href="' . $urlStripePayment . '" style= "text-decoration: none; color: #fff; outline: none;" target="_blank"><table width="130px" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td style="padding: 10px 18px 10px 18px; border-radius: 3px;" align="center" bgcolor="#937d45"><a href="' . $urlStripePayment . '" target="_blank" style="font-size: 14px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color:#fff; text-decoration: none; display: inline-block;"><strong>Accept</strong></a></td></tr></tbody></table></a></td>
<td ><a href="' . $declinePayment . '" target="_blank"> <table width="130px" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td style="outline: none; padding: 10px 18px 10px 18px; border-radius: 3px;" align="center" bgcolor="#da0101"><a href="' . $declinePayment . '" _blank" style="font-size: 14px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color:#fff; text-decoration: none; display: inline-block;"><strong>Decline</strong></a></td></tr></tbody></table></a></td></tr></tbody></table> 
                    </div>';
        $templateObj->body_html = str_replace("\$button", $button_html, $templateObj->body_html);
        $templateObj->body_html = str_replace("\$unsubscribe", '<a href="' . $optedout . '">click here</a>', $templateObj->body_html);

        while ($selectQueryDa = $GLOBALS["db"]->fetchByAssoc($result)) {
            $data[$i]['id'] = $selectQueryDa['id'];
            $data[$i]['name'] = $selectQueryDa['name'];
            $data[$i]['email'] = $selectQueryDa['email'];
            $data[$i]['event_status'] = $selectQueryDa['event_status'];
            $data[$i]['pa']=$selectQueryDa['pa'];
            $data[$i]['email_flag']= $selectQueryDa['email_flag'];
            $data[$i]['add_admin_in_cc']= $selectQueryDa['add_admin_in_cc'];
            $i++;
        }
        $data['body_html'] = $templateObj->body_html;
        $data['subject'] = $templateObj->subject;
        return $data;
    }

}

