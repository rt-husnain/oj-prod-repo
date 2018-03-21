<?php

require_once('include/api/SugarApi.php');
require_once('include/SugarQuery/SugarQuery.php');

class CreateSessionsApi extends SugarApi {

    /**
     * @function registerApiRest
     * @description registering the API calls for Stay in touch process
     * @return type
     */
    public function registerApiRest() {
        return array(
            'createSessions' => array(
                'reqType' => 'POST',
                'path' => array('oj_Events', 'create_sessions'),
                'pathVars' => array('', ''),
                'method' => 'createSessions',
                'shortHelp' => 'Send INvitations',
                'longHelp' => '',
            ),
        );
    }

    /**
     * create sessions for the event from default sessions list
     *
     * @param $api ServiceBase The API class of the request
     * @param $args array The arguments array passed in from the API
     * @return array
     */
    public function createSessions($api, $args) {
        $this->requireArgs($args, array('record_id'));
        if (!empty($args['record_id'])) {
            $event_bean = BeanFactory::getBean("oj_Events", $args['record_id']);
            
            if ($event_bean->is_sessions_created_c == "1") {
                return array('success' => false, 'level' => 'info', 'message' => 'Sessions are already created.');
            } else {
                
                $selectQuery = "select id_c from oj_events_cstm where id_c = '{$args['record_id']}'";
                $selectQueryResult = $GLOBALS['db']->query($selectQuery, true);
                $selectQueryRow = $GLOBALS['db']->fetchByAssoc($selectQueryResult);

                if (!empty($selectQueryRow['id_c'])) {
                    $query = "UPDATE oj_events_cstm SET is_sessions_created_c = 1 WHERE id_c = '{$args['record_id']}'";
                } else {
                    $query = "INSERT INTO oj_events_cstm(id_c,is_sessions_created_c) VALUES ('{$args['record_id']}','1')";
                }
                $GLOBALS['db']->query($query, true);
            }            
            
            $fields = array('id', 'name', 'startdate_time', 'enddate_time', 'description', 'title', 'session_type');
            $query = new SugarQuery();
            $query->select($fields);
            // Only master sessions to add
            $query->from(BeanFactory::getBean('oj_DefaultSessions'), array('team_security' => false));
            $query->joinTable('oj_defaultsessions_oj_defaultsessions_1_c', array('alias' => 'def1', 'joinType' => 'left', 'linkingTable' => true))
                    ->on()
                    ->equalsField('def1.oj_defaultsessions_oj_defaultsessions_1oj_defaultsessions_idb', 'oj_defaultsessions.id')
                    ->equals('def1.deleted', 0);
            $query->where()->isNull('def1.oj_defaultsessions_oj_defaultsessions_1oj_defaultsessions_idb');
            $results = $query->execute();
            if (empty($results)) {
                return array('success' => false, 'level' => 'info', 'message' => 'There is no Default Sessions record.');
            }
            
            foreach ($results as $result) {
                $parent_sessionBean = $this->saveSessionBean($result, $event_bean,'01');
                if ($event_bean->load_relationship('oj_sessions_oj_events')) {
                    $event_bean->oj_sessions_oj_events->add($parent_sessionBean->id);
                }
                // Adding sub sessions to the session
                $related_default_sessions = $this->getRelatedDefaultSessions($result['id']);
                if (!empty($related_default_sessions)) {
                    foreach ($related_default_sessions as $related_default_session) {
                        $child_sessionBean = $this->saveSessionBean($related_default_session, $event_bean,'02');
                        if ($parent_sessionBean->load_relationship('oj_sessions_oj_sessions_1')) {
                            $parent_sessionBean->oj_sessions_oj_sessions_1->add($child_sessionBean->id);
                        }
                        if ($event_bean->load_relationship('oj_sessions_oj_events')) {
                            $event_bean->oj_sessions_oj_events->add($child_sessionBean->id);
                        }
                    }
                }
            }
            
            return array('success' => true, 'level' => 'success', 'message' => 'Sessions are created.');
            
        }
        return array('success' => false, 'level' => 'error', 'message' => "Couldn't Record.");
    }

    /**
     * Gets the values in array and a Save a session bean
     * 
     * @param array $result
     * @param Objecy $event_bean
     * @return Object
     */
    private function saveSessionBean($result, $event_bean, $master_slave_session_value) {
        $sessionBean = BeanFactory::newBean('oj_Sessions');
        if (!empty($result['startdate_time']) && !empty($event_bean->event_start)) {
            // session start = eventDate + defaultSession Starttime
            $sessionBean->start_datetime = $GLOBALS['timedate']->merge_date_time($GLOBALS['timedate']->to_db_date($GLOBALS['timedate']->getDatePart($event_bean->event_start), false), $GLOBALS['timedate']->getTimePart($result['startdate_time']));
            $sessionBean->start_datetime = $GLOBALS['timedate']->fromString($sessionBean->start_datetime);
            $sessionBean->start_datetime = $GLOBALS['timedate']->asDb($sessionBean->start_datetime, false);
            // session end = eventDate + defaultSession Endtime
            $sessionBean->end_datetime = $GLOBALS['timedate']->merge_date_time($GLOBALS['timedate']->to_db_date($GLOBALS['timedate']->getDatePart($event_bean->event_start), false), $GLOBALS['timedate']->getTimePart($result['enddate_time']));
            $sessionBean->end_datetime = $GLOBALS['timedate']->fromString($sessionBean->end_datetime);
            $sessionBean->end_datetime = $GLOBALS['timedate']->asDb($sessionBean->end_datetime, false);
        }
        // session name = Event short code + Session title and it is coded in name field formula
        $sessionBean->description = $result['description'];
        $sessionBean->title = $result['title'];
        $sessionBean->session_type = $result['session_type'];
        $sessionBean->session_order = 1;
        $sessionBean->oj_sessions_oj_eventsoj_events_ida = $event_bean->id;
        $sessionBean->master_slave_session_type = $master_slave_session_value;
        $sessionBean->save();
        return $sessionBean;
    }

    /**
     * Gets the related default sessions against the given id
     * 
     * @param string $id
     * @return array
     */
    private function getRelatedDefaultSessions($id) {
        $query = new SugarQuery();
        $query->from(BeanFactory::getBean('oj_DefaultSessions'), array('team_security' => false));

        $query->joinTable('oj_defaultsessions_oj_defaultsessions_1_c', array('alias' => 'def1', 'joinType' => 'INNER', 'linkingTable' => true))
                ->on()
                ->equalsField('def1.oj_defaultsessions_oj_defaultsessions_1oj_defaultsessions_ida', 'oj_defaultsessions.id')
                ->equals('def1.deleted', 0);
        $query->joinTable('oj_defaultsessions', array('alias' => 'def2', 'joinType' => 'left', 'linkingTable' => true))
                ->on()
                ->equalsField('def1.oj_defaultsessions_oj_defaultsessions_1oj_defaultsessions_idb', 'def2.id')
                ->equals('def2.deleted', 0);
        $query->where()->equals("oj_defaultsessions.id", "{$id}");
        $fields = array('def2.id', 'def2.name', 'def2.startdate_time', 'def2.enddate_time', 'def2.description', 'def2.title', 'def2.session_type');
        $query->select($fields);
        return $query->execute();
    }

}
