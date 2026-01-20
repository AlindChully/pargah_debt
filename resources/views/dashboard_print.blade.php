<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Report (Print)</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <style>
        body {
            font-family: Tahoma, Arial, sans-serif;
            direction: rtl;
            font-size: 13px;
        }

        /* مهم لإظهار الألوان */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
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
            max-height: 125px;
        }

        .header-text {
            text-align: center;
            flex: 1;
        }

        .header-text h2 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
        }

        .header-text p {
            margin: 3px 0;
            font-size: 13px;
        }

        .title-wrapper {
            display: table;
            margin: 10px auto;
            padding: 6px 18px;
            font-size: 22px;
            font-weight: bold;
            background: #d8d8d8;
            border-radius: 4px;
        }

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
        }

        .totals {
            width: 50%;
            margin-top: 20px;
        }

        .ltr {
            direction: ltr;
            text-align: left;
        }

        @media print {
            body {
                margin: 20px;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <!-- ===== Header ===== -->
    <div class="header">
        <div class="header-box">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" width="100" alt="Logo">
            </div>

            <div class="header-text">
                <h2>پەرگەهـ و فوتوکوپیا چولی</h2>

                <p class="desc">
                    بو هەمی پێتڤیێن قوتابخانا و فوتوکوپیێ و چاپکرنێ
                </p>

                <p>
                    <strong>ناونیشان:</strong>
                    دهوك - تاخێ رەزا - پشت ئوتێلا سی ریکسوس
                </p>

                <p>
                    <strong>موبایل:</strong>
                    م. چولی 8653 457 0750 /
                    پەرگەهـ 7108 316 0750
                </p>

                <p class="print-date">
                    <strong>تاريخ الطباعة:</strong> {{ now()->format('Y-m-d H:i') }}
                </p>
            </div>
        </div>

        <hr>

        <div class="title-wrapper">
            كشف ديون الزبائن
        </div>
    </div>

    <!-- ===== Table ===== -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الهاتف</th>
                <th>عدد الديون</th>
                <th>غير مدفوع</th>
                <th>مدفوع</th>
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
                <td>{{ $customer->debts->where('is_paid', false)->count() }}</td>
                <td>{{ number_format($customer->debts->where('is_paid', false)->sum('amount')) }}</td>
                <td>{{ number_format($customer->receipts->sum('amount')) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ===== Totals ===== -->
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
