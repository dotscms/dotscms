Dots.Events.on('bootstrap', function () {
    Dots.Events.on('block.view.initEditors', Dots.Blocks.View.Slideshow.init);
    Dots.Events.on('section.blocks.init.slideshow_content', function (sectionView, block, opts) {
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
        events:function () {
            var events = {
                'click .cancel-slideshow':'_cancelSlideshow',
                'click .save-slideshow':'_saveSlideshow',
                'click .delete-slideshow-image': '_deleteSlideshowImage'
            }
            return _.extend(events, Dots.Blocks.View.Slideshow.__super__.events);
        },
        _cancelSlideshow:function (e) {
            var self = this,
                blockId = this.model.get('id');
            if (!blockId) {
                this.removeEditors();
                this.remove();
            } else {
                var data = {
                    block_id:blockId
                };
                $.get('dots/block/view/', data, function (html) {
                    self.removeEditors();
                    var $block = $(html);
                    self.$el.replaceWith($block);
                    self.setElement($block[0]);
                    $('.nivoSlider').nivoSlider({
                        effect:$(".effect").val(),
                        animSpeed:$(".animSpeed").val(),
                        pauseTime:$(".pauseTime").val()
                    });
                });
            }

        },
        setElement:function (element, delegateEvents) {
            Backbone.View.prototype.setElement.call(this, element, delegateEvents);
            this.$el.data('view', this);
            return this;
        },
        removeEditors:function () {
            Dots.Events.trigger('block.view.removeEditors', this.$el);
        },
        _saveSlideshow:function (e) {
            var $images = this.$(".thumbnail"),
                images = [], slideshow, data, self = this;
            $.each($images, function (index, image) {
                $image = $(image);
                imageModel = {
                    filename:$image.find("img").data("filename"),
                    id:$image.find("img").data("id"),
                    caption:$image.find("textarea").val(),
                    order:index
                }
                images.push(imageModel);
            });
            slideshow = {
                effect:this.$(".slideshowEffect").val(),
                animSpeed:this.$(".animSpeed").val(),
                pauseTime:this.$(".pauseTime").val(),
                theme:this.$(".slideshowTheme").val(),
                id:this.$(".slideshow-wrapper").data("id")
            }
            data = {
                _method:(this.model.get('id') ? 'PUT' : 'POST'),
                model:JSON.stringify(this.model),
                alias:Dots.Pages.Model.Page.getAlias(),
                images:images,
                slideshow:slideshow
            };
            $.post("dots/block", data, function (data) {
                if (true == data.success) {
                    self.model.set({id:data.block_id});
                    self._cancelSlideshow(self);
                }
            }, 'json');
        },
        _deleteSlideshowImage:function(event){
            event.preventDefault();
            var $element = $(event.target).parents("li"),
                filename = $element.find("img").data("filename"),
                id = $element.find("img").data("id");
            $.post("dots/slideshow/delete-image",{filename:filename,id:id},function(data){
                if(true == data.success){
                    $element.remove();
                }
            },'json');
        }
    }, {
        init:function () {
            $('.fileupload').fileupload({
                dataType:'json',
                done:function (e, data) {
                    var template = _.template($("#dots-tpl-slideshow-thumbnail-item-wrap").html()),
                        thumbnails = "";
                    $.each(data.result, function (index, file) {
                        thumbnails += template({src:file.thumbnail_url, filename:file.name});
                    });
                    $(".thumbnails").append(thumbnails);
                }
            });
        }

    }
);