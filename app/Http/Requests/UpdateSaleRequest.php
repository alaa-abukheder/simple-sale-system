<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSaleRequest extends FormRequest
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
            // "sale_id" => ["required","numeric",Rule::exists("sales","id")],
            "products" => ["array","required"],
            "products.*" => ["array","required"],
            "products.*.SaleTransaction_id" => ["required","numeric",Rule::exists("sale_transactions","id")],
            "products.*.quantity" => ["required","numeric"],
        ];
    }
}
