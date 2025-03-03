<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Category",
    title: "Category",
    description: "Category model representing a category",
    required: ["id", "name"],
    properties: [
        new OA\Property(property: "id", description: "UUID of the category", type: "string"),
        new OA\Property(property: "name", description: "Category name", type: "string"),
    ],
    type: "object"
)]
class Category extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['name'];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_category');
    }
}
