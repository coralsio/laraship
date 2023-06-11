<?php

$google2fa = app('pragmarx.google2fa');


$userAuthProviderOptions = user()->getTwoFactorAuthProviderOptions();
$user_secret = $userAuthProviderOptions['token'] ?? "";

if (!$user_secret) {
    $user_secret = $google2fa->generateSecretKey(64);
}
if(!\TwoFactorAuth::isEnabled(user())){

$QR_Image = $google2fa->getQRCodeInline(
    config('app.name'),
    user()->email,
    $user_secret
);

?>
<p class="text-info"> @lang('User::labels.2fa.scan_barcode')</p>
<img src="{!!  $QR_Image!!}"/>
<p class="alert alert-info"> {{$user_secret}}</p>
<input type="hidden" name="google2fa_secret" value="{{$user_secret}}">

{!! CoralsForm::text('activation_token','User::labels.2fa.token',true) !!}

<?php }else { ?>


<p class="text-info"> @lang('User::labels.2fa.google_2fa_enabled')</p>


<?php } ?>
