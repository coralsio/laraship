@extends('layouts.master')

@section('editable_content')
    <div class="container">
        {!! $pricing->rendered !!}
    </div>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @foreach($products as $product)
                        <strong class="text-primary" style="font-size: 2em;">
                            {{ $product->name }}
                        </strong>
                        <br/>
                        <div class="row">
                            <div class="col-md-2">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                     style="width:200px;height:200px;padding:20px;"/>
                            </div>
                            <div class="col-md-10">
                                <p style="height: 200px;vertical-align: middle;display: table-cell;">{{ $product->description }}</p>
                            </div>
                        </div>
                        <br/>
                        {!!   \Shortcode::compile( 'pricing',$product->id ) !!}
                        <hr/>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection