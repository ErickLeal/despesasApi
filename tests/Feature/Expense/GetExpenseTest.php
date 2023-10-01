<?php

namespace Tests\Feature;

use App\Models\Expense;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Support\Str;

class GetExpenseTest extends TestCase
{
    use DatabaseMigrations;

    public function test_get_all_user_expenses_should_success()
    {

        $user = User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);

        Expense::factory(3)->create([
            'user_id' => $user->id
        ]);
       
        $response =  $this->actingAs($user, 'sanctum')->getJson('/api/expense');
        $response->assertStatus(200)
        ->assertJsonCount(3, 'expenses');
    }

    public function test_get_one_expenses_should_success()
    {

        $user = User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);

        $expense = Expense::factory()->create([
            'user_id' => $user->id
        ]);
       
        $response =  $this->actingAs($user, 'sanctum')->getJson('/api/expense/'.$expense->id);
        $response->assertStatus(200)
        ->assertJsonPath('expense.id',$expense->id);
        
    }

    public function test_get_one_expenses_should_fail_with_user_not_authorized()
    {

        $user = User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);

        $expense = Expense::factory()->create([
            'user_id' => $user->id
        ]);

        $user2 = User::factory()->create([
            'password' => '321',
            'email' => 'user2@email.com'
        ]);

        $response =  $this->actingAs($user2, 'sanctum')->getJson('/api/expense/'.$expense->id);

        $response->assertStatus(403);
    }

    public function test_get_one_expenses_should_fail_if_expense_not_found()
    {

        $user = User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);

        $response =  $this->actingAs($user, 'sanctum')->getJson('/api/expense/22');
        $response->assertStatus(404);
    }


}
