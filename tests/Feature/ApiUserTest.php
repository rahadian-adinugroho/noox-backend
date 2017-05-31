<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JWTAuth;
use Noox\Models\User;

class ApiUserTest extends TestCase
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
     * Test: POST /api/users.
     */
    public function it_register_user()
    {
        $data = ['email' => 'username@email.org', 'password' => 'testpass', 'name' => 'Test User'];

        $this->post('/api/users', $data)
        ->assertStatus(201);
    }

    /**
     * @test
     *
     * Test: POST /api/users.
     */
    public function it_returns_error_when_data_invalid()
    {
        $data = ['email' => 'username@email..org', 'password' => '12345', 'name' => 'Test User'];
        $this->post('/api/users', $data)
        ->assertJsonStructure([
            'errors' => [
                'email', 'password'
            ]
            ]);
    }

    /**
     * @test
     *
     * Test: POST /api/users.
     */
    public function it_422_when_email_already_used()
    {
        $data = ['email' => 'username@email.org', 'password' => 'testpass', 'name' => 'Test User'];
        $this->post('/api/users', $data);

        $this->post('/api/users', $data)
        ->assertStatus(422);
    }

    /**
     * @test
     * 
     * Test: PUT /api/personal
     */
    public function it_accepts_valid_profile_update()
    {
        $data = ['email' => 'username@email.org', 'name' => 'Test User', 'gender' => 'f'];
        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->put('/api/personal', $data, $this->headers($user))
        ->assertStatus(200);
    }

    /**
     * @test
     * 
     * Test: PUT /api/personal
     */
    public function it_rejects_invalid_profile_update()
    {
        $data = ['email' => 'aaaa', 'name' => 'Test User@', 'gender' => 'f'];
        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->put('/api/personal', $data, $this->headers($user))
        ->assertStatus(422);
    }

    /**
     * @test
     * 
     * Test: PUT /api/personal
     */
    public function it_accepts_password_update()
    {
        $data = ['oldpassword' => 'foo', 'newpassword' => 'mypass'];
        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->put('/api/personal/password', $data, $this->headers($user))
        ->assertStatus(200);
    }

    /**
     * @test
     * 
     * Test: PUT /api/personal
     */
    public function it_rejects_invalid_password_update()
    {
        $data = ['oldpassword' => 'bar', 'newpassword' => 'mypass'];
        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->put('/api/personal/password', $data, $this->headers($user))
        ->assertStatus(400);
    }

    /**
     * @test
     * 
     * Test: POST /api/personal/news_preferences
     */
    public function it_accepts_valid_user_preferences()
    {
        $this->seed('UserTableSeeder');
        $this->seed('NewsCategoryTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/personal/news_preferences', ['categories' => ['national', 'technology']], $this->headers($user))
        ->assertStatus(200);
    }

    /**
     * @test
     *
     * Test: GET /api/personal/news_preferences
     */
    public function it_returns_current_user_preferences()
    {
        $this->seed('UserTableSeeder');
        $this->seed('NewsCategoryTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->get('/api/personal/news_preferences', $this->headers($user))
        ->assertJsonStructure(['categories' => []]);
    }

    /**
     * @test
     *
     * Test: get /api/user/1.
     */
    public function it_fetch_user_details()
    {
        $this->seed('UserTableSeeder');
        $this->get('/api/user/1')
        ->assertJsonStructure([
            'data' => [
            'id'   ,
            'fb_id',
            'name' ,
            'level'   ,
            'member_since',
            'comments_count',
            ]
            ]);
    }

    /**
     * @test
     * 
     * Test: GET /api/personal.
     */
    public function it_fetch_personal_details()
    {
        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->get('/api/personal', $this->headers($user))
        ->assertJsonStructure([
            'data' => [
            'id',
            'name',
            'gender',
            'level',
            'experience',
            'member_since',
            'comments_count',
            'achievements_count',
            'liked_news_count',
            'latest_achievement',
            ]
            ]);
    }

    /**
     * @test
     * 
     * Test: GET /api/personal/achievements.
     */
    public function it_fetch_personal_achievements()
    {
        $this->seed('AchievementsTableSeeder');
        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $user->achievements()->attach([1 => ['earn_date' => \Carbon\Carbon::now()]]);
        $this->get('/api/personal/achievements', $this->headers($user))
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'key', 'title', 'description', 'earn_date'
                ]
            ]
        ]);
    }

    /**
     * @test
     * 
     * Test: GET /api/personal/achievements.
     */
    public function it_fetch_personal_news_comments()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/1/comment', ['content' => 'Nice news.'], $this->headers($user));

        $this->get('/api/personal/comments', $this->headers($user))
        ->assertJsonStructure([
            'data' => [
                'data' => [
                    '*' => [
                        'news_id',
                        'created_at',
                        'content',
                        'news' => ['id', 'title']
                    ]
                ]
            ]
        ]);
    }

    /**
     * @test
     * 
     * Test: GET /api/personal/achievements.
     */
    public function it_fetch_users_liked_news()
    {
        $this->seed('NewsCategoryTableSeeder');
        $this->seed('NewsSourceTableSeeder');
        $this->seed('NewsTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/news/1/like', [], $this->headers($user));

        $this->get('/api/personal/liked_news', $this->headers($user))
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'news_id',
                    'title',
                    'pubtime',
                    'source' => ['id', 'source_name', 'base_url']
                ]
            ],
            'meta'
        ]);
    }

    /**
     * @test
     *
     * Test: POST /api/user/3/report.
     */
    public function it_accepts_report_from_authenticated_user()
    {
        $this->seed('UserTableSeeder');
        $this->seed('ReportStatusesTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/user/3/report', ['content' => 'This person is a spammer.'], $this->headers($user))
        ->assertStatus(201);
    }

    /**
     * @test
     *
     * Test: POST /api/user/3/report.
     */
    public function it_rejects_report_from_unauthenticated_user()
    {
        $this->post('/api/user/3/report', ['content' => 'This person is a spammer.'], $this->headers())
        ->assertStatus(401);
    }

    /**
     * @test
     *
     * Test: POST /api/user/3/report.
     */
    public function it_rejects_deformed_report()
    {
        $this->seed('UserTableSeeder');
        $this->seed('ReportStatusesTableSeeder');

        $user = factory(User::class)->create(['password' => bcrypt('foo')]);
        $this->post('/api/user/3/report', ['content' => ''], $this->headers($user))
        ->assertStatus(422);
    }
}
