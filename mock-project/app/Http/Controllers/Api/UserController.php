<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Traits\TLog;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use TLog;

    private $modelRepo;

    //
    public function __construct(
        UserRepositoryInterface $modelRepo
    )
    {
        $this->modelRepo = $modelRepo;
    }

    public function search(Request $request)
    {
        try {
            $users = $this->modelRepo->searchForUser($request, 'division');

            return responseApi($users->paginate());
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi($e->getMessage(), false, 500);
        }
    }

    public function show($id)
    {
        try {
            if ($user = $this->modelRepo->find($id, 'division')) {
                return responseApi($user);
            }

            return responseApi('User not found', false, 404);

        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi($e->getMessage(), false, 500);
        }
    }
}
