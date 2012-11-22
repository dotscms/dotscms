/* Setup Namespaces */

// Init Links block, setup move handlers and editors events on bootstrap
Dots.Events.on('bootstrap', function () {
    Dots.Events.on('block.view.initEditors', Dots.Blocks.View.LinkBlock.initMoveHandler);
    Dots.Events.on('linkBlock.view.enableEditors', Dots.Blocks.View.LinkBlockItem.enableEditors);
    Dots.Blocks.View.LinkBlock.init(); //init events
});

/**
 * Dots Link Item View
 * @type {*}
 */
Dots.Blocks.View.LinkBlockItem = Backbone.View.extend({
    isEditMode:false,
    events:{
        'click [data-action="link_remove"]':'_onRemoveEvent',
        'click [data-action="link_edit"]':'_onEditEvent'
    },
    editEvents:{
        'click [data-action="link_cancel"]':'_onCancelEvent',
        'click [data-action="link_save"]':'_onSaveEvent'
    },
    initialize: function (args){
        this.setEditMode(args.isEditMode);
    },
    setEditMode:function(edit){
        this.isEditMode = edit==true;
        this.undelegateEvents();
        this.delegateEvents(this.isEditMode ? this.editEvents : this.events);
    },
    _onRemoveEvent:function (event) {
        var $this = $(event.currentTarget);
        var item = $this.parents('[data-block-link-id]:first');
        var link_id = item.attr('data-block-link-id');
        var data = {
            id:link_id
        };
        $.get('dots/link-block/remove/', data, function (response) {
            item.remove();
        });
        return false;
    },
    _onEditEvent:function (event) {
        var self = this;
        var $this = $(event.currentTarget);
        var block_id = $this.parents('.dots-block').attr('data-block');
        var link_id = $this.parents('li').first().attr('data-block-link-id');
        var data = {
            block_id:block_id,
            id:link_id
        };
        $.get('dots/link-block/edit/', data, function (html) {
            var $form = $('<li class="link-form static">' + html + '</li>');
            $form.insertBefore($this.parents('li').first());
            $form.find('[name="type"]').trigger('change');
            $this.parents('li').first().hide();
            var itemView = new Dots.Blocks.View.LinkBlockItem({el:$form});
            $form.data('view', itemView);
            itemView.setEditMode(true);
            Dots.Events.trigger('linkBlock.view.enableEditors', $form);
        });
        return false;
    },
    _onCancelEvent:function (event) {
        this.$el.next('li').show();
        this.$el.remove();
        return false;
    },
    _onSaveEvent:function (event) {
        var self = this;
        var $form = this.$el.find('form');
        var $block = this.$el.parents('.dots-block');
        var pos = this.$el.prevAll('li').length + 1;
        var blockPos = $block.prevAll('.dots-block').length + 1;
        var section = $block.parents('.dots-blocks').attr('data-section');
        var data = {
            position:blockPos,
            block_id:$block.attr('data-block'),
            section:section,
            alias:Dots.Pages.Model.Page.getAlias()
        };
        var id = $form.find('[name="id"]').val();
        $form.find('[name="position"]').val(pos);
        $form.ajaxSubmit({
            dataType:'json',
            data:data,
            type:'POST',
            url:'dots/link-block/save/',
            success:function (response, status, xhr, form) {
                if (!response.success) {
                    Dots.View.Dialog.renderErrors(form, response.errors, null);
                } else {
                    var data = response.data;
                    var item = $(_.template($('#dots-tpl-link-item').text(), data));
                    //@todo need to changed this in order to have an instance of the parent view class and update the model from there
                        $block.attr('data-block', data.block_id);
                        $block.data('view').model.set({id:data.block_id});
                    self.$el.before(item);
                    var prevEl = self.$el;
                    self.setElement(item[0]);
                    prevEl.remove();
                }
            }
        });
        return false;
    }
}, {
    enableEditors:function (form) {
        form.find('[name="page"]').autocomplete({
            source:'dots/link-block/get-pages/',
            minLength:2,
            select:function (event, ui) {
                $(this).parents('.link-form').find('[name="entity_id"]').val(ui.item.id);
            }
        }).data('autocomplete')._renderMenu = function (ui, items) {
            if (!ui.hasClass('dropdown-menu'))
                ui.addClass('dropdown-menu');

            for (var key in items) {
                this._renderItem(ui, items[key]);
            }
        };
        form.on('change', '[name="type"]', function (event) {
            var value = $(this).val();
            $(this).children().each(function () {
                if ($(this).val() != value) {
                    var input = form.find('[name="' + $(this).val() + '"]');
                    input.val('');
                    input.parent('dd').hide().prev().hide();
                }
            });
            var input = form.find('[name="' + value + '"]');
            input.parent('dd').show().prev().show();
            form.find('[id$="' + value + '-label"]').show().next().show();
        });
        form.find('[name="type"]').trigger('change');
    }
});

/**
 * Dots Link Block View class
 * @type Backbone.View
 */
Dots.Blocks.View.LinkBlock = Dots.Blocks.View.Block.extend({
    // append events to the default block events list
    events:function () {
        var events = {
            'click [data-action="link_add"]':'_onLinkAddEvent'
        };
        return _.extend(events, Dots.Blocks.View.LinkBlock.__super__.events);
    },
    initialize:function (args){
        Dots.Blocks.View.Block.prototype.initialize.call(this, args);
    },
    initEditors:function () {
        this.$el.find('[data-block-link-id]').each(function () {
            var itemView = new Dots.Blocks.View.LinkBlockItem({el:this});
            $(this).data('view', itemView);
        });
        Dots.Events.trigger('block.view.initEditors', this.$el);
    },
    removeEditors:function () {
        this.$el.find('[data-block-link-id]').each(function () {
            var itemView = $(this).data('view');
            if (itemView){
                itemView.setEditMode(false);
            }
        });
        Dots.Events.trigger('block.view.removeEditors', this.$el);
    },
    /**
     * Handle add item event
     * @param event
     * @return {Boolean}
     * @private
     */
    _onLinkAddEvent:function (event) {
        var self = this;
        var $target = $(event.currentTarget).parent('li');
        $.get('dots/link-block/add/', function (html) {
            var $form = $('<li class="link-form static">' + html + '</li>');
            $target.before($form);
            $form.find('[name="type"]').trigger('change');
            $target.hide();

            var itemView = new Dots.Blocks.View.LinkBlockItem({el:$form});
            $form.data('view', itemView);
            itemView.setEditMode(true);
            Dots.Events.trigger('linkBlock.view.enableEditors', $form);
        });
        return false;
    }
}, {
    init:function () {
        Dots.Events.on('section.blocks.init.links_content', function (view, block) {
            var $block = $(block);
            var model = new Dots.Blocks.Model.LinkBlock({
                id:$block.attr('data-block'),
                type:$block.attr('data-block-type'),
                position:$block.attr('data-block-position')
            });
            view.model.getBlocks().add(model, {at:model.get('position')});
            new Dots.Blocks.View.LinkBlock({el:block, model:model});
        }, this);
    },
    initMoveHandler:function ($block) {
        var self = this;
        $block.find(".dots-links-block").sortable({
            handle:'[data-action="link_move"]',
            helper:function (event, item) {
                $(item).addClass('dots-move-link-helper');
                return item;
            },
            stop:function (event, ui) {
                var $item = $(ui.item);
                $block = $item.parents('.dots-block');
                $item.removeClass('dots-move-link-helper');
                var position = $item.prevAll('li').length + 1;
                var data = {
                    alias:Dots.Pages.Model.Page.getAlias(),
                    block_id:$block.attr('data-block'),
                    id: $item.attr('data-block-link-id'),
                    position: position
                };
                $.getJSON('dots/link-block/move/', data, function (resp) {

                });
            },
            axis:'y',
            cursorAt:{ left:-60, top:20 },
            items:"li:not(.static)"
        }).disableSelection();
    }
});

/**
 * Link Block Model
 * @type {*}
 */
Dots.Blocks.Model.LinkBlock = Dots.Blocks.Model.Block.extend({
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
































///* Setup Namespaces */
//Dots.namespace("Dots.Blocks.Links.Handlers");
//Dots.namespace("Dots.Blocks.Links.Helpers");
//
//Dots.Blocks.Links.init = function(){
//    $(document).on('click', '.dots-blocks>.dots-block .dots-links-block [data-action="add_link"]', Dots.Blocks.Links.Handlers.add);
//    $(document).on('click', '.dots-blocks>.dots-block .dots-links-block [data-action="edit_link"]', Dots.Blocks.Links.Handlers.edit);
//    $(document).on('click', '.dots-blocks>.dots-block .dots-links-block [data-action="remove_link"]', Dots.Blocks.Links.Handlers.remove);
//    $(document).on('click', '.dots-blocks>.dots-block .dots-links-block [data-action="cancel-link-block"]', Dots.Blocks.Links.Handlers.cancel);
//    $(document).on('click', '.dots-blocks>.dots-block .dots-links-block [data-action="save-link-block"]', Dots.Blocks.Links.Handlers.save);
//    Dots.Blocks.Links.Helpers.setupFormActions();
//};

/**
 * Handlers
 */
//Dots.Blocks.Links.Handlers.add = function (){
//    var $this = $(this);
//    var block_id = $this.parents('.dots-block').attr('data-block');
//    var data = {
//
//    };
//    $.get('/dots/link-block/add/', data, function (html){
//        var $form = $('<li class="link-form static">'+html+'</li>');
//        $form.insertBefore($this.parent('li'));
//        $form.find('[name$="[type]"]').trigger('change');
//        $this.parent('li').hide();
//        Dots.Blocks.Links.Helpers.enableEditors($form);
//    });
//    return false;
//};

//Dots.Blocks.Links.Handlers.edit = function (){
//    var $this = $(this);
//    var block_id = $this.parents('.dots-block').attr('data-block');
//    var link_id = $this.parents('li').first().attr('data-block-link-id');
//    var data = {
//        block_id: block_id,
//        id: link_id
//    };
//    $.get('/dots/link-block/edit/', data, function (html){
//        var $form = $('<li class="link-form static">' + html + '</li>');
//        $form.insertBefore($this.parents('li').first());
//        $form.find('[name$="[type]"]').trigger('change');
//        $this.parents('li').first().hide();
//        Dots.Blocks.Links.Helpers.enableEditors($form);
//    });
//    return false;
//};

//Dots.Blocks.Links.Handlers.remove = function (){
//    var $this = $(this);
//    var link_id = $this.parents('li').first().attr('data-block-link-id');
//    var data = {
//        id: link_id
//    };
//    $.get('/dots/link-block/remove/', data, function (response) {
//        console.log(response);
//        $this.parents('li').first().remove();
//    });
//    return false;
//};

//Dots.Blocks.Links.Handlers.save = function (){
//    var $form = $(this).parents('form');
//    var $li = $form.parent('li');
//    var $block = $li.parents('.dots-block');
//    var pos = $li.prevAll('li').length + 1;
//    var blockPos = $block.prevAll('.dots-block').length + 1;
//    var section = $block.parents('.dots-blocks').attr('data-section');
//    var data = {
//        position: blockPos,
//        block_id: $block.attr('data-block'),
//        section: section,
//        alias: Dots.Pages.Model.Page.getAlias()
//    };
//    var id = $form.find('[name$="[id]"]').val();
//    $form.find('[name$="[position]"]').val(pos);
//    $form.ajaxSubmit({
//        dataType: 'json',
//        data: data,
//        type: 'POST',
//        url: 'dots/link-block/save/',
//        success: function (response, status, xhr, form) {
//            if (!response.success) {
//                Dots.Admin.renderErrors(form, response.errors, null);
//            } else {
//                var data = response.data;
//                var $item = $('<li data-block-link-id="' + data.id + '"></li>');
//                $block.attr('data-block', data.block_id);
//                $item.insertBefore($li);
//                $item.html( '<div class="link-edit"> ' +
//                                '<a data-action="move_link" href="#move-link"><i class="icon-move"></i></a> ' +
//                                '<a data-action="edit_link" href="#edit-link"><i class="icon-pencil"></i></a> ' +
//                                '<a data-action="remove_link" href="#remove-link"><i class="icon-remove"></i></a> ' +
//                            '</div> ' +
//                            '<a href="'+ data.href +'">' + data.title + '</a> ');
//                if (id){
//                    $li.next().remove();
//                }else{
//                    $li.next().show();  //show the add link anchor
//                }
//
//                $li.remove();       //remove the form
////                Dots.Blocks.Links.Handlers.cancel.call(form);
//            }
//        }
//    });
//    return false;
//};

//Dots.Blocks.Links.Handlers.cancel = function (){
//    var $li = $(this).parents('li.link-form');
//    $li.next('li').show();
//    $li.remove();
//    return false;
//};

/**
 * Helpers
 */
//Dots.Blocks.Links.Helpers.setupFormActions = function(){
//    $(document).on('change', '.dots-blocks>.dots-block .dots-links-block .link-form [name$="[type]"]', function(event){
//        var value = $(this).val();
//        $(this).children().each(function(){
//            if ($(this).val()!=value){
//                var input = $('.dots-blocks>.dots-block .dots-links-block .link-form [name$="[' + $(this).val() + ']"]');
//                input.val('');
//                input.parent('dd').hide().prev().hide();
//            }
//        });
//        var input = $('.dots-blocks>.dots-block .dots-links-block .link-form [name$="[' + value + ']"]');
//        input.parent('dd').show().prev().show();
//        $('.dots-blocks>.dots-block .dots-links-block .link-form [id$="'+value+'-label"]').show().next().show();
//    });
//    Dots.Events.on('block.initEditors', Dots.Blocks.Links.Helpers.setupMoveHandler);
//};

//Dots.Blocks.Links.Helpers.setupMoveHandler = function($block){
//    $block.find(".dots-links-block").sortable({
//        handle:'[data-action="move_link"]',
//        helper:function (event, item) {
//            $(item).addClass('dots-move-link-helper');
//            return item;
//        },
//        stop:function (event, ui) {
//            var $target = $(event.target);
//            var $item = $(ui.item);
//            $item.removeClass('dots-move-link-helper');
//            $block = $item.parents('.dots-block');
//            var position = $item.prevAll('li').length + 1;
//            var data = {
//                block_id:$block.attr('data-block'),
//                id: $item.attr('data-block-link-id'),
//                alias:Dots.Pages.Admin.getAlias(),
//                position:position
//            };
//            $.getJSON('/dots/link-block/move/', data, function (resp) {
//
//            });
//        },
//        axis:'y',
//        cursorAt:{ left:-60, top:20 },
//        items:"li:not(.static)"
//    }).disableSelection();
//};

//Dots.Blocks.Links.Helpers.enableEditors = function($linkForm){
//    $linkForm.find('[name$="[page]"]').autocomplete({
//        source:'dots/link-block/get-pages/',
//        minLength:2,
//        select:function (event, ui) {
//            $(this).parents('.link-form').find('[name$="[entity_id]"]').val(ui.item.id);
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