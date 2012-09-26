/* Setup Namespaces */
createNamespace("Dots.Pages.Admin.Handlers");
createNamespace("Dots.Pages.View");
createNamespace("Dots.Pages.Model");


Dots.Events.on('bootstrap', function (){
    $('#dots_pages_admin_add').click(Dots.Pages.Admin.Handlers.btn_add);
    $('#dots_pages_admin_edit').click(Dots.Pages.Admin.Handlers.btn_edit);
    $('#dots_pages_admin_remove').click(Dots.Pages.Admin.Handlers.btn_remove);
});
/**
 * Admin features
 */
//Dots.Pages.Admin = {};
//Dots.Pages.Admin.init = function (){
//    $('#dots_pages_admin_add').click(Dots.Pages.Admin.Handlers.btn_add);
//    $('#dots_pages_admin_edit').click(Dots.Pages.Admin.Handlers.btn_edit);
//    $('#dots_pages_admin_remove').click(Dots.Pages.Admin.Handlers.btn_remove);
//};

Dots.Pages.Model.Page = Backbone.Model.extend({
    defaults:function(){
        return {
            alias:'',
            title:'',
            template:'',
            language:'en',
            position:1
        }
    },
    initialize:function (){

    }
}, {
    getPageAlias:function(){
        return window.location.pathname.substr(1);
    }
});

Dots.Pages.View.Page = Backbone.View.extend({
    tagName: 'div',
//    template:_.template($('#dots-page-edit-template').html()),
    events:{
        "dblclick .view":"edit",
        "click a.destroy":"clear",
        "blur .edit":"close"
    },
    initialize:function () {
        this.model.bind('change', this.render, this);
        this.model.bind('destroy', this.remove, this);
    },
    render:function () {
        this.$el.html(this.template(this.model.toJSON()));
//        this.input = this.$('.edit');
        return this;
    },
    edit:function () {},
    close:function () {},
    clear:function () {
        this.model.destroy();
    }
});

//
//Dots.Pages.Admin.getPageAlias = function(){
//    return window.location.pathname.substr(1);
//};

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
    var alias = Dots.Pages.Model.Page.getPageAlias();
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
    var alias = Dots.Pages.Model.Page.getPageAlias();
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
