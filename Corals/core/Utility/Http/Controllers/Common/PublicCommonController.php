<?php

namespace Corals\Utility\Http\Controllers\Common;

use Corals\Foundation\Http\Controllers\PublicBaseController;
use Illuminate\Http\Request;

class PublicCommonController extends PublicBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribeNewsLetter(Request $request)
    {
        try {
            $list_id = $request->input('list_id', \Settings::get('utility_mailchimp_list_id'));
            $email = $request->input('email');

            $validation = \Validator::make(
                array(
                    'email' => $request->input('email'),
                    'list_id' => $list_id
                ),
                array(
                    'email' => array('required', 'email'),
                    'list_id' => array('required')
                )
            );

            if ($validation->fails()) {
                return response()->json(['errors' => $validation->errors(), 'message' => 'Error'], 422);
            }

            $mc = new \NZTim\Mailchimp\Mailchimp(\Settings::get('utility_mailchimp_api_key'));
            // Adds/updates an existing subscriber:
            $mc->subscribe($list_id, $email, $merge = [], $confirm = false);


            $message = ['level' => 'success', 'message' => trans('Utility::messages.subscription.success')];
        } catch (\Exception $exception) {
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
