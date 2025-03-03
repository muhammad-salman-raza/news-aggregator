<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Tymon\JWTAuth\Contracts\JWTSubject;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "User",
    title: "User",
    description: "User model representing a registered user",
    required: ["id", "name", "email"],
    properties: [
        new OA\Property(property: "id", description: "UUID of the user", type: "string"),
        new OA\Property(property: "name", description: "Name of the user", type: "string"),
        new OA\Property(property: "email", description: "Email of the user", type: "string"),
        new OA\Property(property: "preferredAuthors", description: "Array of preferred authors", type: "array", items: new OA\Items(ref: "#/components/schemas/Author")),
        new OA\Property(property: "preferredCategories", description: "Array of preferred categories", type: "array", items: new OA\Items(ref: "#/components/schemas/Category")),
        new OA\Property(property: "preferredSources", description: "Array of preferred sources", type: "array", items: new OA\Items(ref: "#/components/schemas/Source")),
    ],
    type: "object"
)]
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function preferredCategories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'user_category');
    }

    public function preferredSources(): BelongsToMany
    {
        return $this->belongsToMany(Source::class, 'user_source');
    }

    public function preferredAuthors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'user_author');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
