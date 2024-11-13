<?php

namespace App\Http\Requests\Login;

use App\Constants\RequestKeys;
use Illuminate\Foundation\Http\FormRequest;

class InvokeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            RequestKeys::EMAIL    => ['required', 'email'],
            RequestKeys::PASSWORD => ['required'],
        ];
    }
}
