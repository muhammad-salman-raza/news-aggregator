<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "LoginRequest",
    title: "Login Request",
    description: "Request parameters for login",
    properties: [
        new OA\Property(property: "email", description: "Email of user", type: "string"),
        new OA\Property(property: "password", description: "Password of the user", type: "string"),
    ],
    type: "object"
)]
class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }
}
