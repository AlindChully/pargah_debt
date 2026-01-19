<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>

    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: DejaVu Sans, Tahoma, Arial, sans-serif;
            font-size: 13px;
            color: #000;
        }

        .header-fixed {
            position: fixed;
            top: -100px;
            left: 0;
            right: 0;
            height: 100px;
        }

        .footer-fixed {
            position: fixed;
            bottom: -80px;
            left: 0;
            right: 0;
            height: 60px;
            text-align: center;
            font-size: 12px;
        }

        .header {
            text-align: center;
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

        .header-text h2 {
            margin: 0;
            font-size: 22px;
            line-height: 100px;
            /* نفس ارتفاع الصورة لضبط المحاذاة */
        }

        .header-text p {
            margin: 3px 0;
            font-size: 13px;
            line-height: 1.2;
        }

        /* ===== PDF SUMMARY CARDS ===== */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin-bottom: 20px;
        }

        .summary-table tr {
            border: none !important;
        }

        .summary-table td {
            border: none !important;
            padding: 5px;
        }

        .summary-card {
            border: none;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
        }

        .summary-card h5 {
            margin: 5px 0 0;
            font-size: 16px;
        }

        .summary-card p {
            margin: 0;
            font-size: 12px;
        }

        .card-danger {
            background: #fdecea;
        }

        .card-success {
            background: #eafaf1;
        }

        .card-warning {
            background: #fff6e5;
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

        thead {
            background: #f2f2f2;
            font-weight: bold;
        }

        .text-danger {
            color: #c0392b;
            font-weight: bold;
        }

        .text-success {
            color: #27ae60;
            font-weight: bold;
        }

        .ltr {
            direction: ltr;
            unicode-bidi: bidi-override;
            font-weight: bold;
        }

        @media print {
            body {
                margin: 20px;
            }

            img {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <table class="t1" style="width:100%;">
            <tr>
                <td style="width:120px; vertical-align: middle;">
                    <img src="{{ public_path('images/logo.png') }}"
                        style="width:100px; height:100px; object-fit:contain;">
                </td>
                <td style="text-align:center; vertical-align: middle; ">
                    <h2 style="margin:0;">پەرگەهـ و فوتوکوپیا چولی</h2>
                    <p class="desc" style="margin:3px 0; font-size:12px;">
                        بو هەمی پێتڤیێن قوتابخانا و فوتوکوپیێ و چاپکرنێ
                    </p>
                    <p style="margin:3px 0;">
                        <strong>ناونیشان:</strong> دهوك - تاخێ رەزا - پشت ئوتێلا سی ریکسوس
                    </p>
                    <p style="margin:3px 0;">
                        <strong>موبایل:</strong> م. چولی 8653 457 0750 / پەرگەهـ 7108 316 0750
                    </p>
                    <p class="print-date" style="margin-top:6px; font-size:12px;">
                        <strong>تاريخ الطباعة:</strong> {{ now()->format('Y-m-d H:i') }}
                    </p>
                </td>
            </tr>
        </table>

        <hr>

        <h2>{{ $title }}</h2>
        <p>
            من {{ $from->format('Y-m-d') }} إلى {{ $to->format('Y-m-d') }}
        </p>
        <p>
            تاريخ الإنشاء: {{ now()->timezone(config('app.timezone'))->format('Y-m-d H:i') }}
        </p>
    </div>

    {{-- ===== الملخص ===== --}}
    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-card card-warning">
                    <p>عدد الديون غير المدفوعة</p>
                    <h5>
                        {{ $customers->sum(fn($c)=>$c->debts->where('is_paid',false)->count()) }}
                    </h5>
                </div>
            </td>

            <td>
                <div class="summary-card card-success">
                    <p>إجمالي المقبوض</p>
                    <h5>
                        {{ number_format($paidTotal) }}
                    </h5>
                </div>
            </td>

            <td>
                <div class="summary-card card-danger">
                    <p>إجمالي غير المدفوع</p>
                    <h5>
                        {{ number_format($customers->sum(fn($c)=>$c->debts->where('is_paid',false)->sum('amount'))) }}
                    </h5>
                </div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الهاتف</th>
                <th>ديون غير مدفوعة</th>
                <th>المبلغ غير المدفوع</th>
                <th>المبلغ المدفوع</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $customer->name }}</td>
                <td><span class="ltr">{{ $customer->formatted_phone }}</span></td>
                <td>{{ $customer->debts->where('is_paid',false)->count() }}</td>
                <td class="text-danger">
                    {{ number_format($customer->debts->where('is_paid',false)->sum('amount')) }}
                </td>
                <td class="text-success">
                    {{ number_format($customer->receipts->sum('amount')) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>