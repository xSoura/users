<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function test_users_index()
    {
        $users = User::factory()->count(5)->create();
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users');

        $response->assertOk()
            ->assertJson($users->toArray());
    }

    /**
     * @test
     */
    public function a_user_can_be_created()
    {
        $data = [
            "name" => "Carlos de la Cruz",
            "email" => "carlosmorales.sistemas@gmail.com",
            "password" => '123456'
        ];

        $this->postJson('/api/users', $data)
            ->assertStatus(201)
            ->assertJson([
                "message" => "User created successfully"
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_be_retrieved()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJson([
                "name" => $user->name,
                "email" => $user->email
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_be_updated()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/users/" . $user->id, [
            "name" => "Eduardo",
            "email" => "eduardo@gmail.com",
            "password" => '987655'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     "message" => "User Updated"
                 ]);
    }

    /**
     * @test
     */
    public function a_user_can_be_deleted()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/users/" . $user->id);

        $response->assertStatus(200)
                 ->assertJson([
                     "message" => "User deleted"
                 ]);
    }
}
