<?php

namespace Corals\User\Traits;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait UserLoginTrait
{
    /**
     * @param $user
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    protected function doLogin($user, $message = '')
    {
        $activeToken = $user->tokens()->where('revoked', false)->first();

        if ($activeToken) {
//            $activeToken->revoke();
        }

        if ($user->getProperty('force_reset')) {
            return $this->passwordResetRedirector($user);
        }

        $tokenResult = $user->createToken('Corals-API');

        $token = $tokenResult->token;

        $token->save();

        $user->setPresenter(new \Corals\User\Transformers\API\UserPresenter());

        $userDetails = $user->presenter();

        $userDetails['authorization'] = 'Bearer ' . $tokenResult->accessToken;

        $userDetails['expires_at'] = Carbon::parse(
            $tokenResult->token->expires_at
        )->toDateTimeString();

        return apiResponse($userDetails, $message);
    }

    /**
     * @param $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function passwordResetRedirector($user)
    {
        $brokerUser = $this->broker()->getUser(['email' => $user->email]);

        $tokens = $this->broker()->getRepository();

        $token = $tokens->create($brokerUser);

        Auth::logout();

        return apiResponse([
            'token' => $token
        ], '', 'force_reset', 302);

    }

}
