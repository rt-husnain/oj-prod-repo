({      
    extendsFrom : 'PreviewView',
    previewForSessionModuleForAttendeeLayout : false, 
    
    initialize: function(options) {    
    this._super('initialize', [options]);
    this.previewForAttendeeSchedule = false;
    app.events.on("preview:sessionOnAttendeeschedule", this.updatepreviewForSessionModuleForAttendeeLayout,  this);   
    },
    
    updatepreviewForSessionModuleForAttendeeLayout: function(previewForSessionModuleForAttendeeLayout) {
        this.previewForSessionModuleForAttendeeLayout = previewForSessionModuleForAttendeeLayout;
    },
    
    /**
     * Renders the preview dialog with the data from the current model and collection.
     * @param model Model for the object to preview
     * @param collection Collection of related objects to the current model
     * @param {Boolean} fetch Optional Indicates if model needs to be synched with server to populate with latest data
     * @param {Number|String} previewId Optional identifier use to determine event origin. If event origin is not the same
     * but the model id is the same, preview should still render the same model.
     * @private
     */
    _renderPreview: function(model, collection, fetch, previewId){
                   
        var self = this;

        // If there are drawers there could be multiple previews, make sure we are only rendering preview for active drawer
        if(app.drawer && !app.drawer.isActive(this.$el)){
            return;  //This preview isn't on the active layout
        }

        // Close preview if we are already displaying this model
        if(this.model && model && (this.model.get("id") == model.get("id") && previewId == this.previewId) &&
         (!this.previewForSessionModuleForAttendeeLayout || model.module != 'oj_Sessions')) {
            // Remove the decoration of the highlighted row
            app.events.trigger("list:preview:decorate", false);
            // Close the preview panel
            app.events.trigger('preview:close');            
            return;
        }

        if (app.metadata.getModule(model.module).isBwcEnabled) {
            // if module is in BWC mode, just return
            return;
        }

        if (model) {
            // Use preview view if available, otherwise fallback to record view
            var viewName = 'preview';
            var previewMeta = app.metadata.getView(model.module, 'preview');
            var recordMeta = app.metadata.getView(model.module, 'record');
            // Check if the session details for minified preivew on attendee layout
            if(this.previewForSessionModuleForAttendeeLayout && model.module == 'oj_Sessions') {
                 previewMeta = app.metadata.getView(model.module, 'minified-preview-for-attendee-schedule');
                 recordMeta = {};
            }     
            if (_.isEmpty(previewMeta) || _.isEmpty(previewMeta.panels)) {
                viewName = 'record';
            }
            this.meta = this._previewifyMetadata(_.extend({}, recordMeta, previewMeta));

            if (fetch) {
                model.fetch({
                    //Show alerts for this request
                    showAlerts: true,
                    success: function(model) {
                        self.renderPreview(model, collection);
                    },
                    //The view parameter is used at the server end to construct field list
                    view: viewName
                });
            } else {
                this.renderPreview(model, collection);
            }
        }

        this.previewId = previewId;
    },    
})
