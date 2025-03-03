<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sourcesData = [
            ['external_id' => 'techcrunch', 'name' => 'TechCrunch'],
            ['external_id' => 'cnn', 'name' => 'CNN'],
            ['external_id' => 'business-insider', 'name' => 'Business Insider'],
            ['external_id' => null, 'name' => 'LADbible'],
        ];
        $sources = collect();
        foreach ($sourcesData as $data) {
            $sources->push(Source::updateOrCreate(
                ['name' => $data['name']],
                $data
            ));
        }

        // Create categories
        $categoriesData = [
            ['name' => 'Technology'],
            ['name' => 'Business'],
            ['name' => 'Sports'],
            ['name' => 'Entertainment'],
        ];
        $categories = collect();
        foreach ($categoriesData as $data) {
            $categories->push(Category::updateOrCreate(
                ['name' => $data['name']],
                $data
            ));
        }

        // Create authors
        $authorsData = [
            ['name' => "Sean O'kane"],
            ['name' => "Joshua Nair"],
            ['name' => "Rebecca Rommen"],
            ['name' => "Pierluigi Paganini"],
            ['name' => "Chris Isidore"],
        ];
        $authors = collect();
        foreach ($authorsData as $data) {
            $authors->push(Author::updateOrCreate(
                ['name' => $data['name']],
                $data
            ));
        }

        // Create 20 articles with random associations
        for ($i = 1; $i <= 20; $i++) {
            $article = Article::create([
                'source_id' => $sources->random()->id,
                'title' => "Sample Article Title $i",
                'description' => "This is a short description for article $i.",
                'content' => "This is the full content for article $i. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat.",
                'url' => "https://example.com/articles/$i",
                'url_to_image' => "https://example.com/images/article-$i.jpg",
                'published_at' => Carbon::now()->subDays(rand(0, 10)),
                'raw_response' => json_encode(['dummy' => 'data']),
            ]);

            // Attach one or two random categories
            $selectedCategoryIds = $categories->random(rand(1, 2))->pluck('id')->toArray();
            $article->categories()->attach($selectedCategoryIds);

            // Attach one or two random authors
            $selectedAuthorIds = $authors->random(rand(1, 2))->pluck('id')->toArray();
            $article->authors()->attach($selectedAuthorIds);
        }
    }
}
