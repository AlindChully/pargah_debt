<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;


class CustomerController extends Controller
{
    public function show(Customer $customer, Request $request)
    {
        $debtsQuery = $customer->debts()
            ->when($request->status, function ($q) use ($request) {
                if ($request->status === 'paid') $q->where('is_paid', true);
                elseif ($request->status === 'unpaid') $q->where('is_paid', false);
            })
            ->when($request->from_date, fn($q) => $q->whereDate('debt_date', '>=', $request->from_date))
            ->when($request->to_date, fn($q) => $q->whereDate('debt_date', '<=', $request->to_date))
            ->when($request->min_qty, fn($q) => $q->where('quantity', '>=', $request->min_qty));

        // ترتيب: Debts غير مدفوعة أولًا، حسب الأقدمية، ثم الديون المدفوعة
        $debtsQuery->orderByRaw('is_paid ASC, debt_date ASC');

        $debts = $debtsQuery->get();
        $paidTotal = $customer->receipts()->sum('amount');

        // نتائج بعد وصل قبض
        $totalDebts = $customer->debts()->sum('amount');
        $balance = $customer->balance->balance ?? 0;
        $realDebt = $totalDebts - $balance;

        return view('customers.show', compact(
            'customer',
            'debts',
            'paidTotal',
            'totalDebts',
            'balance',
            'realDebt'
        ));
    }

    // صفحة الفورم
    public function create()
    {
        return view('customers.create');
    }

    // حفظ الشخص الجديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer = Customer::create($request->only('name', 'phone'));

        return redirect()->route('dashboard')->with('success', 'Customer added successfully!');
    }

    public function addDebt(Request $request, Customer $customer)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'debt_date' => 'required|date',
        ]);

        $customer->debts()->create([
            'amount' => $request->amount,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'notes' => $request->notes,
            'debt_date' => $request->debt_date,
            'is_paid' => $request->amount > 0 ? false : true,
        ]);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Debt added successfully!');
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($request->only('name', 'phone'));

        return redirect()->back()->with('success', 'Customer updated successfully');
    }
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->back()->with('success', 'Customer deleted successfully');
    }

    public function print(Customer $customer, Request $request)
    {
        $debts = $customer->debts()
            ->where('is_paid', false)
            ->when($request->from_date, fn($q) => $q->whereDate('debt_date', '>=', $request->from_date))
            ->when($request->to_date, fn($q) => $q->whereDate('debt_date', '<=', $request->to_date))
            ->orderBy('debt_date', 'desc')
            ->get();

        $totalCount = $debts->count();
        $totalAmount = $debts->sum('amount');

        return view('customers.print', compact(
            'customer',
            'debts',
            'totalCount',
            'totalAmount'
        ));
    }

    public function pdf(Customer $customer)
    {
        $debts = $customer->debts()->where('is_paid', false)->get();

        return Pdf::loadView('customers.pdf', compact('customer', 'debts'))
            ->setOption('encoding', 'UTF-8')
            ->setOption('enable-local-file-access', true)
            ->setOption('print-media-type', true)
            ->setOption('disable-smart-shrinking', true)
            ->download('debts_' . $customer->name . '.pdf');
    }
}
