<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expense.add-expense');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|integer',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'date_of_expense' => 'required|date',
        ]);

         Expense::create([
            'user_id' => Auth::id(),
            'category_id' => $validatedData['category_id'],
            'amount' => $validatedData['amount'],
            'description' => $validatedData['description'],
            'date_of_expense' => $validatedData['date_of_expense'],
        ]);

        return ['redirect' => route('dashboard')];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
