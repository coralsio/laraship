@extends('layouts.crud.create_edit')
@section('content_header')
    @component('components.content_header')
        @php
            $invitationHeaderDefaults =  [
                    'title' => trans('Utility::module.invitation.title', ['title' => 'Invite Friends']),
                    'breadcrumb' => Breadcrumbs::render('utility_invite_friends_create')
                    ];

            $invitationHeader = \Filters::do_filter('invitation_labels', $invitationHeaderDefaults);
        @endphp

        @slot('page_title')
            {{trans('Utility::module.invitation.title', ['title' => data_get($invitationHeader,'title', $invitationHeaderDefaults['title'])])}}
        @endslot

        @slot('breadcrumb')
            {{ data_get($invitationHeader, 'breadcrumb', $invitationHeaderDefaults['breadcrumb']) }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-12">
            @component('components.box')
                @include('Utility::invite_friends.partials.invite_friends_form_details')
            @endcomponent
        </div>
    </div>
@endsection
