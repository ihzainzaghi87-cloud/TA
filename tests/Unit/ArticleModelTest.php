<?php

namespace Tests\Unit;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test article can be created
     */
    public function test_article_can_be_created(): void
    {
        $article = Article::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'This is a test article content',
        ]);

        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
            'slug' => 'test-article',
        ]);
    }

    /**
     * Test article is_published is cast to boolean
     */
    public function test_is_published_is_cast_to_boolean(): void
    {
        $article = Article::create([
            'title' => 'Test Article',
            'slug' => 'test-article-2',
            'content' => 'Content',
            'is_published' => 1,
        ]);

        $this->assertIsBool($article->is_published);
        $this->assertTrue($article->is_published);
    }
}
