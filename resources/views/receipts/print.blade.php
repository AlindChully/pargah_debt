<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>طباعة الوصل</title>
</head>

<body>
    <iframe src="{{ asset('receipts/receipt_' . $receipt->id . '.pdf') }}" style="width:100%; height:100vh;"
        frameborder="0"></iframe>

    <script>
    window.onload = function() {
        // فتح نافذة الطباعة مباشرة عند تحميل الصفحة
        window.frames[0].focus();
        window.frames[0].print();
    };
    </script>

</body>

</html>