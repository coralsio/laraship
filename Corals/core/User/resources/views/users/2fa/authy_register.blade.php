{!! Html::style('assets/corals/plugins/authy/flags.authy.css') !!}
{!! Html::style('assets/corals/plugins/authy/form.authy.css') !!}

<div class="row">
    <div class="col-md-6">
        <div id="country-div"
             class="form-group has-feedback {{ $errors->has('phone_country_code') ? ' has-error' : '' }}">
            <select class="form-control" id="authy-countries" name="phone_country_code"></select>
            @if ($errors->has('phone_country_code'))
                <div class="help-block">
                    <strong>{{ $errors->first('phone_country_code') }}</strong>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group has-feedback {{ $errors->has('phone_number') ? ' has-error' : '' }}">
            <input class="form-control" id="authy-cellphone"
                   placeholder="@lang('User::attributes.user.cell_phone_number')" type="text"
                   value="{{ old('phone_number') }}"
                   name="phone_number"/>
            <span class="glyphicon glyphicon-phone form-control-icon"></span>

            @if ($errors->has('phone_number'))
                <div class="help-block">
                    <strong>{{ $errors->first('phone_number') }}</strong>
                </div>
            @endif
        </div>
    </div>
</div>

@section('js')
    {!! \Html::script('assets/corals/plugins/authy/form.authy.js') !!}
    <script type="text/javascript">
        $('#country-div').on("DOMSubtreeModified", function () {
            $(".countries-input").addClass('form-control');
        });
    </script>
@endsection
