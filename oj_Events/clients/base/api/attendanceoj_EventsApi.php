<?php

require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');

class attendanceoj_EventsApi extends SugarApi {

	/**
	 * @function registerApiRest
	 * @description registering the API calls for Stay in touch process
	 * @return type
	 */
	public function registerApiRest() {
		return array(
			'sentInvitees' => array(
				'reqType' => 'GET',
				'path' => array('oj_Events', 'send_invitees'),
				'pathVars' => array('', ''),
				'method' => 'sentInvitees',
				'shortHelp' => 'Send INvitations',
				'longHelp' => '',
			),
		);
	}

	/**
	 *add attendance records on the base of passed query
	 *
	 * @param $api ServiceBase The API class of the request
	 * @param $args array The arguments array passed in from the API
	 * @return array
	 */
	public function sentInvitees($api, $args) {
		$data_arr = array();
		$eventID = $args['eventId'];
		$contact_ids = $args['contactIDS'];
		$contacts = explode(',',$contact_ids);
		global $db,$current_user;
		$event_short = "select shortcode_c from oj_events_cstm where id_c=(select id from oj_events where id='".$eventID."' and deleted=0)";
		$ev_shortcode = $db->fetchByAssoc($db->query($event_short));
		$evname = '';
		$shortcod = '';
		if(empty($ev_shortcode['shortcode_c'])){
			$event_name = "select name from oj_events where id='".$eventID."' and deleted=0";
			$ev_nam = $db->fetchByAssoc($db->query($event_name));
			$evname = $ev_nam['name'];
		}else{
			$evname = $ev_shortcode['shortcode_c'];
			$shortcod = $ev_shortcode['shortcode_c'];
		}
		$k = 0;
		for($i=0;$i<count($contacts);$i++){
			$cntc_name = $db->fetchByAssoc($db->query("select first_name,last_name from contacts where id='".$contacts[$i]."' and deleted=0"));
			$participant_type = $db->fetchByAssoc($db->query("select participant_type_c,charge_tier_c from contacts_cstm where id_c='".$contacts[$i]."'"));
			$oj_attendance = new oj_attendance();
			$attendance_query = "select id from oj_attendance where deleted=0 and id IN (select oj_events_oj_attendance_1_c.oj_events_oj_attendance_1oj_attendance_idb from oj_events_oj_attendance_1_c where oj_events_oj_attendance_1_c.oj_events_oj_attendance_1oj_events_ida='".$eventID."' and oj_events_oj_attendance_1_c.deleted=0 and oj_events_oj_attendance_1_c.oj_events_oj_attendance_1oj_attendance_idb IN (select contacts_oj_attendance_1_c.contacts_oj_attendance_1oj_attendance_idb from contacts_oj_attendance_1_c where contacts_oj_attendance_1_c.deleted=0 and contacts_oj_attendance_1_c.contacts_oj_attendance_1contacts_ida='".$contacts[$i]."'))";
			$attendanceid = $db->fetchByAssoc($db->query($attendance_query));
			
			$oj_attendance->retrieve($attendanceid['id']);
			$oj_attendance->name = $evname.'-'.$cntc_name['first_name'].' '.$cntc_name['last_name'];
			$oj_attendance->oj_events_oj_attendance_1oj_events_ida = $eventID;
			$oj_attendance->contacts_oj_attendance_1contacts_ida = $contacts[$i];
			$oj_attendance->event_status_c = 'AI';
			$oj_attendance->participant_type_c = $participant_type['participant_type_c'];
			$oj_attendance->charge_tier_c = $participant_type['charge_tier_c'];
			$oj_attendance->assigned_user_id = $current_user->id;
			$oj_attendance->eventshortcode_c = $shortcod;
			$oj_attendance->team_id = 1;
			$oj_attendance->team_set_id = 1;
			$oj_attendance->save();
			/*code to add email address*/
			$contact = new Contact();
			$contact->retrieve($contacts[$i]);
			$addr = $contact->email1;
			$sql = "select * from email_addresses where email_address='".$addr."' and deleted=0";
			$mailids = $db->fetchByAssoc($db->query($sql));
			if(!empty($mailids['id'])){
				$mail_id = create_guid();
				$email_address_addquery = "INSERT INTO email_addresses(id, email_address, email_address_caps, invalid_email, opt_out, deleted) VALUES ('".$mail_id."','".$mailids['email_address']."','".$mailids['email_address_caps']."',".$mailids['invalid_email'].",".$mailids['opt_out'].",".$mailids['deleted'].")";
				$db->query($email_address_addquery);
				$randid = create_guid();
				$insqury = "INSERT INTO email_addr_bean_rel(id, email_address_id, bean_id, bean_module, primary_address, deleted ) VALUES ('$randid', '".$mail_id."', '".$oj_attendance->id."','oj_attendance',1,0)";
				$db->query($insqury);
			}
			/*code to add email address end*/
		}
		return json_encode($i);
		exit();

	}
}
