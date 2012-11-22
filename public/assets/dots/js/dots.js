/**
 * Create a new namespace
 * @param namespace
 */
window.Dots = {
    namespace: function (namespace) {
        var names = namespace.split('.'), obj = window;
        _.each(names, function (name) {
            if (!obj[name])
                obj[name] = {};
            obj = obj[name];
        });
    }
};

/* Setup Namespaces */
Dots.namespace("Dots.Events");
Dots.namespace("Dots.View.Menu");

/* Prepare Backbone for any apache server */
Backbone.emulateHTTP = true;
Backbone.emulateJSON = true;

/**
 * Dots Events
 */
_.extend(Dots.Events, Backbone.Events);
Dots.Events.on('init', function(){
    this.trigger('bootstrap');
    this.trigger('route');
    this.trigger('dispatch');
});


/**
 * Dots Application
 * @type {*}
 */
Dots.View.Application = Backbone.View.extend({
    events:{},
    el:'body',
    menuView:null,
    initialize:function(){
        this.$el.addClass('dots-app');
        this.menuView = new Dots.View.Menu.Admin();
    },
    run:function (){
        Dots.Events.trigger("init");
    }
}, {
    _instance:null,
    getInstance:function (){
        if (!this._instance) {
            this._instance = new Dots.View.Application();
        }
        return this._instance;
    }
});

/**
 * Dots Menu Bar
 */
Dots.View.Menu.Admin = Backbone.View.extend({
    el:'.dotsAdminBar',
    initialize:function (args) {
        this.$el.data('view', this);
    }
});

/**
 * Dots View for Dialog windows
 * @type {*}
 */
Dots.View.Dialog = Backbone.View.extend({
    //handled dialog events
    events:{
        'click [data-action="save"]':'saveDialog'
    },
    //initialize the view with some default values
    initialize:function () {
        if (!this.options.params) {
            this.options.params = {};
        }
    },
    //save dialog click event handler
    saveDialog: function (){
        if (this.options.onSave) {
            return this.options.onSave.call(this, event, this.options);
        }
        return this.save();
    },
    //default save function to persist all values from the dialog to the server
    save:function (){
        var form = null;
        var opts = this.options;
        if (opts.form) {
            form = opts.form;
        } else {
            form = this.$el.find('form');
        }
        form.ajaxSubmit({
            dataType: 'json',
            data: opts.params,
            type: 'POST',
            url: opts.url,
            success:function (response, status, xhr, form) {
                if (!response.success) {
                    Dots.View.Dialog.renderErrors(form, response.errors, null);
                } else {
                    Dots.View.Dialog.runAction(response);
                }
            }
        });
        return false;
    },
    //render the dialog based on the provided options
    render: function(){
        var opts = this.options;
        var _this = this;
        $.get(opts.url, opts.params, function (html) {
            $('#' + opts.id).remove();
            $('body').append(html);
            var $el = $('#' + opts.id);
            $el.modal();
            _this.setElement($el[0]);
            if (opts.onLoad) {
                opts.onLoad.call(_this.$el);
            }
        });
        return this;
    }
}, {
    instance:null,
    //open a new dialog window using the specified options
    open:function (opts){
        this.instance = new Dots.View.Dialog(opts);
        this.instance.render();
    },
    getInstance: function (){
        return this.instance;
    },
    /**
     * @todo Remove this function or find a better alternative for specifying what action should be taken after a response
     * @param response
     * @return mixed
     */
    runAction: function (response) {
        if (response.action) {
            return eval(response.action);
        }
        return '';
    },

    /**
     * render provided errors on a form
     * @todo fix this to work with a class and render the errors in the last version
     */
    renderErrors: function (form, errors, context) {
        var postContext = "", preContext = "";
        if (!context) {
            $(form).find('.errors').remove();
        } else {
            preContext = context + "[";
            postContext = "]";
        }

        for (var name in errors) {
            var errList = errors[name];

            var errKey = _.first(_.keys(errList));

            if (typeof(errList[errKey]) == "string" || errList[errKey] instanceof String) {
                // handle errors
                var $input = $('[name="' + preContext + name + postContext + '"]');
                for (errKey in errList) {
                    var errStr = '<ul class="errors">';
                    errStr += '<li>' + errList[errKey] + '</li>';
                    errStr += '</ul>';
                    $input.after(errStr);
                }
            } else {
                // handle subform errors
                Dots.View.Dialog.renderErrors(form, errList, (!context ? name :context + '[' + name + ']'));
            }
        }
    }
});

$(function () {
    Dots.View.Application.getInstance().run();
});