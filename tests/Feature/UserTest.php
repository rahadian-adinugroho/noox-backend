<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Noox\Models\User;

use JWTAuth;

class UserTest extends TestCase
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
}
