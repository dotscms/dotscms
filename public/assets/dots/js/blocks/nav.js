/* Setup Namespaces */

// Init Navigation block, setup move handlers and editors events on bootstrap
Dots.Events.on('bootstrap', function () {
    Dots.Events.on('block.view.initEditors', Dots.Blocks.View.NavBlock.onEdit_MoveHandler);
    Dots.Events.on('navBlock.view.enableEditors', Dots.Blocks.View.NavBlock.enableEditors);
    Dots.Blocks.View.NavBlock.init(); //init events
});

/**
 * Dots Navigation Block View class
 * @type Backbone.View
 */
Dots.Blocks.View.NavBlock = Dots.Blocks.View.Block.extend({
    // append events to the default block events list
    events:function(){
        var events = {
            'click .dots-nav-block [data-action="nav_add"]':    '_onNavAddEvent',
            'click .dots-nav-block [data-action="nav_remove"]': '_onNavRemoveEvent',
            'click .dots-nav-block [data-action="nav_edit"]':   '_onNavEditEvent'
        };
        return _.extend(events, Dots.Blocks.View.NavBlock.__super__.events);
    },
    /**
     * Handle add item event
     * @param event
     * @return {Boolean}
     * @private
     */
    _onNavAddEvent: function(event){
        var self = this;
        var $target = $(event.target);
        var $li = $target.parents('li').first();
        var pos = $li.prevAll('li').length + 1;
        var $block = self.$el;
        var data = {
            alias:Dots.Pages.Model.Page.getAlias(),
            section:self.model.get('section'),
            position:self.model.get('position'),
            block_id:self.model.get('id')
        };
        Dots.View.Dialog.open({
            url:'/dots/nav-block/add/',
            id:'dotsBlockNav_AddDialog',
            onLoad:function () {
                Dots.Events.trigger('navBlock.view.enableEditors', this.find('form'));
                this.find('[name$="[type]"]').trigger('change');
                this.find('[name$="[position]"]').val(pos);
            },
            onSave:function (event, opts) {
                var dialog = this;
                this.$el.find('form').ajaxSubmit({
                    dataType:'json',
                    data:data,
                    type:'POST',
                    url:'/dots/nav-block/save/',
                    success:function (response, status, xhr, form) {
                        if (!response.success) {
                            Dots.View.Dialog.renderErrors(form, response.errors, null);
                        } else {
                            var data = response.data;
                            var $newItem = null;
                            switch (data.type) {
                                case '-':
                                    $newItem = $(_.template($('#dots-tpl-nav-item-divider').text(), data));
                                    break;
                                case 'header':
                                    $newItem = $(_.template($('#dots-tpl-nav-item-header').text(), data));
                                    break;
                                default:
                                    $newItem = $(_.template($('#dots-tpl-nav-item-default').text(), data));
                            }
                            self.model.set({id:data.block_id});
                            $newItem.insertBefore($li);
                            dialog.$el.modal('hide');
                            dialog.remove();
                        }
                    }
                });
                return false;
            }
        });
        $($target.parents('.dropdown')[0]).removeClass('open');
        return false;
    },
    _onNavRemoveEvent:function (event) {
        var $this = $(event.target);
        var item = $this.parents('[data-dots-type="dots-nav-item"]:first');
        var nav_id = item.attr('data-block-nav-id');
        var data = {
            id:nav_id
        };
        $.get('/dots/nav-block/remove/', data, function (response) {
            item.remove();
        });
        return false;
    },
    _onNavEditEvent:function (event) {
        var self = this;
        var $target = $(event.target);
        var $item = $target.parents('[data-dots-type="dots-nav-item"]:first');
        var pos = $item.prevAll('[data-dots-type="dots-nav-item"]').length + 1;
        var data = {
            alias: Dots.Pages.Model.Page.getAlias(),
            block_id: self.model.id,
            id: $item.attr('data-block-nav-id')
        };

        Dots.View.Dialog.open({
            url:'/dots/nav-block/edit/',
            id:'dotsBlockNav_EditDialog',
            params:data,
            onLoad:function () {
                Dots.Events.trigger('navBlock.view.enableEditors', this.find('form'));
                this.find('[name$="[type]"]').trigger('change');
                this.find('[name$="[position]"]').val(pos);
            },
            onSave:function (event, opts) {
                var dialog = this;
                this.$el.find('form').ajaxSubmit({
                    dataType:'json',
                    data:data,
                    type:'POST',
                    url:'/dots/nav-block/save/',
                    success:function (response, status, xhr, form) {
                        if (!response.success) {
                            Dots.View.Dialog.renderErrors(form, response.errors, null);
                        } else {
                            var data = response.data;
                            var $newItem = null;
                            switch (data.type) {
                                case '-':
                                    $newItem = $(_.template($('#dots-tpl-nav-item-divider').text(), data));
                                    break;
                                case 'header':
                                    $newItem = $(_.template($('#dots-tpl-nav-item-header').text(), data));
                                    break;
                                default:
                                    $newItem = $(_.template($('#dots-tpl-nav-item-default').text(), data));
                            }
                            self.model.set({id: data.block_id});
                            $newItem.insertBefore($item);
                            $item.remove();
                            dialog.$el.modal('hide');
                            dialog.remove();
                        }
                    }
                });
                return false;
            }
        });
        $($target.parents('.dropdown')[0]).removeClass('open');
        return false;
    }
}, {
    init:function () {
        Dots.Events.on('section.blocks.init.navigation', function (view, block) {
            var $block = $(block);
            var model = new Dots.Blocks.Model.NavBlock({
                id:$block.attr('data-block'),
                type:$block.attr('data-block-type'),
                position:$block.attr('data-block-position')
            });
            view.model.getBlocks().add(model, {at:model.get('position')});
            new Dots.Blocks.View.NavBlock({el:block, model:model});
        }, this);
    },
    enableEditors: function (form){
        form.find('[name$="[page]"]').autocomplete({
            source:'dots/nav-block/get-pages/',
            minLength:2,
            select:function (event, ui) {
                $(this).parents('form').find('[name$="[entity_id]"]').val(ui.item.id);
            }
        }).data('autocomplete')._renderMenu = function (ui, items) {
            if (!ui.hasClass('dropdown-menu'))
                ui.addClass('dropdown-menu');

            for (var key in items) {
                this._renderItem(ui, items[key]);
            }
        };
        form.find('[name$="[type]"]').change(function (event) {
            form.find('[name$="[title]"]').parent('dd').hide().prev().hide();
            var value = $(this).val();
            $(this).children().each(function () {
                if ($(this).val() != value) {
                    var el = form.find('[name$="[' + $(this).val() + ']"]');
                    el.parent('dd').hide().prev().hide();
                    el.val('');
                }
            });
            if (value != '-' && value != 'header') {
                form.find('[name$="[' + value + ']"]').parent('dd').show().prev().show();
            }
            if (value != '-') {
                form.find('[name$="[title]"]').parent('dd').show().prev().show();
            }
        });
    },
    onEdit_MoveHandler:function ($block) {
        $block.find(".dots-nav-block .nav").sortable({
            stop:function (event, ui) {
                var $target = $(event.target);
                var $item = $(ui.item);
                $block = $item.parents('.dots-block');
                var position = $item.prevAll('li').length + 1;
                var data = {
                    block_id:$block.attr('data-block'),
                    id:$item.attr('data-block-nav-id'),
                    alias:Dots.Pages.Model.Page.getAlias(),
                    position:position
                };
                $.getJSON('/dots/nav-block/move/', data, function (resp) {

                });
            },
            items:"li:not(.static)"
        }).disableSelection();
    }
});

Dots.Blocks.Model.NavBlock = Dots.Blocks.Model.Block.extend({
    defaults:function () {
        return {
            section:'',
            type:null,
            position:1,
            class:''
        }
    },
    url:'dots/block'
});



/**
 * Action Handlers
 */
//Dots.Blocks.Nav.Handlers.edit = function () {
//    var $this = $(this);
//    var $block = $this.parents('.dots-block');
//    var block_id = $block.attr('data-block');
//    var $li = $this.parents('li').first();
//    var pos = $li.prevAll('li').length + 1;
//    var data = {
//        alias: Dots.Pages.Model.Page.getAlias(),
//        section: $this.parents('.dots-blocks').attr('data-section'),
//        position: $block.prevAll('.dots-block').length + 1,
//        block_id: $block.attr('data-block'),
//        id: $li.attr('data-block-nav-id')
//    };
//    Dots.Admin.handleDialog({
//        url:'/dots/nav-block/edit/',
//        id:'dotsBlockNav_EditDialog',
//        params:data,
//        onLoad:function(){
//            this.find('[name$="[type]"]').trigger('change');
//            this.find('[name$="[position]"]').val(pos);
//            Dots.Blocks.Nav.Helpers.enableEditors(this.find('form'));
//        },
//        onSave:function(event, opts){
//            $('#dotsBlockNav_EditDialog form').ajaxSubmit({
//                dataType:'json',
//                data:data,
//                type:'POST',
//                url:'/dots/nav-block/save/',
//                success:function (response, status, xhr, form) {
//                    if (!response.success) {
//                        Dots.Admin.renderErrors(form, response.errors, null);
//                    } else {
//                        var data = response.data;
//                        var $item = $('<li data-block-nav-id="' + data.id + '"></li>');
//                        $block.attr('data-block', data.block_id);
//                        $item.insertBefore($li);
//                        var str = '<div class="nav-edit"> ' +
//                            '<a data-action="nav_edit" href="#" ><i class="icon-pencil icon-white"></i></a> ' +
//                            '<a data-action="nav_remove" href="#" ><i class="icon-remove icon-white"></i></a> ' +
//                            '</div> ';
//                        switch (data.type){
//                            case 'header':
//                                $item.addClass('nav-header');
//                                str += '<span>'+data.title+'</span>';
//                                break;
//                            case '-':
//                                $item.addClass('divider-vertical');
//                                break;
//                            default:
//                                str += '<a href="' + data.href + '">' + data.title + '</a> ';
//                        }
//                        $item.html(str);
//                        $li.remove();
//                        $('#dotsBlockNav_EditDialog').modal('hide');
//                        $('#dotsBlockNav_EditDialog').remove();
//                    }
//                }
//            });
//        }
//    });
//    $($(this).parents('.dropdown')[0]).removeClass('open');
//    return false;
//};
//
//Dots.Blocks.Nav.Handlers.add = function (){
//    var $li = $(this).parents('li').first();
//    var pos = $li.prevAll('li').length + 1;
//    var $block = $(this).parents('.dots-block');
//    var data = {
//        alias: Dots.Pages.Model.Page.getAlias(),
//        section: $(this).parents('.dots-blocks').attr('data-section'),
//        position: $block.prevAll('.dots-block').length + 1,
//        block_id: $block.attr('data-block')
//    };
//    Dots.Admin.handleDialog({
//        url:'/dots/nav-block/add/',
//        id:'dotsBlockNav_AddDialog',
//        onLoad: function(){
//            this.find('[name$="[type]"]').trigger('change');
//            this.find('[name$="[position]"]').val(pos);
//            Dots.Blocks.Nav.Helpers.enableEditors(this.find('form'));
//        },
//        onSave: function(event, opts){
//            $('#dotsBlockNav_AddDialog form').ajaxSubmit({
//                dataType: 'json',
//                data: data,
//                type: 'POST',
//                url: '/dots/nav-block/save/',
//                success: function (response, status, xhr, form) {
//                    if (!response.success) {
//                        Dots.Admin.renderErrors(form, response.errors, null);
//                    } else {
//                        var data = response.data;
//                        var $item = $('<li data-block-nav-id="' + data.id + '"></li>');
//                        $block.attr('data-block', data.block_id);
//                        $item.insertBefore($li);
//                        var str = '<div class="nav-edit"> ' +
//                                '<a data-action="nav_edit" href="#" ><i class="icon-pencil icon-white"></i></a> ' +
//                                '<a data-action="nav_remove" href="#" ><i class="icon-remove icon-white"></i></a> ' +
//                            '</div> ';
//                        switch (data.type){
//                            case 'header':
//                                $item.addClass('nav-header');
//                                str += '<span>'+data.title+'</span>';
//                                break;
//                            case '-':
//                                $item.addClass('divider-vertical');
//                                break;
//                            default:
//                                str += '<a href="' + data.href + '">' + data.title + '</a> ';
//                        }
//                        $item.html(str);
//                        $('#dotsBlockNav_AddDialog').modal('hide');
//                        $('#dotsBlockNav_AddDialog').remove();
//                    }
//                }
//            });
//        }
//    });
//    $($(this).parents('.dropdown')[0]).removeClass('open');
//    return false;
//};
//
//Dots.Blocks.Nav.Handlers.remove = function (){
//    var $this = $(this);
//    var nav_id = $this.parents('li').first().attr('data-block-nav-id');
//    var data = {
//        id: nav_id
//    };
//    $.get('/dots/nav-block/remove/', data, function (response) {
//        $this.parents('li').first().remove();
//    });
//    return false;
//};

/**
 * Helpers
 */
//Dots.Blocks.Nav.Helpers.setupFormActions = function(){
//    $(document).on('change', '#dotsBlockNav_AddDialog [name$="[type]"], #dotsBlockNav_EditDialog [name$="[type]"]', function(event){
//        var $dialog = $(this).parents('.modal.dots');
//        $dialog.find('[name$="[title]"]').parent('dd').hide().prev().hide();
//        var value = $(this).val();
//        $(this).children().each(function(){
//            if ($(this).val()!=value){
//                var el = $dialog.find('[name$="[' + $(this).val() + ']"]');
//                el.parent('dd').hide().prev().hide();
//                el.val('');
//            }
//        });
//        if (value != '-' && value!='header'){
//            $dialog.find('[name$="['+value+']"]').parent('dd').show().prev().show();
//        }
//        if (value != '-'){
//            $dialog.find('[name$="[title]"]').parent('dd').show().prev().show();
//        }
//    });
////    Dots.Events.on('block.view.initEditors', Dots.Blocks.Nav.Helpers.setupMoveHandler);
//};

//Dots.Blocks.Nav.Helpers.setupMoveHandler = function($block){
//    $block.find(".dots-nav-block .nav").sortable({
//        stop:function (event, ui) {
//            var $target = $(event.target);
//            var $item = $(ui.item);
//            $block = $item.parents('.dots-block');
//            var position = $item.prevAll('li').length + 1;
//            var data = {
//                block_id: $block.attr('data-block'),
//                id: $item.attr('data-block-nav-id'),
//                alias: Dots.Pages.Model.Page.getAlias(),
//                position: position
//            };
//            $.getJSON('/dots/nav-block/move/', data, function (resp) {
//
//            });
//        },
//        items:"li:not(.static)"
//    }).disableSelection();
//};

//Dots.Blocks.Nav.Helpers.enableEditors = function($linkForm){
//    $linkForm.find('[name$="[page]"]').autocomplete({
//        source:'dots/nav-block/get-pages/',
//        minLength:2,
//        select:function (event, ui) {
//            $(this).parents('form').find('[name$="[entity_id]"]').val(ui.item.id);
//        }
//    }).data('autocomplete')._renderMenu = function(ui, items){
//        if (!ui.hasClass('dropdown-menu'))
//            ui.addClass('dropdown-menu');
//
//        for (var key in items){
//            this._renderItem(ui, items[key]);
//        }
//    };
//};