/* Setup Namespaces */
if (!window.Dots) window.Dots = {};
if (!window.Dots.Blocks) window.Dots.Blocks = {};

/**
 * Init block administration scripts
 */
window.Dots.Blocks.init = function (){
    $('.dots-blocks>.dots-block-header [data-action="add-block"]').click(Dots.Blocks.Handlers.addBlock);
    $('.dots-blocks>.dots-block>.dots-block-header [data-action="edit-block"]').live('click', Dots.Blocks.Handlers.editBlock);
    $('.dots-blocks>.dots-block>.dots-block-header [data-action="remove-block"]').live('click', Dots.Blocks.Handlers.removeBlock);
    $('.dots-blocks>.dots-block [data-action="cancel-block"]').live('click', Dots.Blocks.Handlers.cancelBlock);
    Dots.Blocks.Handlers.setupMoveHandler();
};

Dots.Blocks.Handlers = {};
/**
 * Handle adding a new block
 * @param event
 * @return bool
 */
Dots.Blocks.Handlers.addBlock = function (event){
    var $this = $(this);
    var type = $(this).attr('href').split('#')[1];
    var section = $(this).parents('.dots-blocks').attr('data-section');
    var data = {
        type: type,
        alias: Dots.Pages.Admin.getPageAlias(),
        section: section
    };
    $.get('/dots/block/add/', data, function(html){
        var $currentBlock = $(html);
        $this.parents('.dots-blocks').append($currentBlock);
        Dots.Blocks._enableEditors($currentBlock);
        Dots.Blocks.Handlers.setupSaveHandler($currentBlock, {
            url: '/dots/block/add/',
            type: type
        });
    });
    $($(this).parents('.btn-group')[0]).removeClass('open');
    return false;
};
/**
 * Handle editing an existing block
 * @param event
 * @return bool
 */
Dots.Blocks.Handlers.editBlock = function (event){
    var $this = $(this);
    var $block = $(this).parents('.dots-block');
    var type = $(this).attr('href').split('#')[1];
    var section = $(this).parents('.dots-blocks').attr('data-section');
    var blockId = $block.attr('data-block');
    var data = {
        type: type,
        alias: Dots.Pages.Admin.getPageAlias(),
        section: section,
        block_id: blockId
    };
    $.get('/dots/block/edit/', data, function (html) {
        var $currentBlock = $(html);
        $block.replaceWith($currentBlock);
        Dots.Blocks._enableEditors($currentBlock);
        Dots.Blocks.Handlers.setupSaveHandler($currentBlock, {
            url: '/dots/block/edit/',
            type: type
        });
    });
    $($(this).parents('.btn-group')[0]).removeClass('open');
    return false;
};
/**
 * Handle removing a block
 * @param event
 * @return bool
 */
Dots.Blocks.Handlers.removeBlock = function (event){
    var $currentBlock = $(this).parents('.dots-block');
    var blockId = $currentBlock.attr('data-block');
    if (!blockId) {
        Dots.Blocks._removeBlockEditors($currentBlock);
        $currentBlock.remove();
    } else {
        $.getJSON('/dots/block/remove/', {block_id:blockId}, function (response) {
            if (response.success){
                Dots.Blocks._removeBlockEditors($currentBlock);
                $currentBlock.remove();
            }else{
                //@todo Handle errors when removing a block
            }
        });
    }
    return false;
};
/**
 * Handle cancel editing a block
 * @param event
 * @return bool
 */
Dots.Blocks.Handlers.cancelBlock = function (event) {
    var $currentBlock = $(this).parents('.dots-block');
    Dots.Blocks.Helpers.cancelEdit($currentBlock);
    return false;
};

Dots.Blocks.Handlers.setupMoveHandler = function(){
    $(".dots-blocks").sortable({
        connectWith: ".dots-blocks",
        handle: '[data-action="move-block"]',
        cursorAt: { left:0, top:0 },
        placeholder: "ui-state-highlight",
        items: ".dots-block",
        tolerance: 'pointer',
        stop: function (event, ui) {
            var $target = $(event.target);
            var $item = $(ui.item);
            var fromSection = $target.attr('data-section');
            var toSection = $item.parent().attr('data-section');
            Dots.Blocks.Helpers.updateSectionPositions(fromSection);
            Dots.Blocks.Helpers.updateSectionPositions(toSection);
            if ($item.attr('data-block')!=""){
                var position = $item.attr('data-block-position');
                var data = {
                    block_id: $item.attr('data-block'),
//                    from: fromSection,
                    to: toSection,
                    alias: Dots.Pages.Admin.getPageAlias(),
                    position: position
                };
                $.getJSON('/dots/block/move/', data, function(resp){

                });
            }
        }
    }).disableSelection();
};
/**
 * Handle saving a new or existing block
 * @param $block
 * @param opts
 */
Dots.Blocks.Handlers.setupSaveHandler = function ($block, opts){
    var form = $block.find('form');
    var type = opts.type;
    var section = $block.parents('.dots-blocks').attr('data-section');
    var blockId = $block.attr('data-block');
    var data = {
        type: type,
        alias: Dots.Pages.Admin.getPageAlias(),
        section: section,
        block_id: blockId
    };
    $block.find('[data-action="save-block"]').click( function (event) {
        form.ajaxSubmit({
            dataType: 'json',
            data: data,
            type: 'POST',
            url: opts.url,
            success: function (response, status, xhr, form) {
                if (!response.success) {
                    Dots.Admin.renderErrors(form, response.errors, null);
                } else {
                    $block.attr('data-block', response.block_id);
                    Dots.Blocks.Helpers.cancelEdit($block);
                }
            }
        });
        return false;
    });
};

Dots.Blocks.Helpers = {};
/**
 * Remove the edit form and show the rendered block or remove it if it's not saved
 * @param $block
 */
Dots.Blocks.Helpers.cancelEdit = function ($block){
    var blockId = $block.attr('data-block');
    if (!blockId) {
        Dots.Blocks._removeBlockEditors($block);
        $block.remove();
    } else {
        var data = {
            block_id:blockId
        };
        $.get('/dots/block/view/', data, function (html) {
            Dots.Blocks._removeBlockEditors($block);
            $block.replaceWith(html);
        });
    }
};

Dots.Blocks.Helpers.updateSectionPositions = function (section){
    var i = 1;
    $('[data-section="'+section+'"] .dots-block').each(function (){
        $(this).attr('data-block-position', i++);
    });
};

Dots.Blocks._removeBlockEditors = function ($block) {
    $block.find('.editor').each(function () {
        var id = $(this).attr('id');
        if (tinyMCE.getInstanceById(id)) {
            tinyMCE.execCommand('mceFocus', false, id);
            tinyMCE.remove(tinyMCE.activeEditor);
            tinyMCE.execCommand('mceRemoveControl', true, id);
        }
    });
};

Dots.Blocks._enableEditors = function ($block) {
    $block.find('.editor').each(function () {
        $(this).attr('id', $(this).attr('id') + '_' + Math.floor(10000 * Math.random()));
    });
    $block.find('.editor').tinymce(defaultTinyMCESettings);
};
