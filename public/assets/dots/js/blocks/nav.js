/* Setup Namespaces */
createNamespace("Dots.Blocks.Nav.Handlers");
createNamespace("Dots.Blocks.Nav.Helpers");

Dots.Blocks.Nav.init = function(){
    $('.dots-blocks>.dots-block .dots-nav-block [data-action="nav_add"]').live('click', Dots.Blocks.Nav.Handlers.add);
    $('.dots-blocks>.dots-block .dots-nav-block [data-action="nav_remove"]').live('click', Dots.Blocks.Nav.Handlers.remove);
    $('.dots-blocks>.dots-block .dots-nav-block [data-action="nav_edit"]').live('click', Dots.Blocks.Nav.Handlers.edit);
    Dots.Blocks.Nav.Helpers.setupFormActions();
};

/**
 * Action Handlers
 */
Dots.Blocks.Nav.Handlers.edit = function () {
    var $this = $(this);
    var $block = $this.parents('.dots-block');
    var block_id = $block.attr('data-block');
    var $li = $this.parents('li').first();
    var pos = $li.prevAll('li').length + 1;
    var data = {
        alias: Dots.Pages.Admin.getPageAlias(),
        section: $this.parents('.dots-blocks').attr('data-section'),
        position: $block.prevAll('.dots-block').length + 1,
        block_id: $block.attr('data-block'),
        id: $li.attr('data-block-nav-id')
    };
    Dots.Admin.handleDialog({
        url:'/dots/nav-block/edit/',
        id:'dotsBlockNav_EditDialog',
        params:data,
        onLoad:function(){
            this.find('[name$="[type]"]').trigger('change');
            this.find('[name$="[position]"]').val(pos);
            Dots.Blocks.Nav.Helpers.enableEditors(this.find('form'));
        },
        onSave:function(event, opts){
            $('#dotsBlockNav_EditDialog form').ajaxSubmit({
                dataType:'json',
                data:data,
                type:'POST',
                url:'/dots/nav-block/save/',
                success:function (response, status, xhr, form) {
                    if (!response.success) {
                        Dots.Admin.renderErrors(form, response.errors, null);
                    } else {
                        var data = response.data;
                        var $item = $('<li data-block-link-id="' + data.id + '"></li>');
                        $block.attr('data-block', data.block_id);
                        $item.insertBefore($li);
                        var str = '<div class="nav-edit"> ' +
                            '<a data-action="nav_edit" href="#" ><i class="icon-pencil icon-white"></i></a> ' +
                            '<a data-action="nav_remove" href="#" ><i class="icon-remove icon-white"></i></a> ' +
                            '</div> ';
                        switch (data.type){
                            case 'header':
                                $item.addClass('nav-header');
                                str += data.title;
                                break;
                            case '-':
                                $item.addClass('divider-vertical');
                                break;
                            default:
                                str += '<a href="' + data.href + '">' + data.title + '</a> ';
                        }
                        $item.html(str);
                        $li.remove();
                        $('#dotsBlockNav_EditDialog').modal('hide');
                        $('#dotsBlockNav_EditDialog').remove();
                    }
                }
            });
        }
    });
    $($(this).parents('.dropdown')[0]).removeClass('open');
    return false;
};

Dots.Blocks.Nav.Handlers.add = function (){
    var $li = $(this).parents('li').first();
    var pos = $li.prevAll('li').length + 1;
    var $block = $(this).parents('.dots-block');
    var data = {
        alias: Dots.Pages.Admin.getPageAlias(),
        section: $(this).parents('.dots-blocks').attr('data-section'),
        position: $block.prevAll('.dots-block').length + 1,
        block_id: $block.attr('data-block')
    };
    Dots.Admin.handleDialog({
        url:'/dots/nav-block/add/',
        id:'dotsBlockNav_AddDialog',
        onLoad: function(){
            this.find('[name$="[type]"]').trigger('change');
            this.find('[name$="[position]"]').val(pos);
            Dots.Blocks.Nav.Helpers.enableEditors(this.find('form'));
        },
        onSave: function(event, opts){
            $('#dotsBlockNav_AddDialog form').ajaxSubmit({
                dataType: 'json',
                data: data,
                type: 'POST',
                url: '/dots/nav-block/save/',
                success: function (response, status, xhr, form) {
                    if (!response.success) {
                        Dots.Admin.renderErrors(form, response.errors, null);
                    } else {
                        var data = response.data;
                        var $item = $('<li data-block-link-id="' + data.id + '"></li>');
                        $block.attr('data-block', data.block_id);
                        $item.insertBefore($li);
                        var str = '<div class="nav-edit"> ' +
                                '<a data-action="nav_edit" href="#" ><i class="icon-pencil icon-white"></i></a> ' +
                                '<a data-action="nav_remove" href="#" ><i class="icon-remove icon-white"></i></a> ' +
                            '</div> ';
                        switch (data.type){
                            case 'header':
                                $item.addClass('nav-header');
                                str += data.title;
                                break;
                            case '-':
                                $item.addClass('divider-vertical');
                                break;
                            default:
                                str += '<a href="' + data.href + '">' + data.title + '</a> ';
                        }
                        $item.html(str);
                        $('#dotsBlockNav_AddDialog').modal('hide');
                        $('#dotsBlockNav_AddDialog').remove();
                    }
                }
            });
        }
    });
    $($(this).parents('.dropdown')[0]).removeClass('open');
    return false;
};

Dots.Blocks.Nav.Handlers.remove = function (){
    var $this = $(this);
    var nav_id = $this.parents('li').first().attr('data-block-nav-id');
    var data = {
        id: nav_id
    };
    $.get('/dots/nav-block/remove/', data, function (response) {
        $this.parents('li').first().remove();
    });
    return false;
};

/**
 * Helpers
 */
Dots.Blocks.Nav.Helpers.setupFormActions = function(){
    $('#dotsBlockNav_AddDialog [name$="[type]"], #dotsBlockNav_EditDialog [name$="[type]"]').live('change', function(event){
        var $dialog = $(this).parents('.modal.dots');
        $dialog.find('[id$="title-label"]').hide().next().hide();
        var value = $(this).val();
        $(this).children().each(function(){
            if ($(this).val()!=value){
                $dialog.find('[id$="' + $(this).val() + '-label"]').hide().next().hide().find(':input').val('');
            }
        });
        if (value != '-' && value!='header'){
            $dialog.find('[id$="'+value+'-label"]').show().next().show();
        }
        if (value != '-'){
            $dialog.find('[id$="title-label"]').show().next().show();
        }
    });
    Dots.Event.attach('block.initEditors', Dots.Blocks.Nav.Helpers.setupMoveHandler);
};

Dots.Blocks.Nav.Helpers.setupMoveHandler = function(){
    var $block = this;
    $block.find(".dots-nav-block .nav").sortable({
        stop:function (event, ui) {
            var $target = $(event.target);
            var $item = $(ui.item);
            $block = $item.parents('.dots-block');
            var position = $item.prevAll('li').length + 1;
            var data = {
                block_id: $block.attr('data-block'),
                id: $item.attr('data-block-nav-id'),
                alias: Dots.Pages.Admin.getPageAlias(),
                position: position
            };
            $.getJSON('/dots/nav-block/move/', data, function (resp) {

            });
        },
        items:"li:not(.static)"
    }).disableSelection();
};

Dots.Blocks.Nav.Helpers.enableEditors = function($linkForm){
    $linkForm.find('[name$="[page]"]').autocomplete({
        source:'dots/nav-block/get-pages/',
        minLength:2,
        select:function (event, ui) {
            $(this).parents('form').find('[name$="[entity_id]"]').val(ui.item.id);
        }
    }).data('autocomplete')._renderMenu = function(ui, items){
        if (!ui.hasClass('dropdown-menu'))
            ui.addClass('dropdown-menu');

        for (var key in items){
            this._renderItem(ui, items[key]);
        }
    };
};