<?php

//namespace
namespace App\Repositories;

use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;

class ExpenseRepository
{

    public function createExpense(String $description, string $date, Float $value, Int $userId)
    {
        return Expense::create([
            'description' => $description,
            'date' => Carbon::parse($date),
            'value' => $value,
            'user_id' => $userId
        ]);
    }

    public function updateExpense(Expense $expense, array $data)
    {
        if(isset($data['description'])){
            $expense->description = $data['description'];
        }
        if(isset($data['value'])){
            $expense->value = floatval($data['value']);
        }
        if(isset($data['date'])){
            $expense->date = Carbon::parse($data['date']);
        }

        $expense->save();

        return $expense;
    }

    public function deleteExpense(Expense $expense): bool
    {
        return $expense->delete();
    }

    public function getExpenseById(Int $id)
    {
        return Expense::where('id', $id)->first();
    }

    public function getAllUserExpenses(User $user)
    {
        return $user->expenses;
    }
}
