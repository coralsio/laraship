<?php


namespace Corals\Foundation\Classes\Password;

use Illuminate\Auth\Passwords\DatabaseTokenRepository as DatabaseTokenRepositoryBase;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class DatabaseTokenRepository extends DatabaseTokenRepositoryBase
{
    /**
     * @return int|string
     */
    public function createNewToken()
    {
        return mt_rand(1111, 9999);
    }

    /**
     * @param string $email
     * @param string $token
     * @return array|string[]
     */
    protected function getPayload($email, $token)
    {
        $payload = parent::getPayload($email, $token);

        return array_merge($payload, [
            'token' => $token
        ]);
    }

    public function exists(CanResetPasswordContract $user, $token)
    {
        $record = (array)$this->getTable()->where(
            'email', $user->getEmailForPasswordReset()
        )->first();

        return $record &&
            !$this->tokenExpired($record['created_at']) &&
            $token == $record['token'];
    }
}