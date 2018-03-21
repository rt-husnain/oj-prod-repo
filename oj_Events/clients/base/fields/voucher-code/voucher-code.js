({
    extendsFrom: 'BaseField',
    initialize: function(options) {
        this._super('initialize', [options]);
    },
    _render: function() {
        this._super('_render');
        if(this.$el){
            if(this.$el.find('input[type=text]')){
                this.$el.find('input[type=text]').prop('disabled',true);
            }
        }
    },
})
