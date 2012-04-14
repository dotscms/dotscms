/* Setup Namespaces */
if (!window.Dots) window.Dots = {};
if (!window.Dots.Pages) window.Dots.Pages = {};

/**
 * Admin features
 */
Dots.Pages.Admin = {};
Dots.Pages.Admin.init = function (){
    $('#dots_pages_admin_add').click(Dots.Pages.Admin.Handlers.btn_add);
    $('#dots_pages_admin_edit').click(Dots.Pages.Admin.Handlers.btn_edit);
    $('#dots_pages_admin_remove').click(Dots.Pages.Admin.Handlers.btn_remove);
};

/**
 * Admin event handlers
 */
Dots.Pages.Admin.Handlers = {};

Dots.Pages.Admin.Handlers.btn_add = function (event) {
    Dots.Admin.handleDialog({
       url:'/dots-pages/add/',
       id: 'dotsPagesAdmin_AddDialog'
    });
    $($(this).parents('.dropdown')[0]).removeClass('open');
    return false;
};

Dots.Pages.Admin.Handlers.btn_edit = function (event) {
    Dots.Admin.handleDialog({
        url:'/dots-pages/edit/',
        id:'dotsPagesAdmin_EditDialog'
    });
    $($(this).parents('.dropdown')[0]).removeClass('open');
    return false;
};

Dots.Pages.Admin.Handlers.btn_remove = function (event) {

    $($(this).parents('.dropdown')[0]).removeClass('open');
    return false;
};
