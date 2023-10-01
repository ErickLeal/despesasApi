<?php

namespace Tests\Feature;

use App\Models\Expense;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Support\Str;

class DeleteExpenseTest extends TestCase
{
    use DatabaseMigrations;

    public function test_delete_expenses_should_success()
    {

        $user = User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);

        $expense = Expense::factory()->create([
            'user_id' => $user->id
        ]);
       
        $response =  $this->actingAs($user, 'sanctum')->deleteJson('/api/expense/'.$expense->id);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id
        ]);
        
    }

    public function test_delete_expense_should_fail_with_user_not_authorized()
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
       
        $response =  $this->actingAs($user2, 'sanctum')->deleteJson('/api/expense/'.$expense->id);

        $response->assertStatus(403);
    }

}
