<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Services\ExpenseService;

use App\Exceptions\expense\ExpenseNotFoundException;
use Exception;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    private $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function store(StoreExpenseRequest $request)
    {

        $data = $request->validated();
        try {

            $expense = $this->expenseService->create(
                $data['description'],
                $data['date'],
                $data['value']
            );
        } catch (Exception $e) {

            return response()->json([
                'message' => 'Unexpected error',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Successfully registered expense',
            'expense' => $expense,
        ], 200);
    }

    public function update(UpdateExpenseRequest $request, string $id)
    {
        $data = $request->validated();
        try {

            $expense = $this->expenseService->update($data, intval($id));
        } catch (ExpenseNotFoundException $e) {

            return response()->json([
                'message' => 'Expense not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Successfully updated expense',
            'expense' => $expense,
        ], 200);
    }

    public function index()
    {
        try {
            $expenses = $this->expenseService->getAllUserExpenses();
        } catch (Exception $e) {

            return response()->json([
                'message' => 'Unexpected error',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Success',
            'expenses' => $expenses,
        ], 200);
    }

    public function show(string $id)
    {
        try {

            $expense = $this->expenseService->getOneExpense(intval($id));
        } catch (ExpenseNotFoundException $e) {

            return response()->json([
                'message' => 'Expense not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Success',
            'expense' => $expense,
        ], 200);
    }
   
    public function destroy(string $id)
    {
        try {

            $this->expenseService->delete(intval($id));
        } catch (ExpenseNotFoundException $e) {

            return response()->json([
                'message' => 'Expense not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Success'
        ], 200);
    }
}
