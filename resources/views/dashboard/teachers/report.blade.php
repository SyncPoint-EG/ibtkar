<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تقرير المعلم</title>
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
        }

        body {
            margin: 24px;
            direction: rtl;
            text-align: right;
            color: #1f1f1f;
            unicode-bidi: bidi-override;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .logo {
            height: 70px;
        }

        h1 {
            font-size: 22px;
            margin: 0 0 8px;
        }

        p {
            margin: 0 0 4px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 14px;
            direction: rtl;
        }

        th, td {
            border: 1px solid #d0d0d0;
            padding: 8px;
            direction: rtl;
            unicode-bidi: bidi-override;
            text-align: right;
        }

        th {
            background-color: #f2f2f2;
        }

        .section-title {
            font-size: 18px;
            margin: 24px 0 12px;
        }

        .total {
            font-weight: bold;
            margin-bottom: 24px;
        }

        .no-data {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>تقرير المعلم {{ $teacher->name }}</h1>
            @if($startDate && $endDate)
                <p>الفترة من {{ $startDate }} إلى {{ $endDate }}</p>
            @elseif($startDate)
                <p>منذ {{ $startDate }}</p>
            @elseif($endDate)
                <p>حتى {{ $endDate }}</p>
            @endif
        </div>
        @if($logo)
            <img src="{{ $logo }}" alt="شعار" class="logo">
        @endif
    </div>

    <h2 class="section-title">ملخص المبيعات</h2>
    <table>
        <thead>
            <tr>
                <th>البند</th>
                <th>عدد المدفوعات</th>
                <th>إجمالي الإيرادات (جنيه)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($overallSummary as $row)
                <tr>
                    <td>{{ $row['label'] }}</td>
                    <td>{{ $row['count'] }}</td>
                    <td>{{ number_format((float) $row['total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">إجمالي الإيرادات: {{ number_format((float) $overallTotal, 2) }} جنيه</p>

    @forelse($gradeSummaries as $gradeSummary)
        <h2 class="section-title">الصف: {{ $gradeSummary['grade']->name }}</h2>
        <table>
            <thead>
                <tr>
                    <th>البند</th>
                    <th>عدد المدفوعات</th>
                    <th>إجمالي الإيرادات (جنيه)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gradeSummary['summary'] as $row)
                    <tr>
                        <td>{{ $row['label'] }}</td>
                        <td>{{ $row['count'] }}</td>
                        <td>{{ number_format((float) $row['total'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="total">الإجمالي للصف {{ $gradeSummary['grade']->name }}: {{ number_format((float) $gradeSummary['total'], 2) }} جنيه</p>
    @empty
        <p class="no-data">لا توجد صفوف مرتبطة بهذا المعلم حاليًا.</p>
    @endforelse
</body>
</html>
