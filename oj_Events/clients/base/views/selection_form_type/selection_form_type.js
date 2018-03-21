({
    extendsFrom: 'RecordView',
    events: {
        'click #close_selection_form_type:not(.disabled)': 'closeSelectionFormDrawer',
        'click #send_selection_form_type:not(.disabled)': 'sendSelectionForm'
    },
    initialize: function (options) {
        this._super('initialize', [options]);
        app.alert.dismissAll();
    },
    render: function () {
        this._super('render');
    },
    /**
     * @function closeAttendeeScheduleDrawer
     * @returns {Boolean}
     */
    closeSelectionFormDrawer: function () {
        app.drawer.close();
        return true;
    },
    /**
     * @function saveSchedulerSettings
     * @returns {Boolean}
     */
    sendSelectionForm: function () {
        type = $("input[name=sf_type]:checked").val();
		var self = this;
		app.drawer.close();
        app.alert.dismissAll();
        if (self.model.get('sign_off_selection_form_c') && _.isEqual(app.user.get('type'), 'admin')) {
            app.alert.show('sendPriorityForm', {level: 'process', title: 'Sending Selections Form'});
            var url = 'oj_Events/sendPriorityForm';
            var params = {
                event_id: self.model.id || self.model.get('id'),
            };
			if(type === 'sponsor_expert'){
				params.priority_form_type = "se";
			}
			console.log("Params::", params);
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
})