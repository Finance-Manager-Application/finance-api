<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Factory; 

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful login.
     *
     * @return void
     */
    public function testSuccessfulLogin()
    {
        // Use the factory function from the Illuminate\Database\Eloquent\Factories namespace
        // $user = Factory::factoryForModel(User::class)->create([
        //     'email' => 'john.doe@example.com',
        //     'password' => bcrypt('password123'),
        // ]);

        $loginData = ['email' => 'john.doe@example.com', 'password' => 'password123'];

        $this->json('POST', 'user/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
               "user" => [
                   'id',
                   'name',
                   'email',
                   'email_verified_at',
                   'created_at',
                   'updated_at',
               ],
                "access_token",
                "message"
            ]);

        $this->assertAuthenticated();
    }
}
