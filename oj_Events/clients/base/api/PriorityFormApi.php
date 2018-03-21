<?php

require_once 'include/api/SugarApi.php';
require_once 'include/SugarQuery/SugarQuery.php';
require_once 'include/upload_file.php';
require_once 'include/SugarPHPMailer.php';
require_once 'modules/Emails/Email.php';
require_once 'include/utils.php';

class PriorityFormApi extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API calls for Stay in touch process
     * @return type
     */
    public function registerApiRest() {
        return array(
            'generatePriorityForm' => array(
                'reqType' => 'POST',
                'path' => array('oj_Events', 'generatePriorityForm'),
                'pathVars' => array('', ''),
                'method' => 'generatePriorityForm',
                'shortHelp' => 'Generate the Priority/Selections forms',
                'longHelp' => '',
            ),
            'sendPriorityForm' => array(
                'reqType' => 'POST',
                'path' => array('oj_Events', 'sendPriorityForm'),
                'pathVars' => array('', ''),
                'method' => 'sendPriorityForm',
                'shortHelp' => 'Send the Priority/Selections forms to contacts',
                'longHelp' => '',
            ),
        );
    }

    /**
     * Generate the Priority/Selections Form
     *
     * @param $api ServiceBase The API class of the request
     * @param $args array The arguments array passed in from the API
     * @return array
     */
    public function generatePriorityForm($api, $args) {
        $this->requireArgs($args, array('event_id'));
        if (empty($args['event_id'])) {
            return array('success' => false, 'level' => 'error', 'message' => "Couldn't find the Record.");
        }
        global $timedate;
        $data = array();
        $event_bean = BeanFactory::getBean("oj_Events", $args['event_id']);

        // get related sessions categories sorted by ASC of form_order
        $query = new SugarQuery();
        $query->from(BeanFactory::getBean('oj_Events'), array('team_security' => false));
        $query->joinTable('oj2_sessioncategory_oj_events_1_c', array('alias' => 'secatev', 'joinType' => 'LEFT', 'linkingTable' => true))
                ->on()
                ->equalsField('secatev.oj2_sessioncategory_oj_events_1oj_events_idb', 'oj_events.id')
                ->equals('secatev.deleted', 0);
        $query->joinTable('oj2_sessioncategory', array('alias' => 'secat', 'joinType' => 'INNER', 'linkingTable' => true))
                ->on()
                ->equalsField('secat.id', 'secatev.oj2_sessioncategory_oj_events_1oj2_sessioncategory_ida')
                ->equals('secat.deleted', 0);
        $query->select(array(array('secat.id', 'sessioncategory_id'), array('secat.form_order', 'sessioncategory_form_order'),
            array('secat.session_category_heading', 'sessioncategory_name')));
        $query->where()->equals("oj_events.id", "{$args['event_id']}");
        $query->orderBy('secat.form_order', 'ASC');
        $session_categories = $query->execute();

        if (empty($session_categories)) {
            return array('success' => false, 'level' => 'info', 'message' => "There is no associated Session Categories.");
        }

        $data['event_id'] = $event_bean->id;
        // changed event_name to event_full_name_c
        $data['event_name_with_date'] = $data['event_name'] = htmlspecialchars($event_bean->event_full_name_c);
        if (!empty($event_bean->event_start)) {
            $data['event_name_with_date'] .= ' - ' . date('l j F Y', strtotime($timedate->to_db_date($event_bean->event_start, false)));
        }
        // mulitple banners logic
        $path = '/custom/include/images/SelectionFormImages/';
        if ($event_bean->event_group_c == 'MOMPB' || $event_bean->event_group_c == 'MOMAD' || $event_bean->event_group_c == 'MOMWA' || $event_bean->event_group_c == 'MOMR'){
            $data['banner_path']= $path.'/mom.jpg';
        }
        else if ($event_bean->event_group_c == 'MO'){
            $data['banner_path']=$path.'/mo.jpg';
        }
        else if ($event_bean->event_group_c == 'PE'){
            $data['banner_path']=$path.'pe.jpg';
        }   
        $data['event_address'] = htmlspecialchars($event_bean->venue);
        $data['event_selection_form_header'] = htmlspecialchars($event_bean->selection_form_header_c);
        $data['event_selection_priority_c'] = $event_bean->selection_priority_c;
        $data['categories'] = array();
        foreach ($session_categories as $session_category) {
            // get related sessions sorted by session_order ASC
            $query = new SugarQuery();
            $query->from(BeanFactory::getBean('OJ2_SessionCategory'), array('team_security' => false));
            $query->joinTable('oj2_sessioncategory_oj_sessions_1_c', array('alias' => 'secatse', 'joinType' => 'LEFT', 'linkingTable' => true))
                    ->on()
                    ->equalsField('secatse.oj2_sessioncategory_oj_sessions_1oj2_sessioncategory_ida', 'oj2_sessioncategory.id')
                    ->equals('secatse.deleted', 0);
            $query->joinTable('oj_sessions', array('alias' => 'se', 'joinType' => 'INNER', 'linkingTable' => true))
                    ->on()
                    ->equalsField('se.id', 'secatse.oj2_sessioncategory_oj_sessions_1oj_sessions_idb')
                    ->equals('se.deleted', 0);
            $query->select(array(array('se.id', 'session_id'), array('se.session_order', 'session_session_order'),
                array('se.name', 'session_name'),array('se.title', 'session_title'), array('se.description', 'session_description')));
            $query->where()->equals("oj2_sessioncategory.id", "{$session_category['sessioncategory_id']}");
            $query->where()->equals('se.session_type', '02');
            $query->orderBy('se.session_order', 'ASC');
            $sessions = $query->execute();
            if (!empty($sessions)) {
                $data['categories'][$session_category['sessioncategory_id']] = array();
                $data['categories'][$session_category['sessioncategory_id']]['category_name'] = htmlspecialchars($session_category['sessioncategory_name']);
                $data['categories'][$session_category['sessioncategory_id']]['sessions'] = array();
                foreach ($sessions as $session) {
                    $data['categories'][$session_category['sessioncategory_id']]['sessions'][] = array(
                        'session_name' => htmlspecialchars($session['session_title']),
                        'session_id' => $session['session_id'],
                        'session_description' => htmlspecialchars($session['session_description']),
                        'session_order' => $session['session_session_order']
                    );
                }
            }
        }
        $data['total_sessions'] = empty($sessions) ? 0 : count($sessions);
        if (empty($data['categories'])) {
            return array('success' => false, 'level' => 'info', 'message' => "No Sessions are associated with the Sessions Categories");
        }
        $docInformation = $this->createFormAsDocument($data);
        return array('success' => true, 'level' => 'success', 'message' => "Priority form is created. Please review <a target='_blank' href='#bwc/index.php?module=Documents&action=DetailView&record={$docInformation['documentId']}'>" . $docInformation['documentName'] . "</a>", 'docID' => $docInformation['documentId']);
    }

    /**
     * send the Priority/Selections Form to the Event status records associated with meetings
     *
     * @param $api ServiceBase The API class of the request
     * @param $args array The arguments array passed in from the API
     * @return array
     */
    public function sendPriorityForm($api, $args) {
        $this->requireArgs($args, array('event_id'));
        if (empty($args['event_id'])) {
            return array('success' => false, 'level' => 'error', 'message' => "Couldn't find the Record.");
        }
        $event_status_id = '';        
        if (isset($args['event_status_id']) && !empty($args['event_status_id'])) {
            $event_status_id = $args['event_status_id'];
        }
        global $sugar_config;
        // get the event status + contact and account information
        $query = new SugarQuery();
        $query->from(BeanFactory::getBean('oj_Events'), array('team_security' => false));

        $query->joinTable('oj_events_oj_attendance_1_c', array('alias' => 'ev', 'joinType' => 'LEFT', 'linkingTable' => true))
                ->on()
                ->equalsField('ev.oj_events_oj_attendance_1oj_events_ida', 'oj_events.id')
                ->equals('ev.deleted', 0);
        $query->joinTable('oj_attendance', array('alias' => 'at', 'joinType' => 'LEFT', 'linkingTable' => true))
                ->on()
                ->equalsField('at.id', 'ev.oj_events_oj_attendance_1oj_attendance_idb')
                ->equals('at.deleted', 0);
        $query->joinTable('oj_attendance_cstm', array('alias' => 'atc', 'joinType' => 'LEFT', 'linkingTable' => true))
                ->on()
                ->equalsField('atc.id_c', 'at.id');
        $query->joinTable('contacts_oj_attendance_1_c', array('alias' => 'cta', 'joinType' => 'INNER', 'linkingTable' => true))
                ->on()
                ->equalsField('at.id', 'cta.contacts_oj_attendance_1oj_attendance_idb')->equals('cta.deleted', 0);
        $query->joinTable('contacts', array('alias' => 'ct', 'joinType' => 'INNER', 'linkingTable' => true))
                ->on()
                ->equalsField('ct.id', 'cta.contacts_oj_attendance_1contacts_ida')->equals('ct.deleted', 0);
        $query->joinTable('email_addr_bean_rel', array('alias' => 'eabr', 'joinType' => 'LEFT', 'linkingTable' => true))
                ->on()
                ->equalsField('eabr.bean_id', 'ct.id')->equals('eabr.deleted', 0)
                ->equals('eabr.primary_address', 1);
        $query->joinTable('email_addresses', array('alias' => 'ea', 'joinType' => 'LEFT', 'linkingTable' => true))
                ->on()
                ->equalsField('ea.id', 'eabr.email_address_id')->equals('ea.deleted', 0)
                ->equals('ea.opt_out', 0)
                ->equals('ea.invalid_email', 0);
        $query->joinTable('accounts_contacts', array('alias' => 'acc_cts', 'joinType' => 'LEFT', 'linkingTable' => true))
                ->on()
                ->equalsField('ct.id', 'acc_cts.contact_id')->equals('acc_cts.deleted', 0);
        $query->joinTable('accounts', array('alias' => 'acc', 'joinType' => 'LEFT', 'linkingTable' => true))
                ->on()
                ->equalsField('acc.id', 'acc_cts.account_id')->equals('acc.deleted', 0);
        $query->select(array(array('oj_events.id', 'event_id'), array('atc.id_c', 'event_status_id'),
            array('ea.email_address', 'contact_email_address'),
            array('ct.id', 'contact_id'), array('ct.first_name', 'contact_first_name'),
            array('ct.last_name', 'contact_last_name'), array('acc.name', 'account_name')));
        $query->where()->equals("atc.event_status_c", 'YC');
        $query->where()->equals("atc.selection_status_c", 'STS');
		//added as mentioned in Ticket #52437 (join with group A)
        
        if (isset($args['priority_form_type']) && strtolower($args['priority_form_type']) == "se"){
            $query->where()->in("atc.participant_type_c", array('Sponsor', 'Expert'));
        } else {
            $query->where()->in("atc.participant_type_c", array('Delegate', 'Guest'));
        }
        
        // $query->where()->notEquals("atc.participant_type_c", 'Speaker');
        if (!empty($event_status_id)) {
            $query->where()->equals("atc.id_c", "{$event_status_id}");
        }
        $query->where()->equals("oj_events.id", "{$args['event_id']}");
        $dataSet = $query->execute();
        if (empty($dataSet)) {
            return array('success' => false, 'level' => 'info', 'message' => 'There are no Event Status records');
        }

        // check the document, if no document then first the document
        $event_bean = BeanFactory::getBean("oj_Events", $args['event_id']);
        $document_name = strtolower(trim($event_bean->name)) . '.html';
        $doc = $this->getDocumentBean($document_name);
        if (empty($doc->id)) {            
            $response = $this->generatePriorityForm($api, $args);
            if ($response['success'] && !empty($response['docID'])) {
                $doc = BeanFactory::getBean('Documents', $response['docID']);
            } else {
                return $response;
            }
        }
        if (empty($doc->id) || empty($doc->document_revision_id)) {
            return array('success' => false, 'level' => 'info', 'message' => "Couldn't find the Document Record.");
        }

        // get the file which is created in documents
        /*$uploadFile = new UploadFile();
        $uploadFile->temp_file_location = UploadFile::get_upload_path($doc->document_revision_id);
        $fileOrgContents = $uploadFile->get_file_contents();

        $tempFormPath = 'custom/include/selectionsForm.html';
        $tempFormName = 'selectionsForm.html'; */

        foreach ($dataSet as $data) {
            if (!empty($data['contact_email_address'])) {
                /* $contents = $fileOrgContents;
                $contact_name = return_name($data, 'contact_first_name', 'contact_last_name');
                $contents = str_replace("OJFORMGENERATION_CONTACTNAME", $contact_name, $contents);
                $contents = str_replace("OJFORMGENERATION_ACCOUNTNAME", $data['account_name'], $contents);
                $contents = str_replace("OJFORMGENERATION_EVENTSTATUSID", $data['event_status_id'], $contents); */
                // object to write the file

                /*
                $uploadStream = new UploadStream();
                //open in write mode
                $uploadStream->stream_open($tempFormPath, 'w');
                //write the contents in new temp file
                $uploadStream->stream_write($contents);
                // newly created file path
                $filePath = $uploadStream::path($tempFormPath);
                */
                require_once('modules/EmailTemplates/EmailTemplate.php');
                $template = new EmailTemplate();
                
                if ($event_bean->event_template_c != ""){
                    $template->retrieve($event_bean->event_template_c);
                } else {
                    $template->retrieve_by_string_fields(array('name' => 'Selections form', 'type' => 'email'));
                }
                
                //parsing of contact name
                $template->body = str_replace('$contact_first_name',$data['contact_first_name'],$template->body);
                $template->body_html = str_replace('$contact_first_name',$data['contact_first_name'],$template->body_html);
                $selection_form_link = '<a href="'.trim($sugar_config['site_url'], '/');
                $selection_form_link .= '/index.php?entryPoint=priorityForm&event_id='.$args['event_id'].'&'.'event_status_id='.$data['event_status_id'].'"> Selections form </a>';
                
                if (!empty($template)) {
                    $subject = $template->subject;
                    $body = $template->body_html;
                    $body = str_replace("\$selection_form_link", $selection_form_link, $body);
                } else {
                    $subject = 'Selections Form';
                    $body = 'Hi,<br><br>Please fill and submit the attached form.';
                }
                $this->sendEmail($data['contact_email_address'], $data['contact_id'], $data['event_status_id'], $subject, $body);
                //$uploadStream->unlink($tempFormPath);
            }
        }

        return array('success' => true, 'level' => 'success', 'message' => "Selections Form has been sent successfully.");
    }

    /**
     * send email to particualr email address with selection form as attachment
     */
    private function sendEmail($emailAddress, $contact_id, $event_status_id, $subject, $body, $mime_type = 'text/html') {
        if (!empty($emailAddress)) {
            $emailObj = new Email();
            $defaults = $emailObj->getSystemDefaultEmail();

            $mail = new SugarPHPMailer();
            $mail->setMailerForSystem();
            $mail->From = $defaults['email'];
            $mail->FromName = $defaults['name'];
            $mail->Subject = from_html($subject);
            $mail->IsHTML(true);
            $mail->Body = from_html($body);
            $mail->prepForOutbound();
            $mail->AddAddress($emailAddress);
            //$mail->AddAttachment($filePath, $fileName, 'base64', $mime_type);

            if ($mail->Send()) {
                $args = array('mail_from' => $defaults['email'], 'mail_from_name' => $defaults['name'],
                    'email_subject' => $subject, 'email_body' => $body, 'email_to' => $emailAddress,
                    'parent_type' => 'Contacts', 'parent_id' => $contact_id,
                    'mime_type' => $mime_type);
                $this->attachEmailWithContact($args);
                $this->updateSelectionStatusValue($event_status_id);
                $GLOBALS['log']->debug('Time (' . $GLOBALS['timedate']->nowDb() . '): Email Sent To ' . $emailAddress);
                return true;
            } else {
                $GLOBALS['log']->debug('Time (' . $GLOBALS['timedate']->nowDb() . '): Email failed To ' . $emailAddress);
                return false;
            }
        } else {
            $GLOBALS['log']->debug('Time (' . $GLOBALS['timedate']->nowDb() . '): Invalid Email Address');
            return false;
        }
    }

    /**
     * On Form Sending, change the "Selection Status" value to "Selections Sent" and on form receiving change its value to "Selections Received".
     */
    private function updateSelectionStatusValue($event_status_id) {
        $updateQuery = "UPDATE oj_attendance_cstm SET selection_status_c = 'SS' WHERE id_c='{$event_status_id}'";
        $GLOBALS['db']->query($updateQuery, 1);
    }

    /**
     * Attach the email with Contacts so that it will appear in Emails SubPanel
     */
    private function attachEmailWithContact($args) {
        $email = BeanFactory::newBean('Emails');
        $email->from_addr = $args['mail_from'];
        $email->from_name = $args['mail_from_name'];

        $email->reply_to_name = $args['mail_from_name'];
        $email->reply_to_email = $args['mail_from'];
        $email->name = $args['email_subject'];
        $email->description_html = $args['email_body'];
        $email->to_addrs = $email->to_addrs_arr = $args['email_to'];
        $email->parent_type = $args['parent_type'];
        $email->parent_id = $args['parent_id'];
        $email->status = 'sent';
        $email->assigned_user_id = $GLOBALS['current_user']->id;
        $email->save();
        //$email->saveTempNoteAttachments($args['file_name'], $args['file_location'], $args['mime_type']);
        if ($email->id) {
            $GLOBALS['log']->debug('Time (' . $GLOBALS['timedate']->nowDb() . '): Email Saved To ' . $args['parent_id']);
        } else {
            $GLOBALS['log']->debug('Time (' . $GLOBALS['timedate']->nowDb() . '): Email Not Saved To ' . $args['parent_id']);
        }
    }

    /**
     * create the form in SugarCRM document module
     *
     * @param $data array contains the data of event->session categories->sessions
     * @return array
     */
    private function createFormAsDocument($data) {
        $contents = $this->prepareFormContents($data);

        $document_name = strtolower(trim($data['event_name'])) . '.html';
        $doc = $this->getDocumentBean($document_name);
        $doc->document_name = $document_name;
        $doc->doc_type = 'Sugar';
        $doc->team_id = 1;
        $doc->team_set_id = 1;
        $doc->description = $data['event_id'];
        $doc->assigned_user_id = $GLOBALS['current_user']->id;
        $docId = $doc->save();

        $doc = BeanFactory::getBean('Documents', $docId);

        $uploadFile = new UploadFile();
        $uploadFile->set_for_soap($document_name, $contents);
        $ext_pos = strrpos($document_name, ".");
        $uploadFile->file_ext = substr($document_name, $ext_pos + 1);

        $docRevision = BeanFactory::getBean('DocumentRevisions');
        $document_revisions = $docRevision->get_document_revisions($docId);

        $docRevision->filename = $uploadFile->get_stored_file_name();
        $docRevision->file_mime_type = 'text/html';
        $docRevision->file_ext = $uploadFile->file_ext;
        $docRevision->doc_type = "Sugar";

        $docRevision->revision = empty($document_revisions) ? 1 : max($document_revisions) + 1;
        $docRevision->document_id = $docId;
        $docRevision->save();

        $doc->document_revision_id = $docRevision->id;
        $link = 'oj_events_documents_1';
        $doc->load_relationship($link);
        $doc->$link->add($data['event_id']);
        $doc->save();
        $uploadFile->final_move($docRevision->id);
        return array('documentId' => $docId, 'documentName' => $doc->document_name);
    }

    /**
     * Check if already document exist with document_name
     *
     * @param $data  
     * @return array
     */
    private function getDocumentBean($document_name) {
        $query = new SugarQuery();
        $query->select(array('id'));
        $query->from(BeanFactory::getBean('Documents'), array('team_security' => false));
        $query->where()->equals("document_name", "{$document_name}");
        $id = $query->getOne();

        if (!empty($id)) {
            $doc = BeanFactory::getBean('Documents', $id);
        } else {
            $doc = BeanFactory::getBean('Documents');
        }
        return $doc;
    }

    /**
     * Prepare the html of form
     *
     * @param $data contains the data of event->session categories->sessions 
     * @return array
     */
    private function prepareFormContents($data) {
        global $sugar_config;
        
        $rt_salutation = 'Mr';
        $rt_first_name = 'Aqib';
        $rt_last_name = 'javed';
        $rt_job_title = 'project manager';
        $rt_office_number = '8908xxxxxx';
        $rt_mob = '898789xxxx';
        $rt_admin_first_name = 'david';
        $rt_admin_last_name = 'wrench' ;
        $rt_admin_email = 'davidWrench@rtlabs.com';
        $rt_admin_office = '8908xxxxxx';
        $rt_admin_bio= 'sugarcrm';
        $rt_admin_image = 'dummy val';
        $rt_admin_diety = 'fruits & nuts';
        
        
        
        $html = '<!DOCTYPE html>
<html>
    <head>
        <style>
            body {
                font-size:14px;

            }
            *{
                padding: 0px ;margin:0px;
            }
            .ownes-wrapper{
                color: #000;
                font-size: 13px;
                overflow: hidden;
                padding: 0 0 20px;
            }
            .container{max-width: 1050px;}
            .header-top{
                background: #c5112e;
                padding: 30px 0;
            }
            .header-top .container>img {
                width: 100%;
                height: auto;
                display: block;
            }
            .header-top a{display: block;}
            .header-top a img{width: 80%;}
            .head-content{
                overflow: hidden;
                padding: 15px 0 10px;
            }
            .company-name{
                background: #f2f2f2;
                border:1px solid #000;
                overflow: hidden;
                padding: 4px 10px;
                margin: 8px 0;
            }
            table.own_table.description{
                width:100% !important;
            }
            .head-content hrgroup{
                text-align: center;
            }
            .head-content hrgroup h4 span{color: #cc0517;}

            .own_table{
                font-size: 12px;
                border: 1px solid #000; 
                font-family: Arial, Helvetica, sans-serif;
            } 
            .own_table td {
                padding: 8px;
                margin: 3px;
                border: 1px solid #000;
            }
            .own_table.description td:first-child{
                color: #cc0517;
            }
            .own_table th:first-child {
                width: 5%!important;
            }
            .own_table tr td p span{color: #cc0517;}
            .own_table th {
                font-weight: bold;
                text-align: center;
                padding: 8px;
                border: 1px solid #000;
                color: #cc0517;
            }
            .own_table th:last-child{color: #000; width: 10%!important;}
            .title-row td, .title-row th{
                background: #aa0000;
                color: #fff!important;
                font-weight: bold;
            }
            /*================== own_table_two ====================*/
            .own_table_two{}
            .own_table_two th:last-child{min-width: 112px;}
            .own_table_two p{padding-left: 30px;position: relative;}
            .own_table_two p:before{
                content: "";
                border: 1px solid #cc0517;
                font-size: 30px;
                left: 0;
                position: absolute;
                top: 6px;
                width: 10px;
            }
            .own_table_two td>h4{font-style: normal;padding-left: 30px;}
            .own_table_two td>h4>span {
                overflow: hidden;
                margin-left: -30px;
                padding-right: 20px;
            }
            .own_table_two h4{
                font-style: italic;
                color:#cc0517;
                padding-top:5px;
            }
            p.no-bullet, .no-bullet p{
                padding: 0;
            }
            .no-bullet:before,.no-bullet p:before{
                content:"";
                border:0!important;
            }

            .ownes-wrapper .footer{
                text-align: center;
                padding: 15px 0;
                font-size: 18px;
            }
            .custom_eye_icon{
                margin-left:2px;
                cursor: pointer;
            }
            .modal-header{
                background: #cc0517;
                color: #fff!important;
                font-weight: bold;
            }
            #data-description{
             padding: 6% !important;   
            }
            @media(max-width: 445px){
                .header-top .pull-left,.header-top .pull-right{
                    float: none!important;
                    text-align: center;
                    margin: 0 0 14px;
                }
                .header-top a{

                }
                .header-top a img {width: 58%;}
            }
        </style>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Owen James</title>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>';

        $html .= '<body>            
            <form method="post" action="' . trim($sugar_config['site_url'], '/') . '/index.php?entryPoint=submitPriorityForm"> 
            <input type="hidden" name="event_id" value="' . $data['event_id'] . '">
            <input type="hidden" name="event_status_id" value="OJFORMGENERATION_EVENTSTATUSID">
            <input type="hidden" id="event_selection_priority_c" value="' . $data['event_selection_priority_c'] . '"  />
            <input type ="hidden" id="total_sessions" value="' . $data['total_sessions'] . '"  />
        <div class="ownes-wrapper">
            <header class="header-top">
               <div class="container">
                        <img src="'. trim($sugar_config['site_url'], '/').$data['banner_path'].'">
                </div>
            </header>
            <div class="head-content">
                <div class="container">
                    <hrgroup>
                        <h4>' . $data['event_name_with_date'] . '</h4>
                        <h5>' . $data['event_address'] . '</h5>
                        <h4><span>ROUNDTABLE SELECTION FORM</span></h4>
                    </hrgroup>
                    <div class="company-name">
                        <p><b>Name:</b> OJFORMGENERATION_CONTACTNAME</p>
                        <p><b>Company:</b> OJFORMGENERATION_ACCOUNTNAME</p>
                    </div>
                    <div></div>
                    <p>' . $data['event_selection_form_header'] . '</p>
                </div>
            </div>';

        $html .= '<div class="container">
                <div class="table-responsive">
                    <table class="own_table description">
                        <thead>
                            <tr class="tableizer-firstrow">
                                <th>&nbsp;</th>
                                <th>SESSION TITLE <br></th>
                                <th>Please prioritise 1-' . $data['event_selection_priority_c'] . '</th>
                            </tr>
                        </thead>
                        <tbody>';
        $i = 1;
        foreach ($data['categories'] as $category) {
            $html .='<tr class="title-row">
                        <td colspan="2">' . $category['category_name'] . '</td>
                        <td>&nbsp;</td>
                    </tr>';
            foreach ($category['sessions'] as $session) {
                $html .='<tr>
                        <td>' . $i . '.</td>
                        <td data-description="' . $session['session_description'] . '">' . $session['session_name'] . ' <span class="glyphicon glyphicon-info-sign show_description custom_eye_icon" data-toggle="modal" data-target="#myModal"></span></td>
                        <td><div class="text-center"><input type="hidden" name="session_ids[]" value="' . $session['session_id'] . '"><select class="event_priority_dropdown" id="' . $session['session_id'] . '" name="priority_values_against_session[]" ><option value="-1"> Select priority </option></select></div></td>
                    </tr>';
                $i++;
            }
        }
        $html .='</tbody>
                    </table>
                    <hr>';
        $html .= '</div>
                <footer class="footer">
                    <p><input class="btn" type="submit" name="owenJamesPriority" value="Submit" onclick="return validateMyForm();"> </p>
                </footer>
            </div>
        </div>
        </form>
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                    </div>
                    <div class="modal-body" id="data-description">
                        No description found.
                    </div>

                </div>
            </div>
        </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script>
         
         $("body").delegate(".show_description","click",function(){
            var session_category = $.trim($(this).parent().parent().prevAll(".title-row").first().text());           
            $("#myModalLabel").html(session_category);
            var session_content = $(this).parent().attr("data-description");
            if(session_content == "") {
                session_content = "No description found";
            }
            $("#data-description").html(session_content);

         });
         
            var total_sessions = $("#total_sessions").val();
            var event_selection_priority_c = $("#event_selection_priority_c").val();


            window.optionString = "";
            window.selectedValues = [];
            window.dropdownValues = [];
            window.dropdownMap = [];
            var noDistinction = false;
           

            
            var options = [];
            for (i = 1; i <= event_selection_priority_c; i++) {
                options.push(i);
            }
            
            function populateDropdowns(str, excludeid, start) {
                var data = "";
                if (str == "" && excludeid == "") {
                    data = getHtmlOptions("")
                } else {
                    data = str;
                }

                $(".event_priority_dropdown").each(function () {
                    if (start == true) {
                        $(this).html(data);
                    } else if (excludeid != $(this).attr("id") && $(this).val() == -1) {
                        $(this).html(data);
                    } else if (excludeid != $(this).attr("id") && $(this).val() != -1) {
                        var currentValue = $(this).val();
                        $(this).html(data);                        
                        $(this).append("<option value="+currentValue+">" + currentValue + "</option>");
                        $(this).val(currentValue).prop("selected", true);
                        sortOptions($(this).attr("id"));
                    }

                });

                $(".event_priority_dropdown").each(function () {
                    window.dropdownMap[$(this).attr("id")] = $(this).val();
                });
            }

            function getHtmlOptions(myoptions) {

                if (myoptions == "" && window.selectedValues == "") {

                    if (window.optionString == "") {
                        var string = "<option value=\'-1\'>Select Priority</option>";
                        $.each(options, function (key, value) {
                            string += "<option value=" + value + ">" + value + "</option>";
                        });
                        window.optionString = string;
                        return string;
                    } else {
                        return window.optionString;
                    }
                } else {
                    var string = "<option value=\'-1\'>Select Priority</option>";
                    $.each(myoptions, function (key, value) {
                        string += "<option value=" + value + ">" + value + "</option>";
                    });

                    return string;
                }
            }
            
            if(total_sessions > event_selection_priority_c) {
                populateDropdowns("", "", true);
                noDistinction = true;
            } else {

            function arr_diff(a1, a2) {

                var a = [], diff = [];

                for (var i = 0; i < a1.length; i++) {
                    a[a1[i]] = true;
                }

                for (var i = 0; i < a2.length; i++) {
                    if (a[a2[i]]) {
                        delete a[a2[i]];
                    } else {
                        a[a2[i]] = true;
                    }
                }

                for (var k in a) {
                    diff.push(k);
                }

                return diff;
            }

            function sortOptions(id) {
                var my_options = $("#" + id + " option");
                var selected = $("#" + id).val();

                my_options.sort(function (a, b) {
                    if (a.text > b.text)
                        return 1;
                    if (a.text < b.text)
                        return -1;
                    return 0
                })
                $("#" + id).empty().append(my_options);
                $("#" + id).val(selected);
            }
            
            function setSelectedValues() {
                $.each(window.dropdownValues, function (key, val) {
                    $("#" + val.id).val(val.value).prop("selected", true);
                });
            }



            function setAllDropDowns(val, id) {
                var arr = arr_diff(options, window.selectedValues);
                var preVal = window.dropdownMap[id];
                window.dropdownMap[id] = val;
                if (preVal != -1) {
                    if (arr.indexOf(preVal.toString()) == -1) {
                        arr.push(preVal);
                    }
                }                
                var mystr = getHtmlOptions(arr);
                populateDropdowns(mystr, id);
                setSelectedValues();
            }                                             
            populateDropdowns("", "", true);
        }
        $(".event_priority_dropdown").each(function () {
                $(this).on("change", function () {                                       
                        window.dropdownValues = [];
                        window.selectedValues = getAllDropdownValues();
                        if(!noDistinction) {
                        setAllDropDowns($(this).val(), $(this).attr("id"));
              }
                });
            });


            function getAllDropdownValues() {
                var allValues = [];
                $(".event_priority_dropdown").each(function () {
                    if ($(this).val() != -1) {
                        allValues.push($(this).val());
                        window.dropdownValues.push({id: $(this).attr("id"), value: $(this).val()});
                    }
                });
                return allValues;
        }
        function validateMyForm()
                {
                    if (window.selectedValues == "") {
                        {
                            alert("Please Select atleast one priority");
                            window.history.back();
                            return false;
                        }
                        return true;
                    }
          }
   
        </script>
    </body>
</html>';

        return $html;
    }

}
