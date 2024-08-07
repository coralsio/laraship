<?php

namespace Corals\User\Traits;


use Carbon\Carbon;
use Corals\User\Facades\CoralsAuthentication;
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
        if ($user->getProperty('force_reset')) {
            return $this->passwordResetRedirector($user);
        }

        $user->setPresenter(new \Corals\User\Transformers\API\UserPresenter());

        $userDetails = $user->presenter();

        $userDetails = CoralsAuthentication::login(
            user: $user,
            userDetails: $userDetails
        );

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
