<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Report (PDF)</title>
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <style>
    body {
        font-family: DejaVu Sans, Tahoma, Arial;
        direction: rtl;
        font-size: 13px;
    }

    @page {
        margin: 120px 40px 80px 40px;
    }

    /* ===== Header ثابت ===== */
    .header-fixed {
        position: fixed;
        top: -100px;
        left: 0;
        right: 0;
        height: 100px;
    }

    .header-table {
        width: 100%;
        border-collapse: collapse;
    }

    .header {
        margin-bottom: 20px;
    }

    .header-box {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logo img {
        width: 100px;
        height: 100px;
        /* اجعلها ثابتة لتتناسب مع النص */
        object-fit: contain;
        /* لتجنب التشوه */
    }

    .title-wrapper {
        display: table;
        margin: 10px auto;
        padding: 6px 10px;
        font-size: 22px;
        font-weight: bold;
        background: #d8d8d8;
        border-radius: 5px;
        text-align: center;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .header-text p {
        margin: 3px 0;
        font-size: 13px;
        line-height: 1.2;
    }

    .desc {
        font-size: 12px;
        font-style: italic;
    }

    .print-date {
        margin-top: 6px;
        font-size: 12px;
    }


    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 14px;
    }

    table.t1,
    table.t1 td,
    table.t1 th {
        border: none;
        /* إزالة البوردر */
    }

    /* ===== Footer ثابت ===== */
    .footer-fixed {
        position: fixed;
        bottom: -60px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 11px;
    }

    .page:before {
        content: counter(page);
    }

    .topage:before {
        content: counter(pages);
    }

    /* ===== Table ===== */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 6px;
        text-align: center;
    }

    th {
        background: #f0f0f0;
        font-weight: bold;
    }

    .ltr {
        direction: ltr;
        text-align: left;
    }

    /* ===== Totals ===== */
    .totals {
        margin-top: 20px;
        width: 50%;
    }

    .totals th {
        background: #eee;
    }
    </style>
</head>

<body>

    {{-- ===== Header ===== --}}
    <div class="header">
        <table class="t1" style="width:100%;">
            <tr>
                <td style="width:120px; vertical-align: middle;">
                    <img src="{{ public_path('images/logo.png') }}"
                        style="width:100px; height:100px; object-fit:contain;">
                </td>
                <td style="text-align:center; vertical-align: middle; ">
                    <h2 style="margin: 0;">پەرگەهـ و فوتوکوپیا چولی</h2>
                    <p style="margin-top: 10px;">
                        بو هەمی پێتڤیێن قوتابخانا و فوتوکوپیێ و چاپکرنێ
                    </p>
                    <p style="margin-top: 10px;">
                        <strong>ناونیشان:</strong> دهوك - تاخێ رەزا - پشت ئوتێلا سی ریکسوس
                    </p>
                    <p style="margin-top: 10px;">
                        <strong>موبایل:</strong> م. چولی 8653 457 0750 / پەرگەهـ 7108 316 0750
                    </p>
                    <p class="print-date" style="margin-top: 10px; font-size:12px;">
                        <strong>تاريخ الطباعة:</strong> {{ now()->format('Y-m-d H:i') }}
                    </p>
                </td>
            </tr>
        </table>

        <hr>

        <div class="title-wrapper">
            كشف ديون الزبائن
        </div>
    </div>

    {{-- ===== Table ===== --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الهاتف</th>
                <th>عدد الديون غير المدفوعة</th>
                <th>مبلغ غير مدفوع</th>
                <th>مبلغ مدفوع</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $i => $customer)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $customer->name }}</td>
                <td>
                    <span class="ltr" dir="ltr">
                        {{ $customer->formatted_phone }}
                    </span>
                </td>
                <td>
                    {{ $customer->debts->where('is_paid', false)->count() }}
                </td>
                <td>
                    {{ number_format($customer->debts->where('is_paid', false)->sum('amount')) }}
                </td>
                <td>
                    {{ number_format($customer->receipts->sum('amount')) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ===== Totals ===== --}}
    <table class="totals">
        <tr>
            <th>إجمالي الديون غير المدفوعة</th>
            <td>
                {{ number_format(
                    $customers->sum(fn($c) =>
                        $c->debts->where('is_paid', false)->sum('amount')
                    )
                ) }}
            </td>
        </tr>
        <tr>
            <th>إجمالي المبالغ المقبوضة</th>
            <td>{{ number_format($paidTotal) }}</td>
        </tr>
    </table>

</body>

</html>
