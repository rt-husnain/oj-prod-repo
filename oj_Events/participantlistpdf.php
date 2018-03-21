<?php
require_once('vendor/tcpdf/tcpdf.php');

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        $check = 'new Value';
        $cstm_func_arr = array(
            "cstm_getimagesize" => "getimagesize",
            "cstm_file_exists" => "file_exists",
            "cstm_file_get_contents" => "file_get_contents",
            "cstm_file_put_contents" => "file_put_contents",
            "cstm_unlink" => "unlink"
        );

        global $sugar_config;
        $event_id = $_REQUEST['record'];
        $eventBeanRT = BeanFactory::getBean('oj_Events', $event_id);
        
        $getImageDetails = getImageDetails($eventBeanRT->rt_pdf_banner_c);
        $getImageDetails2 = getImageDetails($eventBeanRT->rt_pdf_banner_2);

        // Logo
        if ($this->page == 1) {
            $this->SetFont('gillsansmtstd', 'B', 16);

			$path = 'custom/include/images/';

			$pe_image = $path.'pe.jpg';
			$mo_image = $path.'/mo.jpg';
			$mom_image = $path.'/mom.jpg';

            if ($eventBeanRT->event_group_c == 'MOMPB' || $eventBeanRT->event_group_c == 'MOMAD' || $eventBeanRT->event_group_c == 'MOMWA' || $eventBeanRT->event_group_c == 'MOMR')
                $this->Image($mom_image, '0', '0', 210, 30, "", '', '', false, 300, '', false, false, false, false);
			
             else if ($eventBeanRT->event_group_c == 'MO') 
                    $this->Image($mo_image, '0', '0', 210, 30, "", '', '', false, 300, '', false, false, false, false); 
				
			else if ($eventBeanRT->event_group_c == 'PE') 
                    $this->Image($pe_image, '0', '0', 210, 30, "", '', '', false, 300, '', false, false, false, false);
                
            
            $this->setPageMark();
        }
    }

    // Page footer
    public function Footer() {
        $cstm_func_arr = array(
            "cstm_getimagesize" => "getimagesize",
            "cstm_file_exists" => "file_exists",
            "cstm_file_get_contents" => "file_get_contents",
            "cstm_file_put_contents" => "file_put_contents",
            "cstm_unlink" => "unlink"
        );
        $this->SetFont('gillsansmtstd', 'I', 8);
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $text = "This list is copyright of the Owen James Group and its contents must be kept confidential and may not \n";
        $text .= "be passed to any third party for any purpose";
        $this->MultiCell(0, 0, $text, 0, 'C');
    }

}

// custom function to getting image details against uploaded hash

function getImageDetails($img_hash = "") {
    $cstm_func_arr = array(
        'cstm_getimagesize' => 'getimagesize',
        'cstm_file_exists' => 'file_exists',
        'cstm_file_get_contents' => 'file_get_contents',
        'cstm_file_put_contents' => 'file_put_contents',
        'cstm_unlink' => 'unlink',
    );
    global $sugar_config;
    $_hash = $sugar_config['upload_dir'] . $img_hash;
	
    $mime = $cstm_func_arr['cstm_getimagesize']($_hash);
    $_ext = str_replace("image/", "", $mime['mime']);
    $img_path = $_hash . "." . $_ext;
	
	if(!empty($_ext)){
		
		if ($cstm_func_arr['cstm_file_exists']($img_path) == 1) { 
			
			$banner_file_contents = $cstm_func_arr['cstm_file_get_contents']($_hash);
			$cstm_func_arr['cstm_file_put_contents']($img_path, $banner_file_contents);
		} else {
			@$cstm_func_arr['cstm_unlink']($img_path);
			$banner_file_contents = $cstm_func_arr['cstm_file_get_contents']($_hash);
			$cstm_func_arr['cstm_file_put_contents']($img_path, $banner_file_contents);
		}
		
		$img_details = array(
			"PDF_IMG_TYPE" => strtoupper($_ext),
			"img_path" => $img_path,
		);
	}
    
    return $img_details;
}

$type = 'dummy val';

function getData(&$type) {
    $cstm_func_arr = array(
        "cstm_getimagesize" => "getimagesize",
        "cstm_file_exists" => "file_exists",
        "cstm_file_get_contents" => "file_get_contents",
        "cstm_file_put_contents" => "file_put_contents",
        "cstm_unlink" => "unlink"
    );
    $event_id = $_REQUEST['record'];
    $eventBean = BeanFactory::getBean('oj_Events', $event_id);

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
    $query->joinTable('contacts_cstm', array('alias' => 'cc', 'joinType' => 'LEFT', 'linkingTable' => true))
            ->on()
            ->equalsField('cc.id_c', 'ct.id');
    $query->joinTable('accounts_contacts', array('alias' => 'acc_cts', 'joinType' => 'LEFT', 'linkingTable' => true))
            ->on()
            ->equalsField('ct.id', 'acc_cts.contact_id')->equals('acc_cts.deleted', 0);
    $query->joinTable('accounts', array('alias' => 'acc', 'joinType' => 'LEFT', 'linkingTable' => true))
            ->on()
            ->equalsField('acc.id', 'acc_cts.account_id')->equals('acc.deleted', 0);
    $query->joinTable('accounts_cstm', array('alias' => 'acstm', 'joinType' => 'LEFT', 'linkingTable' => true))
            ->on()
            ->equalsField('acstm.id_c', 'acc.id');

    if (isset($_REQUEST['type']) && $_REQUEST['type'] == '2') {
        $query->select(array(
            array('atc.participant_type_c', 'participant_type'),
            array('cc.job_function_c', 'contact_job_function'),
            array('cc.financial_advisory_ifa_c', 'financial_advisory_ifa_c'),
            array('cc.winning_advisers_c', 'winning_advisers_c'),
            array('cc.private_banking_wealth_c', 'private_banking_wealth_c'),
            array('cc.retail_class_c', 'retail_class_c'),
            array('oj_events.event_group_c', 'event_group_c'),
            array('cc.retail_financial_services_c', 'retail_financial_services_c'),
            array('acc.name', 'account_name'), array('acc.id', 'account_id'), array('acstm.company_logo_c', 'account_company_logo_c'), /**/
            array('ct.title', 'contact_job_title')));
        
    } else {
        $query->select(array(
            array('atc.participant_type_c', 'participant_type'),
            array('at.id', 'at_id'),
            array('cc.job_function_c', 'contact_job_function'),
            array('ct.first_name', 'contact_first_name'),
            array('ct.id', 'contact_id'), array('cc.financial_advisory_ifa_c', 'financial_advisory_ifa_c'),
            array('cc.winning_advisers_c', 'winning_advisers_c'),
            array('cc.private_banking_wealth_c', 'private_banking_wealth_c'),
            array('cc.retail_class_c', 'retail_class_c'),
            array('oj_events.event_group_c', 'event_group_c'),
            array('cc.retail_financial_services_c', 'retail_financial_services_c'),
            array('ct.last_name', 'contact_last_name'), array('acc.name', 'account_name'), array('acc.id', 'account_id'), array('acstm.company_logo_c', 'account_company_logo_c'), /**/
            array('ct.title', 'contact_job_title')));
    }
                $values = array( 'YC', 'CP', 'HP' );
    
		$query->where()->in("atc.event_status_c",$values);

		$query->where()->equals("oj_events.id", "{$event_id}");
                $query->distinct(true);
		$dataSet = $query->execute();

		$data = array();
		$sponsor_images = array();
		$strategic_partner_images = array();
		$acc_ids_arr = array();

		$data['event_name'] = htmlspecialchars($eventBean->event_full_name_c);
		$data['event_name'] = $eventBean->event_full_name_c;
		if (!empty($eventBean->event_start)) {
			$data['event_start_date'] = date('l j F Y', strtotime($GLOBALS['timedate']->to_db_date($eventBean->event_start, false)));
		} else {
			$data['event_start_date'] = '';
		}
		
		for ($i = 0 ; $i< sizeof($dataSet) ; $i++ ) {
			if(isset($dataSet[$i]["event_group_c"])){
				$dataSet[$i]["event_group_c"]=$eventBean->event_group_c;
			}
		}

		$data['event_address'] = htmlspecialchars($eventBean->venue);
		foreach ($dataSet as $index => $attributes) {
			$title = getTitle($attributes);
			if ($attributes["event_group_c"] == 'PE') {
				$type = 'PE';
				if ($attributes['participant_type'] == 'Delegate') {
					$data['no_heading_pe'] [] =  '<li><span></span>' . $title . '</li>';
				} else if ($attributes['participant_type'] == 'Speaker') {
					$data['speakers_pe'] [] =  '<li><span></span>' . $title . '</li>';
					
				} 
//                                else if ($attributes['participant_type'] == 'Sponsor' || $attributes['participant_type'] == 'Expert') {
//					$data['Sponsors_experts_pe'] [] =  '<li><span></span>' . $title . '</li>';
//				}
			}
			//------------- Mindful of -> Data set preparation
			else if ($attributes["event_group_c"] == 'MO') {
				$type = 'MO';
				if ($attributes['participant_type'] == 'Delegate') {
					if ($attributes['financial_advisory_ifa_c'] == 'Y') {
						$data['advisory_distributors_mo'] [] =  '<li><span></span>' . $title . '</li>';
					} else if ($attributes['winning_advisers_c'] == 'Y') {
						$data['winning_advisers_mo'] [] =  '<li><span></span>' . $title . '</li>';
					} else if ($attributes['private_banking_wealth_c'] == 'Y') {
						$data['private_bankers_wealth_managers_mo'] [] =  '<li><span></span>' . $title . '</li>';
					} else if ($attributes['retail_financial_services_c'] == 'Y') {
						$data['banks_brands_distributing_mo'] [] =  '<li><span></span>' . $title . '</li>';
					} else if ($attributes['contact_job_function'] == 'Paraplanner') {
						$data['paraplanners_mo'] [] =  '<li><span></span>' . $title . '</li>';
					}
				} else if ($attributes['participant_type'] == 'Speaker') {
					$data['speakers_mo'] [] =  '<li><span></span>' . $title . '</li>';
				}
//                                else if ($attributes['participant_type'] == 'Sponsor' || $attributes['participant_type'] == 'Expert') {
//					$data['Sponsors_experts_mo'] [] =  '<li><span></span>' . $title . '</li>';
//				}
                                else if ($attributes['participant_type'] == 'Guest') {
					$data['guests_mo'] [] =  '<li><span></span>' . $title . '</li>';
				}
			}

			//----------------- Meeting of minds -> Data Set preparation
			// Wealth managment & prvt banking -----------------------
			else if ($attributes["event_group_c"] == 'MOMPB') {
				$type = 'MOMPB';
				if ($attributes['participant_type'] == 'Delegate') {
					if ($attributes['contact_job_function'] == 'Business Strategy' || 
							$attributes['contact_job_function'] == 'Compliance' || 
							$attributes['contact_job_function'] == "Customer Services" 
							|| $attributes['contact_job_function'] == 'Finance' 
							|| $attributes['contact_job_function'] == 'IT'
                                                        || $attributes['contact_job_function'] == 'Operations'
							|| html_entity_decode($attributes['contact_job_function']) == "Marketing & Communications" 
							|| html_entity_decode($attributes['contact_job_function']) == "Research & Insight" 
							|| html_entity_decode($attributes['contact_job_function']) == "Sales & Business Development"){

						$data['bussiness_strategy_mom_wm'] [] =  '<li><span></span>' . $title . '</li>';
					} else if ($attributes['contact_job_function'] == 'Proposition Development' ||
							$attributes['contact_job_function'] == 'Investment') {

						$data['investment_mom_wm'] [] =  '<li><span></span>' . $title . '</li>';
					}
				} else if ($attributes['participant_type'] == 'Speaker' || $attributes['participant_type'] == 'Facilitator') {
					$data['facilitators_speakers_mom_wm'] [] =  '<li><span></span>' . $title . '</li>';
				}
//                                else if ($attributes['participant_type'] == 'Sponsor' || $attributes['participant_type'] == 'Expert') {
//					$data['Sponsors_experts_mom_wm'] [] =  '<li><span></span>' . $title . '</li>';
//				} 
                                else if ($attributes['participant_type'] == 'Guest') {
					$data['guest_mom_wm'] [] =  '<li><span></span>' . $title . '</li>';
				}
			}
			//----------------------Advisory Distributors
			else if ($attributes["event_group_c"] == 'MOMAD') {
				$type = 'MOMAD';
				if ($attributes['participant_type'] == 'Delegate') {
					if ($attributes['contact_job_function'] == 'Business Strategy' 
							|| $attributes['contact_job_function'] == 'Compliance' 
							|| $attributes['contact_job_function'] == 'Customer Services' 
							|| $attributes['contact_job_function'] == 'Finance' 
							|| $attributes['contact_job_function'] == 'IT' 
							|| html_entity_decode($attributes['contact_job_function']) == 'Marketing & Communications' 
							|| html_entity_decode($attributes['contact_job_function']) == 'Research & Insight' 
							|| html_entity_decode($attributes['contact_job_function']) == 'Sales & Business Development') {

						$data['bussiness_strategy_mom_ad'] [] =  '<li><span></span>' . $title . '</li>';
					} else if ($attributes['contact_job_function'] == 'Proposition Development' ||
							$attributes['contact_job_function'] == 'Investment') {

						$data['investment_mom_ad'] [] =  '<li><span></span>' . $title . '</li>';
					}
				} else if ($attributes['participant_type'] == 'Speaker' || $attributes['participant_type'] == 'Facilitator') {
					$data['facilitators_speakers_mom_ad'] [] =  '<li><span></span>' . $title . '</li>';
				}
//                                else if ($attributes['participant_type'] == 'Sponsor' || $attributes['participant_type'] == 'Expert') {
//					$data['Sponsors_experts_mom_ad'] [] =  '<li><span></span>' . $title . '</li>';
//				}
                                else if ($attributes['participant_type'] == 'Guest') {
					$data['guest_mom_ad'] [] =  '<li><span></span>' . $title . '</li>';
				}
			}
                        //------------------- Winning Advisers
			else if ($attributes["event_group_c"] == 'MOMWA') {
				$type = 'MOMWA';
				if ($attributes['participant_type'] == 'Delegate') {
					$data['no_heading_mom_wd'] [] =  '<li><span></span>' . $title . '</li>';
				} 
				else if ($attributes['participant_type'] == 'Speaker' || $attributes['participant_type'] == 'Facilitator') {
					$data['facilitators_speakers_mom_wd'][] = '<li><span></span>' . $title . '</li>';
				} 
//				else if ($attributes['participant_type'] == 'Sponsor' || $attributes['participant_type'] == 'Expert') {
//					$data['Sponsors_experts_mom_wd'] [] =  '<li><span></span>' . $title . '</li>';
//				}
                                else if ($attributes['participant_type'] == 'Guest') {
					$data['guest_mom_wd'] [] =  '<li><span></span>' . $title . '</li>';
				}
			}
//                            //------------------- Bank & brand distribution
        else if ($attributes["event_group_c"] == 'MOMR') {
            $type = 'MOMR';
            if ($attributes['participant_type'] == 'Delegate') {
                if ($attributes['retail_financial_services_c'] == 'Y' && $attributes['retail_class_c'] == 'Brand') {
                    $data['brands_mom_bb'] [] =  '<li><span></span>' . $title . '</li>';
                } else if ($attributes['retail_financial_services_c'] == 'Y') {
                    if ($attributes['retail_class_c'] == 'Bank' || $attributes['retail_class_c'] == 'Building Society') {
                        $data['banks_buildings_mom_bb'] [] =  '<li><span></span>' . $title . '</li>';
                    }
                }
            } else if ($attributes['participant_type'] == 'Speaker' || $attributes['participant_type'] == 'Facilitator') {
                $data['facilitators_speakers_mom_bb'] [] =  '<li><span></span>' . $title . '</li>';
            }
//            else if ($attributes['participant_type'] == 'Sponsor' || $attributes['participant_type'] == 'Expert') {
//                $data['Sponsors_experts_mom_bb'] [] =  '<li><span></span>' . $title . '</li>';
//            }
            else if ($attributes['participant_type'] == 'Guest') {
                $data['guest_mom_bb'] [] =  '<li><span></span>' . $title . '</li>';
            }
        }
        // --------------------Data set prepartion complete
    }
	
     // get sponosors images and data
    $data['sponsors_experts_Data']=getSponsorOrSPartnerData($_REQUEST['record'], "Sponsors");
    $data["sponsor_images"] = getSponsorOrSPartnerImages($_REQUEST['record'], "Sponsors");
    
    // get strategic partners images and data
    $data['strategic_partner_mom_wd']=getSponsorOrSPartnerData($_REQUEST['record'], "StrategicPartners");
    $data["strategic_images"] = getSponsorOrSPartnerImages($_REQUEST['record'], "StrategicPartners");

    return $data;
}

function getCountofArray($string) {

    $temp = (explode("</li>", $string));
    $count = sizeof($temp);
    return $count - 1;
}

function getTitle($attributes) {
    $title = "";
    if (!empty($attributes['account_name'])) {
        $title .= '<b>' . ucfirst(stripslashes($attributes['account_name'])) . '</b>';
    }
    $contact_name = return_name($attributes, 'contact_first_name', 'contact_last_name');

    if (!empty($contact_name) && $_REQUEST['type'] != '2') {
        $title .= '<b> - </b>' . ucfirst($contact_name);
    }
    if (!empty($attributes['contact_job_title'])) {
        $title .= ', ' . '<i>' . ucfirst(stripslashes($attributes['contact_job_title'])) . '</i>';
    }
    return $title;
}

function getSponsorOrSPartnerImages($event_id, $type) {
    global $db;
  
  $db->query("UPDATE opportunities_cstm SET event_org_type_c='Sponsors' WHERE event_org_type_c='S'",true);
  $db->query("UPDATE opportunities_cstm SET event_org_type_c='StrategicPartners' WHERE event_org_type_c='SP'",true);
  
    $sponosors_sql = "SELECT 
    distinct accounts.name as account_name,
    accounts.id,
    accounts_cstm.company_logo_c,
    contacts.first_name as contact_first_name,
    contacts.last_name as contact_last_name,
    contacts.title as contact_job_title
FROM
    opportunities
        LEFT JOIN
    oj_events_opportunities_1_c ON opportunities.id = oj_events_opportunities_1_c.oj_events_opportunities_1opportunities_idb
        LEFT JOIN
    opportunities_cstm ON oj_events_opportunities_1_c.oj_events_opportunities_1opportunities_idb = opportunities_cstm.id_c
        LEFT JOIN
    accounts_opportunities ON opportunities_cstm.id_c = accounts_opportunities.opportunity_id
        LEFT JOIN
    accounts ON accounts_opportunities.account_id = accounts.id
        LEFT JOIN
    accounts_cstm ON accounts.id = accounts_cstm.id_c
        LEFT JOIN
    accounts_contacts ON accounts.id = accounts_contacts.account_id
    LEFT JOIN
    contacts ON contacts.id = accounts_contacts.contact_id
WHERE
    opportunities.deleted = 0
        AND opportunities.sales_stage = 'Closed Won'
        AND opportunities_cstm.event_org_type_c = '" . $type . "'
        AND oj_events_opportunities_1_c.deleted = 0
        AND accounts.deleted = 0
		AND accounts_opportunities.deleted=0
        AND oj_events_opportunities_1_c.oj_events_opportunities_1oj_events_ida = '" . $event_id . "'
Group by account_name
ORDER BY account_name";

    $sponsor_imagesRT = array();
    $sponosors_res = $db->query($sponosors_sql);

    while ($sponosors_row = $db->fetchByAssoc($sponosors_res)) {
        if (!empty($sponosors_row["company_logo_c"])){
            $sponsor_imagesRT [] = array("id" => $sponosors_row["id"], "image_details" => getImageDetails($sponosors_row["company_logo_c"]));
           
            }
        else
            {
                $sponsor_imagesRT [] = array("id" => $sponosors_row["id"], "image_details" => array('img_path' => 'custom/include/images/no_logo2.jpg'));
            }
    }
    return $sponsor_imagesRT;

}

function getSponsorOrSPartnerData($event_id, $type) {
    global $db;
  
    $sponosors_sql = "SELECT 
    distinct accounts.name as account_name,
    accounts.id,
    accounts_cstm.company_logo_c,
    contacts.id as contact_id,
    contacts.first_name as contact_first_name,
    contacts.last_name as contact_last_name,
    contacts.title as contact_job_title
FROM
    opportunities
        LEFT JOIN
    oj_events_opportunities_1_c ON opportunities.id = oj_events_opportunities_1_c.oj_events_opportunities_1opportunities_idb
        LEFT JOIN
    opportunities_cstm ON oj_events_opportunities_1_c.oj_events_opportunities_1opportunities_idb = opportunities_cstm.id_c
        LEFT JOIN
    accounts_opportunities ON opportunities_cstm.id_c = accounts_opportunities.opportunity_id
        LEFT JOIN
    accounts ON accounts_opportunities.account_id = accounts.id
        LEFT JOIN
    accounts_cstm ON accounts.id = accounts_cstm.id_c
        LEFT JOIN
    accounts_contacts ON accounts.id = accounts_contacts.account_id
    JOIN
    contacts ON contacts.id = accounts_contacts.contact_id
WHERE
    opportunities.deleted = 0
        AND opportunities.sales_stage = 'Closed Won'
        AND opportunities_cstm.event_org_type_c = '" . $type . "'
        AND oj_events_opportunities_1_c.deleted = 0
        AND accounts.deleted = 0
        AND contacts.deleted = 0 
        AND accounts_contacts.deleted = 0
        AND accounts_opportunities.deleted=0
        AND oj_events_opportunities_1_c.oj_events_opportunities_1oj_events_ida = '" . $event_id . "'
ORDER BY account_name";

    $sponsor_dataRT = array();
    $sponosors_res = $db->query($sponosors_sql);
   
    while ($sponosors_row = $db->fetchByAssoc($sponosors_res)) {
          $subquery = "SELECT 
                ct.id as contact_id_1 , atc.participant_type_c as participant_type
        FROM
            oj_events
                LEFT JOIN
            oj_events_oj_attendance_1_c ev ON (ev.oj_events_oj_attendance_1oj_events_ida = oj_events.id
                AND ev.deleted = 0)
                LEFT JOIN
            oj_attendance at ON (at.id = ev.oj_events_oj_attendance_1oj_attendance_idb
                AND at.deleted = 0)
                LEFT JOIN
            oj_attendance_cstm atc ON (atc.id_c = at.id)
                INNER JOIN
            contacts_oj_attendance_1_c cta ON (at.id = cta.contacts_oj_attendance_1oj_attendance_idb
                AND cta.deleted = 0)
                INNER JOIN
            contacts ct ON (ct.id = cta.contacts_oj_attendance_1contacts_ida
                AND ct.deleted = 0)
                LEFT JOIN
            contacts_cstm cc ON (cc.id_c = ct.id)
                LEFT JOIN
            accounts_contacts acc_cts ON (ct.id = acc_cts.contact_id
                AND acc_cts.deleted = 0)
                LEFT JOIN
            accounts acc ON (acc.id = acc_cts.account_id
                AND acc.deleted = 0)
                LEFT JOIN
            accounts_cstm acstm ON (acstm.id_c = acc.id)
                LEFT JOIN
            oj_events_cstm ON oj_events_cstm.id_c = oj_events.id
        WHERE
            oj_events.deleted = 0
                AND atc.event_status_c IN ('YC' , 'CP', 'HP')
                AND oj_events.id = '".$event_id."'
                AND ct.id = '".$sponosors_row["contact_id"]."' ";
          $sub_response = $db->query($subquery);
            while($sub_row = $db->fetchByAssoc($sub_response)){
                if($sub_row['participant_type'] == 'Sponsor' || $sub_row['participant_type'] == 'Expert'){
                $title = getTitle($sponosors_row);
                $sponsor_dataRT[]='<li><span></span>' . $title . '</li>';
                }
            
            }
         }
    return $sponsor_dataRT;

}

function getStrategicPartnersImages($event_id) {
    $query = new SugarQuery();
    $query->from(BeanFactory::getBean('oj_Events', $event_id));
    $opportunities = $query->join('oj_events_opportunities_1')->joinName();
    $accounts = $query->join('accounts', array('relatedJoin' => $opportunities))->joinName();
    $query->select(array("$accounts.id", "$accounts.company_logo_c"));
    $query->where()->equals("$opportunities.sales_stage", "Closed Won");
    $query->where()->equals("$opportunities.event_org_type_c", "StrategicPartners");
    $query->distinct(true);
    $results = $query->execute();

    $StrategicPartner_imagesRT = array();

    foreach ($results as $res_row) {
        if (!empty($res_row["company_logo_c"]))
            $StrategicPartner_imagesRT [] = array("id" => $res_row["id"], "image_details" => getImageDetails($res_row["company_logo_c"]));
    }

    return $StrategicPartner_imagesRT;
}

$cstm_func_arr = array(
    "cstm_getimagesize" => "getimagesize",
    "cstm_file_exists" => "file_exists",
    "cstm_file_get_contents" => "file_get_contents",
    "cstm_file_put_contents" => "file_put_contents",
    "cstm_unlink" => "unlink",
);

$data = getData($type);

// create new PDF document
$pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->SetFont('gillsansmtstd', '', PDF_FONT_SIZE_MAIN);

//------ Sponsor images array ------------------------------------------------

$strategic_img_couter = 0;
$strategic_img_html = '';
if(sizeof($data["strategic_images"]) > 1){
$strategic_img_html .= '<table border="0" width="100%"><tr><td style="color:#b99d51" width="175%" colspan="4"> <h3>Strategic Partners</h3> <br/></td></tr><tr>';
}
else{
    $strategic_img_html .= '<table border="0" width="100%"><tr><td style="color:#b99d51" width="175%" colspan="4"> <h3>Strategic Partner</h3> <br/></td></tr><tr>';

}

foreach ($data["strategic_images"] as $key => $value) {

    if (!empty($value["image_details"]["img_path"])) { 
		if ($strategic_img_couter != 0 && $strategic_img_couter % 4 == 0) {
            $strategic_img_html .= '</tr><tr><td width="7%" ></td><td  height="56"><img src="' . $value["image_details"]["img_path"] . '"  width="122" height="41" /></td>';
		}
         else {
             $strategic_img_html .= '<td width="7%" ></td><td  height="56"><img src="' . $value["image_details"]["img_path"] . '"  width="122" height="41" /></td>';
       } 
    }
	else {
		$strategic_img_html .= '<td width="7%" ></td><td  height="56"><img src="custom/include/images/no_logo2.jpg"  width="122" height="41" /></td>';
	}
		
    $strategic_img_couter ++;
}

if($strategic_img_couter > 4)
	$missing_td_num_s = ($strategic_img_couter -( floor($strategic_img_couter/4) *4) );
else
	$missing_td_num_s = 4-$strategic_img_couter;

if( $missing_td_num_s > 0){
	for($j=1;$j<=$missing_td_num_s; $j++){
		$strategic_img_html .= '<td>  </td>';
	} 
}

$strategic_img_html .= '</tr></table></br>';

if ($strategic_img_couter > 0) {
     $test .= $strategic_img_html;
}

// -------------- Sponsors images data set completed
//------ Strategic partners images array ------------------------------------------------

$sponsor_img_couter = 0;
$sponsor_img_html = '<table border="0" width="100%"><tr><td style="color:#b99d51" width="175%" colspan="4"> <h3></h3> <br/></td></tr><tr></table>';
if(sizeof($data["sponsor_images"]) > 1){
$sponsor_img_html .= '<table border="0" width="100%"><tr><td style="color:#b99d51" width="175%" colspan="4"> <h3>Sponsors</h3> <br/></td></tr><tr>';
}
else{
    $sponsor_img_html .= '<table border="0" width="100%"><tr><td style="color:#b99d51" width="175%" colspan="4"> <h3>Sponsor</h3> <br/></td></tr><tr>';
}

foreach ($data["sponsor_images"] as $key => $value) {
	
	if (!empty($value["image_details"]["img_path"])) {
		
		if ($sponsor_img_couter != 0 && $sponsor_img_couter % 4 == 0) {
			$sponsor_img_html .= '</tr><tr><td width="7%" ></td><td  height="56"><img src="' . $value["image_details"]["img_path"] . '"  width="122" height="41" /></td>';
		}
		 else {
			$sponsor_img_html .= '<td width="7%" ></td><td  height="56" ><img src="' . $value["image_details"]["img_path"] . '"  width="122" height="41" /></td>';
		 }
		
	}
	else 
		if ($sponsor_img_couter != 0 && $sponsor_img_couter % 4 == 0) 
			$sponsor_img_html .= '</tr><tr><td width="7%" ></td><td height="56"><img src="custom/include/images/no_logo2.jpg"  width="118" height="41" /></td>';
		else
			$sponsor_img_html .= '<td width="7%" ></td><td height="56"><img  styl="border:1px solid red;" src="custom/include/images/no_logo2.jpg"  width="118" height="41" /></td>';
	

    $sponsor_img_couter ++;
}

$missing_td_num = ($sponsor_img_couter -( floor($sponsor_img_couter/4) *4) );

if($sponsor_img_couter > 4)
	$missing_td_num = ($sponsor_img_couter -( floor($sponsor_img_couter/4) *4) );
else
	$missing_td_num = 4-$sponsor_img_couter;


if( $missing_td_num > 0){
	for($i=1;$i<=$missing_td_num; $i++){
		$sponsor_img_html .= '<td>  </td>';	
	} 
}

$sponsor_img_html .= '</tr></table>';

if ($sponsor_img_couter > 0) {
     $test .= $sponsor_img_html;
}

// -------------- Strategic partners images data set completed
// set margins
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();

$test2 = '<table width="109%" bgcolor="#b99d51" style="color:#b20d22;font-weight:bold" border="0"><tr><td>' . html_entity_decode($data["event_name"]) . '</td></tr><tr><td>' . $data["event_start_date"] . ', ' . html_entity_decode($data["event_address"]) . '</td></tr></table>';
$X_old = $pdf->GetX();

$pdf->SetXY(0, $pdf->GetY() + 20);
$pdf->SetMargins(0, 10, PDF_MARGIN_RIGHT);
$pdf->writeHTML($test2, true, false, true, false, "C");
$pdf->SetXY(14, $pdf->GetY() + 3);


$pdf->setCellHeightRatio(0);
$pdf->writeHTML($test, true, false, false, false, '');


$test = '<table width="900" border="0"><tr><td width="38"></td><td style="" ></td></tr></table>    <table width="900" border="0">
	<tr><td width="38"></td><td style="height:15px" ><h1 style="color:#b20d22;">Participant List</h1></td></tr></table>';
$test .= '<table border="0" width="100%">';


//------ Paraplanning Excellence pdf prep

if ($type == 'PE') {

    if (!empty($data['no_heading_pe'])) {
      //  $data['no_heading_pe'] = array_unique($data['no_heading_pe']);
		sort($data['no_heading_pe']);
        $test .= '<tr><td width="25">  </td><td width="175%"><ul>' . implode("",$data['no_heading_pe']) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><br/><h4>Total :' . sizeof($data["no_heading_pe"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['speakers_pe'])) {
       // $data['speakers_pe'] = array_unique($data['speakers_pe']);
		sort($data["speakers_pe"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr><tr><td width="38"> </td><td style="color:#b99d51;height:10px"  width="175%" ><h3>' . translate('LBL_SPEAKERS', 'oj_Events') . '</h3></td></tr>';
        $test .= '<tr><td width="25">  </td><td width="175%"><ul>' . implode("",$data["speakers_pe"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["speakers_pe"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['strategic_partner_mom_wd'])) {
	sort($data["strategic_partner_mom_wd"]);
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38"></td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">STRATEGIC PARTNERS & EXPERTS</span><ul>' . implode("",$data["strategic_partner_mom_wd"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["strategic_partner_mom_wd"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['sponsors_experts_Data'])) {
         //$data['sponsors_experts_Data'] = array_unique($data['sponsors_experts_Data']);
		sort($data["sponsors_experts_Data"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr><tr><td width="38"> </td><td style="color:#b99d51;height:10px"  width="175%" ><h3>SPONSORS & EXPERTS</h3></td></tr>';
        $test .= '<tr><td width="25">  </td><td width="175%"><ul>' . implode("",$data["sponsors_experts_Data"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["sponsors_experts_Data"]) . '</h4></td></tr>';
        }
    }
}

//------------------Mindful of  pdf prep
if ($type == 'MO') {

    if (!empty($data['advisory_distributors_mo'])) {
      //  $data['advisory_distributors_mo'] = array_unique($data['advisory_distributors_mo']);
		sort($data['advisory_distributors_mo']);
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38"></td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">ADVISORY DISTRIBUTORS</span><ul>' . implode("",$data['advisory_distributors_mo']) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["advisory_distributors_mo"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['winning_advisers_mo'])) {
        // $data['winning_advisers_mo'] = array_unique($data['winning_advisers_mo']);
		sort($data["winning_advisers_mo"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">WINNING ADVISERS</span><ul>' . implode("",$data["winning_advisers_mo"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["winning_advisers_mo"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['private_bankers_wealth_managers_mo'])) {
       //  $data['private_bankers_wealth_managers_mo'] = array_unique($data['private_bankers_wealth_managers_mo']);
		sort($data["private_bankers_wealth_managers_mo"] );
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">' . translate('LBL_PRVT_BANKERS', 'oj_Events') . '</span><ul>' . implode("",$data["private_bankers_wealth_managers_mo"]). '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["private_bankers_wealth_managers_mo"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['banks_brands_distributing_mo'])) {
      //  $data['banks_brands_distributing_mo'] = array_unique($data['banks_brands_distributing_mo']);
		sort($data["banks_brands_distributing_mo"]);
		 
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">' . translate('LBL_BANKS_BRANDS', 'oj_Events') . '</span><ul>' . implode("",$data["banks_brands_distributing_mo"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["banks_brands_distributing_mo"]) . '</h4></td></tr>';
        }
    }

    if (!empty($data['paraplanners_mo'])) {
     //   $data['paraplanners_mo'] = array_unique($data['paraplanners_mo']);
		sort($data["paraplanners_mo"]);
		 
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">' . translate('LBL_PARAPLANNERS', 'oj_Events') . '</span><ul>' . implode("",$data["paraplanners_mo"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["paraplanners_mo"]) . '</h4></td></tr>';
        }
    }

    if (!empty($data['speakers_mo'])) {
        // $data['speakers_mo'] = array_unique($data['speakers_mo']);
		sort($data["speakers_mo"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">' . translate('LBL_SPEAKERS', 'oj_Events') . '</span><ul>' . implode("",$data["speakers_mo"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["speakers_mo"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['strategic_partner_mom_wd'])) {
	sort($data["strategic_partner_mom_wd"]);
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38"></td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">STRATEGIC PARTNERS & EXPERTS</span><ul>' . implode("",$data["strategic_partner_mom_wd"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["strategic_partner_mom_wd"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['sponsors_experts_Data'])) {
       //  $data['sponsors_experts_Data'] = array_unique($data['sponsors_experts_Data']);
		sort($data["sponsors_experts_Data"]);
		 
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">' . translate('LBL_SPONSORS_EXP', 'oj_Events') . '</span><ul>' . implode("",$data["sponsors_experts_Data"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["sponsors_experts_Data"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['guests_mo'])) {
      //  $data['guests_mo'] = array_unique($data['guests_mo']);
		sort($data["guests_mo"]); 
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">INDUSTRY BODIES, REGULATORS, ASSOCIATIONS AND GUESTS</span><ul>' . implode("",$data["guests_mo"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["guests_mo"]) . '</h4></td></tr>';
        }
    }
}
//-------------    MOM - Wealth and managment  pdf prep
        
if ($type == 'MOMPB') {


    if (!empty($data['bussiness_strategy_mom_wm'])) {
        // $data['bussiness_strategy_mom_wm'] = array_unique($data['bussiness_strategy_mom_wm']);
		sort($data["bussiness_strategy_mom_wm"]);	
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">' . translate('LBL_BUSINESS_STRATEGY', 'oj_Events') . '</span><ul>' . implode("",$data['bussiness_strategy_mom_wm']) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["bussiness_strategy_mom_wm"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['investment_mom_wm'])) {
       //  $data['investment_mom_wm'] = array_unique($data['investment_mom_wm']);
		sort($data["investment_mom_wm"]) ; 
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">' . translate('LBL_INVESTMENT', 'oj_Events') . '</span><ul>' . implode("",$data["investment_mom_wm"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["investment_mom_wm"]) . '</h4></td></tr>';
        }
    }
    
     if (!empty($data['strategic_partner_mom_wd'])) {
      //  $data['strategic_partner_mom_wd'] = array_unique($data['strategic_partner_mom_wd']);
		sort($data["strategic_partner_mom_wd"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38"></td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">STRATEGIC PARTNERS & EXPERTS</span><ul>' . implode("",$data["strategic_partner_mom_wd"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["strategic_partner_mom_wd"]) . '</h4></td></tr>';
        }
    
    if (!empty($data['sponsors_experts_Data'])) {
      //  $data['sponsors_experts_Data'] = array_unique($data['sponsors_experts_Data']);
		sort($data["sponsors_experts_Data"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">SPONSORS & EXPERTS</span><ul>' . implode("",$data["sponsors_experts_Data"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["sponsors_experts_Data"]) . '</h4></td></tr>';
        }
    }
    
    }
    if (!empty($data['facilitators_speakers_mom_wm'])) {
      //  $data['facilitators_speakers_mom_wm'] = array_unique($data['facilitators_speakers_mom_wm']);
		sort($data["facilitators_speakers_mom_wm"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">FACILITATORS & SPEAKERS</span><ul>' . implode("",$data["facilitators_speakers_mom_wm"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["facilitators_speakers_mom_wm"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['guest_mom_wm'])) {
      //  $data['guest_mom_wm'] = array_unique($data['guest_mom_wm']);
		sort($data["guest_mom_wm"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">INDUSTRY BODIES, REGULATORS, ASSOCIATIONS AND GUESTS</span><ul>' . implode("",$data["guest_mom_wm"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["guest_mom_wm"]) . '</h4></td></tr>';
        }
    }
}
// ------------- MOM - Advisory distributors pdf prep
if ($type == 'MOMAD') {
    if (!empty($data['bussiness_strategy_mom_ad'])) {
       //  $data['bussiness_strategy_mom_ad'] = array_unique($data['bussiness_strategy_mom_ad']);
		sort($data['bussiness_strategy_mom_ad']);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38"></td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">BUSINESS STRATEGY</span><ul>' . implode("",$data['bussiness_strategy_mom_ad']) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["bussiness_strategy_mom_ad"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['investment_mom_ad'])) {
	//	$data['investment_mom_ad'] = array_unique($data['investment_mom_ad']);
		sort($data["investment_mom_ad"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38"></td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">INVESTMENT</span><ul>' . implode("",$data["investment_mom_ad"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["investment_mom_ad"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['strategic_partner_mom_wd'])) {
	sort($data["strategic_partner_mom_wd"]);
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38"></td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">STRATEGIC PARTNERS & EXPERTS</span><ul>' . implode("",$data["strategic_partner_mom_wd"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["strategic_partner_mom_wd"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['sponsors_experts_Data'])) {
	//	$data['sponsors_experts_Data'] = array_unique($data['sponsors_experts_Data']);
		sort( $data["sponsors_experts_Data"]) ; 
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38"></td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">SPONSORS & EXPERTS</span><ul>' .implode("", $data["sponsors_experts_Data"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["sponsors_experts_Data"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['facilitators_speakers_mom_ad'])) {
	//	$data['facilitators_speakers_mom_ad'] = array_unique($data['facilitators_speakers_mom_ad']);
		sort($data["facilitators_speakers_mom_ad"]);
	
	 $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38"></td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">FACILITATORS & SPEAKERS</span><ul>' . implode("",$data["facilitators_speakers_mom_ad"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["facilitators_speakers_mom_ad"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['guest_mom_ad'])) {
	//	$data['guest_mom_ad'] = array_unique($data['guest_mom_ad']);
		sort($data["guest_mom_ad"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="25">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">INDUSTRY BODIES, REGULATORS, ASSOCIATIONS AND GUESTS</span><ul>' . implode("",$data["guest_mom_ad"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["guest_mom_ad"]) . '</h4></td></tr>';
        }
    }
}
// --------------- MOM - Winning Advisers  pdf prep
if ($type == 'MOMWA') {
    if (!empty($data['no_heading_mom_wd'])) {
       // $data['no_heading_mom_wd'] = array_unique($data['no_heading_mom_wd']);
		sort($data['no_heading_mom_wd']);
		
        $test .= '<tr><td width="38"></td><td width="175%" ><ul style="line-height:30px">' . implode("",$data['no_heading_mom_wd']) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["no_heading_mom_wd"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['strategic_partner_mom_wd'])) {
	//	 $data['strategic_partner_mom_wd'] = array_unique($data['strategic_partner_mom_wd']);
		sort($data["strategic_partner_mom_wd"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38"></td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">STRATEGIC PARTNER</span><ul style="margin:0 0 0 0;">' . implode("",$data["strategic_partner_mom_wd"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["strategic_partner_mom_wd"]) . '</h4></td></tr>';
        }
    }
    
    if (!empty($data['sponsors_experts_Data'])) {
	//	 $data['sponsors_experts_Data'] = array_unique($data['sponsors_experts_Data']);
		sort($data["sponsors_experts_Data"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38"></td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">SPONSORS & EXPERTS</span><ul style="margin:0 0 0 0;">' . implode("",$data["sponsors_experts_Data"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["sponsors_experts_Data"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['facilitators_speakers_mom_wd'])) {
       //  $data['facilitators_speakers_mom_wd'] = array_unique($data['facilitators_speakers_mom_wd']);
		sort($data["facilitators_speakers_mom_wd"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">FACILITATORS & SPEAKERS</span><ul>' . implode("",$data["facilitators_speakers_mom_wd"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["facilitators_speakers_mom_wd"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['guest_mom_wd'])) {
      //  $data['guest_mom_wd'] = array_unique($data['guest_mom_wd']);
		sort($data["guest_mom_wd"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">INDUSTRY BODIES, REGULATORS, ASSOCIATIONS AND GUESTS</span><ul>' . implode("",$data["guest_mom_wd"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["guest_mom_wd"]) . '</h4></td></tr>';
        }
    }
}
//---------------- MOM - Bank & Brand Distribution of Retail Financial Services  pdf prep
if ($type == 'MOMR') {

    if (!empty($data['banks_buildings_mom_bb'])) {
     //   $data['banks_buildings_mom_bb'] = array_unique($data['banks_buildings_mom_bb']);
		sort($data["banks_buildings_mom_bb"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">' . translate('LBL_BANKS_BUILDINGS', 'oj_Events') . '</span><ul>' . implode("",$data["banks_buildings_mom_bb"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["banks_buildings_mom_bb"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['brands_mom_bb'])) {
      //   $data['brands_mom_bb'] = array_unique($data['brands_mom_bb']);
		sort($data['brands_mom_bb']);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">' . translate('LBL_BRANDS', 'oj_Events') . '</span><ul>' . implode("",$data['brands_mom_bb']) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["brands_mom_bb"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['strategic_partner_mom_wd'])) {
	sort($data["strategic_partner_mom_wd"]);
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38"></td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">STRATEGIC PARTNERS & EXPERTS</span><ul>' . implode("",$data["strategic_partner_mom_wd"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["strategic_partner_mom_wd"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['sponsors_experts_Data'])) {
     //   $data['sponsors_experts_Data'] = array_unique($data['sponsors_experts_Data']);
		sort($data["sponsors_experts_Data"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">SPONSORS & EXPERTS</span><ul>' . implode("",$data["sponsors_experts_Data"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["sponsors_experts_Data"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['facilitators_speakers_mom_bb'])) {
     //   $data['facilitators_speakers_mom_bb'] = array_unique($data['facilitators_speakers_mom_bb']);
		sort($data["facilitators_speakers_mom_bb"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">FACILITATORS & SPEAKERS</span><ul>' . implode("",$data["facilitators_speakers_mom_bb"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["facilitators_speakers_mom_bb"]) . '</h4></td></tr>';
        }
    }
    if (!empty($data['guest_mom_bb'])) {
    //    $data['guest_mom_bb'] = array_unique($data['guest_mom_bb']);
		sort($data["guest_mom_bb"]);
		
        $test .= '<tr><td colspan="2" style="height:10px"></td></tr>';
        $test .= '<tr><td width="38">  </td><td width="175%"><span style="font-weight:bold;font-size:12; color:#b99d51;height:10px" width="175%">INDUSTRY BODIES, REGULATORS, ASSOCIATIONS AND GUESTS</span><ul>' . implode("",$data["guest_mom_bb"]) . '</ul></td></tr>';
        if ($_REQUEST['type'] == '3') {
            $test .= '<tr><td width="38"> </td><td style="color:#b99d51"  width="175%"><h4>Total :' . sizeof($data["guest_mom_bb"]) . '</h4></td></tr>';
        }
    }
}

$test .= '</table>';

$pdf->setCellHeightRatio(2);
$pdf->writeHTML($test, true, false, false, false, '');

$ucfirst_event_name = ucwords($data["event_name"]);
$event_name_trimmed = $input = preg_replace("/[^a-zA-Z]+/", "", $ucfirst_event_name);
$pdf_file_name = 'ParticipantReportFor_' . $event_name_trimmed . '.pdf';

//clear buffer
ob_clean();

//Close and output PDF document
$pdf->Output($pdf_file_name, 'D');
// $pdf->Output($pdf_file_name, 'I');
?>