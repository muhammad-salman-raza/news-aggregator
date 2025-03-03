<?php

namespace Tests\Unit;

use App\Services\NewsApiSource;
use Illuminate\Support\Collection;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class NewsApiSourceTest extends TestCase
{
    private NewsApiSource $newsApiSource;

    protected function setUp(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'status' => 'ok',
                'totalResults' => 260,
                'articles' => [
                    [
                        'source' => ['id' => 'cbs-news', 'name' => 'CBS News'],
                        'author' => 'Christa Swanson',
                        'title' => 'Protesters gather at Colorado\'s Rocky Mountain National Park as part of nationwide "Protect Your Parks Protest"',
                        'description' => 'Hundreds of protesters gathered in Rocky Mountain National Park on Saturday to oppose the recent firings of approximately 1,000 National Park Service employees nationwide.',
                        'url' => 'https://www.cbsnews.com/colorado/news/colorado-rocky-mountain-national-park-protect-your-parks-protest/',
                        'urlToImage' => 'https://assets2.cbsnewsstatic.com/hub/i/r/2025/03/02/71c982bf-b961-4e5d-ba2b-4517ce6830c2/thumbnail/1200x630/fd4f10bf248a0d3010a531019c954f7c/480885846-10234207239543165-1124407332542235059-n.jpg?v=8de240724d7f6d8b5f54f62bb158c012',
                        'publishedAt' => '2025-03-01T23:45:00Z',
                        'content' => 'Hundreds of protesters gathered in Rocky Mountain National Park on Saturday to oppose the recent firings of approximately 1,000 National Park Service and over 3,000 U.S. Forest Service employees nati… [+2685 chars]'
                    ]
                ]
            ]))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $this->newsApiSource = new NewsApiSource(
            apiKey: 'test_api_key',
            apiUrl: 'https://newsapi.org/v2/everything',
            sources: 'cbs-news',
            pageSize: 100
        );

        // Inject the mocked client into the NewsApiSource instance
        $reflection = new \ReflectionClass($this->newsApiSource);
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setAccessible(true);
        $clientProperty->setValue($this->newsApiSource, $client);
    }

    public function testCanHandle(): void
    {
        $this->assertTrue($this->newsApiSource->canHandle('newsapi'));
        $this->assertFalse($this->newsApiSource->canHandle('otherapi'));
    }

    public function testFetchArticles(): void
    {
        $articles = $this->newsApiSource->fetchArticles();

        $this->assertInstanceOf(Collection::class, $articles);
        $this->assertCount(1, $articles);

        $article = $articles->first();
        $this->assertEquals('Protesters gather at Colorado\'s Rocky Mountain National Park as part of nationwide "Protect Your Parks Protest"', $article->title);
        $this->assertEquals('Christa Swanson', $article->authorNames[0]);
        $this->assertEquals('CBS News', $article->source->name);
    }
}
