/* Setup Namespaces */
Dots.namespace("Dots.Pages.Model");
Dots.namespace("Dots.Pages.View");

//Update Admin View to handle DotsPage buttons
Dots.View.Menu.Admin = Dots.View.Menu.Admin.extend({
    events:{
        'click #dots_pages_admin_add':'_addPageEvent',
        'click #dots_pages_admin_edit':'_editPageEvent',
        'click #dots_pages_admin_remove':'_removePageEvent'
    },
    _addPageEvent:function () {
        Dots.View.Dialog.open({
            url:'dots-pages/add/',
            id:'dotsPagesAdmin_AddDialog'
        });
        this.$el.find('.dropdown.open').removeClass('open');
        return false;
    },
    _editPageEvent:function () {
        var alias = Dots.Pages.Model.Page.getAlias();
        Dots.View.Dialog.open({
            url:'dots-pages/edit/',
            id:'dotsPagesAdmin_EditDialog',
            params:{
                'alias':alias
            }
        });
        this.$el.find('.dropdown.open').removeClass('open');
        return false;
    },
    _removePageEvent:function () {
        var alias = Dots.Pages.Model.Page.getPageAlias();
        var params = {
            'alias':alias
        };
        var url = 'dots-pages/remove/';
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
    defaults:{
        alias:'',
        title:'',
        template:'',
        language:'en',
        position:1
    },
    initialize:function (){
        if (!this.get("alias")){
            this.set({alias:Dots.Pages.Model.Page.getAlias()});
        }
    }
}, {
    getAlias:function(){
        var base = $('base').attr('href');
        var uri = window.location.pathname;
        if (base.substring(0,4)=='http'){
            uri = "" + window.location;
        }
        return uri.substring(base.length);
    }
});

Dots.Pages.View.Page = Backbone.View.extend({
    el: 'body'
}, {
    instance:null,
    getInstance:function (){
        if (!this.instance){
            this.instance = new Dots.Pages.View.Page({});
        }
        return this.instance;
    }
});