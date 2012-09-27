/* Setup Namespaces */
Dots.namespace("Dots.Pages.Model");

//Update Admin View to handle DotsPage buttons
Dots.View.Menu.Admin = Dots.View.Menu.Admin.extend({
    events:{
        'click #dots_pages_admin_add':'_add',
        'click #dots_pages_admin_edit':'_edit',
        'click #dots_pages_admin_remove':'_remove'
    },
    _add:function () {
        Dots.View.Dialog.open({
            url:'/dots-pages/add/',
            id:'dotsPagesAdmin_AddDialog'
        });
        this.$el.find('.dropdown.open').removeClass('open');
        return false;
    },
    _edit:function () {
        var alias = Dots.Pages.Model.Page.getPageAlias();
        Dots.View.Dialog.open({
            url:'/dots-pages/edit/',
            id:'dotsPagesAdmin_EditDialog',
            params:{
                'alias':alias
            }
        });
        this.$el.find('.dropdown.open').removeClass('open');
        return false;
    },
    _remove:function () {
        var alias = Dots.Pages.Model.Page.getPageAlias();
        var params = {
            'alias':alias
        };
        var url = '/dots-pages/remove/';
        $.getJSON(url, params, function (resp) {
            if (resp.success) {
                Dots.View.Dialog.runAction(resp);
            }
        });
        this.$el.find('.dropdown.open').removeClass('open');
        return false;
    }
});

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
        if (!this.get("alias")){
            this.set({alias:Dots.Pages.Model.Page.getPageAlias()});
        }
    }
}, {
    getPageAlias:function(){
        return window.location.pathname.substr(1);
    }
});