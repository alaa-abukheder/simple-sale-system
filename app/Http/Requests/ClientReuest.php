<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientReuest extends FormRequest
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
        $request = Request::isMethod("put");
        return [
            "name" => ["required","string"],
            "email" => [$request ? "nullable" : "required","email",Rule::unique("users","email")],
            "password" => ["required","string","min:8"],
            "last_name" => ["required","string"],
            "mobile" => ["required","numeric"],
            "role" => [$request ? "nullable" : "required",Rule::in(["admin","saler","client"])],
        ];
    }
}
