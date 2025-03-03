<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Article",
    title: "Article",
    description: "Article model representing a news article",
    required: ["id", "title", "url", "published_at"],
    properties: [
        new OA\Property(property: "id", description: "UUID of the article", type: "string"),
        new OA\Property(property: "source_id", description: "ID of the source", type: "string"),
        new OA\Property(property: "title", description: "Article title", type: "string"),
        new OA\Property(property: "description", description: "Article description", type: "string"),
        new OA\Property(property: "content", description: "Full content of the article", type: "string"),
        new OA\Property(property: "url", description: "URL of the article", type: "string"),
        new OA\Property(property: "url_to_image", description: "URL of the article image", type: "string"),
        new OA\Property(property: "published_at", description: "Publication date", type: "string", format: "date-time"),
        new OA\Property(property: "source", ref: "#/components/schemas/Source", description: "Source of the article"),
        new OA\Property(property: "categories", description: "Categories of the article", type: "array", items: new OA\Items(ref: "#/components/schemas/Category")),
        new OA\Property(property: "authors", description: "Authors of the article", type: "array", items: new OA\Items(ref: "#/components/schemas/Author")),
    ],
    type: "object"
)]
class Article extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'source_id',
        'title',
        'description',
        'content',
        'url',
        'url_to_image',
        'published_at',
        'raw_response'
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'article_category');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'article_author');
    }
}
