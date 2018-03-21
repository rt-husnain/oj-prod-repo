({
    countAttendeeSchedules: {
        'allocated': 0,
        'available': 0,
        'denied': 0,
    },
    attendee_schedule_view: '',
    events: {
        'click .denied_all': 'deniedAll',
    },
    contacts: [],
    initialize: function (options) {
        this._super('initialize', [options]);
        this.attendee_schedule_view = this.layout.getComponent('attendee_schedule');
    },
    render: function () {
        this._super('render');
    },
    /**
     *
     * Call to API to get the data of event and session and prepares the data in objects for render
     */
    prepareDataOfAttendeeSchedule: function () {
        var self = this;
        app.events.trigger('change:attendee_master_sessions_name', this);
        app.alert.dismiss('err_msg');
        self.contacts = [];
        var event_id = self.model.id || self.model.get('id');
        var session_id = self.model.get('attendee_master_sessions_name');
        var slave_session_id = self.model.get('attendee_slave_sessions_name');
        var group = self.model.get('attendee_group_name');
        if (((!_.isUndefined(session_id) && !_.isEmpty(session_id) && !self.attendee_schedule_view.isSlaveSessionsExists) || (!_.isUndefined(slave_session_id) && !_.isEmpty(slave_session_id)))
                && (!_.isUndefined(group) && !_.isEmpty(group))) {
            app.alert.show('loading_data', {level: 'process', title: 'Loading'});
            if (!_.isUndefined(this.model.get('attendee_slave_sessions_name')) && !_.isEmpty(this.model.get('attendee_slave_sessions_name'))) {
                session_id = this.model.get('attendee_slave_sessions_name');
            }
            var url = 'oj_Events/getAttendeeSchedules?event_id=' + event_id + '&session_id=' + session_id + '&group=' + group;
            app.api.call('read', app.api.buildURL(url), null, {
                success: function (data) {
                    app.alert.dismiss('loading_data');
                    if (data.success) {
                        _.each(data.response.contacts, function (contact) {
                            var newContact = {
                                id: contact.id,
                                name: contact.name,
                                account_name: contact.account_name,
                                schedule_status: contact.schedule_status,
                                priority: contact.priority,
                            }
                            self.contacts.push(newContact);
                            self.countAttendeeSchedules = data.response.countAttendeeSchedules;
                        });
                    } else {
                        app.alert.show('err_msg', {level: data.level, title: data.message, autoClose: true, autoCloseDelay: 4000});
                    }
                    self.render();
                }
            });
        } else {
            self.countAttendeeSchedules = {
                'allocated': 0,
                'available': 0,
                'denied': 0,
            };
            self.render();
        }
    },
    bindDataChange: function () {
        this._super('bindDataChange');
        if (this.model) {
            app.events.on('change:attendee_schedule_values', this.prepareDataOfAttendeeSchedule, this);
        }
    },
    deniedAll: function (e) {
        var currentEl = $(e.currentTarget).parent();
        var contact_id = currentEl.attr('data-id');
        var parentElId = currentEl.parent().attr('id');
        var contact_name = currentEl.find('.contact_name').text();
        if (parentElId != 'sortable3' && !_.isEmpty(contact_id)) {
            app.alert.show('deniedAll_confirmation', {
                level: 'confirmation',
                messages: 'Are you sure to denied contact (' + contact_name + ') for master and its all subsessions',
                onConfirm: _.bind(function () {
                    app.alert.show('saving_denied_all', {level: 'process', title: 'Processing', autoClose: true, autoCloseDelay: 3000});
                    var params = {
                        'contact_id': contact_id,
                        'sessions': this.attendee_schedule_view.current_session_ids,
                        'event_id' :  this.model.id || this.model.get('id') || this.context.get('model').get('id'),
                    };
                    var url = 'oj_Events/saveDeniedAll';
                    app.api.call('create', app.api.buildURL(url), params, {
                        success: function (data) {
                            app.alert.dismissAll();
                            currentEl.appendTo('#sortable3');
                            $(e.currentTarget).hide();
                        }
                    });

                }, this)
            });
        }

    },
    /**
     * 
     * Dispose the events
     */
    _dispose: function () {
        if (this.model) {
            app.events.off('change:attendee_schedule_values', this.prepareDataOfAttendeeSchedule, this);
        }
    },
})