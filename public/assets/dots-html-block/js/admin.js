/**
 * Init text editor on block event
 */
Dots.Events.on('block.view.initEditors', function ($block){
    $block.find('.editor').each(function () {
        $(this).attr('id', $(this).attr('id') + '_' + Math.floor(10000 * Math.random()));
    });
    $block.find('.editor').tinymce(defaultTinyMCESettings);
});

/**
 * Remove text editor on block event
 */
Dots.Events.on('block.view.removeEditors', function ($block) {
    $block.find('.editor').each(function () {
        var id = $(this).attr('id');
        if (tinyMCE.getInstanceById(id)) {
            tinyMCE.execCommand('mceFocus', false, id);
            tinyMCE.remove(tinyMCE.activeEditor);
            tinyMCE.execCommand('mceRemoveControl', true, id);
        }
    });
});