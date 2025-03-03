<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Author",
    title: "Author",
    description: "Author model representing an author",
    required: ["id", "name"],
    properties: [
        new OA\Property(property: "id", description: "UUID of the author", type: "string"),
        new OA\Property(property: "name", description: "Author name", type: "string"),
    ],
    type: "object"
)]
class Author extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['name'];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_author');
    }
}
