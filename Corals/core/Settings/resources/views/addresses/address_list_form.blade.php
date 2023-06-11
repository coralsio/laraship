@php $addressTypes = \Settings::get('address_types',[]); @endphp
<div class="row">
    <div class="col-md-12">
        <table class="table color-table info-table table table-hover table-striped table-condensed">
            <thead>
            <tr>
                <th>@lang('Settings::attributes.address.type')</th>
                <th>@lang('Settings::attributes.address.address_one')</th>
                <th>@lang('Settings::attributes.address.address_two')</th>
                <th>@lang('Settings::attributes.address.city')</th>
                <th>@lang('Settings::attributes.address.state')</th>
                <th>@lang('Settings::attributes.address.zip')</th>
                <th>@lang('Settings::attributes.address.country')</th>
                <th>@lang('Corals::labels.action')</th>
            </tr>
            </thead>
            <tbody>
            @if(is_array($model->address))
                @foreach($model->address as $addressType => $addressRecord)
                    @php
                        if(isset($object) && $object['type'] == $addressType){} else {unset($addressTypes[$addressType]);}
                    @endphp
                    <tr>
                        <td>{{ \Settings::get('address_types')[$addressType]??$addressType }}</td>
                        <td>{{ $addressRecord['address_1'] }}</td>
                        <td>{{ $addressRecord['address_2']??'-' }}</td>
                        <td>{{ $addressRecord['city'] }}</td>
                        <td>{{ $addressRecord['state'] }}</td>
                        <td>{{ $addressRecord['zip'] }}</td>
                        <td>{{ $addressRecord['country'] }}</td>
                        <td>{!! \Settings::getAddressActions($addressType, $url??'',$addressDiv) !!}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">@lang('Settings::labels.address.no_add')</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
@if(!empty($addressTypes))
    <div id="address_form"
         data-url="{{ $url??'' }}"
         data-method="{{ $method??'' }}">
        @include('components.address', ['key'=>'address', 'addressTypes'=>$addressTypes])
        {!! \CoralsForm::button('Settings::labels.address.save', ['id'=>'add_address_btn','class'=>'btn btn-success btn-sm','onClick'=>'handleAddressAddBtn();']) !!}
    </div>
@endif

<script type="text/javascript">
    function handleAddressAddBtn() {
        divSubmit($("#address_form"));
    }
</script>