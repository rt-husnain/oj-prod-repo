({
    extendsFrom: 'RecordView',
    initialize: function (options) {
        this._super("initialize", [options]);
        this.context.on('button:send_invitation:click', this.send_invitation, this);
        this.context.on('button:send_invitations:click', this.send_invitations, this);
        this.context.on('button:send_email_options:click', this.send_email_options, this);
        this.model.addValidationTask('event_id_c', _.bind(this._doValidateEventID, this));
    },

    send_email_options: function () {
        if (this.model.get('sign_off_invitations_emailing')) {
            var filterOptions = new app.utils.FilterOptions()
                .config({
                    'initial_filter': 'ev_stat_filter',
                    'initial_filter_label': 'LBL_EV_STAT_FILTER',
                    'filter_populate': {
                        'oj_events_oj_attendance_1oj_events_ida':
                            [
                                this.model.id
                            ],
                        'count_email_sent_c':''
                    },
                })
                .format();
            this.context["filterOptions"] = filterOptions;
            app.drawer.open({
                layout: "event_status",
                context: {
                    filterOptions: filterOptions,
                    module: 'oj_attendance',
                    parent: this.context
                }
            });
        } else {
            app.alert.show('sign_off_invitations_emailing_alert', {
                level: 'error',
                messages: 'Unable to process as "Sign Off Invitations Emailing" is Off. Please contact your administrator.',
                autoClose: true,
                autoCloseDelay: 4000
            });
        }
    },
    send_invitations: function () {

        console.log('Function');
        var self = this;

        var url = app.api.buildURL('oj_Events/fetch_Contacts?event_id=' + self.model.id);
        app.api.call('GET', url, null, {
            success: _.bind(function (data) {
                console.log(data);
                var index = 0;
                this.clientData = data;
                if (data != "") {
                } else {
                    app.alert.show('message-id', {
                        level: 'info',
                        messages: 'No clients assigned to you',
                        autoClose: true
                    });
                }
            }, this)
        });

    },


    _doValidateEventID: function (fields, errors, callback) {
        var self = this;
        if (self.model.get('app_sync_c')) {
            if (!_.isEmpty(this.model.get('event_id_c'))) {
                var url = app.api.buildURL('oj_Events/validateEventID?event_id=' + self.model.get('event_id_c'));
                app.api.call('GET', url, null, {
                    success: function (response) {
                        if (!response.success) {
                            errors['event_id_c'] = 'Invalid CrowdCompass Event ID.';
                            errors['event_id_c'].required = true;
                        }
                        callback(null, fields, errors);
                    }
                });
            } else {
                errors['event_id_c'] = errors['event_id_c'] || {};
                errors['event_id_c'].required = true;
                callback(null, fields, errors);
            }
        } else {
            callback(null, fields, errors);
        }
    },
    send_invitation: function () {
        app.drawer.open({
            layout: 'sent_invite',
            context: {
                module: 'Contacts',
            }
        });
    },

    create_sessions: function () {
        var self = this;
        app.alert.show('saving_sessions', {level: 'process', title: 'Creating sessions'});
        var url = 'oj_Events/create_sessions';
        var params = {
            record_id: self.model.id,
            is_sessions_created_c: self.model.get('is_sessions_created_c'),
        };
        app.api.call('create', app.api.buildURL(url), params, {
            success: function (data) {

                app.alert.dismiss('saving_sessions');
                app.alert.show('sessions_call', {level: data.level, messages: data.message, autoClose: true, autoCloseDelay: 4000});

                if (data.success) {
                    var linkName = 'oj_sessions_oj_events';
                    var subpanelSessionsCollection = self.model.getRelatedCollection(linkName);
                    subpanelSessionsCollection.fetch({relate: true});
                    var create_sessions_button = self.getField("create_sessions_button", self.model);
                    create_sessions_button.setDisabled();
                }
            }
        });
    },
    openAttendeeScheduleLayout: function () {
        var self = this;
        var event_id = this.model.id || this.model.get('id');
        var url = 'oj_Events/getRelatedSessions?event_id=' + event_id;
        app.api.call('read', app.api.buildURL(url), null, {
            success: function (data) {
                if (data.success) {
                    if (data.response.sessions.length > 0) {
                        app.drawer.open({
                            layout: 'attendee_schedule',
                            context: {
                                module: self.module || self.model.module,
                                model: self.model,
                                sessions_options: data.response.sessions,
                            }
                        });
                    }
                } else {
                    app.alert.show('no_selective_session', {level: 'error', messages: 'There is no selective session with this Event.', autoClose: true, autoCloseDelay: 4000});
                }
            }
        });
    },
    generatePriorityForm: function () {
        var self = this;
        app.alert.dismissAll();
        if (_.isEqual(app.user.get('type'), 'admin')) {
            app.alert.show('generatePriorityForm', {level: 'process', title: 'Generating Selections Form'});
            var url = 'oj_Events/generatePriorityForm';
            var params = {
                event_id: self.model.id || self.model.get('id'),
            };
            app.api.call('create', app.api.buildURL(url), params, {
                success: function (data) {
                    app.alert.dismiss('generatePriorityForm');
                    app.alert.show('generatePriorityForm_completion', {level: data.level, messages: data.message, autoClose: true, autoCloseDelay: 4000});
                }
            });
        } else {
            app.alert.show('sign_off_selection_alert1', {level: 'error', messages: 'Unable to process. Please contact with your administrator.', autoClose: true, autoCloseDelay: 4000});
        }
    },
    sendPriorityForm: function () {
        var self = this;
        app.alert.dismissAll();
        if (self.model.get('sign_off_selection_form_c') && _.isEqual(app.user.get('type'), 'admin')) {
            app.alert.show('sendPriorityForm', {level: 'process', title: 'Sending Selections Form'});
            var url = 'oj_Events/sendPriorityForm';
            var params = {
                event_id: self.model.id || self.model.get('id'),
            };
            app.api.call('create', app.api.buildURL(url), params, {
                success: function (data) {
                    app.alert.dismiss('sendPriorityForm');
                    app.alert.show('sendPriorityForm_completion', {level: data.level, messages: data.message, autoClose: true, autoCloseDelay: 4000});
                }
            });
        } else {
            app.alert.show('sign_off_selection_alert2', {level: 'error', messages: 'Unable to process as "Sign Off Selections" is Off. Please contact with your administrator.', autoClose: true, autoCloseDelay: 4000});
        }
    },
    sendPriorityForm_SE: function () {
        var self = this;
        app.alert.dismissAll();
        if (self.model.get('sign_off_selection_form_c') && _.isEqual(app.user.get('type'), 'admin')) {
            app.alert.show('sendPriorityForm', {level: 'process', title: 'Sending Selections Form'});
            var url = 'oj_Events/sendPriorityForm';
            var params = {
                event_id: self.model.id || self.model.get('id'),
                priority_form_type: "se",
            };
            app.api.call('create', app.api.buildURL(url), params, {
                success: function (data) {
                    app.alert.dismiss('sendPriorityForm');
                    app.alert.show('sendPriorityForm_completion', {level: data.level, messages: data.message, autoClose: true, autoCloseDelay: 4000});
                }
            });
        } else {
            app.alert.show('sign_off_selection_alert2', {level: 'error', messages: 'Unable to process as "Sign Off Selections" is Off. Please contact with your administrator.', autoClose: true, autoCloseDelay: 4000});
        }
    },
    syncEverybodySchedules: function () {
        var self = this;
        app.alert.dismissAll();
        var error_msg = '';
        var and = '';
        if(!self.model.get('app_sync_c')) {
            error_msg += 'App sync is off';
            and = ' and ';
        }
        if(_.isEmpty(self.model.get('event_id_c'))) {
            error_msg += and + 'event id is empty'
        }
        if (self.model.get('app_sync_c') && !_.isEmpty(self.model.get('event_id_c')) && _.isEqual(app.user.get('type'), 'admin')) {
            app.alert.show('sync_everbody_schedules_msg', {level: 'process', title: 'Syncing everybody schedules'});
            var url = 'oj_Events/syncEverybodySchedules';
            var params = {
                event_id: self.model.id || self.model.get('id'),
                crowdcompass_id: self.model.get('event_id_c'),
            };
            app.api.call('create', app.api.buildURL(url), params, {
                success: function (data) {
                    app.alert.dismiss('sync_everbody_schedules_msg');
                    app.alert.show('sync_everbody_schedules_completion', {level: data.level, messages: data.message, autoClose: true, autoCloseDelay: 4000});
                }
            });
        } else {
            app.alert.show('ap_sync_off_error', {level: 'error', messages: 'Unable to process as "' + error_msg +'" Please contact with your administrator.', autoClose: true, autoCloseDelay: 3000});
        }
    },
    bindDataChange: function () {
        this._super('bindDataChange');
        this.context.on('button:create_sessions_button:click', this.create_sessions, this);
        this.context.on('button:attendee_schedule_button:click', this.openAttendeeScheduleLayout, this);
        this.context.on('button:priority_form_generation_button:click', this.generatePriorityForm, this);
        this.context.on('button:send_priority_form_button:click', this.sendPriorityForm, this);
        this.context.on('button:send_priority_form_se_button:click', this.sendPriorityForm_SE, this);
        this.context.on('button:sync_everybody_schedules:click', this.syncEverybodySchedules, this);
        this.context.on('button:generate_list_pdf_1:click', this.generateParticipantListReport, this);
        this.context.on('button:generate_list_pdf_2:click', this.generateParticipantListReport2, this);
        this.context.on('button:generate_list_pdf_3:click', this.generateParticipantListReport3, this);
        this.context.on('button:generate_list_pdf_bmoc:click', this.generateParticipantListReportBMOC, this);
        this.context.on('button:generate_list_pdf_bmoc_2:click', this.generateParticipantListReportBMOC_2, this);
        this.context.on('button:send_event_invitations:click', this.send_event_invitations, this);

    },
    _dispose: function () {
        this._super('_dispose');
        if (this.model) {
            this.context.off('button:create_sessions_button:click', this.create_sessions, this);
            this.context.off('button:attendee_schedule_button:click', this.openAttendeeScheduleLayout, this);
            this.context.off('button:priority_form_generation_button:click', this.generatePriorityForm, this);
            this.context.off('button:send_priority_form_button:click', this.sendPriorityForm, this);
            this.context.off('button:send_priority_form_se_button:click', this.sendPriorityForm_SE, this);
            this.context.off('button:sync_everybody_schedules:click', this.syncEverybodySchedules, this);
            this.context.off('button:generate_list_pdf_1:click', this.generateParticipantListReport, this);
            this.context.off('button:generate_list_pdf_2:click', this.generateParticipantListReport2, this);
            this.context.off('button:generate_list_pdf_3:click', this.generateParticipantListReport3, this);
            this.context.off('button:generate_list_pdf_bmoc:click', this.generateParticipantListReportBMOC, this);
            this.context.off('button:generate_list_pdf_bmoc_2:click', this.generateParticipantListReportBMOC_2, this);
            this.context.off('button:send_event_invitations:click', this.send_event_invitations, this);
        }
    },
    generateParticipantListReport: function () {

        var urlParams = $.param({
            'action': 'participantlistpdf',
            'module': this.module,
            'record': this.model.id,
            'type' : '1'
            //'event_group_c':'PE'
            //'sugarpdf': 'participantlist'
        });
        var url = '?' + urlParams;
        app.bwc.login(null, _.bind(function () {
            this._triggerParticipantListReportDownload(url);
        }, this));

        app.alert.show('address-ok', {
            level: 'success 1',
            messages: 'downloading',
            autoClose: true
        });

    },
    generateParticipantListReport2: function () {

        var urlParams = $.param({
            'action': 'participantlistpdf',
            'module': this.module,
            'record': this.model.id,
            'type' : '2'
            //'event_group_c':'MO'
            //'sugarpdf': 'participantlist'
        });
        var url = '?' + urlParams;
        app.bwc.login(null, _.bind(function () {
            this._triggerParticipantListReportDownload(url);
        }, this));

        app.alert.show('address-ok', {
            level: 'success 2',
            messages: 'downloading',
            autoClose: true
        });

    },
    generateParticipantListReport3: function () {

        var urlParams = $.param({
            'action': 'participantlistpdf',
            'module': this.module,
            'record': this.model.id,
            'type' : '3'
            //'event_group_c':'MOMPB'
            //'sugarpdf': 'participantlist'
        });
        var url = '?' + urlParams;
        app.bwc.login(null, _.bind(function () {
            this._triggerParticipantListReportDownload(url);
        }, this));

        app.alert.show('address-ok', {
            level: 'success 3',
            messages: 'downloading',
            autoClose: true
        });

    },
    generateParticipantListReportBMOC: function () {

        var urlParams = $.param({
            'action': 'participantlistpdfbmoc',
            'module': this.module,
            'record': this.model.id,
            'type' : '1'
        });
        var url = '?' + urlParams;
        app.bwc.login(null, _.bind(function () {
            this._triggerParticipantListReportDownload(url);
        }, this));

        app.alert.show('address-ok', {
            level: 'success 1',
            messages: 'downloading',
            autoClose: true
        });

    },
    generateParticipantListReportBMOC_2: function () {

        var urlParams = $.param({
            'action': 'participantlistpdfbmoc',
            'module': this.module,
            'record': this.model.id,
            'type' : '2'
        });
        var url = '?' + urlParams;
        app.bwc.login(null, _.bind(function () {
            this._triggerParticipantListReportDownload(url);
        }, this));

        app.alert.show('address-ok', {
            level: 'success 1',
            messages: 'downloading',
            autoClose: true
        });

    },
    send_event_invitations: function () {
        var self = this;
        if (this.model.get('event_invitation_status_c')) {
            app.alert.show('SendEventInvitations', {level: "info", messages: "Event Invitations are already sent.", autoClose: true, autoCloseDelay: 4000});
            return false;
        }
        if (this.model.get('event_invitation_template_c') == "") {
            app.alert.show('SendEventInvitations', {level: "info", messages: "Please select any template for Event Invitation.", autoClose: true, autoCloseDelay: 4000});
            return false;
        }

        var url = 'oj_Events/get_queue_count';
        var params = {
            record_id: this.model.id,
        };

        app.api.call('create', app.api.buildURL(url), params, {
            success: function (data) {

                if(data.numrows > 0)
                {
                    app.alert.show('message-id', {
                        level: 'confirmation',
                        messages: data.message,
                        autoClose: false,
                        onConfirm: function () {
                            app.alert.show('SendingEventInvitations', {level: 'process', title: 'Sending invitations'});
                            var url = 'oj_Events/send_event_invitations';
                            var params = {
                                record_id: self.model.id,
                            };
                            app.api.call('create', app.api.buildURL(url), params, {
                                success: function (data) {

                                    app.alert.dismiss('SendingEventInvitations');
                                    app.alert.show('EventInvitationsResponse', {level: data.level, messages: data.message, autoClose: true, autoCloseDelay: 4000});

                                    if (data.success) {
                                        if (!_.isUndefined(self)) {
                                            self.model.fetch();
                                        }
                                    }
                                }
                            });
                        }
                    });
                }
                else
                {
                    app.alert.show('SendEventInvitations', {level: "info", messages: data.message, autoClose: true, autoCloseDelay: 4000});
                }
            }
        });
    },

    _triggerParticipantListReportDownload: function (url) {
        app.api.fileDownload(url, {
            error: function (data) {
                app.error.handleHttpError(data, {});
            }
        }, {
            iframe: this.$el
        });
    },
    handleSave: function () {
        if (!this.model.get('apply_concession')) {
            this.model.set('concession_description', '');
            this.model.set('discount_help', '');
        }
        this._super('handleSave');
    },
    _render: function () {
        if (app.user.get('type') != 'admin') {
            _.each(this.options.meta.buttons, function (buttonMeta) {
                if (buttonMeta.buttons) {
                    buttonMeta.buttons = _.reject(buttonMeta.buttons, function (btn) {
                        return _.contains(["priority_form_generation_button", "send_priority_form_button","send_priority_form_se_button", "create_sessions_button", "sync_everybody_schedules"], btn.name);
                    });
                }
            }, this);

        }
        this._super('_render');
    }
})
