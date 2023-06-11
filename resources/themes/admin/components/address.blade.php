<div class="row">
    <div class="{{ $container??'col-md-10' }}">
        <div class="row">
            <div class="{{ isset($type)?'col-md-6':'col-md-5' }}">
                {!! CoralsForm::text($key.'[address_1]','Corals::labels.address_label.address_one',true, $object['address_1'] ?? '') !!}
            </div>
            <div class="{{ isset($type)?'col-md-6':'col-md-4' }}">
                {!! CoralsForm::text($key.'[address_2]', 'Corals::labels.address_label.address_two',false, $object['address_2'] ?? '') !!}
            </div>
            @if(isset($type))
                {!! Form::hidden($key.'[type]', $type) !!}
            @else
                <div class="col-md-3">
                    {!! CoralsForm::select($key.'[type]', 'Corals::labels.address_label.type', $addressTypes??\Settings::get('address_types',[]), true, $object['type'] ?? '') !!}
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-3">
                {!! CoralsForm::text($key.'[city]', 'Corals::labels.address_label.city',true, $object['city'] ?? '') !!}
            </div>
            <div class="col-md-3">
                {!! CoralsForm::text($key.'[state]', 'Corals::labels.address_label.state',true, $object['state'] ?? '') !!}
            </div>
            <div class="col-md-3">
                {!! CoralsForm::text($key.'[zip]', 'Corals::labels.address_label.zip',true, $object['zip'] ?? '') !!}
            </div>
            <div class="col-md-3">
                {!! CoralsForm::select($key.'[country]', 'Corals::labels.address_label.country', \Settings::getCountriesList(), true, $object['country'] ?? '',[], 'select2') !!}
            </div>
        </div>
    </div>
</div>

