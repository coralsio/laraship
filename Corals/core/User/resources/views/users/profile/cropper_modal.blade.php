{!! Html::style('assets/corals/plugins/cropper/cropper.css') !!}
<style>
    #image_source {
        cursor: pointer;
    }
</style>
<div class="modal fade modal-image" id="modal-image-crop" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="icons-office-52"></i></button>
                <h4 class="modal-title"><strong>@lang('User::labels.image.change_image')</strong></h4>
            </div>
            <div class="modal-body">
                <img width="100%" src="" id="image_cropper" alt="picture 1" class="img-responsive img-fluid">
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="file col-md-6">
                        <div class="text-left">
    <span class="btn btn-info btn-file">
    @lang('User::labels.image.browse_files')
        <input type="file" class="custom-file" id="cropper"
               onchange="document.getElementById('uploader').value = this.value;" required>

    </span>

                        </div>
                    </div>
                    <div class="col-md-6 pull-right m-b-10">
                        <button type="button" class="btn btn-primary rotate pull-right" data-method="rotate"
                                data-option="-30">
                            <i class="fa fa-undo"></i></button>
                        <button type="button" class="btn btn-primary rotate pull-right m-r-10" data-method="rotate"
                                data-option="30">
                            <i class="fa fa-repeat"></i></button>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary m-r-10 m-l-10"
                            id="Save">@lang('User::labels.image.save',['title'=>''])</button>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">@lang('User::labels.image.close')</button>

                </div>
            </div>
        </div>
    </div>
</div>


@push('partial_js')
    {!! Html::script('assets/corals/plugins/cropper/cropper.js') !!}
    <script type="text/javascript">

        $(function () {
            /////// Cropper Options set
            var $cropper = $('#image_cropper');
            var options = {
                aspectRatio: 1 / 1,
                minContainerWidth: 350,
                minContainerHeight: 250,
                minCropBoxWidth: 145,
                minCropBoxHeight: 145,
                rotatable: true,
                cropBoxResizable: true,
                crop: function (e) {
                    $("#cropped_value").val(JSON.stringify(e.detail));
                }
            };
            ///// Show cropper on existing Image
            $("body").on("click", "#image_source", function () {
                var src = $("#image_source").attr("src");
                src = src.replace("/thumb", "");
                $cropper.attr('src', src);
                $("#modal-image-crop").modal("show");
            });
            ///// Destroy Cropper on Model Hide
            $("#modal-image-crop").on("hide.bs.modal", function () {
                $cropper.cropper('destroy');
                $(".cropper-container").remove();

            });
            /// Show Cropper on Model Show
            $("#modal-image-crop").on("show.bs.modal", function () {
                $cropper.cropper(options);
            });
            ///// Rotate Image
            $("body").on("click", "#modal-image-crop .rotate", function () {
                var degree = $(this).attr("data-option");
                $cropper.cropper('rotate', degree);
            });
            ///// Saving Image with Ajax Call
            $("body").on("click", "#Save", function () {
                var cropped_image = $cropper.cropper('getCroppedCanvas');
                var canvasURL = cropped_image.toDataURL('image/jpeg');
                $("#image_source").attr('src', canvasURL);
                $("input[name=profile_image]").val(canvasURL);

                $cropper.cropper('destroy');
                $("#modal-image-crop").modal("hide");
            });

            ////// When user upload image
            $(document).on("change", "#cropper", function () {
                var imagecheck = $(this).data('imagecheck'),
                    file = this.files[0],
                    imagefile = file.type,
                    _URL = window.URL || window.webkitURL;
                img = new Image();
                img.src = _URL.createObjectURL(file);
                img.onload = function () {
                    var match = ["image/jpeg", "image/png", "image/jpg"];
                    if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
                        alert('Please Select A valid Image File');
                        return false;
                    } else {
                        var reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onloadend = function () { // set image data as background of div
                            $('#image_cropper').attr('src', this.result);
                            $cropper.cropper('destroy');
                            $cropper.cropper(options);
                        }
                    }
                }
            });
        });
    </script>
@endpush
