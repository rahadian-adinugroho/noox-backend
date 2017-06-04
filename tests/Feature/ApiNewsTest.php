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
     * Test: get /api/news/top_news
     */
    public function it_fetch_the_list_of_top_news()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->get('/api/news/top_news')->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'pubtime',
                    'readers_count',
                    'category',
                    'source'
                ]
            ]
            ]);
    }

    /**
     * @test
     *
     * GET /api/news/search
     */
    public function it_search_news_by_query()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');

        $this->get('/api/news/search?q=a&category=national')->assertJsonStructure([
            'data' => [
                'total',
                'current_page',
                'next_page_url',
                'prev_page_url',
                'per_page',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'pubtime',
                        'readers_count',
                        'comments_count',
                        'category',
                        'source'
                    ]
                ]
            ]
            ]);
    }

    /**
     * @test
     *
     * GET /api/news/search
     */
    public function it_422_when_no_query_supplied()
    {
        $this->get('/api/news/search?q=&category=national')
        ->assertStatus(422);
    }

    /**
     * @test
     *
     * GET /api/news/category/{cateory}
     */
    public function it_fetch_news_by_category()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');

        $this->get('/api/news/category/national')->assertJsonStructure([
            'data' => [
                'total',
                'current_page',
                'next_page_url',
                'prev_page_url',
                'per_page',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'pubtime',
                        'readers_count',
                        'comments_count',
                        'category',
                        'source'
                    ]
                ]
            ]
            ]);
    }

    /**
     * @test
     *
     * GET /api/news/search
     */
    public function it_400_when_no_category_not_exist()
    {
        $this->get('/api/news/category/notexist')
        ->assertStatus(400);
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
                'likers_count',
                'comments_count',
            ]
            ]);
    }

    /**
     * @test
     * 
     * Test: POST /api/news/1/like
     * Test: DELETE /api/news/1/like
     */
    public function it_accepts_and_deletes_user_like()
    {
        $this->seed('UserTableSeeder');
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/1/like', [], $this->headers($user))
        ->assertStatus(200);

        $this->delete('/api/news/1/like', [], $this->headers($user))
        ->assertStatus(204);
    }

    /**
     * @test
     *
     * Test: POST /api/news/1/like
     */
    public function it_rejects_duplicate_user_like()
    {
        $this->seed('UserTableSeeder');
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/1/like', [], $this->headers($user));

        $this->post('/api/news/1/like', [], $this->headers($user))
        ->assertStatus(400);
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
                'likers_count',
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
                            'likers_count',
                            'latest_replies',
                            'author' => ['id', 'fb_id', 'name']]
                    ]
            ]
            ]);
    }

    /**
     * @test
     *
     * Test: GET /api/news_comment/1.
     */
    public function it_fetch_a_comment_details()
    {
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->seed('UserTableSeeder');
        $this->seed('NewsCommentTableSeeder');

        $this->get('/api/news/comment/1')
        ->assertJsonStructure([
            'data' => [
                'id',
                'author' => ['id', 'fb_id', 'name'],
                'created_at',
                'replies_count',
                'likers_count',
                'content'], 
        ]);
    }

    /**
     * @test
     *
     * Test: GET /api/news_comment/1.
     */
    public function it_fetch_comment_replies()
    {
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->seed('UserTableSeeder');
        $this->seed('NewsCommentTableSeeder');

        $this->get('/api/news/comment/1/replies')
        ->assertJsonStructure([
            'data' => [
                'total',
                'current_page',
                'next_page_url',
                'prev_page_url',
                'per_page',
                'data' => [
                    '*' => [
                        'id',
                        'author' => ['id', 'fb_id', 'name'],
                        'created_at',
                        'likers_count',
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
        $this->get('/api/news/comment/1', $this->headers($user))
        ->assertJsonStructure([
            'data' => [
                'id',
                'author' => ['id', 'fb_id', 'name'],
                'created_at',
                'likers',
                'likers_count',
                'content'], 
        ]);
    }

    /**
     * @test
     *
     * Test: POST /api/news/1/comment.
     */
    public function it_rejects_unauthenticated_user_comment()
    {
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
        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/1/comment', ['content' => ''], $this->headers($user))
        ->assertStatus(422);
    }

    /**
     * @test
     *
     * Test: POST /api/news/comment/1/reply.
     */
    public function it_rejects_unauthenticated_user_comment_reply()
    {
        $this->post('/api/news/comment/1/reply', ['content' => 'I agree.'], $this->headers())
        ->assertStatus(401);
    }

    /**
     * @test
     *
     * Test: POST /api/news/comment/1/reply.
     */
    public function it_accepts_authenticated_user_comment_reply()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->seed('UserTableSeeder');
        $this->seed('NewsCommentTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/comment/1/reply', ['content' => 'I agree.'], $this->headers($user))
        ->assertStatus(201);
    }

    /**
     * @test
     *
     * Test: POST /api/news/comment/1/reply.
     */
    public function it_rejects_deformed_user_comment_reply()
    {
        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/comment/1/reply', ['content' => ''], $this->headers($user))
        ->assertStatus(422);
    }

    /**
     * @test
     *
     * Test: POST /api/news/comment/1/like.
     * Test: DELETE /api/news/comment/1/like.
     */
    public function it_accepts_and_deletes_user_comment_like()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->seed('UserTableSeeder');
        $this->seed('NewsCommentTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/comment/1/like', [], $this->headers($user))
        ->assertStatus(200);

        $this->delete('/api/news/comment/1/like', [], $this->headers($user))
        ->assertStatus(204);
    }

    /**
     * @test
     *
     * Test: POST /api/news/comment/1/like.
     */
    public function it_rejects_duplicate_user_comment_like()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->seed('UserTableSeeder');
        $this->seed('NewsCommentTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/comment/1/like', [], $this->headers($user));

        $this->post('/api/news/comment/1/like', [], $this->headers($user))
        ->assertStatus(400);
    }

    /**
     * @test
     *
     * Test: POST /api/news/comment/1/reply.
     */
    public function it_rejects_reply_if_comment_not_found()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->seed('UserTableSeeder');
        $this->seed('NewsCommentTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/comment/20/reply', ['content' => 'I agree.'], $this->headers($user))
        ->assertStatus(422);
    }

    /**
     * @test
     *
     * Test: POST /api/news/comment/1/reply.
     */
    public function it_rejects_reply_of_reply()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->seed('UserTableSeeder');
        $this->seed('NewsCommentTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/comment/2/reply', ['content' => 'I also agree.'], $this->headers($user))
        ->assertStatus(422);
    }

    /**
     * @test
     *
     * Test: POST /api/news/1/report.
     */
    public function it_accepts_report_from_authenticated_user()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->seed('ReportStatusesTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/1/report', ['content' => 'This news is turns out to be a hoax.'], $this->headers($user))
        ->assertStatus(201);
    }

    /**
     * @test
     *
     * Test: POST /api/news/1/report.
     */
    public function it_rejects_report_from_unauthenticated_user()
    {
        $this->post('/api/news/1/report', ['content' => 'This news is turns out to be a hoax.'], $this->headers())
        ->assertStatus(401);
    }

    /**
     * @test
     *
     * Test: POST /api/news/1/report.
     */
    public function it_rejects_deformed_user_report()
    {
        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/1/report', ['content' => ''], $this->headers($user))
        ->assertStatus(422);
    }

    /**
     * @test
     *
     * Test: POST /api/news/1/report.
     */
    public function it_accepts_comment_report_from_authenticated_user()
    {
        $this->seed('UserTableSeeder');
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');
        $this->seed('ReportStatusesTableSeeder');
        $this->seed('NewsCommentTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/comment/1/report', ['content' => 'This comment is offensive.'], $this->headers($user))
        ->assertStatus(201);
    }
}
