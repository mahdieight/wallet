<?php

namespace App\Http\Requests;

use App\Rules\ExistsActiveKeyCurrency;
use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
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
            "amount" => "required|numeric",
            "currency_key" => [
                "required",
                "string",
                new ExistsActiveKeyCurrency()
            ],
        ];
    }
}
