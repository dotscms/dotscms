/* Setup Namespaces */
createNamespace("Dots.Pages.Admin.Handlers");

/**
 * Admin features
 */
//Dots.Pages.Admin = {};
Dots.Pages.Admin.init = function (){
    $('#dots_pages_admin_add').click(Dots.Pages.Admin.Handlers.btn_add);
    $('#dots_pages_admin_edit').click(Dots.Pages.Admin.Handlers.btn_edit);
    $('#dots_pages_admin_remove').click(Dots.Pages.Admin.Handlers.btn_remove);
};

Dots.Pages.Admin.getPageAlias = function(){
    return window.location.pathname.substr(1);
};

/**
 * Admin event handlers
 */

//Add button handler
Dots.Pages.Admin.Handlers.btn_add = function (event) {
    Dots.Admin.handleDialog({
       url:'/dots-pages/add/',
       id: 'dotsPagesAdmin_AddDialog'
    });
    $($(this).parents('.dropdown')[0]).removeClass('open');
    return false;
};

//Edit button handler
Dots.Pages.Admin.Handlers.btn_edit = function (event) {
    var alias = Dots.Pages.Admin.getPageAlias();
    Dots.Admin.handleDialog({
        url:'/dots-pages/edit/',
        id:'dotsPagesAdmin_EditDialog',
        params: {
            'alias':alias
        }
    });
    $($(this).parents('.dropdown')[0]).removeClass('open');
    return false;
};

//Remove button handler
Dots.Pages.Admin.Handlers.btn_remove = function (event) {
    var alias = Dots.Pages.Admin.getPageAlias();
    var params = {
        'alias':alias
    };
    var url = '/dots-pages/remove/';
    $.getJSON(url, params, function (resp) {
        if (resp.success){
            Dots.Admin.runAction(resp);
        }
    });
    $($(this).parents('.dropdown')[0]).removeClass('open');
    return false;
};
