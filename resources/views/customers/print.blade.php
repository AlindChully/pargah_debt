<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>Debts_{{ $customer->name }} (Print)</title>

    <style>
    body {
        font-family: Tahoma, Arial, sans-serif;
        direction: rtl;
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
    }

    .signatures {
        width: 100%;
        margin-top: 80px;
        overflow: hidden;
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

    /* خط التوقيع */
    .signature p {
        margin-top: 60px;
        padding-top: 10px;
    }

    .line {
        margin-top: 40px;
        border-top: 1px solid #000;
    }

    .stamp {
        margin-top: 40px;
        text-align: center;
        font-weight: bold;
        width: 200px;
        margin-left: auto;
        margin-right: auto;
        padding: 20px;
    }

    .ltr {
        direction: ltr;
        text-align: left;
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

<body onload="window.print()">

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

        <h3 style="text-align:center;margin-top:10px;">
            كشف ديون الزبون: {{ $customer->name }} / الهاتف: <span class="ltr"
                dir="ltr">{{ $customer->formatted_phone }}</span>
        </h3>
    </div>


    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>التاريخ</th>
                <th>كمية</th>
                <th>عدد</th>
                <th>وصف</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($debts->where('is_paid', false) as $i => $debt)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $debt->debt_date->format('Y-m-d') }}</td>
                <td>{{ number_format($debt->amount) }}</td>
                <td>{{ $debt->quantity }}</td>
                <td>{{ $debt->description }}</td>
                <td>{{ !$debt->is_paid ? 'غير واصل' : '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table style="width: 40%; margin-top:20px;">
            <tr>
                <th>عدد الديون غير المدفوعة</th>
                <td>{{ $debts->count() }}</td>
            </tr>
            <tr>
                <th>المجموع الكلي</th>
                <td>{{ number_format($debts->sum('amount')) }}</td>
            </tr>
        </table>
    </div>

    <div class="signatures">
        <div class="signature right">
            <p>توقيع الزبون</p>
        </div>

        <div class="signature left">
            <p>ختم و توقيع</p>
        </div>
    </div>


</body>

</html>