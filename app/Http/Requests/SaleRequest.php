<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class SaleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "client_id" => ["required","numeric",Rule::exists("users","id")],
            "seller_id" => ["required","numeric",Rule::exists("users","id")],
            "sales" => ["array","required"],
            "sales.*" => ["array","required"],
            "sales.*.product_id" => ["required","numeric",Rule::exists("products","id")],
            "sales.*.quantity" => ["required","numeric"],
        ];
    }
}
