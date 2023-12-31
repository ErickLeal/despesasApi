<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Models\User;
use Tests\TestCase;

class SingInUserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_singin_user_should_fail_without_required_fields()
    {

        $response =  $this->postJson('/api/users/singin', ['']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_singin_user_should_success()
    {

        User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);

        $response =  $this->postJson('/api/users/singin', [
            'email' => 'user@email.com',
            'password' => '123'
        ]);
       
        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'user' => [
                    'name',
                    'email',
                    'token'
                ],
            ]);
    }

    /**
     * @dataProvider provider_singin_user_incorrect_credentials
     */
    public function test_singin_user_should_fail_with_incorrect_credentials($email, $password)
    {
        User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);

        $response =  $this->postJson('/api/users/singin', [
            'email' => $email,
            'password' => $password
        ]);
     
        $response->assertStatus(401);
    }

    public static function provider_singin_user_incorrect_credentials()
    {

        return [
            ['user@email.com', 'error'],
            ['error@email.com', '123'],
        ];
    }

}
