<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\DTO\SimpleSearchDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\SourceSearchRequest;
use App\Repositories\SourceRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SourceController extends Controller
{
    public function __construct(private readonly SourceRepository $repository)
    {
    }

    /**
     * @throws UnknownProperties
     */
    #[OA\Get(
        path: '/api/v1/sources',
        operationId: 'getSources',
        description: 'Returns a list of sources filtered by keyword',
        summary: 'Search sources by keyword',
        tags: ['Sources'],
        parameters: [
            new OA\Parameter(
                name: 'keyword',
                description: 'Search term for source name',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Page number for pagination',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 1)
            ),
            new OA\Parameter(
                name: 'per_page',
                description: 'Number of items per page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 10)
            ),
            new OA\Parameter(
                name: 'order_direction',
                description: 'Order direction (asc or desc)',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', default: 'asc')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of filtered sources',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Source')
                        )
                    ]
                )
            )
        ]
    )]
    public function search(SourceSearchRequest $request): JsonResponse
    {
        $articles = $this->repository->search(
            SimpleSearchDTO::createFromArray($request->validated())
        );

        return response()->json(['data' => $articles]);
    }
}
