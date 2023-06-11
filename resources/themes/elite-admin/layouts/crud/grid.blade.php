@extends('layouts.master')

@section('title', $title)



@section('actions')
    @isset($resourceModel)
        {!! $resourceModel->getGenericActions() !!}
    @endisset
@endsection

@section('content')
    <div class="row flex-wrap">
        @forelse($grid_items as $item)
            <div class="col-md-4">
                @include($grid_item_view,['item'=>$item])
            </div>
        @empty
            <div class="col-md-4">
                <div class="alert alert-info">
                    <strong><i class="fa fa-info-circle"></i> @lang('corals-elite-admin::labels.crud.no_item_found')
                    </strong>
                    <br/>@lang('corals-elite-admin::labels.crud.create_item')
                </div>
            </div>
        @endforelse
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! $grid_items->links('partials.paginator') !!}
        </div>
    </div>
@endsection

@section('js')
@endsection
