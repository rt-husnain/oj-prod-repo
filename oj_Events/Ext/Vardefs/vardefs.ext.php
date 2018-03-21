<?php
// WARNING: The contents of this file are auto-generated.
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/oj_events_oj_attendance_1_oj_Events.php

// created: 2015-07-02 09:27:41
$dictionary["oj_Events"]["fields"]["oj_events_oj_attendance_1"] = array (
  'name' => 'oj_events_oj_attendance_1',
  'type' => 'link',
  'relationship' => 'oj_events_oj_attendance_1',
  'source' => 'non-db',
  'module' => 'oj_attendance',
  'bean_name' => 'oj_attendance',
  'vname' => 'LBL_OJ_EVENTS_OJ_ATTENDANCE_1_FROM_OJ_EVENTS_TITLE',
  'id_name' => 'oj_events_oj_attendance_1oj_events_ida',
  'link-type' => 'many',
  'side' => 'left',
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/contacts_oj_events_1_oj_Events.php


$dictionary["oj_Events"]["fields"]["contacts_oj_events_1"] = array (
  'name' => 'contacts_oj_events_1',
  'type' => 'link',
  'relationship' => 'contacts_oj_events_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'side' => 'right',
  'vname' => 'LBL_CONTACTS_OJ_EVENTS_1_FROM_OJ_EVENTS_TITLE',
  'id_name' => 'contacts_oj_events_1contacts_ida',
  'link-type' => 'one',
);
$dictionary["oj_Events"]["fields"]["contacts_oj_events_1_name"] = array (
  'name' => 'contacts_oj_events_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_OJ_EVENTS_1_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'contacts_oj_events_1contacts_ida',
  'link' => 'contacts_oj_events_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'full_name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["oj_Events"]["fields"]["contacts_oj_events_1contacts_ida"] = array (
  'name' => 'contacts_oj_events_1contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_OJ_EVENTS_1_FROM_OJ_EVENTS_TITLE_ID',
  'id_name' => 'contacts_oj_events_1contacts_ida',
  'link' => 'contacts_oj_events_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/oj_events_opportunities_1_oj_Events.php

// created: 2015-09-17 09:02:52
$dictionary["oj_Events"]["fields"]["oj_events_opportunities_1"] = array (
  'name' => 'oj_events_opportunities_1',
  'type' => 'link',
  'relationship' => 'oj_events_opportunities_1',
  'source' => 'non-db',
  'module' => 'Opportunities',
  'bean_name' => 'Opportunity',
  'vname' => 'LBL_OJ_EVENTS_OPPORTUNITIES_1_FROM_OJ_EVENTS_TITLE',
  'id_name' => 'oj_events_opportunities_1oj_events_ida',
  'link-type' => 'many',
  'side' => 'left',
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/oj_events_documents_1_oj_Events.php

// created: 2015-11-13 16:51:07
$dictionary["oj_Events"]["fields"]["oj_events_documents_1"] = array (
  'name' => 'oj_events_documents_1',
  'type' => 'link',
  'relationship' => 'oj_events_documents_1',
  'source' => 'non-db',
  'module' => 'Documents',
  'bean_name' => 'Document',
  'vname' => 'LBL_OJ_EVENTS_DOCUMENTS_1_FROM_DOCUMENTS_TITLE',
  'id_name' => 'oj_events_documents_1documents_idb',
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/accounts_oj_events_1_oj_Events.php

// created: 2015-09-09 12:33:42
$dictionary["oj_Events"]["fields"]["accounts_oj_events_1"] = array (
  'name' => 'accounts_oj_events_1',
  'type' => 'link',
  'relationship' => 'accounts_oj_events_1',
  'source' => 'non-db',
  'module' => 'Accounts',
  'bean_name' => 'Account',
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_OJ_EVENTS_1_FROM_OJ_EVENTS_TITLE',
  'id_name' => 'accounts_oj_events_1accounts_ida',
  'link-type' => 'one',
);
$dictionary["oj_Events"]["fields"]["accounts_oj_events_1_name"] = array (
  'name' => 'accounts_oj_events_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_OJ_EVENTS_1_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'accounts_oj_events_1accounts_ida',
  'link' => 'accounts_oj_events_1',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["oj_Events"]["fields"]["accounts_oj_events_1accounts_ida"] = array (
  'name' => 'accounts_oj_events_1accounts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_OJ_EVENTS_1_FROM_OJ_EVENTS_TITLE_ID',
  'id_name' => 'accounts_oj_events_1accounts_ida',
  'link' => 'accounts_oj_events_1',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_status2.php

 // created: 2015-07-27 14:17:23
$dictionary['oj_Events']['fields']['status']['default']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_currency_id.php

 // created: 2016-04-19 17:01:59

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_base_rate.php

 // created: 2016-04-19 17:01:59

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/full_text_search_admin.php

 // created: 2016-09-16 18:29:20
$dictionary['oj_Events']['full_text_search']=false;

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_eventstart_c.php

 // created: 2016-09-16 18:29:24
$dictionary['oj_Events']['fields']['eventstart_c']['labelValue'] = 'eventstart';
$dictionary['oj_Events']['fields']['eventstart_c']['formula'] = '$event_start';
$dictionary['oj_Events']['fields']['eventstart_c']['enforced'] = 'false';
$dictionary['oj_Events']['fields']['eventstart_c']['dependency'] = '';
$dictionary['oj_Events']['fields']['eventstart_c']['full_text_search']['boost'] = 1;


?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_event_hashtag_c.php

 // created: 2016-09-16 18:29:24
$dictionary['oj_Events']['fields']['event_hashtag_c']['labelValue'] = 'Event Hashtag';
$dictionary['oj_Events']['fields']['event_hashtag_c']['full_text_search']['enabled'] = true;
$dictionary['oj_Events']['fields']['event_hashtag_c']['full_text_search']['searchable'] = false;
$dictionary['oj_Events']['fields']['event_hashtag_c']['full_text_search']['boost'] = 1;
$dictionary['oj_Events']['fields']['event_hashtag_c']['enforced'] = '';
$dictionary['oj_Events']['fields']['event_hashtag_c']['dependency'] = '';


?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_event_type_c.php

 // created: 2016-09-16 18:29:24
$dictionary['oj_Events']['fields']['event_type_c']['labelValue'] = 'Legacy Sheet Column Value';
$dictionary['oj_Events']['fields']['event_type_c']['dependency'] = '';
$dictionary['oj_Events']['fields']['event_type_c']['visibility_grid'] = '';
$dictionary['oj_Events']['fields']['event_type_c']['full_text_search']['boost'] = 1;


?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_event_invite_url_c.php

 // created: 2016-09-16 18:29:24
$dictionary['oj_Events']['fields']['event_invite_url_c']['labelValue'] = 'Event Invite URL';
$dictionary['oj_Events']['fields']['event_invite_url_c']['full_text_search']['enabled'] = true;
$dictionary['oj_Events']['fields']['event_invite_url_c']['full_text_search']['searchable'] = false;
$dictionary['oj_Events']['fields']['event_invite_url_c']['full_text_search']['boost'] = 1;
$dictionary['oj_Events']['fields']['event_invite_url_c']['enforced'] = '';
$dictionary['oj_Events']['fields']['event_invite_url_c']['dependency'] = '';


?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_event_start.php

 // created: 2016-09-16 18:29:24
$dictionary['oj_Events']['fields']['event_start']['options'] = 'date_range_search_dom';
$dictionary['oj_Events']['fields']['event_start']['enable_range_search'] = '1';
$dictionary['oj_Events']['fields']['event_start']['full_text_search']['boost'] = 1;


?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_concession_c.php

 // created: 2016-09-16 18:29:24
$dictionary['oj_Events']['fields']['concession_c']['labelValue'] = 'Concession';
$dictionary['oj_Events']['fields']['concession_c']['enforced'] = '';
$dictionary['oj_Events']['fields']['concession_c']['dependency'] = '';
$dictionary['oj_Events']['fields']['concession_c']['related_fields'][0] = 'currency_id';
$dictionary['oj_Events']['fields']['concession_c']['related_fields'][1] = 'base_rate';


?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_status.php

 // created: 2016-09-16 18:29:24
$dictionary['oj_Events']['fields']['status']['default'] = 'planning';
$dictionary['oj_Events']['fields']['status']['full_text_search']['boost'] = 1;


?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_shortcode_c.php

 // created: 2016-09-16 18:29:24
$dictionary['oj_Events']['fields']['shortcode_c']['labelValue'] = 'Event Short Code';
$dictionary['oj_Events']['fields']['shortcode_c']['full_text_search']['enabled'] = true;
$dictionary['oj_Events']['fields']['shortcode_c']['full_text_search']['searchable'] = true;
$dictionary['oj_Events']['fields']['shortcode_c']['full_text_search']['boost'] = 1;
$dictionary['oj_Events']['fields']['shortcode_c']['enforced'] = '';
$dictionary['oj_Events']['fields']['shortcode_c']['dependency'] = '';


?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_imported_from_c.php

 // created: 2016-09-16 18:29:24
$dictionary['oj_Events']['fields']['imported_from_c']['labelValue'] = 'Imported From';
$dictionary['oj_Events']['fields']['imported_from_c']['full_text_search']['enabled'] = true;
$dictionary['oj_Events']['fields']['imported_from_c']['full_text_search']['searchable'] = false;
$dictionary['oj_Events']['fields']['imported_from_c']['full_text_search']['boost'] = 1;
$dictionary['oj_Events']['fields']['imported_from_c']['enforced'] = '';
$dictionary['oj_Events']['fields']['imported_from_c']['dependency'] = '';


?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_event_charging_c.php

 // created: 2016-09-16 18:29:24
$dictionary['oj_Events']['fields']['event_charging_c']['labelValue'] = 'Enable Event Charging';
$dictionary['oj_Events']['fields']['event_charging_c']['dependency'] = '';


?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_accom_rate_c.php

 // created: 2016-09-26 15:55:48
$dictionary['oj_Events']['fields']['accom_rate_c']['labelValue']='Accommodation Rate';
$dictionary['oj_Events']['fields']['accom_rate_c']['enforced']='';
$dictionary['oj_Events']['fields']['accom_rate_c']['dependency']='';
$dictionary['oj_Events']['fields']['accom_rate_c']['related_fields']=array (
  0 => 'currency_id',
  1 => 'base_rate',
);

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/att_schedule_fields.php


// These fields are for the attendee schedule layout
$dictionary['oj_Events']['fields']['attendee_master_sessions_name'] = array(
    'name' => 'attendee_master_sessions_name',
    'type' => 'enum',
    'options' => 'attendee_schedule_sessions_list',
    'source' => 'non-db',
    'vname' => 'LBL_MASTER_SESSIONS_NAME',
    'required' => true,
);

$dictionary['oj_Events']['fields']['attendee_slave_sessions_name'] = array(
    'name' => 'attendee_slave_sessions_name',
    'type' => 'enum',
    'options' => 'attendee_schedule_sessions_list',
    'source' => 'non-db',
    'vname' => 'LBL_SLAVE_SESSIONS_NAME',
    'separator' => '<br>',   
);

$dictionary['oj_Events']['fields']['attendee_group_name'] = array(
    'name' => 'attendee_group_name',
    'type' => 'enum',
    'options' => 'attendee_schedule_group_list',
    'source' => 'non-db',
    'vname' => 'LBL_GROUP_NAME',
    'required' => true,
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_is_sessions_created_c.php

 // created: 2016-08-23 17:02:48
$dictionary['oj_Events']['fields']['is_sessions_created_c']['labelValue']='is sessions created';
$dictionary['oj_Events']['fields']['is_sessions_created_c']['enforced']='';
$dictionary['oj_Events']['fields']['is_sessions_created_c']['dependency']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/oj_sessions_oj_events_oj_Events.php

// created: 2016-08-18 16:47:20
$dictionary["oj_Events"]["fields"]["oj_sessions_oj_events"] = array (
  'name' => 'oj_sessions_oj_events',
  'type' => 'link',
  'relationship' => 'oj_sessions_oj_events',
  'source' => 'non-db',
  'module' => 'oj_Sessions',
  'bean_name' => 'oj_Sessions',
  'vname' => 'LBL_OJ_SESSIONS_OJ_EVENTS_FROM_OJ_EVENTS_TITLE',
  'id_name' => 'oj_sessions_oj_eventsoj_events_ida',
  'link-type' => 'many',
  'side' => 'left',
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_event_id_c.php

 // created: 2016-08-24 12:46:19
$dictionary['oj_Events']['fields']['event_id_c']['labelValue']='Event id';
$dictionary['oj_Events']['fields']['event_id_c']['full_text_search']=array (
  'boost' => '0',
  'enabled' => false,
);
$dictionary['oj_Events']['fields']['event_id_c']['enforced']='';
$dictionary['oj_Events']['fields']['event_id_c']['dependency']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_app_sync_c.php

 // created: 2016-08-18 17:20:45
$dictionary['oj_Events']['fields']['app_sync_c']['labelValue']='App sync';
$dictionary['oj_Events']['fields']['app_sync_c']['enforced']='';
$dictionary['oj_Events']['fields']['app_sync_c']['dependency']='';
$dictionary['oj_Events']['fields']['app_sync_c']['type']='bool';

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/oj2_sessioncategory_oj_events_1_oj_Events.php

// created: 2016-08-30 18:29:19
$dictionary["oj_Events"]["fields"]["oj2_sessioncategory_oj_events_1"] = array (
  'name' => 'oj2_sessioncategory_oj_events_1',
  'type' => 'link',
  'relationship' => 'oj2_sessioncategory_oj_events_1',
  'source' => 'non-db',
  'module' => 'OJ2_SessionCategory',
  'bean_name' => 'OJ2_SessionCategory',
  'vname' => 'LBL_OJ2_SESSIONCATEGORY_OJ_EVENTS_1_FROM_OJ2_SESSIONCATEGORY_TITLE',
  'id_name' => 'oj2_sessioncategory_oj_events_1oj2_sessioncategory_ida',
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_sign_off_selection_form_c.php

 // created: 2016-09-29 10:49:59
$dictionary['oj_Events']['fields']['sign_off_selection_form_c']['labelValue']='Sign off selections';
$dictionary['oj_Events']['fields']['sign_off_selection_form_c']['enforced']='';
$dictionary['oj_Events']['fields']['sign_off_selection_form_c']['dependency']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_selection_priority_c.php

 // created: 2016-09-08 15:45:03
$dictionary['oj_Events']['fields']['selection_priority_c']['labelValue']='Highest Selection Priority';
$dictionary['oj_Events']['fields']['selection_priority_c']['full_text_search']=array (
  'boost' => '0',
  'enabled' => false,
);
$dictionary['oj_Events']['fields']['selection_priority_c']['enforced']='';
$dictionary['oj_Events']['fields']['selection_priority_c']['dependency']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_selection_form_header_c.php

 // created: 2016-09-15 18:17:09
$dictionary['oj_Events']['fields']['selection_form_header_c']['labelValue']='Selection Form Header';
$dictionary['oj_Events']['fields']['selection_form_header_c']['full_text_search']=array (
  'boost' => '0',
  'enabled' => false,
);
$dictionary['oj_Events']['fields']['selection_form_header_c']['enforced']='';
$dictionary['oj_Events']['fields']['selection_form_header_c']['dependency']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_rt_pdf_banner_c.php

 // created: 2016-10-31 07:43:50
$dictionary['oj_Events']['fields']['rt_pdf_banner_c']['labelValue']='PDF Banner';
$dictionary['oj_Events']['fields']['rt_pdf_banner_c']['dependency']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_anythingtest_c.php

 // created: 2016-10-31 11:28:30
$dictionary['oj_Events']['fields']['anythingtest_c']['labelValue']='PDF Banner';
$dictionary['oj_Events']['fields']['anythingtest_c']['enforced']='';
$dictionary['oj_Events']['fields']['anythingtest_c']['dependency']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_anything2_test_c.php

 // created: 2016-10-31 11:29:20
$dictionary['oj_Events']['fields']['anything2_test_c']['labelValue']='PDF Banner';
$dictionary['oj_Events']['fields']['anything2_test_c']['enforced']='';
$dictionary['oj_Events']['fields']['anything2_test_c']['dependency']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_part_btn_cstm_c.php

 // created: 2016-10-31 11:30:44
$dictionary['oj_Events']['fields']['part_btn_cstm_c']['labelValue']='Generate Participant Report';
$dictionary['oj_Events']['fields']['part_btn_cstm_c']['enforced']='';
$dictionary['oj_Events']['fields']['part_btn_cstm_c']['dependency']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/oj_priortyformdata_oj_events_oj_Events.php


$dictionary['oj_Events']['fields']['oj_priortyformdata_oj_events'] = array(
    'name' => 'oj_priortyformdata_oj_events',
    'type' => 'link',
    'relationship' => 'oj_priortyformdata_oj_events',
    'module' => 'oj_PriortyFormData',
    'source' => 'non-db',
    'vname' => 'LBL_OJ_PRIORTYFORMDATA_OJ_EVENTS_FROM_OJ_EVENTS_TITLE',
);
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_event_full_name_c.php

 // created: 2017-01-09 15:31:02
$dictionary['oj_Events']['fields']['event_full_name_c']['labelValue']='Event Full Name';
$dictionary['oj_Events']['fields']['event_full_name_c']['full_text_search']=array (
  'enabled' => '0',
  'boost' => '1',
  'searchable' => false,
);
$dictionary['oj_Events']['fields']['event_full_name_c']['enforced']='';
$dictionary['oj_Events']['fields']['event_full_name_c']['dependency']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_rsvp_payment_header_c.php

 // created: 2017-03-07 19:34:55
$dictionary['oj_Events']['fields']['rsvp_payment_header_c']['labelValue']='RSVP Payment Header';
$dictionary['oj_Events']['fields']['rsvp_payment_header_c']['full_text_search']=array (
  'enabled' => '0',
  'boost' => '1',
  'searchable' => false,
);
$dictionary['oj_Events']['fields']['rsvp_payment_header_c']['enforced']='';
$dictionary['oj_Events']['fields']['rsvp_payment_header_c']['dependency']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_event_group_c.php

 // created: 2017-03-07 19:35:39
$dictionary['oj_Events']['fields']['event_group_c']['labelValue']='Event Group';
$dictionary['oj_Events']['fields']['event_group_c']['dependency']='';
$dictionary['oj_Events']['fields']['event_group_c']['visibility_grid']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_event_template_c.php


$dictionary['oj_Events']['fields']['event_template_c'] = array(
    'name' => 'event_template_c',
    'vname' => 'LBL_EVENT_TEMPLATE',
    'required' => true,
    'studio' => 'visible',
    'type' => 'enum',
    'len' => '36',
    'function' => 'getTemplates',
    'massupdate' => true,
    'importable' => 'true',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'source' => 'custom_fields',
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_dinner_accommodation_c.php


$dictionary['oj_Events']['fields']['dinner_accommodation_c'] = array(
    'name' => 'dinner_accommodation_c',
    'vname' => 'LBL_DINNER_ACCOMMODATION',
    'required' => false,
    'studio' => 'visible',
    'type' => 'bool',
    'massupdate' => true,
    'importable' => 'true',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'source' => 'custom_fields',
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_hotel_price_information_c.php


$dictionary['oj_Events']['fields']['hotel_price_information_c'] = array(
    'name' => 'hotel_price_information_c',
    'vname' => 'LBL_HOTEL_PRICE_INFORMATION',
    'required' => false,
    'studio' => 'visible',
    'type' => 'text',
    'rows' => '4',
    'cols' => '40',
    'massupdate' => true,
    'importable' => 'true',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'source' => 'custom_fields',
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_eb_valid_c.php

 // created: 2017-05-12 02:15:30
$dictionary['oj_Events']['fields']['eb_valid_c']['labelValue']='Early Bird Valid Until';
$dictionary['oj_Events']['fields']['eb_valid_c']['enforced']='';
$dictionary['oj_Events']['fields']['eb_valid_c']['dependency']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_eb_c.php

 // created: 2017-05-12 02:38:14
$dictionary['oj_Events']['fields']['eb_c']['labelValue']='Early Bird';
$dictionary['oj_Events']['fields']['eb_c']['enforced']='';
$dictionary['oj_Events']['fields']['eb_c']['dependency']='';
$dictionary['oj_Events']['fields']['eb_c']['related_fields']=array (
  0 => 'currency_id',
  1 => 'base_rate',
);

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_eb_minus_concession_c.php

 // created: 2017-05-12 03:02:30
$dictionary['oj_Events']['fields']['eb_minus_concession_c']['duplicate_merge_dom_value']=0;
$dictionary['oj_Events']['fields']['eb_minus_concession_c']['labelValue']='Early Bird minus Concession';
$dictionary['oj_Events']['fields']['eb_minus_concession_c']['calculated']='true';
$dictionary['oj_Events']['fields']['eb_minus_concession_c']['formula']='subtract($eb_c,$concession_c)';
$dictionary['oj_Events']['fields']['eb_minus_concession_c']['enforced']='true';
$dictionary['oj_Events']['fields']['eb_minus_concession_c']['dependency']='';
$dictionary['oj_Events']['fields']['eb_minus_concession_c']['related_fields']=array (
  0 => 'currency_id',
  1 => 'base_rate',
);

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_std_minus_concession_c.php

 // created: 2017-05-12 03:19:23
$dictionary['oj_Events']['fields']['std_minus_concession_c']['duplicate_merge_dom_value']=0;
$dictionary['oj_Events']['fields']['std_minus_concession_c']['labelValue']='Standard minus Concession';
$dictionary['oj_Events']['fields']['std_minus_concession_c']['calculated']='true';
$dictionary['oj_Events']['fields']['std_minus_concession_c']['formula']='subtract($std_c,$concession_c)';
$dictionary['oj_Events']['fields']['std_minus_concession_c']['enforced']='true';
$dictionary['oj_Events']['fields']['std_minus_concession_c']['dependency']='';
$dictionary['oj_Events']['fields']['std_minus_concession_c']['related_fields']=array (
  0 => 'currency_id',
  1 => 'base_rate',
);

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_std_plus_vat_c.php

 // created: 2017-05-12 03:40:14
$dictionary['oj_Events']['fields']['std_plus_vat_c']['duplicate_merge_dom_value']=0;
$dictionary['oj_Events']['fields']['std_plus_vat_c']['labelValue']='Standard Plus VAT';
$dictionary['oj_Events']['fields']['std_plus_vat_c']['calculated']='true';
$dictionary['oj_Events']['fields']['std_plus_vat_c']['formula']='add($std_c,$std_vat_c)';
$dictionary['oj_Events']['fields']['std_plus_vat_c']['enforced']='true';
$dictionary['oj_Events']['fields']['std_plus_vat_c']['dependency']='';
$dictionary['oj_Events']['fields']['std_plus_vat_c']['related_fields']=array (
  0 => 'currency_id',
  1 => 'base_rate',
);

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_eb_plus_vat_c.php

 // created: 2017-05-12 03:42:53
$dictionary['oj_Events']['fields']['eb_plus_vat_c']['duplicate_merge_dom_value']=0;
$dictionary['oj_Events']['fields']['eb_plus_vat_c']['labelValue']='Early Bird plus VAT';
$dictionary['oj_Events']['fields']['eb_plus_vat_c']['calculated']='true';
$dictionary['oj_Events']['fields']['eb_plus_vat_c']['formula']='add($eb_c,$eb_vat_c)';
$dictionary['oj_Events']['fields']['eb_plus_vat_c']['enforced']='true';
$dictionary['oj_Events']['fields']['eb_plus_vat_c']['dependency']='';
$dictionary['oj_Events']['fields']['eb_plus_vat_c']['related_fields']=array (
  0 => 'currency_id',
  1 => 'base_rate',
);

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_eb_minus_con_plus_vat_c.php

 // created: 2017-05-12 03:56:41
$dictionary['oj_Events']['fields']['eb_minus_con_plus_vat_c']['duplicate_merge_dom_value']=0;
$dictionary['oj_Events']['fields']['eb_minus_con_plus_vat_c']['labelValue']='Early Bird minus Concession plus VAT';
$dictionary['oj_Events']['fields']['eb_minus_con_plus_vat_c']['calculated']='true';
$dictionary['oj_Events']['fields']['eb_minus_con_plus_vat_c']['formula']='add($eb_minus_concession_c,$eb_minus_concession_vat_c)';
$dictionary['oj_Events']['fields']['eb_minus_con_plus_vat_c']['enforced']='true';
$dictionary['oj_Events']['fields']['eb_minus_con_plus_vat_c']['dependency']='';
$dictionary['oj_Events']['fields']['eb_minus_con_plus_vat_c']['related_fields']=array (
  0 => 'currency_id',
  1 => 'base_rate',
);

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_eb_vat_c.php

 // created: 2017-05-12 04:11:58
$dictionary['oj_Events']['fields']['eb_vat_c']['duplicate_merge_dom_value']=0;
$dictionary['oj_Events']['fields']['eb_vat_c']['labelValue']='Early Bird VAT';
$dictionary['oj_Events']['fields']['eb_vat_c']['calculated']='1';
$dictionary['oj_Events']['fields']['eb_vat_c']['formula']='divide(multiply($eb_c,$vat_rate_c),100)';
$dictionary['oj_Events']['fields']['eb_vat_c']['enforced']='1';
$dictionary['oj_Events']['fields']['eb_vat_c']['dependency']='';
$dictionary['oj_Events']['fields']['eb_vat_c']['related_fields']=array (
  0 => 'currency_id',
  1 => 'base_rate',
);

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_std_vat_c.php

 // created: 2017-05-12 04:23:57
$dictionary['oj_Events']['fields']['std_vat_c']['duplicate_merge_dom_value']=0;
$dictionary['oj_Events']['fields']['std_vat_c']['labelValue']='Standard VAT';
$dictionary['oj_Events']['fields']['std_vat_c']['calculated']='1';
$dictionary['oj_Events']['fields']['std_vat_c']['formula']='divide(multiply($std_c,$vat_rate_c),100)';
$dictionary['oj_Events']['fields']['std_vat_c']['enforced']='1';
$dictionary['oj_Events']['fields']['std_vat_c']['dependency']='';
$dictionary['oj_Events']['fields']['std_vat_c']['related_fields']=array (
  0 => 'currency_id',
  1 => 'base_rate',
);

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_eb_minus_concession_vat_c.php

 // created: 2017-05-12 04:33:33
$dictionary['oj_Events']['fields']['eb_minus_concession_vat_c']['duplicate_merge_dom_value']=0;
$dictionary['oj_Events']['fields']['eb_minus_concession_vat_c']['labelValue']='Early Bird minus Concession VAT';
$dictionary['oj_Events']['fields']['eb_minus_concession_vat_c']['calculated']='1';
$dictionary['oj_Events']['fields']['eb_minus_concession_vat_c']['formula']='divide(multiply($eb_minus_concession_c,$vat_rate_c),100)';
$dictionary['oj_Events']['fields']['eb_minus_concession_vat_c']['enforced']='1';
$dictionary['oj_Events']['fields']['eb_minus_concession_vat_c']['dependency']='';
$dictionary['oj_Events']['fields']['eb_minus_concession_vat_c']['related_fields']=array (
  0 => 'currency_id',
  1 => 'base_rate',
);

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_vat_rate_c.php

 // created: 2017-05-12 04:35:42
$dictionary['oj_Events']['fields']['vat_rate_c']['labelValue']='VAT Rate %';
$dictionary['oj_Events']['fields']['vat_rate_c']['enforced']='';
$dictionary['oj_Events']['fields']['vat_rate_c']['dependency']='';

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_std_minus_concession_vat_c.php

 // created: 2017-05-12 04:40:02
$dictionary['oj_Events']['fields']['std_minus_concession_vat_c']['duplicate_merge_dom_value']=0;
$dictionary['oj_Events']['fields']['std_minus_concession_vat_c']['labelValue']='Standard minus Concession VAT';
$dictionary['oj_Events']['fields']['std_minus_concession_vat_c']['calculated']='1';
$dictionary['oj_Events']['fields']['std_minus_concession_vat_c']['formula']='divide(multiply($std_minus_concession_c,$vat_rate_c),100)';
$dictionary['oj_Events']['fields']['std_minus_concession_vat_c']['enforced']='1';
$dictionary['oj_Events']['fields']['std_minus_concession_vat_c']['dependency']='';
$dictionary['oj_Events']['fields']['std_minus_concession_vat_c']['related_fields']=array (
  0 => 'currency_id',
  1 => 'base_rate',
);

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_std_minus_con_plus_vat_c.php

 // created: 2017-05-12 05:15:58
$dictionary['oj_Events']['fields']['std_minus_con_plus_vat_c']['duplicate_merge_dom_value']=0;
$dictionary['oj_Events']['fields']['std_minus_con_plus_vat_c']['labelValue']='Standard minus Concession plus VAT';
$dictionary['oj_Events']['fields']['std_minus_con_plus_vat_c']['calculated']='1';
$dictionary['oj_Events']['fields']['std_minus_con_plus_vat_c']['formula']='add($std_minus_concession_c,$std_minus_concession_vat_c)';
$dictionary['oj_Events']['fields']['std_minus_con_plus_vat_c']['enforced']='1';
$dictionary['oj_Events']['fields']['std_minus_con_plus_vat_c']['dependency']='';
$dictionary['oj_Events']['fields']['std_minus_con_plus_vat_c']['related_fields']=array (
  0 => 'currency_id',
  1 => 'base_rate',
);

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_std_c.php

 // created: 2017-05-12 05:17:46
$dictionary['oj_Events']['fields']['std_c']['labelValue']='Standard';
$dictionary['oj_Events']['fields']['std_c']['enforced']='';
$dictionary['oj_Events']['fields']['std_c']['dependency']='';
$dictionary['oj_Events']['fields']['std_c']['related_fields']=array (
  0 => 'currency_id',
  1 => 'base_rate',
);

 
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_event_invitation_template_c.php


$dictionary['oj_Events']['fields']['event_invitation_template_c'] = array(
    'name' => 'event_invitation_template_c',
    'vname' => 'LBL_EVENT_INVITATION_TEMPLATE_C',
    'required' => false,
    'studio' => 'visible',
    'type' => 'enum',
    'len' => '36',
    'default' => '',
    'function' => 'getTemplates',
    'massupdate' => true,
    'importable' => 'true',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'source' => 'custom_fields',
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Vardefs/sugarfield_event_invitation_status_c.php


$dictionary['oj_Events']['fields']['event_invitation_status_c'] = array(
    'name' => 'event_invitation_status_c',
    'vname' => 'LBL_EVENT_INVITATION_STATUS_C',
    'required' => false,
    'studio' => 'visible',
    'type' => 'bool',
    'default' => '0',
    'massupdate' => true,
    'importable' => 'true',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'source' => 'custom_fields',
);

?>
