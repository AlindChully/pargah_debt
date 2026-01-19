<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Receipt;
use App\Models\Customer;
use Barryvdh\Snappy\Facades\SnappyPdf;

class DashboardController extends Controller
{
    public function index()
    {
        $paidTotal = Receipt::sum('amount');
        $Debts = Debt::get();
        $totalDebts = Debt::where('is_paid', false)->count();
        $customers = Customer::with(['debts', 'receipts'])->orderBy('name', 'asc')->get();

        return view('dashboard', compact('customers', 'totalDebts', 'paidTotal', 'Debts'));
    }

    public function print()
    {
        $customers = Customer::with(['debts', 'receipts'])->get();
        $paidTotal = Receipt::sum('amount');

        return view('dashboard_print', compact('customers', 'paidTotal'));
    }


    public function pdf()
    {
        $customers = Customer::with(['debts', 'receipts'])->get();
        $paidTotal = Receipt::sum('amount');

        return SnappyPdf::loadView(
            'dashboard_pdf',
            compact('customers', 'paidTotal')
        )
            ->setOption('encoding', 'UTF-8')
            ->setOption('enable-local-file-access', true)
            ->setOption('disable-smart-shrinking', true)
            ->setOption('print-media-type', true)
            ->setOption('no-background', false)
            ->download('ديون_زبائن.pdf');
    }
}
