<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Receipt;
use App\Models\CustomerBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Barryvdh\Snappy\Facades\SnappyPdf;

class ReceiptController extends Controller
{
    public function store(Request $request, Customer $customer)
    {
        try {

            $request->validate([
                'amount'       => 'required|numeric|min:1000',
                'receipt_date' => 'required|date',
                'notes'        => 'nullable|string',
            ]);

            // ================= TRANSACTION =================
            $receipt = DB::transaction(function () use ($request, $customer) {

                // 1️⃣ إنشاء الوصل
                $receipt = Receipt::create([
                    'customer_id'  => $customer->id,
                    'amount'       => $request->amount,
                    'receipt_date' => $request->receipt_date,
                    'notes'        => $request->notes,
                ]);

                $remainingAmount = $request->amount;

                // 2️⃣ الرصيد السابق
                $balance = CustomerBalance::firstOrCreate(
                    ['customer_id' => $customer->id],
                    ['balance' => 0]
                );

                if ($balance->balance > 0) {
                    $remainingAmount += $balance->balance;
                    $balance->update(['balance' => 0]);
                }

                // 3️⃣ الديون غير المدفوعة (الأقدم أولاً)
                $debts = $customer->debts()
                    ->where('is_paid', false)
                    ->orderBy('debt_date', 'asc')
                    ->get();

                foreach ($debts as $debt) {

                    if ($remainingAmount <= 0) break;

                    $date = Carbon::now()->format('d-m-Y');
                    $time = Carbon::now()->format('H:i');

                    // 4️⃣ تسديد كامل
                    if ($remainingAmount >= $debt->amount) {

                        $receipt->debts()->attach($debt->id, [
                            'paid_amount' => $debt->amount
                        ]);

                        $debt->notes = trim(
                            ($debt->notes ? $debt->notes . ' | ' : '') .
                                "تم تسديد الدين كاملًا ({$debt->amount}) بتاريخ {$date} {$time}"
                        );

                        $remainingAmount -= $debt->amount;

                        $debt->update([
                            'amount'  => 0,
                            'is_paid' => true,
                            'notes'   => $debt->notes
                        ]);
                    }
                    // 5️⃣ تسديد جزئي
                    else {

                        $receipt->debts()->attach($debt->id, [
                            'paid_amount' => $remainingAmount
                        ]);

                        $debt->notes = trim(
                            ($debt->notes ? $debt->notes . ' | ' : '') .
                                "تم خصم مبلغ {$remainingAmount} بتاريخ {$date} {$time}"
                        );

                        $debt->update([
                            'amount' => $debt->amount - $remainingAmount,
                            'notes'  => $debt->notes
                        ]);

                        $remainingAmount = 0;
                    }
                }

                // 6️⃣ الرصيد الزائد
                if ($remainingAmount > 0) {
                    $balance->increment('balance', $remainingAmount);
                }

                return $receipt;
            });

            // ================= PDF =================
            $path = public_path('receipts');

            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }

            $fileName = 'receipt_' . $receipt->id . '.pdf';
$filePath = $path . '/' . $fileName;

$debts = $receipt->customer
    ->debts()
    ->where('is_paid', false)
    ->orderBy('debt_date', 'asc')
    ->get();

// تحويل الـ view إلى HTML
$html = View::make('receipts.pdf', compact('receipt', 'debts'))->render();

// إنشاء mPDF
$mpdf = new Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'default_font' => 'dejavusans', // مهم لدعم العربية
    'margin_top' => 10,
    'margin_bottom' => 10,
    'margin_left' => 10,
    'margin_right' => 10,
]);

$mpdf->WriteHTML($html);

// حفظ الملف
$mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);

            if (!file_exists($filePath)) {
                throw new \Exception('PDF was not created');
            }

            return response()->json(['pdf_url' => asset("receipts/{$fileName}"), 'download_url' => asset("receipts/{$fileName}")]);
        } catch (\Throwable $e) {

            return response()->json([
                'error'   => 'PDF_ERROR',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function print(Receipt $receipt)
    {
        return view('receipts.print', compact('receipt'));
    }

    public function pdf(Receipt $receipt)
    {
        // توليد HTML من Blade
        $html = View::make('receipts.pdf', compact('receipt'))->render();

        // إنشاء ملف PDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans', // مهم جداً للعربي
        ]);

        $mpdf->WriteHTML($html);

        $fileName = 'receipt_' . $receipt->id . '.pdf';

        // تحميل مباشر
        return response($mpdf->Output($fileName, 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
