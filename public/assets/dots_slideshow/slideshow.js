Dots.Events.on('bootstrap', function () {
    Dots.Events.on('block.view.initEditors', Dots.Blocks.View.Slideshow.init);
    Dots.Events.on('section.blocks.init.slideshow_content', function(sectionView, block, opts){
        var $block = $(block);
        if (!opts)
            opts = {isEditMode:false};
        var model = new Dots.Blocks.Model.Block({
            id:$block.attr('data-block'),
            type:$block.attr('data-block-type'),
            position:$block.attr('data-block-position')
        });
        sectionView.model.getBlocks().add(model, {at:model.get('position')});
        new Dots.Blocks.View.Slideshow({el:block, model:model, opts:opts});
    }, this);
});

Dots.Blocks.View.Slideshow = Dots.Blocks.View.Block.extend({
        events:function(){
            var events = {
                'click .cancel-slideshow':'_cancelSlideshow',
                'click .save-slideshow':'_saveSlideshow'
            }
            return _.extend(events, Dots.Blocks.View.Slideshow.__super__.events);
        },
        _cancelSlideshow:function (e) {
            e.preventDefault();
            console.log("cancel");
        },
        _saveSlideshow:function (e) {
            e.preventDefault();
            console.log("save");
        }
    }, {
        init:function () {
            $('.fileupload').fileupload({
                dataType: 'json',
                done: function (e, data) {
                    var template = _.template($("#dots-tpl-slideshow-thumbnail-item-wrap").html()),
                        thumbnails = "";
                    $.each(data.result, function (index, file) {
                        thumbnails += template({src:file.thumbnail_url,name:name}) ;
                    });
                    $(".thumbnails").append(thumbnails);
                }
            });
        }
    }
);