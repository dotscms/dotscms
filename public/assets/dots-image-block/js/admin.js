/**
 * Init image crop editor on block event
 */
Dots.Events.on('block.view.initEditors', function ($block) {
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
Dots.Events.on('block.view.removeEditors', function ($block) {
    var $img = $block.find('.dots-img-crop .dots-img-content img');
    if ($img.imgAreaSelect) {
        $img.imgAreaSelect({remove:true});
    }
});