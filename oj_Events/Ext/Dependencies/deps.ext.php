<?php
// WARNING: The contents of this file are auto-generated.
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Dependencies/create_sessions_button_readonly.php


$dependencies['oj_Events']['create_sessions_button_readonly'] = array(
    'hooks' => array("edit"),
    'trigger' => 'true',
    'triggerFields' => array('is_sessions_created_c'),
    'onload' => true,
    'actions' => array(
        array(
            'name' => 'ReadOnly',
            'params' => array(
                'target' => 'create_sessions_button',
                'label' => 'LBL_CREATE_SESSIONS',
                'value' => 'and(equal($is_sessions_created_c, "1"), greaterThan(count($oj_sessions_oj_events), 0))'
            )
        ),
    ),
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Dependencies/sign_off_selection_form_visibility.php


$dependencies['oj_Events']['sign_off_selection_form_visibility'] = array(
    'hooks' => array("all"),
    'trigger' => 'true',
    'triggerFields' => array('id'),
    'onload' => true,
    'actions' => array(
        array(
            'name' => 'SetVisibility',
            'params' => array(
                'target' => 'sign_off_selection_form_c',
                'value' => 'isAdmin()'
            )
        ),
    ),
);

?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/Dependencies/app_sync_visbility.php


$dependencies['oj_Events']['app_sync_selection_form_visibility'] = array(
    'hooks' => array("all"),
    'trigger' => 'true',
    'triggerFields' => array('id'),
    'onload' => true,
    'actions' => array(
        array(
            'name' => 'SetVisibility',
            'params' => array(
                'target' => 'app_sync_c',
                'value' => 'isAdmin()'
            )
        ),
    ),
);

?>
