<?php


$viewdefs['oj_Events']['base']['view']['view_event_invitation'] = array(

    'buttons' => array(
        array(
            'name' => 'cancel_button',
            'type' => 'button',
            'label' => 'LBL_CLOSE_BUTTON_LABEL',
            'events' => array(
                'click' => 'button:cancel_button:click',
            ),
        ),
    ),

    'panels' => array(
        array(
            'name' => 'panel_body',
            'labelsOnTop' => true,
            'placeholders' => true,
            'fields' => array(
                array(
                    'name' => 'paragraph_select_template',
                    'label' => 'Please Select the Template',
                    'type' => 'button',
                    // 'event' => 'button:template:click',
                    'css_class' => 'paragraph_select_template',

                ),
                array(
                    'name' => 'template_for_events',
                    'label' => 'LBL_TEMPLATE_FOR_EVENTS',
                    'event' => 'button:template:click',
                    'css_class' => 'select-template-email',

                ),
                array(
                    'type' => 'emailaction-paneltop',
                    'name' => 'email_compose_button',
                    'label' => 'Send emails one by one',
                    'acl_action' => 'create',
                    'set_recipient_to_parent' => true,
                    'set_related_to_parent' => true,
                    'tooltip' => 'LBL_CREATE_BUTTON_LABEL',
                    'event' => 'button:email_compose_button:click',
                    'event' => 'button:email_compose_button:click',

                ),

                array(
                    'type' => 'button',
                    'name' => 'send_to_all',
                    'label' => 'Send emails to all contacts',
                    'tooltip' => 'Send to all',
                ),


            ),

        ),
    ),

);
