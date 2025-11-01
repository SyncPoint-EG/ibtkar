<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تقرير المعلم</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 24px;
            direction: rtl;
            text-align: right;
            color: #1f1f1f;
        }

        h1, h2, p {
            font-family: 'DejaVu Sans', sans-serif;
        }

        table {
            font-family: 'DejaVu Sans', sans-serif;
        }

        .ltr {
            direction: ltr;
            unicode-bidi: embed;
            display: inline-block;
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
            direction: rtl;
        }

        th, td {
            border: 1px solid #d0d0d0;
            padding: 8px;
            text-align: right;
        }

        td.numeric, th.numeric {
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .section-title {
            font-size: 18px;
            margin: 24px 0 12px;
        }

        .panel {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            background-color: #fafafa;
        }

        .panel + .panel {
            page-break-inside: avoid;
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
                <p>الفترة من <span class="ltr">{{ $startDate }}</span> إلى <span class="ltr">{{ $endDate }}</span></p>
            @elseif($startDate)
                <p>منذ <span class="ltr">{{ $startDate }}</span></p>
            @elseif($endDate)
                <p>حتى <span class="ltr">{{ $endDate }}</span></p>
            @endif
        </div>
        @if($logo)
            <img src="{{ $logo }}" alt="شعار" class="logo">
        @endif
    </div>

    <div class="panel">
        <h2 class="section-title">ملخص المبيعات</h2>
        <table>
            <thead>
                <tr>
                    <th>البند</th>
                    <th class="numeric">عدد المدفوعات</th>
                    <th class="numeric">إجمالي الإيرادات (جنيه)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($overallSummary as $row)
                    <tr>
                        <td>{{ $row['label'] }}</td>
                        <td class="numeric"><span class="ltr">{{ $row['count'] }}</span></td>
                        <td class="numeric"><span class="ltr">{{ number_format((float) $row['total'], 2) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="total">إجمالي الإيرادات <span class="ltr">{{ number_format((float) $overallTotal, 2) }}</span> جنيه</p>
    </div>

    @forelse($gradeSummaries as $gradeSummary)
        <div class="panel">
            <h2 class="section-title">الصف {{ $gradeSummary['grade']->name }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>البند</th>
                        <th class="numeric">عدد المدفوعات</th>
                        <th class="numeric">إجمالي الإيرادات (جنيه)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gradeSummary['summary'] as $row)
                        <tr>
                            <td>{{ $row['label'] }}</td>
                            <td class="numeric"><span class="ltr">{{ $row['count'] }}</span></td>
                            <td class="numeric"><span class="ltr">{{ number_format((float) $row['total'], 2) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="total">الإجمالي للصف {{ $gradeSummary['grade']->name }} <span class="ltr">{{ number_format((float) $gradeSummary['total'], 2) }}</span> جنيه</p>
        </div>
    @empty
        <p class="no-data">لا توجد صفوف مرتبطة بهذا المعلم حاليًا.</p>
    @endforelse
</body>
</html>
