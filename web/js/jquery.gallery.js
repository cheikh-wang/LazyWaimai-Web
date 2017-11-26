(function ($) {
    'use strict';

    var galleryDefaults = {
        csrfToken: $('meta[name=csrf-token]').attr('content'),
        csrfTokenName: $('meta[name=csrf-param]').attr('content'),

        uploadUrl: '',
        deleteUrl: '',
        arrangeUrl: '',

        photos: []
    };

    function gallery(el, options) {
        //Extending options:
        var opts = $.extend({}, galleryDefaults, options);
        //code
        var csrfParams = opts.csrfToken ? '&' + opts.csrfTokenName + '=' + opts.csrfToken : '';
        var photos = {}; // photo elements by id
        var $gallery = $(el);

        var $sorter = $('.sorter', $gallery);
        var $images = $('.images', $sorter);
        var $progressOverlay = $('.progress-overlay', $gallery);
        var $uploadProgress = $('.upload-progress', $progressOverlay);

        var photoTemplate = '<div class="photo">'
            + '<div class="image-preview">'
            + '<a href="" data-lightbox="imgbox"><img src="" /></a></div>'
            + '<div class="actions">'
            + '<span class="deletePhoto btn btn-danger btn-xs">'
            + '<i class="glyphicon glyphicon-remove glyphicon-white"></i>'
            + '</span>'
            + '</div>'
            + '<input type="checkbox" class="photo-select" />'
            + '</div>';

        function addPhoto(id, thumb, original, rank) {
            var photo = $(photoTemplate);
            photos[id] = photo;
            photo.data('id', id);
            photo.data('rank', rank);

            $('img', photo).attr('src', thumb);
            $('a', photo).attr('href', original);
            $images.append(photo);

            return photo;
        }

        function removePhotos(ids) {
            $.ajax({
                type: 'POST',
                url: opts.deleteUrl,
                data: 'id[]=' + ids.join('&id[]=') + csrfParams,
                dataType : 'json',
                success: function (resp) {
                    if (resp.status === 'ok') {
                        for (var i = 0, l = ids.length; i < l; i++) {
                            photos[ids[i]].remove();
                            delete photos[ids[i]];
                        }
                    } else {
                        alert(resp.message);
                    }
                },
                error: function() {
                    alert('系统异常');
                }
            });
        }

        function deleteClick(e) {
            e.preventDefault();
            var photo = $(this).closest('.photo');
            var id = photo.data('id');
            // here can be question to confirm delete
            // if (!confirm(deleteConfirmation)) return false;
            removePhotos([id]);
            return false;
        }

        function updateButtons() {
            var selectedCount = $('.photo.selected', $sorter).length;
            $('.select_all', $gallery).prop('checked', $('.photo', $sorter).length == selectedCount);
            if (selectedCount == 0) {
                $('.remove_selected', $gallery).addClass('disabled');
            } else {
                $('.remove_selected', $gallery).removeClass('disabled');
            }
        }

        function selectChanged() {
            var $this = $(this);
            if ($this.is(':checked'))
                $this.closest('.photo').addClass('selected');
            else
                $this.closest('.photo').removeClass('selected');
            updateButtons();
        }

        $images
            .on('click', '.photo .deletePhoto', deleteClick)
            .on('click', '.photo .photo-select', selectChanged);

        $('.images', $sorter).sortable({tolerance: "pointer"}).disableSelection().bind("sortstop", function () {
            var data = [];
            $('.photo', $sorter).each(function () {
                var t = $(this);
                data.push('order[' + t.data('id') + ']=' + t.data('rank'));
            });
            $.ajax({
                type: 'POST',
                url: opts.arrangeUrl,
                data: data.join('&') + csrfParams,
                dataType: "json"
            }).done(function (data) {
                for (var id in data[id]) {
                    photos[id].data('rank', data[id]);
                }
                // order saved!
                // we can inform user that order saved
            });
        });

        $('.afile', $gallery).on('change', function (e) {
            e.preventDefault();

            $uploadProgress.css('width', '5%');
            $progressOverlay.show();

            var data = {};
            if (opts.csrfToken)
                data[opts.csrfTokenName] = opts.csrfToken;
            $.ajax({
                type: 'POST',
                url: opts.uploadUrl,
                data: data,
                files: $(this),
                iframe: true,
                processData: false,
                dataType: "json"
            }).done(function (resp) {
                if (resp.status === 'ok') {
                    addPhoto(resp.data['id'], resp.data['thumb_url'], resp.data['original_url'], resp.data['rank']);
                    $uploadProgress.css('width', '100%');
                } else {
                    alert(resp.message);
                }
                $progressOverlay.hide();
            });
        });

        $('.remove_selected', $gallery).click(function (e) {
            e.preventDefault();
            var ids = [];
            $('.photo.selected', $sorter).each(function () {
                ids.push($(this).data('id'));
            });
            removePhotos(ids);
        });

        $('.select_all', $gallery).change(function () {
            if ($(this).prop('checked')) {
                $('.photo', $sorter).each(function () {
                    $('.photo-select', this).prop('checked', true)
                }).addClass('selected');
            } else {
                $('.photo.selected', $sorter).each(function () {
                    $('.photo-select', this).prop('checked', false)
                }).removeClass('selected');
            }
            updateButtons();
        });

        for (var i = 0, l = opts.photos.length; i < l; i++) {
            var resp = opts.photos[i];
            addPhoto(resp['id'], resp['thumb_url'], resp['original_url'], resp['rank']);
        }
    }

    // The actual plugin
    $.fn.gallery = function (options) {
        if (this.length) {
            this.each(function () {
                gallery(this, options);
            });
        }
    };
})(jQuery);