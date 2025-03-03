<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateUserRequest",
    title: "Update User Request",
    description: "Request parameters for updating user data",
    properties: [
        new OA\Property(property: "name", description: "Name of the user", type: "string"),
        new OA\Property(property: "email", description: "Email of the user", type: "string"),
        new OA\Property(property: "password", description: "New password for the user", type: "string"),
        new OA\Property(property: "authors", description: "Array of author UUIDs", type: "array", items: new OA\Items(type: "string", format: "uuid")),
        new OA\Property(property: "categories", description: "Array of category UUIDs", type: "array", items: new OA\Items(type: "string", format: "uuid")),
        new OA\Property(property: "sources", description: "Array of source UUIDs", type: "array", items: new OA\Items(type: "string", format: "uuid")),
    ],
    type: "object"
)]
class UpdateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $this->user()->id,
            'password' => 'nullable|string|min:8',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:authors,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'sources' => 'nullable|array',
            'sources.*' => 'exists:sources,id',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
