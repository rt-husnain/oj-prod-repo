({
    events: {
        'click  a[name=send_to_all]': 'send_to_all',
        'click a[name=cancel_button]': 'cancelButton',
        
    },

    initialize: function (options) {
        
        this._super("initialize", [options]);

    },
    send_to_all:function() {
        
         var url = app.api.buildURL('oj_Events/fetch_event_status');
             if(this.model.attributes.event_template_id) {
             app.api.call('create', url, {events:this.context.attributes.idCSV,event_id:this.context.attributes.eventID,templateID:this.model.attributes.event_template_id}, {
            success: function(data)
            {
                
                app.alert.show('message-id', {
                level: 'success',
                messages: 'Email Sent to all!',
                autoClose: true
            });
            app.drawer.close();
            },
            error: function(error) {
                
                app.alert.show("server-error", {
                    level: 'error',
                    messages: 'ERR_GENERIC_SERVER_ERROR',
                    autoClose: false
                });
                app.error.handleHttpError(error);
            }
            
        });
        
        
}
else {
    
     app.alert.show("server-error", {
                    level: 'error',
                    messages: 'Please select the template',
                    autoClose: true,
                    autoCloseDelay: 1000,
                });
    
}
    },
   
  cancelButton: function () {
       app.drawer.close();
   },

render:function () {
    
    
   this._super("render");
   var a=document.getElementsByName('email_compose_button')[0];
    
}

})

















