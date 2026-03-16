<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/inter/v12/UcCO3FwrK3iLTeHuS_fvQtMwCp50KnMw2boKoduKmMEVuLyfMZhrib2Bg-4.ttf) format('truetype');
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1a1a1a;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #E11D48;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #E11D48;
            text-transform: uppercase;
            margin: 0;
            font-size: 18px;
            letter-spacing: 1px;
        }
        .info {
            margin-bottom: 15px;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #0f172a;
            color: white;
            text-transform: uppercase;
            font-size: 8px;
            padding: 8px;
            border: 1px solid #ddd;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 9px;
            color: #64748b;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>@yield('title')</h1>
        <p>Hệ thống Quản lý Tài sản - AMS v1.0</p>
    </div>

    <div class="info">
        Ngày lập báo cáo: {{ now()->format('d/m/Y H:i') }}<br>
        Người lập: {{ auth()->user()->name }}
    </div>

    @yield('content')

    <div class="footer">
        Báo cáo được trích xuất tự động từ hệ thống.<br>
        Trang 1 / 1
    </div>
</body>
</html>
