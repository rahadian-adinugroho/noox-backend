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
            'name' ,
            'level'   ,
            'member_since',
            'comments_count'  ,
            'comments',
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
            'level',
            'experience',
            'member_since',
            'comments_count',
            'achievements_count',
            'news_likes_count',
            'latest_achievement',
            'comments',
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
