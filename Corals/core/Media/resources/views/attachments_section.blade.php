<div class="row">
    @if((isset($showAttachmentsForm) && $showAttachmentsForm) || !isset($showAttachmentsForm))
        <div class="col-md-12">
            <h4>@lang('Media::labels.media.attachment.add_new_attachments')</h4>
            @if((isset($hasForm) && $hasForm) || !isset($hasForm))
                {!! Form::model($object, ['url' => $url,'method'=>'POST','class'=>'', 'files' => true,'class'=>'ajax-form']) !!}
            @endif

            <table id="values-table" style="width:100%;" class="table table-striped table-responsive key-value-table">
                <thead>
                <tr>
                    <th style="width:50%;">@lang('Media::labels.media.attachment.description')</th>
                    <th style="width:50%;">@lang('Media::labels.media.attachment.attachments')</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <button type="button" class="btn btn-success btn-sm" id="add-value"><i
                        class="fa fa-plus"></i>
            </button>

            <span class="help-block">@lang('Media::labels.media.attachment.click_to_add_new_row')</span>
            <div class="form-group">
                <span data-name="attachments"></span>
            </div>
            @if((isset($hasForm) && $hasForm) || !isset($hasForm))
                {!! CoralsForm::formButtons('<i class="fa fa-save"></i> '.trans('Media::labels.media.attachment.add_attachments') ,[],['show_cancel'=>false]) !!}

                {!! Form::close() !!}
            @endif
        </div>
    @endif
    @if($object->exists)
        <div class="col-md-12">
            <h4>@lang('Media::labels.media.attachment.attachments')</h4>
            @include('Media::show_attachments', ['object'=>$object, 'canDelete'=> ((isset($showAttachmentsForm) && $showAttachmentsForm) || !isset($showAttachmentsForm))])
        </div>
    @endif
</div>

@if((isset($showAttachmentsForm) && $showAttachmentsForm) || !isset($showAttachmentsForm))
    @push('partial_js')
        <script>
            var attachmentScripts = function () {
                let index = $('#values-table tbody tr:last').data('index');

                $('#add-value').on('click', function () {

                    let lastRow = $('#values-table tbody ');

                    if (isNaN(index)) {
                        index = 0;
                    } else {
                        index++;
                    }

                    let newInput = $('<input>', {
                        type: 'text',
                        name: 'attachments[' + index + '][description]',
                        class: 'form-control form-white field',
                    });

                    let newFileInput = $('<input>', {
                        type: 'file',
                        id: 'attachments',
                        name: 'attachments[' + index + '][file]',
                        class: 'form-control form-white field',
                    });

                    let newRemoveButton = $('<button/>', {
                        class: "btn btn-danger btn-sm remove-value",
                        style: "margin:0;",
                        type: "button"
                    });

                    newRemoveButton.append('<i class="fa fa-remove"></i>');

                    inputDiv = $('<div/>', {
                        class: 'form-group'
                    });

                    let downTd = $('<td/>');
                    let selectTd = $('<td/>').append(inputDiv);
                    let fileTd = selectTd.clone();
                    let removeButtonTd = selectTd.clone();
                    fileTd.find('div').append(newFileInput);
                    selectTd.find('div').append(newInput);
                    removeButtonTd.find('div').append(newRemoveButton);

                    let tr = $('<tr/>').append(selectTd).append(fileTd).append(downTd).append(removeButtonTd);

                    lastRow.append(tr);

                });
                $(document).on('click', '.remove-value', function () {
                    let row = $(this).closest('tr');

                    let index = $(this).data('action');

                    if (!index) {
                        row.remove();
                        return true;
                    }
                });

            };

            window.initFunctions.push('attachmentScripts');
        </script>
    @endpush
@endif
