<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Models\Debt;
use App\Models\Receipt;
use App\Models\Customer;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;
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

        $html = View::make('dashboard_pdf', compact('customers', 'paidTotal'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans'
        ]);

        $mpdf->WriteHTML($html);

        return response($mpdf->Output('ديون_زبائن.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }
}
