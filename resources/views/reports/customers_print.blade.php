<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'تقرير الزبائن' }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: Tahoma, Arial, sans-serif;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 100% !important;
            margin: 0;
            padding: 20px;
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

        .report-box {
            background: #fff;
            padding: 20px;
        }

        .report-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 20px;
        }

        .report-period {
            font-size: 13px;
            color: #555;
        }

        .report-box {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(0, 0, 0, .08);
        }

        .report-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-summary {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            page-break-inside: avoid;
        }

        .report-summary .col-md-4 {
            flex: 1;
        }

        .report-period {
            font-size: 14px;
            color: #777;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: none;
        }

        .ltr {
            direction: ltr;
            unicode-bidi: bidi-override;
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        table thead {
            background: #f0f0f0;
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
            display: inline-block;
            font-weight: bold;
        }

        @media print {
            body {
                background: #fff;
                font-size: 13px;
            }

            .container {
                padding: 15mm;
            }

            .no-print {
                display: none !important;
            }

            .report-box {
                box-shadow: none;
                padding: 0;
            }

            .report-summary {
                display: flex !important;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="report-box">

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

                {{-- ===== العنوان ===== --}}
                <div style="text-align:center; margin-bottom:20px;">
                    <div class="report-title">{{ $title ?? 'تقرير الزبائن' }}</div>
                    <div class="report-period">
                        من {{ $from->format('Y-m-d') }} إلى {{ $to->format('Y-m-d') }}
                    </div>
                </div>
            </div>


            {{-- ===== الملخص ===== --}}
            <div class="row text-center mb-4 report-summary">
                <div class="col-md-4 mb-2">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <i class="bi bi-exclamation-circle text-danger fs-3"></i>
                            <p class="mb-1">عدد الديون غير المدفوعة</p>
                            <h5 class="fw-bold">
                                {{ $customers->sum(fn($c)=>$c->debts->where('is_paid',false)->count()) }}
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-2">
                    <div class="card border-0 shadow-sm bg-success-subtle">
                        <div class="card-body">
                            <i class="bi bi-cash-stack text-success fs-3"></i>
                            <p class="mb-1">إجمالي المقبوض</p>
                            <h5 class="fw-bold">{{ number_format($paidTotal) }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-2">
                    <div class="card border-0 shadow-sm bg-danger-subtle">
                        <div class="card-body">
                            <i class="bi bi-wallet2 text-danger fs-3"></i>
                            <p class="mb-1">إجمالي غير المدفوع</p>
                            <h5 class="fw-bold">
                                {{ number_format($customers->sum(fn($c)=>$c->debts->where('is_paid',false)->sum('amount'))) }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== الجدول ===== --}}
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
                    @forelse($customers as $customer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>
                            <span class="ltr">{{ $customer->formatted_phone }}</span>
                        </td>
                        <td>{{ $customer->debts->where('is_paid',false)->count() }}</td>
                        <td class="text-danger">
                            {{ number_format($customer->debts->where('is_paid',false)->sum('amount')) }}
                        </td>
                        <td class="text-success">
                            {{ number_format($customer->receipts->sum('amount')) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">لا توجد بيانات</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

</body>

</html>