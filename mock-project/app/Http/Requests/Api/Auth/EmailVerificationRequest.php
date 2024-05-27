<?php

namespace App\Http\Requests\Api\Auth;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Foundation\Auth\EmailVerificationRequest as BaseEmailVerificationRequest;
use Illuminate\Foundation\Http\FormRequest;

class EmailVerificationRequest extends BaseEmailVerificationRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this->setUserResolver(fn() => app(UserRepositoryInterface::class)->find($this->route('id')));
        return parent::authorize();
    }
}
