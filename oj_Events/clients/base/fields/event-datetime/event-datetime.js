({
    extendsFrom: 'DatetimecomboField',

    initialize: function(options) {
        this._super('initialize', [options]);
    },
    handleHideDatePicker: function() {
        var $dateField = this.$(this.fieldTag),
            $timeField = this.$(this.secondaryFieldTag),
            d = $dateField.val(),
            t = $timeField.val(),
            datetime = this.unformat(this.handleDateTimeChanges(d, t));

        if (!datetime) {
            $dateField.val('');
            $timeField.val('');
        }

        if (_.isEmptyValue(datetime) && _.isUndefined(this.model.get(this.name))) {
            return;
        }
        if(this.value === null || (this.name === "event_start" && (this.value.date !== d || this.value.time !== t)))
        {
            datetime_end = datetime.replace("T"+t,"T17:30");
            datetime = datetime.replace("T"+t,"T08:15");
            this.model.set("event_end", datetime_end);
        }

        this.model.set(this.name, datetime);
    },

})
