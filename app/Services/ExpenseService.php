<?php

//namespace
namespace App\Services;

use Illuminate\Auth\Access\AuthorizationException;
use App\Exceptions\expense\ExpenseNotFoundException;

use App\Http\Resources\ExpenseCollection;
use App\Http\Resources\ExpenseResource;
use App\Repositories\ExpenseRepository;
use App\Notifications\ExpenseCreated;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ExpenseService
{

    private $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }


    public function create(String $description, String $date, Float $value): ExpenseResource
    {
        $user = Auth::user();
      
        $expense = $this->expenseRepository->createExpense($description, $date, $value, $user->id);

        $user->notify(new ExpenseCreated($expense));

        return new ExpenseResource($expense);
    }

    public function update(array $data, int $id): ExpenseResource
    {
        $expense = $this->getExpense($id);

        $expense = $this->expenseRepository->updateExpense($expense, $data);
        
        return new ExpenseResource($expense);
    }

    public function delete(int $id)
    {
        $expense = $this->getExpense($id);

        $this->expenseRepository->deleteExpense($expense);
    }

    public function getOneExpense(int $id): ExpenseResource
    {

        $expense = $this->getExpense($id);

        return new ExpenseResource($expense);
    }

    public function getAllUserExpenses(): ExpenseCollection
    {
        $user = Auth::user();
     
        $expenses = $this->expenseRepository->getAllUserExpenses($user);
       
        return new ExpenseCollection($expenses);
    }

    private function getExpense(int $id)
    {
     
        $expense = $this->expenseRepository->getExpenseById($id);
        
        if(!$expense){
            throw new ExpenseNotFoundException();
        }

        if (Gate::denies('expense-user', $expense)) {
            throw new AuthorizationException('This expense not belong to the user');
        }

        return $expense;
    }

   
}
