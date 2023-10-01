<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($expense) {
            return [
                'expense_id' =>  $expense->id,
                'description' => $expense->description,
                'date' => $expense->date,
                'value' => 'R$ ' . number_format($expense->value, 2, ',', '.')
            ];
        })->all();
    }
}