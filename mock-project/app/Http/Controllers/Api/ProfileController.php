<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use App\Repositories\User\UserEloquentRepository;
use App\Services\Traits\TLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    use TLog;

    //
    public function __construct(
        private UserEloquentRepository $modelRepo
    )
    {
    }

    public function profile()
    {
        try {
            $user = $this->modelRepo->find(auth()->id());

            return responseApi($user);
        } catch (\Exception $e) {
            $this->log($e);

            return responseApi($e->getMessage(), false, 500);
        }
    }

    public function update(UpdateProfileRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = $this->modelRepo->update(auth()->id(), $request->validated());

            DB::commit();

            return responseApi($user);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e);

            return responseApi($e->getMessage(), false, 500);
        }
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $user = $this->modelRepo->updateAvatar(auth()->id(), $request->avatar);

            DB::commit();

            return responseApi($user);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e);

            return responseApi($e->getMessage(), false, 500);
        }
    }
}
