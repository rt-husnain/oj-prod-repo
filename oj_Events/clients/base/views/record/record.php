<?php
$viewdefs['oj_Events'] = 
array (
  'base' => 
  array (
    'view' => 
    array (
      'record' => 
      array (
        'panels' => 
        array (
          0 => 
          array (
            'name' => 'panel_header',
            'label' => 'LBL_RECORD_HEADER',
            'header' => true,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'picture',
                'type' => 'avatar',
                'width' => 42,
                'height' => 42,
                'dismiss_label' => true,
                'readonly' => true,
              ),
              1 => 'name',
              2 => 
              array (
                'name' => 'favorite',
                'label' => 'LBL_FAVORITE',
                'type' => 'favorite',
                'readonly' => true,
                'dismiss_label' => true,
              ),
              3 => 
              array (
                'name' => 'follow',
                'label' => 'LBL_FOLLOW',
                'type' => 'follow',
                'readonly' => true,
                'dismiss_label' => true,
              ),
            ),
          ),
          1 => 
          array (
            'name' => 'panel_body',
            'label' => 'LBL_RECORD_BODY',
            'columns' => 2,
            'labelsOnTop' => true,
            'placeholders' => true,
            'newTab' => true,
            'panelDefault' => 'expanded',
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'event_start',
                'label' => 'LBL_EVENT_START',
              ),
              1 => 
              array (
                'name' => 'event_end',
                'label' => 'LBL_EVENT_END',
              ),
              2 => 
              array (
                'name' => 'event_full_name_c',
                'comment' => 'event full name field',
                'label' => 'LBL_EVENT_FULL_NAME',
              ),
              3 => 
              array (
                'name' => 'shortcode_c',
                'label' => 'LBL_SHORTCODE',
              ),
              4 => 
              array (
                'name' => 'venue',
                'label' => 'LBL_VENUE',
              ),
              5 => 
              array (
                'name' => 'status',
                'studio' => 'visible',
                'label' => 'LBL_STATUS',
              ),
              6 => 
              array (
                'name' => 'event_hashtag_c',
                'label' => 'LBL_EVENT_HASHTAG',
              ),
              7 => 
              array (
                'name' => 'event_group_c',
                'label' => 'LBL_EVENT_GROUP',
              ),
              8 => 
              array (
                'name' => 'event_invite_url_c',
                'label' => 'LBL_EVENT_INVITE_URL',
                'span' => 12,
              ),
              9 => 
              array (
                'name' => 'date_entered_by',
                'readonly' => true,
                'type' => 'fieldset',
                'label' => 'LBL_DATE_ENTERED',
                'fields' => 
                array (
                  0 => 
                  array (
                    'name' => 'date_entered',
                  ),
                  1 => 
                  array (
                    'type' => 'label',
                    'default_value' => 'LBL_BY',
                  ),
                  2 => 
                  array (
                    'name' => 'created_by_name',
                  ),
                ),
              ),
              10 => 
              array (
                'name' => 'date_modified_by',
                'readonly' => true,
                'type' => 'fieldset',
                'label' => 'LBL_DATE_MODIFIED',
                'fields' => 
                array (
                  0 => 
                  array (
                    'name' => 'date_modified',
                  ),
                  1 => 
                  array (
                    'type' => 'label',
                    'default_value' => 'LBL_BY',
                  ),
                  2 => 
                  array (
                    'name' => 'modified_by_name',
                  ),
                ),
              ),
              11 => 'assigned_user_name',
              12 => 
              array (
                'name' => 'team_name',
              ),
            ),
          ),
          2 => 
          array (
            'newTab' => true,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL2',
            'label' => 'LBL_RECORDVIEW_PANEL2',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'selection_form_header_c',
              ),
              1 => 
              array (
                'name' => 'sign_off_selection_form_c',
                'type' => 'switch-button',
              ),
              2 => 
              array (
                'name' => 'app_sync_c',
                'label' => 'LBL_APP_SYNC',
                'type' => 'switch-button',
                'related_fields' => 
                array (
                  0 => 'is_sessions_created_c',
                ),
              ),
              3 => 
              array (
                'name' => 'selection_priority_c',
                'label' => 'LBL_SELECTION_PRIORITY',
              ),
              4 => 
              array (
                'name' => 'event_id_c',
                'label' => 'LBL_EVENT_ID',
                'span' => 12,
              ),
              5 => 'event_template_c',
              6 => 
              array (
                'name' => 'dinner_accommodation_c',
              ),
              7 => 
              array (
                'name' => 'hotel_price_information_c',
              ),
              8 => 
              array (
              ),
            ),
          ),
          3 => 
          array (
            'newTab' => true,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL1',
            'label' => 'LBL_RECORDVIEW_PANEL1',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'event_charging_c',
                'label' => 'LBL_EVENT_CHARGING',
              ),
              1 => 
              array (
                'name' => 'vat_rate_c',
                'label' => 'LBL_VAT_RATE',
              ),
              2 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'concession_c',
                'label' => 'LBL_CONCESSION',
              ),
              3 => 
              array (
                'name' => 'eb_valid_c',
                'label' => 'LBL_EB_VALID',
              ),
              4 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'std_c',
                'label' => 'LBL_STD',
              ),
              5 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'eb_c',
                'label' => 'LBL_EB',
              ),
              6 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'std_minus_concession_c',
                'label' => 'LBL_STD_MINUS_CONCESSION',
              ),
              7 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'eb_minus_concession_c',
                'label' => 'LBL_EB_MINUS_CONCESSION',
              ),
              8 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'std_vat_c',
                'label' => 'LBL_STD_VAT',
              ),
              9 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'eb_vat_c',
                'label' => 'LBL_EB_VAT',
              ),
              10 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'std_minus_concession_vat_c',
                'label' => 'LBL_STD_MINUS_CONCESSION_VAT',
              ),
              11 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'eb_minus_concession_vat_c',
                'label' => 'LBL_EB_MINUS_CONCESSION_VAT',
              ),
              12 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'std_plus_vat_c',
                'label' => 'LBL_STD_PLUS_VAT',
              ),
              13 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'eb_plus_vat_c',
                'label' => 'LBL_EB_PLUS_VAT',
              ),
              14 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'std_minus_con_plus_vat_c',
                'label' => 'LBL_STD_MINUS_CON_PLUS_VAT',
              ),
              15 => 
              array (
                'related_fields' => 
                array (
                  0 => 'currency_id',
                  1 => 'base_rate',
                ),
                'name' => 'eb_minus_con_plus_vat_c',
                'label' => 'LBL_EB_MINUS_CON_PLUS_VAT',
              ),
              16 => 
              array (
                'name' => 'rsvp_payment_header_c',
                'studio' => 'visible',
                'label' => 'LBL_RSVP_PAYMENT_HEADER',
              ),
              17 => 
              array (
              ),
            ),
          ),
          4 => 
          array (
            'newTab' => true,
            'panelDefault' => 'expanded',
            'name' => 'LBL_RECORDVIEW_PANEL3',
            'label' => 'LBL_RECORDVIEW_PANEL3',
            'columns' => 2,
            'labelsOnTop' => 1,
            'placeholders' => 1,
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'event_invitation_status_c',
                'label' => 'LBL_EVENT_INVITATION_STATUS_C',
              ),
              1 => 
              array (
                'name' => 'event_invitation_template_c',
                'label' => 'LBL_EVENT_INVITATION_TEMPLATE_C',
              ),
             
            ),
          ),
        ),
        'templateMeta' => 
        array (
          'useTabs' => true,
        ),
        'buttons' => 
        array (
          0 => 
          array (
            'type' => 'button',
            'name' => 'cancel_button',
            'label' => 'LBL_CANCEL_BUTTON_LABEL',
            'css_class' => 'btn-invisible btn-link',
            'showOn' => 'edit',
          ),
          1 => 
          array (
            'type' => 'rowaction',
            'event' => 'button:save_button:click',
            'name' => 'save_button',
            'label' => 'LBL_SAVE_BUTTON_LABEL',
            'css_class' => 'btn btn-primary',
            'showOn' => 'edit',
            'acl_action' => 'edit',
          ),
          2 => 
          array (
            'type' => 'actiondropdown',
            'name' => 'main_dropdown',
            'primary' => true,
            'showOn' => 'view',
            'buttons' => 
            array (
              0 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:edit_button:click',
                'name' => 'edit_button',
                'label' => 'LBL_EDIT_BUTTON_LABEL',
                'acl_action' => 'edit',
              ),
              1 => 
              array (
                'type' => 'shareaction',
                'name' => 'share',
                'label' => 'LBL_RECORD_SHARE_BUTTON',
                'acl_action' => 'view',
              ),
              2 => 
              array (
                'type' => 'pdfaction',
                'name' => 'download-pdf',
                'label' => 'LBL_PDF_VIEW',
                'action' => 'download',
                'acl_action' => 'view',
              ),
              3 => 
              array (
                'type' => 'pdfaction',
                'name' => 'email-pdf',
                'label' => 'LBL_PDF_EMAIL',
                'action' => 'email',
                'acl_action' => 'view',
              ),
              4 => 
              array (
                'type' => 'divider',
              ),
              5 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:find_duplicates_button:click',
                'name' => 'find_duplicates_button',
                'label' => 'LBL_DUP_MERGE',
                'acl_action' => 'edit',
              ),
              6 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:duplicate_button:click',
                'name' => 'duplicate_button',
                'label' => 'LBL_DUPLICATE_BUTTON_LABEL',
                'acl_module' => NULL,
                'acl_action' => 'create',
              ),
              7 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:audit_button:click',
                'name' => 'audit_button',
                'label' => 'LNK_VIEW_CHANGE_LOG',
                'acl_action' => 'view',
              ),
              8 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:create_sessions_button:click',
                'name' => 'create_sessions_button',
                'label' => 'LBL_CREATE_SESSIONS',
                'acl_action' => 'view',
              ),
              9 => 
              array (
                'type' => 'divider',
              ),
              10 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:delete_button:click',
                'name' => 'delete_button',
                'label' => 'LBL_DELETE_BUTTON_LABEL',
                'acl_action' => 'delete',
              ),
              11 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:send_invitation:click',
                'name' => 'send_invitation',
                'label' => 'Add Invitees',
                'acl_action' => 'send_invitation',
              ),
              12 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:attendee_schedule_button:click',
                'name' => 'attendee_schedule_button',
                'label' => 'LBL_ATTENDEE_SCHEDULE',
                'acl_action' => 'view',
              ),
              13 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:send_event_invitations:click',
                'name' => 'send_event_invitations',
                'label' => 'LBL_SEND_EVENT_INVITATIONS',
                'acl_action' => 'view',
              ),
              14 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:priority_form_generation_button:click',
                'name' => 'priority_form_generation_button',
                'label' => 'LBL_PRIORITY_FORM_GENERATION_BUTTON',
                'acl_action' => 'view',
              ),
              15 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:send_priority_form_button:click',
                'name' => 'send_priority_form_button',
                'label' => 'LBL_SEND_PRIORITY_FORM_BUTTON',
                'acl_action' => 'view',
              ),
              16 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:send_priority_form_se_button:click',
                'name' => 'send_priority_form_se_button',
                'label' => 'LBL_SEND_PRIORITY_FORM_SE_BUTTON',
                'acl_action' => 'view',
              ),
              17 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:sync_everybody_schedules:click',
                'name' => 'sync_everybody_schedules',
                'label' => 'LBL_SYNC_EVERYBODY_SCHEDULES',
                'acl_action' => 'view',
              ),
              18 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:generate_list_pdf_1:click',
                'name' => 'generate_list_pdf_1',
                'label' => 'LBL_PDF_LIST_1',
                'acl_action' => 'view',
              ),
              19 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:generate_list_pdf_2:click',
                'name' => 'generate_list_pdf_2',
                'label' => 'LBL_PDF_LIST_2',
                'acl_action' => 'view',
              ),
              20 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:generate_list_pdf_3:click',
                'name' => 'generate_list_pdf_3',
                'label' => 'LBL_PDF_LIST_3',
                'acl_action' => 'view',
              ),
              21 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:generate_list_pdf_bmoc:click',
                'name' => 'generate_list_pdf_bmoc',
                'label' => 'LBL_PDF_LIST_BMOC',
                'acl_action' => 'view',
              ),
              22 => 
              array (
                'type' => 'rowaction',
                'event' => 'button:generate_list_pdf_bmoc_2:click',
                'name' => 'generate_list_pdf_bmoc_2',
                'label' => 'LBL_PDF_LIST_BMOC_2',
                'acl_action' => 'view',
              ),
            ),
          ),
          3 => 
          array (
            'name' => 'sidebar_toggle',
            'type' => 'sidebartoggle',
          ),
        ),
      ),
    ),
  ),
);
