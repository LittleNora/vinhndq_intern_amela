<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\EmailVerificationRequest;
use App\Services\Traits\TLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    use TLog;

    public function verify(EmailVerificationRequest $request)
    {
        DB::beginTransaction();

        try {
            $request->fulfill();

            DB::commit();

            return responseApi([
                'message' => __('Email verified successfully!')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Email verification failed!')
            ], false, 500);
        }
    }

    public function sendVerificationEmail(Request $request)
    {
        try {
            $request->user()->sendEmailVerificationNotification(false);

            return responseApi([
                'message' => __('Email verification link sent!')
            ]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Email verification link sending failed!')
            ], false, 500);
        }
    }
}
