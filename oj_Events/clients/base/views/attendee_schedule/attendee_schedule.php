<?php

$viewdefs['oj_Events']['base']['view']['attendee_schedule'] = array(
    'panels' => array(
        /*
         * Panel to display sessions and group field.
         * 
         */
        array(
            'name' => 'sessions_panel',
            'label' => 'LBL_ATTENDEE_SCHEDULE',
            'fields' => array(
                array(
                    'name' => 'attendee_master_sessions_name',
                    'label' => 'LBL_MASTER_SESSIONS_NAME',
                    'type' => 'enum',
                    'options' => 'attendee_schedule_sessions_list',
                ),
                array(
                    'name' => 'attendee_slave_sessions_name',
                    'label' => 'LBL_SLAVE_SESSIONS_NAME',
                    'type' => 'enum',
                    'options' => 'attendee_schedule_sessions_list',
                ),
                array(
                    'name' => 'attendee_group_name',
                    'label' => 'LBL_GROUP_NAME',
                    'type' => 'enum',
                    'options' => 'attendee_schedule_group_list',
                ),
            ),
        ),
    )
);
