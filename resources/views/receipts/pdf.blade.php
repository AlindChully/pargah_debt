<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>وصل قبض</title>
    <style>
    body {
        font-family: DejaVu Sans, Tahoma, Arial;
        font-size: 14px;
        direction: rtl;
    }

    .container {
        width: 100%;
        padding: 15px;
    }

    .header-fixed {
        position: fixed;
        top: -100px;
        left: 0;
        right: 0;
        height: 100px;
    }

    .header {
        text-align: center;
        margin-bottom: 15px;
    }

    .header .t1 {
        border-collapse: collapse;
        border: none;
    }

    .header h2 {
        margin: 0;
    }

    .info-table {
        width: 100%;
        margin-bottom: 15px;
    }

    .info-table td {
        padding: 6px;
        text-align: right;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table th,
    table td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
    }

    table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .total {
        margin-top: 15px;
        font-size: 16px;
        font-weight: bold;
    }

    .divH2 {
        display: inline-block;
        margin: 5px auto;
        background: rgba(230, 230, 230, 0.9);
        padding: 8px 20px;
        border-radius: 8px;
        color: #000;
        font-weight: bold;
        text-align: center;
        align-items: center;
        box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.1);
    }

    .divH2 h2 {
        margin: 0;
        font-size: 20px;
    }

    .divH3 {
        display: inline-block;
        margin-top: 5px;
        background: rgba(201, 201, 201, 0.9);
        padding: 5px 12px;
        border-radius: 6px;
        color: #000;
        font-weight: bold;
    }

    .divH3 h3 {
        margin: 0;
        font-size: 16px;
    }

    .footer {
        margin-top: 30px;
        display: flex;
        justify-content: space-between;
    }

    .signature.left {
        text-align: left;
    }

    .signature.right {
        text-align: right;
    }
    </style>
</head>

<body>
    <div> {{-- العنوان --}}
        <div class="header">
            <table class="t1" style="width:100%;">
                <tr>
                    <td style="width:120px; vertical-align: middle;"> <img src="{{ public_path('images/logo.png') }}"
                            style="width:100px; height:100px; object-fit:contain;"> </td>
                    <td style="text-align:center; vertical-align: middle; ">
                        <h2 style="margin:0;">پەرگەهـ و فوتوکوپیا چولی</h2>
                        <p style="margin-top: 10px;"> بو هەمی پێتڤیێن قوتابخانا و فوتوکوپیێ و
                            چاپکرنێ </p>
                        <p style="margin-top: 10px;"> <strong>ناونیشان:</strong> دهوك - تاخێ رەزا - پشت ئوتێلا سی ریکسوس
                        </p>
                        <p style="margin-top: 10px;"> <strong>موبایل:</strong> م. چولی 8653 457 0750 / پەرگەهـ 7108 316
                            0750
                        </p>
                    </td>
                </tr>
            </table>
            <hr>
            <div class="divH2">
                <h2>وصل قبض</h2>
            </div>
        </div>
        {{-- معلومات الوصل --}}
        <table class="info-table">
            <tr>
                <td><strong>رقم الوصل:</strong> <strong
                        style="font-weight: bold; color: red;">{{ $receipt->id }}</strong></td>
                <td>
                    <strong>تاريخ الوصل:</strong> {{ \Carbon\Carbon::parse($receipt->receipt_date)->format('d-m-Y') }} /
                    <strong>وقت الوصل:</strong> {{ \Carbon\Carbon::parse($receipt->receipt_date)->format('H:i') }}
                </td>
            </tr>
            <tr>
                <td colspan="2"> <strong>اسم الزبون:</strong>
                    <strong style="font-weight: bold;">{{ $receipt->customer->name ?? '—' }}</strong>
                </td>
            </tr>
        </table> {{-- جدول التفاصيل --}}
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>البيان</th>
                    <th>المبلغ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>مبلغ مقبوض من الزبون</td>
                    <td>{{ number_format($receipt->amount) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- المجموع --}}
        <div class="total divH3">
            <h3>
                المبلغ الإجمالي: {{ number_format($receipt->amount) }}
            </h3>
        </div>
        <br><br>
        {{-- الديون المتبقية --}}
        @if($receipt->customer && $receipt->customer->debts->where('is_paid', false)->count())
        <div style="text-align: center;">
            <div class="divH2">
                <h2>الديون المتبقية</h2>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>التاريخ</th>
                    <th>الوقت</th>
                    <th>التفاصيل</th>
                    <th>المبلغ المتبقي</th>
                    <th>ملاحذة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($debts as $debt)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($debt->debt_date)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($debt->debt_date)->format('H:i') }}</td>
                    <td>{{ $debt->description ?? '—' }}</td>
                    <td>{{ number_format($debt->amount) }}</td>
                    <td style="font-size: 10px;">{{ $debt->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divH3">
            <h3>
                مجموع مبلغ الديون المتبقية:
                {{ number_format(optional($receipt->customer)->debts?->where('is_paid', false)->sum('amount') ?? 0) }}
            </h3>
        </div>
        @else
        <p style="margin-top:15px;"><strong>لا توجد ديون متبقية على الزبون</strong></p>
        @endif

        @if($receipt->notes) <p><strong>ملاحظات:</strong> {{ $receipt->notes }}</p> @endif {{-- التواقيع --}}
        <table style="width:100%; margin-top:30px; border-collapse:collapse; border:none;">
            <tr>
                <td style="width:50%; text-align:right; border:none;">
                    <p>توقيع و الختم</p>
                </td>
                <td style="width:50%; text-align:left; border:none;">
                    <p>توقيع المستلم</p>
                </td>
            </tr>
        </table>
</body>

</html>