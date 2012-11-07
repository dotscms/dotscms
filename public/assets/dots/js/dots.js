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
 * Architecture class that allows the extension of JS classes to completely overwrite any functionality in the application core
 */
//Dots.namespace("Dots.Arch");
//Dots._Arch = function(classes){
//    this.classes = classes || {};
//
//};
//_.extend(Dots._Arch.prototype, {}, {
//    get: function (name) {
//        if (!this.has(name)) {
//            name = name.replace(/\.([^\.]*)$/, '._default');
//        }
//        if (this.classes[name]) {
//            return this.classes[name];
//        }
//        throw new Error('Invalid architectural block requested: "' + name +'".');
//    },
//
//    has: function (name) {
//        return (this.classes[name] != undefined);
//    },
//
//    set: function (name, func) {
//        this.classes[name] = func;
//    },
//    getObject: function (name, opts) {
//        var cls = this.get(name);
//        return new cls(opts);
//    }
//});
//Dots.arch = new Dots._Arch();

/**
 * Dots Events
 */
_.extend(Dots.Events, Backbone.Events);
Dots.Events.on('init', function(){
    this.on('bootstrap', function () {
        new Dots.View.Menu.Admin();
    });
    this.trigger('bootstrap');
    this.trigger('route');
    this.trigger('dispatch');
});

/**
 * Dots View for the admin menu bar
 */
Dots.View.Menu.Admin = Backbone.View.extend({
    el: '#dotsAdminBar',
    initialize:function (args){
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
    //render provided errors on a form
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
                for (errKey in errList) {
                    var $input = $('[name="' + preContext + name + postContext + '"]');
                    var $errors = $('<ul class="errors"></ul>');
                    $errors.append('<li>' + errList[errKey] + '</li>');
                    $errors.insertAfter($input);
                }
            } else {
                // handle subform errors
                if (!context) {
                    Dots.View.Dialog.renderErrors(form, errList, name);
                } else {
                    Dots.View.Dialog.renderErrors(form, errList, context + '[' + name + ']');
                }
            }
        }
    }
});
