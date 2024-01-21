<?php

namespace Corals\User\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\User\Http\Requests\UserImportRequest;
use Corals\User\Jobs\HandleUsersImportFile;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Str;
use Illuminate\View\View;
use League\Csv\CannotInsertRecord;
use League\Csv\Reader;
use League\Csv\Writer;


class UserImportController extends BaseController
{
    protected $importHeaders;
    protected $importTarget;

    public function __construct()
    {
        $segments = request()->segments();

        $target = $segments[1] ?? "";

        if (!$target) {
            return;
        }

        $target = Str::singular($target);

        $this->importTarget = $target;


        $this->resource_url = config("user.models.$target.resource_url");

        $this->importHeaders = trans("User::import.$target-headers");

        $this->middleware(function ($request, \Closure $next) use ($target) {

            $model = 'Corals\\User\\Models\\' . ucfirst($target);

            abort_if(user()->cannot('create', $model), 403, 'Unauthorized');

            return $next($request);
        });

        parent::__construct();
    }

    /**
     * @param UserImportRequest $request
     * @return Application|Factory|View
     */
    public function getImportModal(UserImportRequest $request)
    {

        $headers = $this->importHeaders;
        $target = $this->importTarget;


        return view('User::partials.import_modal')
            ->with(compact('headers', 'target'));
    }

    /**
     * @param UserImportRequest $request
     * @throws CannotInsertRecord
     */
    public function downloadImportSample(UserImportRequest $request)
    {

        $csv = Writer::createFromFileObject(new \SplTempFileObject())
            ->setDelimiter(config('corals.csv_delimiter', ','));

        //we insert the CSV header
        $csv->insertOne(array_keys($this->importHeaders));

        $target = Str::plural($this->importTarget, 0);

        $csv->output(sprintf('user_%s_%s.csv', $target, now()->format('Y-m-d-H-i')));

        die;
    }

    /**
     * @param UserImportRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImportFile(UserImportRequest $request)
    {
        try {
            // store file in temp folder
            $file = $request->file('file');

            $roles = $request->input('roles',[]);

            $groups = $request->input('groups',[]);

            $importsPath = storage_path('User/imports');

            $fileName = sprintf("%s_%s", Str::random(), $file->getClientOriginalName());

            $fileFullPath = $importsPath . '/' . $fileName;
            $file->move($importsPath, $fileName);

            $reader = Reader::createFromPath($fileFullPath, 'r')
                ->setDelimiter(config('corals.csv_delimiter', ','))
                ->setHeaderOffset(0);

            $header = $reader->getHeader();

            // validate file headers
            if (count(array_diff(array_keys($this->importHeaders), $header))) {
                unset($reader);
                @unlink($fileFullPath);
                throw new \Exception(trans('User::import.exceptions.invalid_headers'));
            }

            switch ($this->importTarget) {
                case 'user':
                    $rolesListForLoggedInUser = \Roles::getRolesListForLoggedInUser();
                    $this->dispatch(
                        new HandleUsersImportFile(
                            $fileFullPath,
                            user(),
                            $roles,
                            $groups,
                            $rolesListForLoggedInUser)
                    );
                    break;
            }

            return response()->json([
                'level' => 'success',
                'action' => 'closeModal',
                'message' => trans('User::import.messages.file_uploaded')
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'level' => 'error',
                'message' => $exception->getMessage()
            ], 400);
        }
    }


}
