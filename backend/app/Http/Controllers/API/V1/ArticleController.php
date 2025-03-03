<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\DTO\ArticleSearchDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleSearchRequest;
use App\Repositories\ArticleRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class ArticleController extends Controller
{
    public function __construct(private readonly ArticleRepository $repository)
    {
    }

    /**
     * @throws UnknownProperties
     */
    #[OA\Get(
        path: '/api/v1/articles',
        operationId: 'getArticles',
        description: 'Returns a list of articles filtered by keyword, date, categories, sources, and authors',
        summary: 'Search and filter articles',
        tags: ['Articles'],
        parameters: [
            new OA\Parameter(
                name: 'keyword',
                description: 'Search term for article titles or descriptions',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'start_date',
                description: 'Start date (YYYY-MM-DD)',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date')
            ),
            new OA\Parameter(
                name: 'end_date',
                description: 'End date (YYYY-MM-DD)',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date')
            ),
            new OA\Parameter(
                name: 'categories',
                description: 'Array of category UUIDs',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', format: 'uuid'))
            ),
            new OA\Parameter(
                name: 'sources',
                description: 'Array of source UUIDs',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', format: 'uuid'))
            ),
            new OA\Parameter(
                name: 'authors',
                description: 'Array of author UUIDs',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', format: 'uuid'))
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
                name: 'order_by',
                description: 'Column to order by (e.g. published_at, title)',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', default: 'published_at')
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
                description: 'List of filtered articles',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Article')
                        )
                    ]
                )
            )
        ]
    )]
    public function search(ArticleSearchRequest $request): JsonResponse
    {
        $articles = $this->repository->search(
            ArticleSearchDTO::createFromArray($request->validated())
        );

        return response()->json(['data' => $articles]);
    }

    /**
     * @throws UnknownProperties
     */
    #[OA\Get(
        path: '/api/v1/user/articles',
        operationId: 'getUserArticles',
        description: 'Returns articles based on the user\'s saved authors, categories, and sources.',
        summary: 'Get user-specific articles',
        tags: ['Articles'],
        parameters: [
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
                name: 'order_by',
                description: 'Column to order by (e.g. published_at, title)',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', default: 'published_at')
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
                description: 'List of user-specific articles',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Article')
                        )
                    ]
                )
            )
        ]
    )]
    public function getUserArticles(ArticleSearchRequest $request): JsonResponse
    {
        $user = auth()->user();

        $articles = $this->repository->getUserArticles(
            $user,
            ArticleSearchDTO::createFromArray($request->validated())
        );

        return response()->json(['data' => $articles]);
    }
}
