<?php

namespace App\Http\Requests\Api\Admin\Schedule;

use App\Rules\ValidateScheduleDateTime;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'subject' => ['required', 'string'],
            'date' => ['required', 'date'],
            'send_at' => ['required', 'date_format:H:i', new ValidateScheduleDateTime],
            'send_to' => ['required', 'email'],
            'send_to_user_id' => ['required', 'integer', 'exists:users,id'],
            'message' => ['required', 'string'],
            'recipients' => ['nullable', 'array'],
            'recipients.*.email' => ['required', 'email'],
            'attachments' => ['nullable', 'array'],
        ];
    }
}
