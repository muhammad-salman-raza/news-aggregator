<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use OpenApi\Attributes as OA;


#[OA\Schema(
    schema: "ArticleSearchRequest",
    title: "Article Search Request",
    description: "Request parameters for searching articles",
    properties: [
        new OA\Property(property: "keyword", description: "Search term for article titles or descriptions", type: "string"),
        new OA\Property(property: "start_date", description: "Start date for publication (YYYY-MM-DD)", type: "string", format: "date"),
        new OA\Property(property: "end_date", description: "End date for publication (YYYY-MM-DD)", type: "string", format: "date"),
        new OA\Property(property: "categories", description: "Array of category UUIDs", type: "array", items: new OA\Items(type: "string", format: "uuid")),
        new OA\Property(property: "sources", description: "Array of source UUIDs", type: "array", items: new OA\Items(type: "string", format: "uuid")),
        new OA\Property(property: "authors", description: "Array of author UUIDs", type: "array", items: new OA\Items(type: "string", format: "uuid")),
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
            property: 'order_by',
            description: 'Column to order by (e.g. published_at, title)',
            type: 'string',
        ),
        new OA\Property(
            property: 'order_direction',
            description: 'Order direction (asc or desc)',
            type: 'string',
        )
    ],
    type: "object"
)]
class ArticleSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'keyword' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'uuid|exists:categories,id',
            'sources' => 'nullable|array',
            'sources.*' => 'uuid|exists:sources,id',
            'authors' => 'nullable|array',
            'authors.*' => 'uuid|exists:authors,id',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1',
            'order_by' => 'nullable|string',
            'order_direction' => 'nullable|in:asc,desc',
        ];
    }

    public function messages(): array
    {
        return [
            'end_date.after_or_equal' => 'The end date must be equal to or after the start date.',
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
