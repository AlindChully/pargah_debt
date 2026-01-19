<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Debt;
use App\Models\Receipt;
use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf;

class ReportController extends Controller
{
    public function customers(Request $request)
    {
        $type = $request->type ?? 'monthly';

        if ($type === 'monthly') {
            $month = $request->month ?? now()->format('Y-m');
            $from = Carbon::parse($month)->startOfMonth();
            $to   = Carbon::parse($month)->endOfMonth();
            $title = 'monthly';
        } else {
            $year = $request->year ?? now()->year;
            $from = Carbon::create($year, 1, 1);
            $to   = Carbon::create($year, 12, 31);
            $title = 'annual';
        }

        $customers = Customer::with([
            'debts' => function ($q) use ($from, $to) {
                $q->whereBetween('debt_date', [$from, $to]);
            },
            'receipts' => function ($q) use ($from, $to) {
                $q->whereBetween('receipt_date', [$from, $to]);
            }
        ])->get();

        $paidTotal = Receipt::whereBetween('receipt_date', [$from, $to])->sum('amount');

        return view('reports.customers', compact(
            'customers',
            'from',
            'to',
            'paidTotal',
            'title'
        ));
    }

    public function customersPrint(Request $request)
    {
        $type = $request->type ?? 'monthly';

        if ($type === 'monthly') {
            $month = $request->month ?? now()->format('Y-m');
            $from = Carbon::parse($month)->startOfMonth();
            $to   = Carbon::parse($month)->endOfMonth();
            $title = 'تقرير شهري';
        } else {
            $year = $request->year ?? now()->year;
            $from = Carbon::create($year, 1, 1);
            $to   = Carbon::create($year, 12, 31);
            $title = 'تقرير سنوي';
        }

        $customers = Customer::with([
            'debts' => function ($q) use ($from, $to) {
                $q->whereBetween('debt_date', [$from, $to]);
            },
            'receipts' => function ($q) use ($from, $to) {
                $q->whereBetween('receipt_date', [$from, $to]);
            }
        ])->get();

        $paidTotal = Receipt::whereBetween('receipt_date', [$from, $to])->sum('amount');

        return view('reports.customers_print', compact(
            'customers',
            'from',
            'to',
            'paidTotal',
            'title'
        ));
    }

    public function customersPdf(Request $request)
    {
        $type = $request->type ?? 'monthly';

        if ($type === 'monthly') {
            $month = $request->month ?? now()->format('Y-m');
            $from = Carbon::parse($month)->startOfMonth();
            $to   = Carbon::parse($month)->endOfMonth();
            $title = 'تقرير شهري';
        } else {
            $year = $request->year ?? now()->year;
            $from = Carbon::create($year, 1, 1);
            $to   = Carbon::create($year, 12, 31);
            $title = 'تقرير سنوي';
        }

        $customers = Customer::with([
            'debts' => fn($q) => $q->whereBetween('debt_date', [$from, $to]),
            'receipts' => fn($q) => $q->whereBetween('receipt_date', [$from, $to]),
        ])->get();

        $paidTotal = Receipt::whereBetween('receipt_date', [$from, $to])->sum('amount');

        $fileName = 'customers_report_' . now()->format('Ymd_His') . '.pdf';

        return SnappyPdf::loadView(
            'reports.customers_pdf',
            compact('customers', 'from', 'to', 'title', 'paidTotal')
        )
            ->setPaper('a4')
            ->download($fileName);
    }
}
