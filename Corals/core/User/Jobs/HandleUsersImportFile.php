<?php

namespace Corals\User\Jobs;

use Corals\Foundation\Traits\ImportTrait;
use Corals\User\Http\Requests\{UserRequest};
use Corals\User\Models\{User};
use Corals\User\Services\{UserService};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use League\Csv\{Exception as CSVException};

class HandleUsersImportFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ImportTrait;

    protected $importFilePath;

    /**
     * @var array
     */
    protected $importHeaders;
    protected $user;
    protected $roles;
    protected $groups;

    /**
     * HandleBrandsImportFile constructor.
     * @param $importFilePath
     * @param $user
     */
    public function __construct($importFilePath, $user, $roles, $groups)
    {
        $this->user = $user;
        $this->roles = $roles;
        $this->groups = $groups;
        $this->importFilePath = $importFilePath;
        $this->importHeaders = array_keys(trans('User::import.user-headers'));
    }


    /**
     * @throws CSVException
     */
    public function handle()
    {
        $this->doImport();
    }

    /**
     * @param $record
     * @throws \Exception
     */
    protected function handleImportRecord($record)
    {
        $record = array_map('trim', $record);

        //prepare user data
        $userData = $this->getUserData($record);

        $this->validateRecord($userData);

        $userModel = $this->gerUserModel($userData['email']);

        $userRequest = new UserRequest();

        $userRequest->replace($userData);

        $userService = new UserService();

        if ($userModel) {
            $userService->update($userRequest, $userModel);
        } else {
            $userService->store($userRequest, User::class);
        }
    }

    protected function gerUserModel($email)
    {
        return User::query()->where('email', $email)->first();
    }

    /**
     * @param $record
     * @return array
     * @throws \Exception
     */
    protected function getUserData($record)
    {
        return array_filter([
            'name' => data_get($record, 'name'),
            'last_name' => data_get($record, 'last_name'),
            'email' => data_get($record, 'email'),
            'roles' => $this->roles,
            'groups' => $this->groups,
        ]);
    }

    protected function initHandler()
    {
    }

    protected function getValidationRules($data): array
    {
        return [
            'name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'email' => 'required',
            'roles' => 'required|array',
        ];
    }
}
