@extends('layouts.master')

@section('title', $title)



@section('actions')
    @unless(isset($hideCreate))
        {!! CoralsForm::link(url($resource_url.'/create'), trans('Corals::labels.create'),['class'=>'btn btn-success']) !!}
    @endunless
@endsection

@section('content')
    <div class="row">
        @forelse($grid_items as $item)
            <div class="col-md-4">
                @include($grid_item_view,['item'=>$item])
            </div>
        @empty
            <div class="col-md-4">
                <div class="alert alert-info">
                    <strong><i class="fa fa-info-circle"></i> @lang('corals-admin::labels.crud.no_item_found')</strong>
                    <br/>@lang('corals-admin::labels.crud.create_item')
                </div>
            </div>
        @endforelse
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            {!! $grid_items->links() !!}
        </div>
    </div>
@endsection

@section('js')
@endsection
