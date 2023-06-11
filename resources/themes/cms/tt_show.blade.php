@extends('layouts.master')

@section('editable_content')
    <div class="py-5">
        @include('TroubleTicket::troubleTickets.public.partial_show', ['troubleTicket'=>$troubleTicket])
    </div>
@endsection
