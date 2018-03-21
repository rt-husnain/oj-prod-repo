({
    extendsFrom: 'RecordView',
    eventName: '',
    sessions: [],
    isMasterSessionsExists: false,
    isSlaveSessionsExists: false,
    attendeeScheduleContactsView: '',
    current_session_ids: {},
    events: {
        'click #close_attendee_schedule:not(.disabled)': 'closeAttendeeScheduleDrawer',
        'click #save_schedule_settings:not(.disabled)': 'saveSchedulerSettings'
    },
    initialize: function (options) {
        this._super('initialize', [options]);
        app.alert.dismissAll();
        this.eventName = this.context.get('model').get('name');
    },
    render: function () {
        this._super('render');
        if (!this.isMasterSessionsExists) {
            $('.record-cell[data-name=attendee_slave_sessions_name]').css('visibility', 'hidden');
        }       
        this.setSessionOptions(false);
    },
    /**
     * @function closeAttendeeScheduleDrawer
     * @returns {Boolean}
     */
    closeAttendeeScheduleDrawer: function () {
        app.drawer.close();
        this.model.set('attendee_master_sessions_name', '');
        this.model.set('attendee_slave_sessions_name', '');
        this.model.set('attendee_group_name', '');
        app.events.trigger("preview:sessionOnAttendeeschedule", false);
        this._dispose();
        return true;
    },
    /**
     * @function saveSchedulerSettings
     * @returns {Boolean}
     */
    saveSchedulerSettings: function () {
        var self = this;
        var allFields = self.getFields(self.module);
        var doValidateFields = {};
        app.alert.show('validating_data', {level: 'process', title: 'Validating fields'});
        var toBeValidatedField = ["attendee_master_sessions_name", "attendee_group_name"];
        _.each(allFields, function (field) {
            if ($.inArray(field.name, toBeValidatedField) !== -1) {
                doValidateFields[field.name] = {};
                _.extend(doValidateFields[field.name], field);
            }
        });
        self.model.doValidate(doValidateFields, _.bind(self._validationComplete, self));
    },
    /**
     * Called on model validation
     * @param {type} isValid    
     */
    _validationComplete: function (isValid) {
        app.alert.dismiss('validating_data');
        if (isValid) {
            this.saveAttendeeScheduleData();
        }
    },
    /**
     * 
     * 
     */
    updateSessionValues: function () {
        var session_id = this.model.get('attendee_master_sessions_name');
        this.model.set('attendee_slave_sessions_name', '');
        this.setSessionOptions(session_id);
        app.events.trigger('change:attendee_schedule_values', this);
        this.showSessionPreview(session_id);
    },
    updateSubSessionValues: function () {
        app.events.trigger('change:attendee_schedule_values', this);
        var session_id = this.model.get('attendee_master_sessions_name');
        if (this.isSlaveSessionsExists) {
            session_id = this.model.get('attendee_slave_sessions_name');
        }
        this.showSessionPreview(session_id);
    },
    updateGroupValues: function () {
        app.events.trigger('change:attendee_schedule_values', this);
    },
    /**
     * 
     * Send call for API to save the settings of attendee schedule
     */
    saveAttendeeScheduleData: function () {
        app.alert.dismissAll();
        app.alert.show('saving_data', {level: 'process', title: 'Saving Attendee Schedules'});
        var contactsData = [];
        $("li.contacts_li").each(function () {
            contactsData.push({id: $(this).attr('data-id'), attendee_schedule_status: $(this).parent().attr('data-status')});
        });
        var session_id = this.model.get('attendee_master_sessions_name');
        if (!_.isUndefined(this.model.get('attendee_slave_sessions_name')) && !_.isEmpty(this.model.get('attendee_slave_sessions_name'))) {
            session_id = this.model.get('attendee_slave_sessions_name');
        }
        var params = {
            'session_id': session_id,
            'contacts': contactsData,
            'event_id' :  this.model.id || this.model.get('id') || this.context.get('model').get('id'),
        };

        var url = 'oj_Events/saveAttendeeSchedules';
        app.api.call('create', app.api.buildURL(url), params, {
            success: function (data) {
                app.alert.dismissAll();
                app.alert.show('saving_suceess_msg', {level: data.level, title: data.message, autoClose: true, autoCloseDelay: 4000});
            }
        });
    },
    /**
     * Set the options of sessions in the dropdowns     
     */
    setSessionOptions: function (master_session_id) {
        var self = this;
        if (!self.isMasterSessionsExists) {
            self.sessions = self.context.get('sessions_options');
            var master_options = {};
            master_options[''] = '';
            _.each(self.sessions, function (session) {
                master_options[session.id] = session.name;
            });
            var attendee_master_sessions_name = self.getField("attendee_master_sessions_name", this.model);
            attendee_master_sessions_name.items = master_options;
            self.isMasterSessionsExists = true;
        }
        self.isSlaveSessionsExists = false;
        if (master_session_id) {
            var slave_options = {};
            slave_options[''] = '';
            self.current_session_ids = [];
            var Foundsession = _.where(self.sessions, {id: master_session_id});
            if (Foundsession.length > 0) {
                self.current_session_ids.push(master_session_id);
                var slave_sessions = Foundsession[0].slave_sessions;
                slave_options[master_session_id] = Foundsession[0].name;
                if (slave_sessions.length > 0) {
                    _.each(slave_sessions, function (slave_session) {
                        slave_options[slave_session.id] = slave_session.name;
                        self.current_session_ids.push(slave_session.id);
                    });
                    var attendee_slave_sessions_name = self.getField("attendee_slave_sessions_name", this.model);
                    attendee_slave_sessions_name.items = slave_options;
                    $('.record-cell[data-name=attendee_slave_sessions_name]').css('visibility', 'visible');
                    self.isSlaveSessionsExists = true;
                } else {
                    $('.record-cell[data-name=attendee_slave_sessions_name]').css('visibility', 'hidden');
                }
            }
        } else {
            $('.record-cell[data-name=attendee_slave_sessions_name]').css('visibility', 'hidden');
        }
    },
    showSessionPreview: function (beanID) {
        if (!_.isEmpty(beanID) && beanID) {
            var beanName = 'oj_Sessions';
            var previewCollection = new Backbone.Collection();
            var bean = SUGAR.App.data.createBean(beanName, {
                id: beanID
            });
            bean.fetch({
                success: function () {
                    previewCollection.add(bean);
                    app.events.trigger("preview:sessionOnAttendeeschedule", true);
                    app.events.trigger("preview:render", bean, previewCollection, true);
                }
            });
        } else {
            app.events.trigger('preview:close');
            app.events.trigger("preview:sessionOnAttendeeschedule", false);
        }
    },
    /**
     * 
     * Called when model changes
     */
    bindDataChange: function () {
        this._super('bindDataChange');
        if (this.model) {
            this.model.on('change:attendee_master_sessions_name', this.updateSessionValues, this);
            this.model.on('change:attendee_slave_sessions_name', this.updateSubSessionValues, this);
            this.model.on('change:attendee_group_name', this.updateGroupValues, this);
        }
    },
    /**
     * 
     * Dispose the events
     */
    _dispose: function () {
        if (this.model) {
            this.model.off('change:attendee_master_sessions_name', this.updateSessionValues, this);
            this.model.off('change:attendee_slave_sessions_name', this.updateSubSessionValues, this);
            this.model.off('change:attendee_group_name', this.updateGroupValues, this);
        }
    },
})