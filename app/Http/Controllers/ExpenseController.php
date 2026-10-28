<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Budget;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('budget')
            ->latest('expense_date')
            ->paginate(10);

        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $budgets = Budget::orderBy('name')->get();

        return view('expenses.create', compact('budgets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'budget_id' => 'nullable|exists:budgets,id',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
        ]);

        Expense::create($validated);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load('budget');

        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $budgets = Budget::orderBy('name')->get();

        return view('expenses.edit', compact('expense', 'budgets'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'budget_id' => 'nullable|exists:budgets,id',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
        ]);

        $expense->update($validated);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
