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
        var $target = $(event.currentTarget);
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
            url:'dots/nav-block/add/',
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
                    url:'dots/nav-block/save/',
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
        var $this = $(event.currentTarget);
        var item = $this.parents('[data-dots-type="dots-nav-item"]:first');
        var nav_id = item.attr('data-block-nav-id');
        var data = {
            id:nav_id
        };
        $.get('dots/nav-block/remove/', data, function (response) {
            item.remove();
        });
        return false;
    },
    _onNavEditEvent:function (event) {
        var self = this;
        var $target = $(event.currentTarget);
        var $item = $target.parents('[data-dots-type="dots-nav-item"]:first');
        var pos = $item.prevAll('[data-dots-type="dots-nav-item"]').length + 1;
        var data = {
            alias: Dots.Pages.Model.Page.getAlias(),
            block_id: self.model.id,
            id: $item.attr('data-block-nav-id')
        };

        Dots.View.Dialog.open({
            url:'dots/nav-block/edit/',
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
                    url:'dots/nav-block/save/',
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
            messages:{
                noResults:"",
                results:function(){return '';}
            },
            minLength:2,
            select:function (event, ui) {
                $(this).parents('form').find('[name$="[entity_id]"]').val(ui.item.id);
            }
        }).data('autocomplete')._renderMenu = function (ui, items) {
            if (!ui.hasClass('dropdown-menu'))
                ui.addClass('dropdown-menu');
            for (var key in items) {
                this._renderItemData(ui, items[key]);
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
                var $target = $(event.currentTarget);
                var $item = $(ui.item);
                $block = $item.parents('.dots-block');
                var position = $item.prevAll('li').length + 1;
                var data = {
                    block_id:$block.attr('data-block'),
                    id:$item.attr('data-block-nav-id'),
                    alias:Dots.Pages.Model.Page.getAlias(),
                    position:position
                };
                $.getJSON('dots/nav-block/move/', data, function (resp) {

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