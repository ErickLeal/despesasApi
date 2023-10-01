<?php

namespace Tests\Feature;

use App\Models\Expense;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Support\Str;

class UpdateExpenseTest extends TestCase
{
    use DatabaseMigrations;

    public function test_update_expense_should_success()
    {

        $user = User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);

        $expense = Expense::factory()->create([
            'user_id' => $user->id
        ]);

        $response =  $this->actingAs($user, 'sanctum')->putJson('/api/expense/' . $expense->id, [
            'description' => 'gas',
            'date' => now()->addDays(2)->format("Y-m-d H:i:s"),
            'value' => 200.00
        ]);

        $response->assertOk();

        $response = $response->json();

        $this->assertDatabaseHas('expenses', [
            'id' => $response['expense']['id'],
            'description' => 'gas',
            'value' => 200
        ]);
    }

    public function test_update_expense_should_fail_if_expense_not_found()
    {

        $user = User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);

        $response =  $this->actingAs($user, 'sanctum')->putJson('/api/expense/2', [
            'description' => 'gas',
            'date' => now()->addDays(2)->format("Y-m-d H:i:s"),
            'value' => 200.00
        ]);

        $response->assertStatus(404);
    }

    public function test_update_expense_should_fail_with_user_not_authenticated()
    {

        $user = User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);
        $expense = Expense::factory()->create([
            'user_id' => $user->id
        ]);

        $response =  $this->putJson('/api/expense/' . $expense->id, [
            'description' => 'gas',
            'date' => now()->addDays(2)->format("Y-m-d H:i:s"),
            'value' => 200.00
        ]);

        $response->assertStatus(403);
    }

    public function test_update_expense_should_fail_with_user_not_authorized()
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

        $response =  $this->actingAs($user2, 'sanctum')->putJson('/api/expense/' . $expense->id, [
            'description' => 'gas',
            'date' => now()->addDays(2)->format("Y-m-d H:i:s"),
            'value' => 200.00
        ]);

        $response->assertStatus(403);
    }

    /**
     * @dataProvider provider_update_expense_invalid_request
     */
    public function test_update_expense_should_fail_with_incorrect_request(
        String $errorField,
        String $description,
        String $date,
        Float $value
    ) {

        $user = User::factory()->create([
            'password' => '123',
            'email' => 'user@email.com'
        ]);

        $expense = Expense::factory()->create([
            'user_id' => $user->id
        ]);

        $response =  $this->actingAs($user, 'sanctum')->putJson('/api/expense/' . $expense->id, [
            'description' => $description,
            'date' => $date,
            'value' => $value
        ]);

        $response->assertStatus(422)
            ->assertJsonCount(1, "errors.$errorField");
    }

    public static function provider_update_expense_invalid_request()
    {

        return [
            ['description', Str::random(192), now()->addDays(2)->format("Y-m-d H:i:s"), 150.35],
            ['date', 'gas', now()->subDays(2)->format("Y-m-d H:i:s"), 150.35],
            ['value', 'gas', now()->addDays(2)->format("Y-m-d H:i:s"), 0]
        ];
    }
}
