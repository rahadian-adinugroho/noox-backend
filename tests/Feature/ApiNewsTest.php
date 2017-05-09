<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     *
     * Test: POST /api/news/1.
     */
    public function it_fetch_news_details()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->get('/api/news/1')->assertJsonStructure([
            'news' => [
                'id', 'source' => ['id'], 'category' => ['name', 'id'], 'pubtime', 'author', 'content', 'likes_count', 'comments_count', 'comments' => []
            ]
            ]);
    }
}
