<div id="gallery">
    @if($editable)
        {!! \Html::style('assets/corals/plugins/dropzone-4.3.0/dropzone.min.css') !!}
        {!! \Html::script('assets/corals/plugins/dropzone-4.3.0/dropzone.min.js') !!}
        <script>
            Dropzone.autoDiscover = false;
        </script>
    @endif
    <style>
        .masonry img {
            max-width: 100%;
            vertical-align: bottom;
        }

        .masonry {
            -moz-transition: all .5s ease-in-out;
            -webkit-transition: all .5s ease-in-out;
            transition: all .5s ease-in-out;
            -moz-column-gap: 15px;
            -webkit-column-gap: 15px;
            column-gap: 15px;
            -moz-column-fill: initial;
            -webkit-column-fill: initial;
            column-fill: initial;
        }

        .masonry .brick {
            margin-bottom: 15px;
            position: relative;
        }

        .masonry .brick img {
            -moz-transition: all .5s ease-in-out;
            -webkit-transition: all .5s ease-in-out;
            transition: all .5s ease-in-out;
        }

        .masonry.bordered {
            -moz-column-rule: 1px solid #eee;
            -webkit-column-rule: 1px solid #eee;
            column-rule: 1px solid #eee;
            -moz-column-gap: 50px;
            -webkit-column-gap: 50px;
            column-gap: 50px;
        }

        .masonry.bordered .brick {
            padding-bottom: 25px;
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
        }

        .masonry.gutterless {
            -moz-column-gap: 0;
            -webkit-column-gap: 0;
            column-gap: 0;
        }

        .masonry.gutterless .brick {
            margin-bottom: 0;
        }

        @media only screen and (min-width: 1024px) {
            .masonry {
                -moz-column-count: 3;
                -webkit-column-count: 3;
                column-count: 3;
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 1023px) {
            .masonry {
                -moz-column-count: 2;
                -webkit-column-count: 2;
                column-count: 2;
            }
        }

        .add-photo {
            padding: 0 !important;
            opacity: 0.1;
        }

        .add-photo:hover {
            opacity: 0.2;
        }

        .dropzone .dz-message {
            margin: 3em 0;
        }

        .dz-message i {
            font-size: 8em;
            color: #000;
        }

        .dropzone {
            border: 1px solid rgba(0, 0, 0, 0.2);
        }

        .item-buttons {
            top: 2px;
            left: 2px;
            position: absolute;
            margin: 2px 2px;
        }

        .featured-item {
            position: absolute;
            top: 5px;
            left: 5px;
            color: #ffd400;
            text-shadow: #7a7a7a 0 0 2px;
        }

        .dz-preview {
            display: none;
        }

        .gallery-item.favorite .favorite-btn {
            display: none;
        }
    </style>

    @if(($gallery = $galleryModel->getMedia($galleryModel->galleryMediaCollection))->isNotEmpty() || $editable)
        <div class="masonry">
            @foreach($gallery as $media)
                <div class="brick gallery-item">
                    @if($media->getCustomProperty('featured', false))
                        <span class="featured-item"><i class="fa fa-fw fa-2x fa-star"></i></span>
                    @endif
                    <a href="{{ $media->getFullUrl() }}" data-lightbox="product-gallery">
                        <img src="{{ $media->getFullUrl() }}">
                    </a>
                    @if($editable)
                        <div class="item-buttons" style="display: none;">
                            <a href="{{ url('utilities/gallery/'.$media->id.'/delete') }}"
                               class="btn btn-sm btn-danger item-button" title="Delete Gallery Item"
                               data-action="delete" data-page_action="reloadGallery">
                                <i class="fa fa-fw fa-remove"></i>
                            </a>
                            @if(!$media->getCustomProperty('featured', false))
                                <a href="{{ url('utilities/gallery/'.$media->id.'/mark-as-featured') }}"
                                   class="btn btn-sm btn-warning item-button favorite-btn"
                                   title="Mark as Featured" data-action="post" data-page_action="reloadGallery">
                                    <i class="fa fa-fw fa-star"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div>
            <h4 class="text-muted">No images found.</h4>
        </div>
    @endif
    @if($editable)
        <div class="m-t-10">
            <form action="{{ url(str_replace('//','/','utilities/gallery/'.$galleryModel->hashed_id.'/upload')) }}"
                  class="dropzone"
                  id="galleryDZ">
                {{ csrf_field() }}
                {{ Form::hidden('model_class', get_class($galleryModel)) }}
            </form>
        </div>
        <script type="text/javascript">
            var modelExists = parseInt('{{ $galleryModel->hashed_id?1:0 }}');

            var galleryDZ = new Dropzone("#galleryDZ", {
                maxFilesize: 10, // MB
                acceptedFiles: 'image/*',
                previewsContainer: '.masonry',
                dictDefaultMessage: '<i class="fa fa-plus fa-fw fa-5x add-photo"></i>',
                queuecomplete: function () {
                    reloadGallery();
                },
                success: function (file, response) {
                    $('.dz-preview').remove();

                    if (!modelExists && response.file_link) {
                        let image = $('<div>', {
                            class: 'brick gallery-item',
                            'data-hash': response.file_hash,
                        }).append($('<a>', {
                            href: response.file_link,
                            'data-lightbox': 'product-gallery',
                        }).append($('<img>', {
                            src: response.file_link,
                            alt: ''
                        })))

                        let itemButtons = $('<div>', {
                            class: 'item-buttons',
                        }).css('display', 'none')
                            .html(`
                            <button class="btn btn-sm btn-danger item-button" type="button"
                                    title="Delete Gallery Item"
                                    onclick="deleteImage(event,${response.file_hash})">
                                <i class="fa fa-fw fa-remove"></i>
                            </button>
                            <button class="btn btn-sm btn-warning item-button  favorite-btn" type="button"
                           title="Mark as Featured" onclick="markAsFav(event,${response.file_hash})">
                            <i class="fa fa-fw fa-star"></i></button>
                        `);

                        image.append(itemButtons);

                        $('.masonry').append(image);
                        addImage(response.file_hash);
                    }
                },
                error: function (file, response) {
                    var message;
                    if ($.type(response) === "string")
                        var message = response; //dropzone sends it's own error messages in string
                    else
                        var message = response.message;
                    file.previewElement.classList.add("dz-error");
                    _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
                    _results = [];
                    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                        node = _ref[_i];
                        _results.push(node.textContent = message);
                    }
                    return _results;
                }
            });

            function addImage(file_hash) {
                let galleryNew = $("#gallery_new");

                let value = galleryNew.val();

                if (value) {
                    value += "," + file_hash;
                } else {
                    value = file_hash;
                }

                galleryNew.val(value);
            }

            function deleteImage(event, file_hash) {
                let galleryDeleted = $("#gallery_deleted");

                let value = galleryDeleted.val();

                if (value) {
                    value += "," + file_hash;
                } else {
                    value = file_hash;
                }

                galleryDeleted.val(value);

                $(event.target).closest('.gallery-item').remove();
            }

            function markAsFav(event, file_hash) {
                $("#gallery_favorite").val(file_hash);

                $('.gallery-item').removeClass('favorite');

                $('.gallery-item .featured-item').remove();

                $(event.target).closest('.gallery-item').addClass('favorite')
                    .prepend(`<span class="featured-item"><i class="fa fa-fw fa-2x fa-star"></i></span>`);
            }

            function reloadGallery() {
                if (!modelExists) {
                    return;
                }
                setTimeout(function () {
                    $.ajax({
                        type: "GET",
                        url: '{{ url('utilities/gallery/'.$galleryModel->hashed_id) }}',
                        data: {
                            model_class: '{!! getObjectClassForViews($galleryModel) !!}'
                        },
                        success: function (data) {
                            $('#gallery').html(data);
                        }
                    });
                }, 500);
            }

            function initGalleryItemButtons() {
                $(document).on("mouseenter", ".gallery-item", function (e) {
                    $(this).find(".item-buttons").show();
                });

                $(document).on("mouseleave", ".gallery-item", function (e) {
                    $(this).find(".item-buttons").hide();
                });
            }

            window.onload = function () {
                initGalleryItemButtons();
            }
        </script>
    @endif
</div>
