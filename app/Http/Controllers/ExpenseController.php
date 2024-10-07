<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('categories')->paginate(10);
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'installments' => 'nullable|integer|min:1|max:24',
            'categories' => 'required|array',
        ]);

        $expense = Expense::create([
            'expense_date' => $validated['expense_date'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'installments' => $validated['installments'] ?? 1,
            'user_id' => auth()->id(),
        ]);

        $expense->categories()->attach($validated['categories']);

        // Criar a transação
        Transaction::create([
            'transaction_date' => $validated['expense_date'],
            'amount' => $validated['amount'],
            'transaction_type' => 'exit',
            'payment_method' => $validated['payment_method'],
            'installments' => $validated['installments'] ?? 1,
            'payment_fee' => 0,
            'cart_id' => null,
            'observations' => null,
            'expense_id' => $expense->id,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        $categories = Category::all();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'installments' => 'nullable|integer|min:1|max:24',
            'categories' => 'required|array',
        ]);

        $expense->update([
            'expense_date' => $validated['expense_date'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'installments' => $validated['installments'] ?? 1,
        ]);

        $expense->categories()->sync($validated['categories']);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);

        // Deletar a transação associada
        $transaction = Transaction::where('expense_id', $expense->id)->first();
        if ($transaction) {
            $transaction->delete();
        }

        // Deletar a despesa
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense and associated transaction deleted successfully.');
    }
}
