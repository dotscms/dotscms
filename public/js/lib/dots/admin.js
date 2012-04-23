/* Setup Namespaces */
if (!window.Dots) window.Dots = {};
if (!window.Dots.Admin) window.Dots.Admin = {};

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
    if (opts.params)
        params = opts.params;

    $.get(opts.url, params, function (html) {
        $('#' + opts.id).remove();
        $('body').append(html);
        $('#' + opts.id).modal();
        $('#' + opts.id + ' [data-action="save"]').click(function(event){
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
 * Admin action handler
 */
Dots.Admin.Handler = {};

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
    });
    return false;
};