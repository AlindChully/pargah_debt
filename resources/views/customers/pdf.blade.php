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
            font-family: DejaVu Sans, Tahoma, Arial;
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

        .ltr {
            direction: ltr;
            text-align: left;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
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
