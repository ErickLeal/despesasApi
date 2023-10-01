<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Support\Str;

class CreateExpenseTest extends TestCase
{
    use DatabaseMigrations;

    public function test_create_expense_should_success(){
        
        $user = User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);

        $response =  $this->actingAs($user, 'sanctum')->postJson('/api/expense', [
            'description' => 'gas',
            'date' => now()->addDays(2)->format("Y-m-d H:i:s"),
            'value' => 150.30
        ]);

        $response->assertOk();

        $response = $response->json();

        $this->assertDatabaseHas('expenses', [
            'id' => $response['expense']['id']
        ]);
    }

    
    public function test_create_expense_should_fail_without_required_fields(){

        $user = User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);

        $response =  $this->actingAs($user, 'sanctum')->postJson('/api/expense', ['']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['description', 'date', 'value']);
    }

    /**
     * @dataProvider provider_create_expense_invalid_request
     */
    public function test_create_expense_should_fail_with_incorrect_request(
        String $errorField,
        String $description,
        String $date,
        Float $value
     ){

        $user = User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);

        $response =  $this->actingAs($user, 'sanctum')->postJson('/api/expense', [
            'description' => $description,
            'date' => $date,
            'value' => $value
        ]);
       
        $response->assertStatus(422)
        ->assertJsonCount(1, "errors.$errorField");
    }

    public static function provider_create_expense_invalid_request()
    {

        return [
            ['description', Str::random(192), now()->addDays(2)->format("Y-m-d H:i:s"), 150.35],
            ['date', 'gas', now()->subDays(2)->format("Y-m-d H:i:s"), 150.35],
            ['value','gas', now()->addDays(2)->format("Y-m-d H:i:s"), 0]
        ];
    }
  

}
