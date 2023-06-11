@if(isset($object) && $object->exists)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                @if(!isset($hide_description) || $hide_description != true)
                    <th>
                        @lang('Media::labels.media.attachment.description')
                    </th>
                @endif
                <th>@lang('Media::labels.media.attachment.attachments')</th>
                <th style="width:5%;"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($object->media as $attachment)
                <tr id="tr_{{ hashids_encode($attachment->id) }}">
                    @if(!isset($hide_description) || $hide_description != true)
                        <td>
                            {!! $attachment->getCustomProperty('description') !!}
                        </td>
                    @endif
                    <td>
                        {!!  \CoralsForm::link(get_media_url($attachment), $attachment->file_name, ['target' => "_blank"]) !!}
                    </td>
                    <td>
                        @if(isset($canDelete) && $canDelete && user() && user()->hasPermissionTo('Administrations::admin.media'))
                            <a href="{{ get_media_url($attachment) }}" data-action="delete"
                               data-page_action="removeMedia"
                               class="btn btn-danger btn-sm remove-value">
                                <i class="fa fa-remove"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">
                        <div class="text-center">
                            @lang('Media::labels.media.attachment.no_files_attached')
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
        function removeMedia(response) {
            $("#tr_" + response.deleted_hashed_id).remove();
        }
    </script>
@endif
