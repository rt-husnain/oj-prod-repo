<?php
// created: 2015-09-09 12:34:04
$viewdefs['oj_Events']['base']['view']['subpanel-for-accounts-accounts_oj_events_1'] = array (
  'panels' => 
  array (
    0 => 
    array (
      'name' => 'panel_header',
      'label' => 'LBL_PANEL_1',
      'fields' => 
      array (
        0 => 
        array (
          'label' => 'LBL_NAME',
          'enabled' => true,
          'default' => true,
          'name' => 'name',
          'link' => true,
        ),
		1 => 
		  array (
			'name' => 'venue',
			'label' => 'LBL_VENUE',
			'enabled' => true,
			'width' => '10%',
			'default' => true,
		  ),
		  2 => 
		  array (
			'name' => 'shortcode_c',
			'label' => 'LBL_SHORTCODE',
			'enabled' => true,
			'width' => '10%',
			'default' => true,
		  ),
        3 => 
        array (
          'name' => 'event_start',
          'label' => 'LBL_EVENT_START',
          'enabled' => true,
          'default' => true,
        ),
		4 => 
        array (
          'name' => 'event_end',
          'label' => 'LBL_EVENT_END',
          'enabled' => true,
          'default' => true,
        ),
      ),
    ),
  ),
  'orderBy' => 
  array (
    'field' => 'date_modified',
    'direction' => 'desc',
  ),
  'type' => 'subpanel-list',
);