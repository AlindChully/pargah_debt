<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>Debts_{{ $customer->name }}</title>

    <style>
    @page {
        margin: 20px;
    }

    body {
        font-family: Tahoma, Arial, sans-serif;
        direction: rtl;
        font-size: 13px;
    }

    .header {
        text-align: center;
        margin-bottom: 15px;
    }

    .header table {
        width: 100%;
        border-collapse: collapse;
    }

    .header img {
        width: 90px;
        height: 90px;
        object-fit: contain;
    }

    .header h2 {
        margin: 0;
        font-size: 20px;
    }

    .header p {
        margin: 3px 0;
        font-size: 12px;
    }

    hr {
        margin: 10px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
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

    .totals {
        margin-top: 15px;
        width: 40%;
    }

    .totals td,
    .totals th {
        border: 1px solid #000;
        padding: 6px;
    }

    .signatures {
        margin-top: 60px;
        overflow: hidden;
        page-break-inside: avoid;
    }

    .signature {
        width: 40%;
        text-align: center;
    }

    .signature.right {
        float: left;
    }

    .signature.left {
        float: right;
    }

    .signature p {
        margin-top: 60px;
        padding-top: 5px;
    }

    .ltr {
        direction: ltr;
        text-align: left;
    }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <table>
            <tr>
                <td style="width:100px;">
                    <img src="{{ public_path('images/logo.png') }}">
                </td>
                <td>
                    <h2>پەرگەهـ و فوتوکوپیا چولی</h2>
                    <p>بو هەمی پێتڤیێن قوتابخانا و فوتوکوپیێ و چاپکرنێ</p>
                    <p><strong>العنوان:</strong> دهوك - تاخێ رەزا - پشت ئوتێلا سی ریکسوس</p>
                    <p><strong>الموبايل:</strong> 0750 457 8653 / 0750 316 7108</p>
                    <p><strong>تاريخ الطباعة:</strong> {{ now()->format('Y-m-d H:i') }}</p>
                </td>
            </tr>
        </table>

        <hr>

        <h3>
            كشف ديون الزبون:
            {{ $customer->name }}
            /
            الهاتف:
            <span class="ltr">{{ $customer->formatted_phone }}</span>
        </h3>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>التاريخ</th>
                <th>المبلغ</th>
                <th>الكمية</th>
                <th>الوصف</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($debts as $i => $debt)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $debt->debt_date->format('Y-m-d') }}</td>
                <td>{{ number_format($debt->amount) }}</td>
                <td>{{ $debt->quantity }}</td>
                <td>{{ $debt->description }}</td>
                <td>{{ $debt->is_paid ? 'واصل' : 'غير واصل' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TOTALS -->
    <table class="totals">
        <tr>
            <th>عدد الديون</th>
            <td>{{ $debts->count() }}</td>
        </tr>
        <tr>
            <th>المجموع الكلي</th>
            <td>{{ number_format($debts->sum('amount')) }}</td>
        </tr>
    </table>

    <!-- SIGNATURES -->
    <div class="signatures">
        <div class="signature right">
            <p>توقيع الزبون</p>
        </div>

        <div class="signature left">
            <p>ختم وتوقيع</p>
        </div>
    </div>

</body>

</html>