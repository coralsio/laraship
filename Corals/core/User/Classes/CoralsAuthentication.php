<?php


namespace Corals\User\Classes;


use Carbon\Carbon;
use Corals\User\Models\User;
use Illuminate\Http\Request;

class CoralsAuthentication
{
    /**
     * CoralsAuthentication constructor.
     * @param Request $request
     */
    public function __construct(public Request $request)
    {
    }

    /**
     * @param User $user
     * @param array $userDetails
     * @return array
     */
    public function login(User $user, array &$userDetails): array
    {
        return $this->tokenLogin(user: $user, userDetails: $userDetails);
    }

    /**
     * @param User $user
     * @param array $userDetails
     * @return array
     */
    public function tokenLogin(User $user, array &$userDetails): array
    {
        $tokenResult = $user->createToken('Corals-API');

        $userDetails['bearer_token'] = $tokenResult->plainTextToken;
        $userDetails['authorization'] = "Bearer " . $tokenResult->plainTextToken;

        $userDetails['expires_at'] = Carbon::parse(
            $tokenResult->accessToken->expires_at
        )->toDateTimeString();

        return $userDetails;
    }

    /**
     *
     */
    public function logout(): void
    {
        $this->tokenLogout();
    }

    /**
     *
     */
    public function tokenLogout(): void
    {
        $this->request->user()->currentAccessToken()->delete();
    }

    /**
     * @param $user
     * @throws \Exception
     */
    public function sendConfirmationToUser($user)
    {
        $user->update([
            'confirmation_code' => (string)random_int(100000, 999999)
        ]);

        event('notifications.user.confirmation', ['user' => $user]);
    }
}