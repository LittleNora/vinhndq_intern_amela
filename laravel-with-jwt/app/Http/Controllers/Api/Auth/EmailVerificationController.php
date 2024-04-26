<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmailVerificationController extends Controller
{
    public function verifyEmail(EmailVerificationRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();

        try {
            if ($request->user()->hasVerifiedEmail()) {
                return response()->json(['message' => __('Email already verified')], 400);
            }

            $request->user()->markEmailAsVerified();

            event(new Verified($request->user()));

            DB::commit();

            return response()->json(['message' => __('Email verified successfully')]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json(['error' => __('Unable to verify email')], 500);
        }
    }

    public function sendVerificationEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->user()->hasVerifiedEmail()) {
                return response()->json(['message' => __('Email already verified')], 400);
            }

            $request->user()->sendEmailVerificationNotification();

            return response()->json(['message' => __('Email verification link sent on your email id.')]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['error' => __('Unable to send email verification link')], 500);
        }
    }
}
