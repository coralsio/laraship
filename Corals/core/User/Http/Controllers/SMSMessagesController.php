<?php


namespace Corals\User\Http\Controllers;

use Corals\Modules\SMS\Http\Controllers\BaseMessagesController;
use Corals\User\Models\User;

class SMSMessagesController extends BaseMessagesController
{
    /**
     * @var string
     */
    public $messageableClass = User::class;
}