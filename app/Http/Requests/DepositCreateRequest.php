<?php

namespace App\Http\Requests;

use App\Rules\ExistsActiveKeyCurrency;
use Illuminate\Foundation\Http\FormRequest;

class DepositCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->method(), $this->allowedMethods());
    }


    public function allowedMethods(): array
    {
        return ['POST'];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => "required|numeric|exists:users,id",
            'target_user_id' => "required|numeric|exists:users,id",
            'amount' => "required|numeric|min:1",
            'currency_key' => [
                'required',
                'string',
                new ExistsActiveKeyCurrency()
            ],
        ];
    }
}
