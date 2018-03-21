({
    extendsFrom: 'CreateView',
    initialize: function (options) {
        this._super("initialize", [options]);
        //validate event id field
        this.model.addValidationTask('event_id_c', _.bind(this._doValidateEventID, this));
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
})
