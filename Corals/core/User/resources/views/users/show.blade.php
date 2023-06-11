@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('user_show') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            @component('components.box',['box_class'=>'box-success'])
                <div style="font-size: medium">
                    {!! $user->present('action') !!}
                </div>

                <img style="width: 60%;margin: 0 auto;display: block;"
                     class="img-responsive img-fluid img-circle mb-2 m-b-5"
                     src="{{ asset($user->picture) }}"
                     alt="{{ $user->full_name }}">

                <p class="profile-username text-center">{{ $user->full_name }}</p>
                <p class="text-center">{{ $user->present('email') }}</p>
                <p class="text-center">Since {{ format_date($user->created_at) }}</p>
                <p class="text-center">
                    {!! formatArrayAsLabels($user->roles->pluck('label'),'success') !!}
                </p>
                <p class="text-center">
                    {{ $user->job_title }}
                </p>
                <p class="text-center">
                    {{ $user->phone }}
                </p>
            @endcomponent
        </div>
        <div class="col-md-8">
            @component('components.box',['box_class'=>'box-success'])
                @php \Actions::do_action('display_profile',$user) @endphp
            @endcomponent
        </div>

    </div>
@endsection
