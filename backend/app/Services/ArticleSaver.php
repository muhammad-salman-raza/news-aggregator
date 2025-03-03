<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ArticleDTO;
use App\DTO\SourceDTO;
use App\Models\Article;
use App\Models\Source;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ArticleSaver
{
    /**
     * @param Collection<ArticleDTO> $articleDTOs
     * @return void
     */
    public function saveArticles(Collection $articleDTOs): void
    {
        foreach ($articleDTOs as $articleDTO) {
            $this->saveArticle($articleDTO);
        }
    }

    private function saveArticle(ArticleDTO $articleDTO): void
    {
        $source = $this->saveSource($articleDTO->source);

        $article = Article::create([
            'id' => Str::uuid(),
            'source_id' => $source->id,
            'title' => $articleDTO->title,
            'description' => $articleDTO->description,
            'content' => $articleDTO->content,
            'url' => $articleDTO->url,
            'url_to_image' => $articleDTO->urlToImage,
            'published_at' => $articleDTO->publishedAt,
            'raw_response' => $articleDTO->rawResponse,
        ]);

        // Save categories
        $this->saveCategories($articleDTO->categoryNames, $article);

        // Save authors
        $this->saveAuthors($articleDTO->authorNames, $article);
    }

    private function saveSource(SourceDTO $sourceDTO): Source
    {
        return Source::firstOrCreate(
            ['external_id' => $sourceDTO->externalId],
            ['name' => $sourceDTO->name]
        );
    }

    private function saveCategories(array $categoryNames, Article $article): void
    {
        foreach ($categoryNames as $categoryName) {
            $category = Category::firstOrCreate(
                ['name' => $categoryName]
            );
            $article->categories()->attach($category->id);
        }
    }

    private function saveAuthors(array $authorNames, Article $article): void
    {
        foreach ($authorNames as $authorName) {
            $author = Author::firstOrCreate(
                ['name' => $authorName]
            );
            $article->authors()->attach($author->id);
        }
    }
}
