<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Source",
    title: "Source",
    description: "Source model representing a source",
    required: ["id", "name"],
    properties: [
        new OA\Property(property: "id", description: "UUID of the source", type: "string"),
        new OA\Property(property: "external_id", description: "Source external id", type: "string"),
        new OA\Property(property: "name", description: "Source name", type: "string"),
    ],
    type: "object"
)]
class Source extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'external_id',
        'name'
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
