<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JWTAuth;
use Noox\Models\User;

class ApiAuthTest extends TestCase
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
     * Test: POST /api/auth/login.
     */
    public function it_authenticate_a_user()
    {
        $user = factory(User::class)->create(['password' => bcrypt('foo')]);

        $this->post('/api/auth/login', ['email' => $user->email, 'password' => 'foo'])
        ->assertJsonStructure(['valid_until', 'refresh_before', 'token']);
    }

    /**
     * @test
     *
     * Test: POST /api/auth/auth_renew.
     */
    public function it_renew_a_token()
    {
        $user = factory(User::class)->create(['password' => bcrypt('foo')]);

        $this->get('/api/auth/renew_token', $this->headers($user))
        ->assertJsonStructure(['valid_until', 'refresh_before', 'token']);
    }

    /**
     * @test
     *
     * Test: POST /api/auth/logout.
     */
    public function it_invalidate_a_token()
    {
        $user = factory(User::class)->create(['password' => bcrypt('foo')]);

        $token = JWTAuth::fromUser($user);

        $this->post('/api/auth/logout', ['token' => $token])
        ->assertStatus(200);
    }
}
