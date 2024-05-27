<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\Password\UpdatePasswordRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Traits\TLog;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    use TLog;

    //
    public function __construct(
        private UserRepositoryInterface $modelRepo
    )
    {
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        DB::beginTransaction();

        try {

            $this->modelRepo->updatePassword(auth()->id(), $request->password);

            auth('api')->logout();

            DB::commit();

            return responseApi([
                'message' => __('Password updated successfully!')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Password update failed!')
            ], false, 500);
        }
    }

    public function createResetPasswordToken(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        try {
            $user = $this->modelRepo->findBy('email', $request->email);

            if (!$user) {
                return responseApi([
                    'message' => __('User not found!')
                ], false, 404);
            }

            $user->sendPasswordResetNotification(Password::broker()->createToken($user));

            return responseApi(['message' => __('Password reset token sent!')]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Password reset token sending failed!')
            ], false, 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        DB::beginTransaction();

        $modelRepo = $this->modelRepo;

        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request, $modelRepo) {
                    $modelRepo->updatePassword($user->id, $request->password);

                    event(new PasswordReset($user));
                }
            );

            DB::commit();

            return $status === Password::PASSWORD_RESET
                ? responseApi(['message' => __('Password reset successfully!')])
                : responseApi(['message' => __('Unable to reset password.')], false, 500);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e->getMessage(), 'error');

            return responseApi(['message' => __('Password reset failed!')], false, 500);
        }
    }
}
