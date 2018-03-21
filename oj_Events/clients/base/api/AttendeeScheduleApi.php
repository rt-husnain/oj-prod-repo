<?php

require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');

class AttendeeScheduleApi extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API calls for Stay in touch process
     * @return type
     */
    public function registerApiRest() {
        return array(
            'saveAttendeeSchedules' => array(
                'reqType' => 'POST',
                'path' => array('oj_Events', 'saveAttendeeSchedules'),
                'pathVars' => array('', ''),
                'method' => 'saveAttendeeSchedules',
                'shortHelp' => 'Save the modified attendee schedules',
                'longHelp' => '',
            ),
            'saveDeniedAll' => array(
                'reqType' => 'POST',
                'path' => array('oj_Events', 'saveDeniedAll'),
                'pathVars' => array('', ''),
                'method' => 'saveDeniedAll',
                'shortHelp' => 'Save the contacts to denied all for all related sessions of parent session',
                'longHelp' => '',
            ),
            'getAttendeeSchedules' => array(
                'reqType' => 'GET',
                'path' => array('oj_Events', 'getAttendeeSchedules'),
                'pathVars' => array('', ''),
                'method' => 'getAttendeeSchedules',
                'shortHelp' => 'Get contacts',
                'longHelp' => '',
            ),
            'getRelatedSessions' => array(
                'reqType' => 'GET',
                'path' => array('oj_Events', 'getRelatedSessions'),
                'pathVars' => array('', ''),
                'method' => 'getRelatedSessions',
                'shortHelp' => 'Get related Sessions',
                'longHelp' => '',
            ),
            'syncEverybodySchedules' => array(
                'reqType' => 'POST',
                'path' => array('oj_Events', 'syncEverybodySchedules'),
                'pathVars' => array('', ''),
                'method' => 'syncEverybodySchedules',
                'shortHelp' => 'Sync everybody schedules',
                'longHelp' => '',
            ),
        );
    }

    public function getAttendeeSchedules($api, $args) {
        $this->requireArgs($args, array('event_id', 'session_id', 'group'));
        $event_id = $args['event_id'];
        $session_id = $args['session_id'];
        $group = $args['group'];
        $groupValues = '';
        if ($group == 01) {
            // Group A = Delegate
            $groupValues = array('Delegate');
        } else if ($group == 02) {
            // Group B = Sponsor,Speaker,Facilitator,Expert,Guest,Staff
            $groupValues = array('Sponsor',
                'Speaker',
                'Facilitator',
                'Expert',
                'Guest',
                'Staff',
            );
        }
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
        $query->joinTable('accounts_contacts', array('alias' => 'acc_cts', 'joinType' => 'LEFT', 'linkingTable' => true))
                ->on()
                ->equalsField('ct.id', 'acc_cts.contact_id')->equals('acc_cts.deleted', 0);
        $query->joinTable('accounts', array('alias' => 'acc', 'joinType' => 'LEFT', 'linkingTable' => true))
                ->on()
                ->equalsField('acc.id', 'acc_cts.account_id')->equals('acc.deleted', 0);
        $query->select(array(array('oj_events.id', 'event_id'), array('atc.id_c', 'status_id'),array('atc.participant_type_c', 'participant_type'),
            array('ct.id', 'contact_id'), 'ct.first_name', 'ct.last_name', array('acc.name', 'account_name')));
        //$query->where()->equals("oj_events_cstm.app_sync_c", 1);
        $query->where()->equals("atc.event_status_c", 'YC');
        $query->where()->in('atc.participant_type_c', $groupValues);
        $query->where()->equals("oj_events.id", "{$event_id}");
        $contacts = $query->execute();
        if (empty($contacts)) {
            return array('success' => false, 'level' => 'info', 'message' => 'There are no contacts.');
        }
        $response = $this->contactsOrderering($contacts, $event_id, $session_id);
        if (!$response) {
            return array('success' => false, 'level' => 'info', 'message' => 'There are no contacts.');
        }
        return array('success' => true, 'response' => $response);
    }

    /**
     * Prioritize form data based on priority form and 
     * Returns the ordered contacts in the form of allocated/available/denied against a session id
     * @param array $contacts
     * @param string $session_id
     * @return array
     */
    private function contactsOrderering($contacts, $event_id, $session_id) {


        $session_id_for_prioirity = $session_id;
        // get the master session id
        $query = new SugarQuery();
        $query->from(BeanFactory::getBean('oj_Sessions'), array('team_security' => false));
        $query->joinTable('oj_sessions_oj_sessions_1_c', array('alias' => 'se1', 'joinType' => 'left', 'linkingTable' => true))
                ->on()
                ->equalsField('se1.oj_sessions_oj_sessions_1oj_sessions_idb', 'oj_sessions.id')
                ->equals('se1.deleted', 0);
        $query->where()->notNull('se1.oj_sessions_oj_sessions_1oj_sessions_idb');
        $fields = array('se1.oj_sessions_oj_sessions_1oj_sessions_ida');
        $query->where()->equals("oj_sessions.session_type", "02");
        $query->where()->equals("oj_sessions.id", "{$session_id}");
        $query->select($fields);
        $master_session_id = $query->getOne();
        $id_string = '';
        $session_ids_to_ommit = '';
        if (!empty($master_session_id)) {
            $session_id_for_prioirity = $master_session_id;
            $id_string = "" . $master_session_id . "";
        }
        $slave_sessions = $this->getSlaveSessions($session_id_for_prioirity);
        if (!empty($slave_sessions)) {
            foreach ($slave_sessions as $index => $ss) {
                if ($ss['id'] == $session_id) {
                    unset($slave_sessions[$index]);
                }
            }
            $session_ids_to_ommit = !empty($slave_sessions) ? "" . implode(",", $this->array_column($slave_sessions, 'id')) . "" : '';
        } else {
            $session_ids_to_ommit = "" . $master_session_id . "";
        }
        if (!empty($session_ids_to_ommit) && !empty($id_string)) {
            $session_ids_to_ommit = $id_string . ',' . $session_ids_to_ommit;
        } else {
            $session_ids_to_ommit = !empty($session_ids_to_ommit) ? $id_string . $session_ids_to_ommit : $id_string;
        }
        
        if (!empty($session_ids_to_ommit)){
            $final_session_id_list = explode(',', $session_ids_to_ommit);
        }
        if (!empty($final_session_id_list)) {
            foreach ($contacts as $index => $contact) {
                // Check for Group A , i.e filter records its group A only
            if($contact['participant_type']== 'Delegate'){
                
                $query = new SugarQuery();
                $query->from(BeanFactory::getBean('oj_attendee_schedule'), array('team_security' => false));
                $query->select(array('id', 'schedule_status'));
                $query->where()->equals("contact_id", "{$contact['contact_id']}");
                $query->where()->equals("schedule_status", "01");
                $query->where()->equals("event_id", "{$event_id}");
                $query->where()->in('oj_session_id', $final_session_id_list);
                $schedule = $query->execute();
                if (!empty($schedule)) {
                    unset($contacts[$index]);
                }
            }
        }
        }
        // Prioritize form data based on priority form
        $event_status_ids = "'" . implode("','", $this->array_column($contacts, 'status_id')) . "'";
        $selectQuery = "Select oj_attendance_id_c as status_id,priorty from oj_priortyformdata where oj_attendance_id_c IN ({$event_status_ids}) AND oj_events_id_c = '{$event_id}' AND oj_sessions_id_c = '{$session_id_for_prioirity}' AND deleted = 0 order by priorty";
        $selectQueryResult = $GLOBALS['db']->query($selectQuery, true);
        $searchedContacts = array();
        $remainingContacts = $contacts;
        while ($selectQueryData = $GLOBALS['db']->fetchByAssoc($selectQueryResult)) {
            $sortedArray = $this->search_from_list($contacts, 'status_id', $selectQueryData['status_id']);
            $remainingContacts = array_values($this->pop_from_list($remainingContacts, 'status_id', $selectQueryData['status_id']));
            $sortedArray[0]['priority'] = $selectQueryData['priorty'];
            array_push($searchedContacts, $sortedArray[0]);
        }

        //if (empty($searchedContacts)) {
        //  return false;
        //}

        $FinalsortedContacts = array_merge($searchedContacts, $remainingContacts);
        //$FinalsortedContacts = $searchedContacts;
        // Order the contacts in the allocated/available/denied in seperated lists
        $response = array();
        $response['session_id'] = $session_id;
        $response['contacts'] = array();
        $countAttendeeSchedules = array(
            'allocated' => 0,
            'available' => 0,
            'denied' => 0,
        );
        foreach ($FinalsortedContacts as $contact) {
            $attendeeScheduleRecord = $this->findAttendeeScheduleRecord($contact['contact_id'], $session_id);
            $scheduleStatus = empty($attendeeScheduleRecord) ? '02' : $attendeeScheduleRecord['schedule_status'];
            if ($scheduleStatus == '01') {
                $countAttendeeSchedules['allocated'] ++;
            } else if ($scheduleStatus == '02') {
                $countAttendeeSchedules['available'] ++;
            } else if ($scheduleStatus == '03') {
                $countAttendeeSchedules['denied'] ++;
            }
            array_push($response['contacts'], array('id' => $contact['contact_id'],
                'name' => $contact['first_name'] . ' ' . $contact['last_name'],
                'account_name' => $contact['account_name'],
                'schedule_status' => $scheduleStatus,
                'priority' => isset($contact['priority']) ? $contact['priority'] : 'N/A',
            ));
        }
        $response['countAttendeeSchedules'] = $countAttendeeSchedules;
        return $response;
    }

    /**
     * sync the schedule changes in sugarcrm
     *
     * @param $api ServiceBase The API class of the request
     * @param $args array The arguments array passed in from the API
     * @return array
     */
    public function saveAttendeeSchedules($api, $args) {
        $this->requireArgs($args, array('contacts', 'session_id', 'event_id'));
        $contacts = $args['contacts'];
        if (!empty($contacts)) {
            if (!empty($args['session_id'])) {
                foreach ($contacts as $contact) {
                    // Create/update Schedule changes record in Sugarcrm
                    $this->updateAttendeeScheduleRecord($contact['id'], $args['session_id'], $contact['attendee_schedule_status'], $args['event_id']);
                }
                return array('success' => true, 'level' => 'success', 'message' => "Attendee Schedules are saved.");
            } else {
                return array('success' => false, 'level' => 'error', 'message' => "Couldn't found Session ID");
            }
        } else {
            return array('success' => false, 'level' => 'error', 'message' => "There is no Contact selected.");
        }
    }

    /**
     * Insert/update the attendee schedule record for contact_id and session_id
     */
    public function updateAttendeeScheduleRecord($contact_id, $session_id, $attendee_schedule_status, $event_id) {
        // Create/update Schedule changes record in Sugarcrm
        $attendeeScheduleRecord = $this->findAttendeeScheduleRecord($contact_id, $session_id);
        if (empty($attendeeScheduleRecord)) {
            $scheduleBean = BeanFactory::newBean('oj_attendee_schedule');
            $scheduleBean->name = 'AttendeeSchedule_' . $GLOBALS['timedate']->nowDb();
            $scheduleBean->contact_id = $contact_id;
            $scheduleBean->oj_session_id = $session_id;
            $scheduleBean->schedule_status = $attendee_schedule_status;
            $scheduleBean->event_id = $event_id;
            $scheduleBean->save();
        } else {
            if ($this->check_if_status_is_same($attendeeScheduleRecord['id'], $attendee_schedule_status)) {
                $everybody = false;
                $everybodyDeny = false;
                $scheduleBean = BeanFactory::getBean('oj_attendee_schedule', $attendeeScheduleRecord['id']);
                $scheduleBean->schedule_status = $attendee_schedule_status;
                $scheduleBean->event_id = $event_id;
                $query = new SugarQuery();
                $query->from(BeanFactory::getBean('oj_Sessions'), array('team_security' => false,));
                $query->select(array('session_type'));
                $query->where()->equals('id', "{$session_id}");
                $session_type = $query->getOne();
                if ($session_type == '01' && $attendee_schedule_status == '03') {
                    $scheduleBean->processed_session = 0;
                    $everybody = true;
                    $everybodyDeny = false;
                }
                // Check for db status for everybody.. Only change if it is not denied
                if($session_type == '01' && $attendeeScheduleRecord['schedule_status'] == '03') {
                    $everybodyDeny = true;
                    $everybody = true;
                }
                
                if(!$everybody || !$everybodyDeny) {
                $scheduleBean->save();
                }
            }
        }
        // if attendee schedule status is Allocated then add a relationship of this session with Event Status for
        // Allocated Sessions Subpanel and if status is available or denied then remove the relationship.
        $this->establishRelationshipOfEventStatusAndSession($contact_id, $session_id, $attendee_schedule_status, $event_id);
    }

    /**
     * if attendee schedule status is Allocated then add a relationship of this session with Event Status for
     * Allocated Sessions Subpanel and if status is available or denied then remove the relationship.
     * 
     * @param string $contact_id
     * @param string $session_id
     * @param string $event_id
     * @return array|null
     */
    private function establishRelationshipOfEventStatusAndSession($contact_id, $session_id, $attendee_schedule_status, $event_id) {
        $query = new SugarQuery();
        $query->from(BeanFactory::getBean('oj_attendance'), array('team_security' => false));
        $query->select(array('id'));
        $query->where()->equals("contacts_oj_attendance_1contacts_ida", $contact_id);
        $query->where()->equals("oj_events_oj_attendance_1oj_events_ida", $event_id);
        $query->where()->equals("deleted", 0);
        $eventStatusID = $query->getOne();
        if (!empty($eventStatusID)) {
            $eventStatusBean = BeanFactory::getBean('oj_attendance', $eventStatusID);
            if ($eventStatusBean->load_relationship('oj_attendance_oj_sessions_allocated')) {
                if ($attendee_schedule_status == '01') {
                    $eventStatusBean->oj_attendance_oj_sessions_allocated->add($session_id);
                } else {
                    $eventStatusBean->oj_attendance_oj_sessions_allocated->delete($eventStatusID, $session_id);
                }
            }
        }
    }

    /**
     * Finds the record of attendee schedule
     * @param string $contact_id
     * @param session $session_id
     * @return array|null
     */
    private function findAttendeeScheduleRecord($contact_id, $session_id) {
        $query = new SugarQuery();
        $query->from(BeanFactory::getBean('oj_attendee_schedule'), array('team_security' => false));
        $query->select(array('id', 'schedule_status'));
        $query->where()->equals("contact_id", "{$contact_id}");
        $query->where()->equals("oj_session_id", "{$session_id}");
        $schedule = $query->execute();
        if (!empty($schedule)) {
            return $schedule[0];
        }
        return array();
    }

    /**
     * Check if the attendee schedule status is not changed
     * @param string $id
     * @param session $newstatus
     * @return boolean
     */
    private function check_if_status_is_same($id, $newstatus) {
        $query = new SugarQuery();
        $query->from(BeanFactory::getBean('oj_attendee_schedule'), array('team_security' => false));
        $query->select(array('schedule_status'));
        $query->where()->equals("id", "{$id}");
        $fetched_status = $query->getOne();
        if ($fetched_status != $newstatus) {
            return true;
        }
        return false;
    }

    /**
     * Returns the sessions and its related sessions as master/slave sessions     
     * @param $api ServiceBase The API class of the request
     * @param $args array The arguments array passed in from the API
     * @return array
     */
    public function getRelatedSessions($api, $args) {
        $this->requireArgs($args, array('event_id'));
        $query = new SugarQuery();
        $query->from(BeanFactory::getBean('oj_Sessions'), array('team_security' => false));

        $query->joinTable('oj_sessions_oj_events_c', array('alias' => 'eve_se', 'joinType' => 'left', 'linkingTable' => true))
                ->on()
                ->equalsField('eve_se.oj_sessions_oj_eventsoj_sessions_idb', 'oj_sessions.id')
                ->equals('eve_se.deleted', 0);
        // Only master sessions to populate
        $query->joinTable('oj_sessions_oj_sessions_1_c', array('alias' => 'se1', 'joinType' => 'left', 'linkingTable' => true))
                ->on()
                ->equalsField('se1.oj_sessions_oj_sessions_1oj_sessions_idb', 'oj_sessions.id')
                ->equals('se1.deleted', 0);
        $query->where()->isNull('se1.oj_sessions_oj_sessions_1oj_sessions_idb');
        $fields = array('oj_sessions.id', 'oj_sessions.name');
        $query->where()->equals("eve_se.oj_sessions_oj_eventsoj_events_ida", "{$args['event_id']}");
        $query->where()->equals("oj_sessions.session_type", "02");
        $query->orderBy('start_datetime', 'ASC');
        $query->select($fields);
        $master_sessions = $query->execute();
        $response = array();
        $response['sessions'] = array();
        if (!empty($master_sessions)) {
            foreach ($master_sessions as $master_session) {
                $slave_sessions = $this->getSlaveSessions($master_session['id']);
                array_push($response['sessions'], array('id' => $master_session['id'],
                    'name' => $master_session['name'],
                    'slave_sessions' => $slave_sessions)
                );
            }
            return array('success' => true, 'response' => $response);
        }

        return array('success' => false, 'level' => 'error', 'message' => "There is no related sessions");
    }

    /**
     * Returns the child sessions of the given session id     
     * @param $parent_id string
     * @return array
     */
    private function getSlaveSessions($parent_id) {
        $query = new SugarQuery();
        $query->from(BeanFactory::getBean('oj_Sessions'), array('team_security' => false));

        $query->joinTable('oj_sessions_oj_sessions_1_c', array('alias' => 'se1', 'joinType' => 'INNER', 'linkingTable' => true))
                ->on()
                ->equalsField('se1.oj_sessions_oj_sessions_1oj_sessions_ida', 'oj_sessions.id')
                ->equals('se1.deleted', 0);
        $query->joinTable('oj_sessions', array('alias' => 'se2', 'joinType' => 'left', 'linkingTable' => true))
                ->on()
                ->equalsField('se1.oj_sessions_oj_sessions_1oj_sessions_idb', 'se2.id')
                ->equals('se2.deleted', 0);
        $query->where()->equals("oj_sessions.id", "{$parent_id}");
        $query->orderBy('start_datetime', 'ASC');
        $fields = array('se2.id', 'se2.name');
        $query->select($fields);
        return $query->execute();
    }

    /**
     * Save the denied all session and contact id
     * @param $api ServiceBase The API class of the request
     * @param $args array The arguments array passed in from the API
     * @return array
     */
    public function saveDeniedAll($api, $args) {
        $this->requireArgs($args, array('sessions', 'contact_id', 'event_id'));
        $sessions = $args['sessions'];
        foreach ($sessions as $session_id) {
            // '03' is a value for denied/drop status
            $this->updateAttendeeScheduleRecord($args['contact_id'], $session_id, '03', $args['event_id']);
        }
        return array('success' => true, 'level' => 'success', 'message' => "Contact denied for all sessions");
    }
    
    /**
     * Sync the eveybody schedules for the event id
     * @param $api ServiceBase The API class of the request
     * @param $args array The arguments array passed in from the API
     * @return array
     */
    public function syncEverybodySchedules($api, $args) {
        $this->requireArgs($args, array('event_id', 'crowdcompass_id'));        
        require_once 'custom/include/Helpers/CrowdCompass/EventSync.php';
        $eventsync = new EventSync();
        $eventsync->syncAttendeeSchedulesChanges($args['event_id'], $args['crowdcompass_id'], true);        
        return array('success' => true, 'level' => 'success', 'message' => "Sync completed");
    }
    
    /**
     * Recursively call to search the array from key and value from given array and returns the searched array
     * @param array $array
     * @param string $key
     * @param string $value
     * @return array
     */
    private function search_from_list($array, $key, $value) {
        $results = array();
        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, $this->search_from_list($subarray, $key, $value));
            }
        }
        return $results;
    }

    /**
     * Removes the array to find from key and value and returns the remaining array
     * @param array $array
     * @param string $key
     * @param string $value
     * @return array
     */
    private function pop_from_list($array, $key, $value) {
        if (is_array($array)) {
            foreach ($array as $index => $subarray)
                if ($subarray[$key] == $value) {
                    unset($array[$index]);
                }
        }
        return $array;
    }

    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    private function array_column($input = null, $columnKey = null, $indexKey = null) {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();
        if ($argc < 2) {
            $GLOBALS['log']->debug("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }
        if (!is_array($params[0])) {
            $GLOBALS['log']->debug(
                    'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING
            );
            return null;
        }
        if (!is_int($params[1]) && !is_float($params[1]) && !is_string($params[1]) && $params[1] !== null && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            $GLOBALS['log']->debug('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        if (isset($params[2]) && !is_int($params[2]) && !is_float($params[2]) && !is_string($params[2]) && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            $GLOBALS['log']->debug('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }
        $resultArray = array();
        foreach ($paramsInput as $row) {
            $key = $value = null;
            $keySet = $valueSet = false;
            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }
            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }
            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }
        }
        return $resultArray;
    }

}
