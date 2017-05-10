<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JWTAuth;
use Noox\Models\User;

class NewsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Return request headers needed to interact with the API.
     *
     * @return Array array of headers.
     */
    protected function headers($user = null)
    {
        $headers = ['Accept' => 'application/json'];

        if (!is_null($user)) {
            $token = JWTAuth::fromUser($user);
            JWTAuth::setToken($token);
            $headers['Authorization'] = 'Bearer '.$token;
        }

        return $headers;
    }

    /**
     * @test
     *
     * Test: get /api/news/1.
     */
    public function it_fetch_news_details()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->get('/api/news/1')->assertJsonStructure([
            'data' => [
                'id',
                'source' => [
                    'id'
                ],
                'category' => [
                    'name',
                    'id'
                ],
                'pubtime',
                'author',
                'content',
                'likes_count',
                'comments_count',
            ]
            ]);
    }

     /**
     * not compatible
     *
     * Test: get /api/news/1.
     */
    public function it_fetch_news_details_with_likes_if_user_logged_in()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->get('/api/news/1', $this->headers($user))
        ->assertJsonStructure([
            'data' => [
                'id',
                'source' => [
                    'id'
                ],
                'category' => [
                    'name',
                    'id'
                ],
                'likes' => [],
                'pubtime',
                'author',
                'content',
                'likes_count',
                'comments_count',
                'comments' => []
            ]
            ]);
    }

    /**
     * Disabled due to incompatibility of Ngroup traits with sqlite.
     *
     * Test: get /api/news/1/comments.
     */
    public function if_fetch_news_comments()
    {
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->seed('UserTableSeeder');
        $this->seed('NewsCommentTableSeeder');

        $this->get('/api/news/1/comments')
        ->assertJsonStructure([
            'data' => [
                'total',
                'current_page',
                'per_page',
                'data' => 
                    ['*' 
                        => ['id',
                            'user_id',
                            'created_at',
                            'content',
                            'replies_count',
                            'likes_count',
                            'latest_replies',
                            'author' => ['id', 'name']]
                    ]
            ]
            ]);
    }

    /**
     * @test
     *
     * Test: GET /api/news_comment/1.
     */
    public function it_fetch_a_comment_with_replies()
    {
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->seed('UserTableSeeder');
        $this->seed('NewsCommentTableSeeder');

        $this->get('/api/news_comment/1')
        ->assertJsonStructure([
            'comment' => [
                'id',
                'author',
                'created_at',
                'likes_count',
                'content'], 
            'replies' => [
                'total',
                'current_page',
                'next_page_url',
                'prev_page_url',
                'per_page',
                'data' => [
                    '*' => [
                        'id',
                        'author',
                        'created_at',
                        'likes_count',
                        'content',
                    ]
                ],
            ]
        ]);
    }

    /**
     * not compatible
     *
     * Test: GET /api/news_comment/1.
     */
    public function it_fetch_a_comment_with_replies_with_likes_if_user_logged_in()
    {
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->seed('UserTableSeeder');
        $this->seed('NewsCommentTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->get('/api/news_comment/1', $this->headers($user))
        ->assertJsonStructure([
            'comment' => [
                'id',
                'author',
                'created_at',
                'likes',
                'likes_count',
                'content'], 
            'replies' => [
                'total',
                'current_page',
                'next_page_url',
                'prev_page_url',
                'per_page',
                'data' => [
                    '*' => [
                        'id',
                        'author',
                        'created_at',
                        'likes',
                        'likes_count',
                        'content',
                    ]
                ],
            ]
        ]);
    }

    /**
     * @test
     *
     * Test: POST /api/news/1/comment.
     */
    public function it_rejects_unauthenticated_user_comment()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');

        $this->post('/api/news/1/comment', ['content' => 'Nice news.'], $this->headers())
        ->assertStatus(401);
    }

    /**
     * @test
     *
     * Test: POST /api/news/1/comment.
     */
    public function it_accepts_authenticated_user_comment()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/1/comment', ['content' => 'Nice news.'], $this->headers($user))
        ->assertStatus(201);
    }

    /**
     * @test
     *
     * Test: POST /api/news/1/comment.
     */
    public function it_rejects_deformed_user_comment()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/1/comment', ['content' => ''], $this->headers($user))
        ->assertStatus(422);
    }
}
