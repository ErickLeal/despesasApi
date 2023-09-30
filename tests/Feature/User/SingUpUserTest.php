<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Models\User;
use Tests\TestCase;

class SingUpUserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_singup_user_should_fail_without_required_fields(){

        $response =  $this->postJson('/api/users/singup', ['']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_singup_user_should_success(){

        $response =  $this->postJson('/api/users/singup', [
            'name' => 'User Test',
            'email' => 'user@email.com',
            'password' => 'usertest'
        ]);
        $response->assertOk();

        $this->assertDatabaseHas('users', [
            'name' => 'User Test',
            'email' => 'user@email.com'
        ]);
    }

}
