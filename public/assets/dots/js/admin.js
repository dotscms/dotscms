/**
 * Create a new namespace
 * @param namespace
 */
function createNamespace(namespace){
    var names = namespace.split('.');
    var obj = window;
    for (var k in names){
        if (!obj[names[k]])
            obj[names[k]] = {};
        obj = obj[names[k]];
    }
}

/* Setup Namespaces */
createNamespace("Dots.Admin.Handler");
createNamespace("Dots.Events");
createNamespace("Dots.View");

/**
 * Dots Events
 */
_.extend(Dots.Events, Backbone.Events);
Dots.Events.on('init', function(){
    this.trigger('bootstrap');
    this.trigger('route');
    this.trigger('dispatch');
});

Dots.View.Dialog = Backbone.View.extend({
    events:{
        'click [data-action="save"]':'saveDialog'
    },
    saveDialog: function (){
        if (this.options.onSave) {
            return this.options.onSave.call(this, event, this.options);
        }
        return this.save();
    },
    save:function (){
        var form = null;
        var opts = this.options;
        if (opts.form) {
            form = opts.form;
        } else {
            form = this.$el.find('form');//$('#' + opts.id + " form");
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
        return this;
    },
    initialize:function (){
        if (!this.options.params){
            this.options.params = {};
        }
    },
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
    render:function (opts){
        var dialog = new Dots.View.Dialog(opts);
        dialog.render();
    },
    runAction: function (response) {
        if (response.action) {
            return eval(response.action);
        }
    },
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

/**
 * Handler dialog actions
 * {
 *     'url': '/', //url to the page
 *     'id': 'dotsPagesAdmin_AddDialog'
 * }
 * @param opts
 */
Dots.Admin.handleDialog = function(opts){
    var params = {};
    if (opts.params) params = opts.params;
    opts.data = opts.params;

    $.get(opts.url, params, function (html) {
        $('#' + opts.id).remove();
        $('body').append(html);
        $('#' + opts.id).modal();
        if (opts.onLoad){
            opts.onLoad.call($('#' + opts.id));
        }
        $('#' + opts.id + ' [data-action="save"]').click(function(event){
            if (opts.onSave){
                return opts.onSave.call(this, event, opts);
            }
            return Dots.Admin.Handler.save.call(this, event, opts);
        });
    });
};

/**
 * Render received errors on a form or subform
 */
Dots.Admin.renderErrors = function (form, errors, context){
    var postContext = "", preContext = "";
    if (!context){
        $(form).find('.errors').remove();
    }else {
        preContext = context + "[";
        postContext = "]";
    }

    for(var name in errors){
        var errList = errors[name];

        for(var errKey in errList){break;}

        if (typeof(errList[errKey]) == "string" || errList[errKey] instanceof String) {
            // handle errors
            for (var errKey in errList) {
                var $input = $('[name="' + preContext + name + postContext + '"]');
                var $errors = $('<ul class="errors"></ul>');
                $errors.append('<li>' + errList[errKey] + '</li>');
                $errors.insertAfter($input);
            }
        }else{
            // handle subform errors
            if (!context) {
                Dots.Admin.renderErrors(form, errList, name);
            } else {
                Dots.Admin.renderErrors(form, errList, context + '[' + name + ']');
            }
        }
    }
};

/**
 * Run javascript action
 * @param response
 */
Dots.Admin.runAction = function (response){
    if (response.action){
        return eval(response.action);
    }
};

/**
 * Admin action handlers
 */

/**
 * Dialog save form handler
 * @param event
 * @param opts
 */
Dots.Admin.Handler.save = function (event, opts){
    var form = null;
    var data = {};
    if (opts.form){
        form = opts.form;
    }else{
        form = $('#' + opts.id + " form");
    }
    if (opts.data){
        data = opts.data;
    }
    form.ajaxSubmit({
        dataType: 'json',
        data: data,
        type: 'POST',
        url: opts.url,
        success: function(response, status, xhr, form){
            if (!response.success){
                Dots.Admin.renderErrors(form, response.errors, null);
            }else{
                Dots.Admin.runAction(response);
            }
        }
//        ,
//        uploadProgress: function (event, position, total, percentComplete){
//            console.log(event, position, total, percentComplete);
//        }
    });
    return false;
};