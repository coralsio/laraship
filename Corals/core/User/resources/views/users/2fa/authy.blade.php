{!! Html::style('assets/corals/plugins/authy/flags.authy.css') !!}
{!! Html::style('assets/corals/plugins/authy/form.authy.css') !!}

@if(!empty(\TwoFactorAuth::getSupportedChannels()))
    {!! CoralsForm::radio('channel','User::attributes.user.channel', false,\TwoFactorAuth::getSupportedChannels(),\Arr::get($user->getTwoFactorAuthProviderOptions(),'channel', null)) !!}
@endif


@push('partial_js')
    {!! \Html::script('assets/corals/plugins/authy/form.authy.js') !!}
@endpush
