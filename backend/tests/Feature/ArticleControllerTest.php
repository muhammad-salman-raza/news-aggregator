<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchArticles()
    {
        $response = $this->getJson('/api/v1/articles?keyword=sample');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => []]);
    }

    public function testGetUserArticles()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->getJson('/api/v1/user/articles');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => []]);
    }
} 