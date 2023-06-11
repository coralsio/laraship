@extends('layouts.master')



@section('title', $title_singular)

@section('actions')
    @isset($showModel)
        {!! $showModel->getActions() !!}
    @endisset
    @isset($edit_url)
        {!! CoralsForm::link(url($edit_url), trans('Corals::labels.edit'), ['class'=>'btn btn-primary']) !!}
    @endisset
@endsection

@section('js')
@endsection
