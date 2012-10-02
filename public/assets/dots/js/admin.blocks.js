/* Setup Namespaces */
Dots.namespace("Dots.Blocks.Handlers");
Dots.namespace("Dots.Blocks.Helpers");
Dots.namespace("Dots.Blocks.View");
Dots.namespace("Dots.Blocks.Model");
Dots.namespace("Dots.Blocks.Collection");

Dots.Events.on('bootstrap', function (){
    Dots.Blocks.View.Section.init(); //init handling for all sections on the page
});

/**
 * Section View
 * @type Dots.Blocks.View.Section
 */
Dots.Blocks.View.Section = Backbone.View.extend({
    className:'dots-blocks',
    events:{
        'click .dots-block-header [data-action="add-block"]':'_addBlockEvent'
    },
    blocks:[],
    initialize:function(args){
        var self = this;
        if (this.$el){
            var blocks = this.$el.find('.dots-block');
            var cBlocks = new Dots.Blocks.Collection.Block();
            //go over all the blocks in the section and add them to the blocks collection and create a view for each one
            blocks.each(function () {
                var $block = $(this);
                var block = new Dots.Blocks.Model.Block({
                    id: $block.attr('data-block'),
                    type: $block.attr('data-block-type'),
                    position: $block.attr('data-block-position')
                });
                cBlocks.add(block, {at:block.get('position')});
                self.blocks[self.blocks.length] = new Dots.Blocks.View.Block({el:this, model:block});
            });
            if (this.model){
                this.model.setBlocks(cBlocks);
            }
        }
    },
    _addBlockEvent:function (event){
        var self = this;
        var $target = $(event.target);
        var type = $target.attr('href').split('#')[1];
        var section = this.$el.attr('data-section');
        var blockModel = new Dots.Blocks.Model.Block({
            type:type,
            section:section,
            position: this.model.getBlocks().length+1
        });
        var data = {
            _method: 'POST',
            model: JSON.stringify(blockModel),
            alias: Dots.Pages.Model.Page.getAlias()
        };

        $.post('/dots/block/add/', data, function(html){
            var $currentBlock = $(html);
            $currentBlock.addClass('edit-dots-block');
            self.$el.append($currentBlock);
            var blockView = new Dots.Blocks.View.Block({el:$currentBlock[0], model:blockModel});
            blockView.initEditors();
            self.model.getBlocks().add(blockModel);
        }, 'text');
        $($target.parents('.btn-group')[0]).removeClass('open');
        return false;
    }
}, {
    sections:{},
    getSections:function (){
        return this.sections;
    },
    setSections:function (sections){
        this.sections = sections;
    },
    init:function (){
        var self = this;
        this.sections = {};
        var cSections = new Dots.Blocks.Collection.Section();
        var sections = Dots.Pages.View.Page.getInstance().$el.find('[data-section]');
        _.each(sections, function (section){
            var mSection = new Dots.Blocks.Model.Section({
                id: $(section).attr('data-section')
            });
            cSections.add(mSection);
            self.sections[mSection.get('id')] = new Dots.Blocks.View.Section({el:section, model:mSection});
        });
        Dots.Blocks.Collection.Section.setInstance(cSections);
        this.setupMoveHandler();
    },
    setupMoveHandler: function () {
        var self = this;
        $(".dots-blocks").sortable({
            connectWith:".dots-blocks",
            handle:'[data-action="move-block"]',
            cursorAt:{ left:0, top:0 },
            placeholder:"ui-state-highlight",
            items:".dots-block",
            tolerance:'pointer',
            revert:true,
//            //match the height of the placeholder with the size of the dragged content
//            start:function (e, ui) {
//                ui.placeholder.height(ui.item.height());
//            },
            stop:function (event, ui) {
                var $target = $(event.target);
                var $item = $(ui.item);
                var fromSection = $target.attr('data-section');
                var toSection = $item.parent().attr('data-section');
                self.updateSectionPositions(fromSection);
                self.updateSectionPositions(toSection);
                if ($item.attr('data-block') != "") {
                    var position = $item.attr('data-block-position');
                    var data = {
                        block_id:$item.attr('data-block'),
    //                    from: fromSection,
                        to:toSection,
                        alias:Dots.Pages.Model.Page.getAlias(),
                        position:position
                    };
                    $.getJSON('/dots/block/move/', data, function (resp) {

                    });
                }
            }
        }).disableSelection();
    },
    updateSectionPositions: function (section) {
        var i = 1;
        $('[data-section="' + section + '"] .dots-block').each(function () {
            $(this).attr('data-block-position', i++);
        });
    }
});

Dots.Blocks.Collection.Section = Backbone.Collection.extend({
    model: Dots.Blocks.Model.Section
}, {
    instance:null,
    getInstance: function (){return this.instance;},
    setInstance: function (instance){this.instance = instance;}
});

Dots.Blocks.Model.Section = Backbone.Model.extend({
    blocks:null,
    initialize:function (args){
        if (args.blocks){
            this.unset('blocks');
            this.setBlocks(args.blocks);
        }
    },
    setBlocks:function(blocks){
        this.blocks = blocks;
        var id = this.get('id');
        var k = 1;
        _.each(this.blocks, function (val){
            val.set({section: id, position:k++});
        });
        return this;
    },
    getBlocks:function(){
        return this.blocks;
    }
});

/**
 * BLOCKS
 */

Dots.Blocks.Collection.Block = Backbone.Collection.extend({
    model: Dots.Blocks.Model.Block
});

Dots.Blocks.Model.Block = Backbone.Model.extend({
    defaults:function(){
        return {
            section:'',
            type:null,
            position:1,
            class:''
        }
    },
    url:'dots/block'
});

Dots.Blocks.View.Block = Backbone.View.extend({
    className:'dots-block',
    events:{
        'click .dots-block-header [data-action="edit-block"]':'_editBlockEvent',
        'click .dots-block-header [data-action="change-settings"]':'_changeSettingsEvent',
        'click .dots-block-header [data-action="remove-block"]':'_removeBlockEvent',
        'click [data-action="save-block"]':'_saveBlockEvent',
        'click [data-action="cancel-block"]':'_cancelBlockEvent'
    },
    initialize: function(args){
        var self = this;
        if (args.model){
            args.model.on('change', this._changeModelEvent, this);
        }
    },
    initEditors: function(){
        Dots.Events.trigger('block.initEditors', this.$el);
    },
    removeEditors: function (){
        Dots.Events.trigger('block.removeEditors', this.$el);
    },
    _changeModelEvent: function(model, val, a,b){
        console.log(model, val, a,b);
    },
    _editBlockEvent: function (event){
        var self = this;
        var data = {
            type: this.model.get('type'),
            alias: Dots.Pages.Model.Page.getAlias(),
            section: this.model.get('section'),
            block_id: this.model.get('id')
        };
        $.get('/dots/block/edit/', data, function (html) {
            var $currentBlock = $(html).addClass('edit-dots-block');
            self.$el.replaceWith($currentBlock);
            self.setElement($currentBlock[0]);
            self.initEditors();
        });
        $($(event.target).parents('.btn-group')[0]).removeClass('open');
        return false;
    },
    _saveBlockEvent: function (){
        var self = this;
        var form = this.$el.find('form');
        var data = {
            _method:(this.model.get('id')?'PUT':'POST'),
            model:JSON.stringify(this.model),
            alias:Dots.Pages.Model.Page.getAlias()
        };
        form.ajaxSubmit({
            dataType: 'json',
            data: data,
            type: 'POST',
            url: '/dots/block',
            success: function (response, status, xhr, form) {
                if (!response.success) {
                    Dots.View.Dialog.renderErrors(form, response.errors, null);
                } else {
                    self.$el.attr('data-block', response.block_id);
                    self._cancelBlockEvent();
                }
            }
        });
        return false;
    },
    _changeSettingsEvent: function (){
        var self = this;
        Dots.View.Dialog.open({
            url:'/dots/block/edit-settings/',
            id:'dotsBlock_EditSettingsDialog',
            params:{id: self.model.get('id')},
            onSave:function (event, opts) {
                var _self = this;
                this.$el.find('form').ajaxSubmit({
                    dataType: 'json',
                    type: 'POST',
                    url: opts.url,
                    data: opts.params,
                    success:function (response, status, xhr, form) {
                        if (!response.success) {
                            Dots.View.Dialog.renderErrors(form, response.errors, null);
                        } else {
                            _self.$el.modal('hide');
                            _self.remove();
                            $.get('/dots/block/view/', { block_id: self.model.get('id') }, function (html) {
                                self.removeEditors();
                                var $currentBlock = $(html);
                                _self.$el.replaceWith($currentBlock);
                                _self.setElement($currentBlock[0]);
                            });
                        }
                    }
                });
            }
        });
        return false;
    },
    _removeBlockEvent: function (){
        var self = this;
        var blockId = this.model.get('id');
        if (!blockId) {
            self.removeEditors();
            this.remove();
        } else {
            $.getJSON('/dots/block/remove/', {block_id: blockId}, function (response) {
                if (response.success) {
                    self.removeEditors();
                    self.remove();
                } else {
                    //@todo Handle errors when removing a block
                }
            });
        }
        return false;
    },
    _cancelBlockEvent: function (){
        var self = this;
        this.$el.removeClass('edit-dots-block');
        var blockId = this.model.get('id');
        if (!blockId) {
            this.removeEditors();
            this.model.destroy();
            this.remove();
        } else {
            var data = {
                block_id:blockId
            };
            $.get('/dots/block/view/', data, function (html) {
                self.removeEditors();
                var $block = $(html);
                self.$el.replaceWith($block);
                self.setElement($block[0]);
            });
        }
        return false;
    }
});





/**
 * Init text editor on block event
 */
Dots.Events.on('block.initEditors', function ($block){
    $block.find('.editor').each(function () {
        $(this).attr('id', $(this).attr('id') + '_' + Math.floor(10000 * Math.random()));
    });
    $block.find('.editor').tinymce(defaultTinyMCESettings);
});

/**
 * Remove text editor on block event
 */
Dots.Events.on('block.removeEditors', function ($block) {
    $block.find('.editor').each(function () {
        var id = $(this).attr('id');
        if (tinyMCE.getInstanceById(id)) {
            tinyMCE.execCommand('mceFocus', false, id);
            tinyMCE.remove(tinyMCE.activeEditor);
            tinyMCE.execCommand('mceRemoveControl', true, id);
        }
    });
});

/**
 * Init image crop editor on block event
 */
Dots.Events.on('block.initEditors', function ($block) {
    $block.find('.dots-img-crop [data-action="crop_image"]').click(function (event) {
        var $content = $block.find('.dots-img-crop .dots-img-content');
        var $x1 = $block.find('.dots-img-crop [data-img-crop-field="x1"]');
        var $y1 = $block.find('.dots-img-crop [data-img-crop-field="y1"]');
        var $x2 = $block.find('.dots-img-crop [data-img-crop-field="x2"]');
        var $y2 = $block.find('.dots-img-crop [data-img-crop-field="y2"]');

        if (!$content.is(":visible")) {
            $content.show();
            var $img = $block.find('.dots-img-crop .dots-img-content img');
            var w = $img.width();
            var h = $img.height();
            var options = {
                handles:true,
//                fadeSpeed:400,
                parent:$block.find('.dots-img-crop .dots-img-content'),
                onSelectEnd:function (img, selection) {
                    var width = $(img).width();
                    var height = $(img).height();
                    $x1.val(100 * selection.x1 / width);
                    $y1.val(100 * selection.y1 / height);
                    $x2.val(100 * selection.x2 / width);
                    $y2.val(100 * selection.y2 / height);
                },
                cancel:function () {
                    $x1.val('');
                    $y1.val('');
                    $x2.val('');
                    $y2.val('');
                }
            };
            if ($x1.val() != "" && $y1.val() != "" && $x2.val() != "" && $y2.val() != "") {
                options['x1'] = $x1.val() * w / 100;
                options['y1'] = $y1.val() * h / 100;
                options['x2'] = $x2.val() * w / 100;
                options['y2'] = $y2.val() * h / 100;
            }
            $block.find('.dots-img-crop .dots-img-content img').imgAreaSelect(options);
        } else {
            $content.hide();
            $(this).removeClass('active');
        }
        return false;
    });
});

/**
 * Remove image crop editor on block event
 */
Dots.Events.on('block.removeEditors', function ($block) {
    var $img = $block.find('.dots-img-crop .dots-img-content img');
    if ($img.imgAreaSelect) {
        $img.imgAreaSelect({remove:true});
    }
});