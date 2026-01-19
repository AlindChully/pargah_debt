<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function edit(Debt $debt)
    {
        return view('debts.edit', compact('debt'));
    }

    public function update(Request $request, Debt $debt)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'debt_date' => 'required|date',
        ]);

        $debt->update([
            'amount' => $request->amount,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'notes' => $request->notes,
            'debt_date' => $request->debt_date,
            'is_paid' => $request->amount > 0 ? false : true,
        ]);

        return redirect()->route('customers.show', $debt->customer_id)
            ->with('success', 'Debt updated successfully!');
    }


    public function destroy(Debt $debt)
    {
        $customer_id = $debt->customer_id;
        $debt->delete();

        return redirect()->route('customers.show', $customer_id)
            ->with('success', 'Debt deleted successfully!');
    }

    public function togglePaid(Debt $debt)
    {
        $debt->is_paid = !$debt->is_paid;
        $debt->save();

        return redirect()->route('customers.show', $debt->customer_id)
            ->with('success', 'Debt status updated!');
    }
}