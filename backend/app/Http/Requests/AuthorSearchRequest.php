<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use OpenApi\Attributes as OA;


#[OA\Schema(
    schema: "AuthorSearchRequest",
    title: "Author Search Request",
    description: "Request parameters for searching categories",
    properties: [
        new OA\Property(
            property: 'keyword',
            description: 'Search term for author name',
            type: 'string'
        ),
        new OA\Property(
            property: 'page',
            description: 'Page number for pagination',
            type: "int"
        ),
        new OA\Property(
            property: 'per_page',
            description: 'Number of items per page',
            type: 'int',
        ),
        new OA\Property(
            property: 'order_direction',
            description: 'Order direction (asc or desc)',
            type: 'string',
        )
    ],
    type: "object"
)]
class AuthorSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'keyword' => 'sometimes|string',
            'page'            => 'sometimes|integer|min:1',
            'per_page'        => 'sometimes|integer|min:1|max:100',
            'order_direction' => 'sometimes|string|in:asc,desc',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'message' => 'The given data was invalid.',
            'errors'  => $validator->errors()
        ], 422);

        throw new HttpResponseException($response);
    }
}
