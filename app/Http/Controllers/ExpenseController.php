<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Expense;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $category_id = $request->input('category');

        $query = Expense::with('category')->where('user_id', Auth::id());

        // $expenses = Expense::with('category')->where('user_id', Auth::id())->where('date_of_expense', '>=', $start_date)->where('date_of_expense', '<=', $end_date)->latest()->get();

     
        $query->where('date_of_expense', '>=', $start_date)->where('date_of_expense', '<=', $end_date);
    
        if ($category_id) {
            $query->where('category_id', $category_id);
        }
    
        $expenses = $query->orderBy('date_of_expense', 'desc')->get();

        return response()->json($expenses);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        try {
            Expense::create([
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'date_of_expense' => $request->date_of_expense,
            ]);

            return ['redirect' => route('dashboard')];
        } catch (\Exception $e) {
            Log::error('Error creating expense: ' . $e->getMessage());
            return response()->json(['error' => 'Error creating expense'], 500);
        }
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
    public function update(ExpenseRequest $request, Expense $expense)
    {
        if (Auth::id() !== $expense->user_id) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        try {
            $expense->update([
                'category_id' => $request->category_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'date_of_expense' => $request->date_of_expense,
            ]);

            return ['redirect' => route('dashboard')];

        } catch (\Exception $e) {
            Log::error('Error updating expense: ' . $e->getMessage());
            return response()->json(['error' => 'Error updating expense'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        if (Auth::id() !== $expense->user_id) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        
        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully'], 200);
    }
}
