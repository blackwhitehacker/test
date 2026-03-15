<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            color: #1a1a1a;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            text-transform: uppercase;
            font-size: 16pt;
            margin-bottom: 5px;
            color: #000;
        }
        .header p {
            margin: 0;
            font-size: 10pt;
            color: #666;
        }
        .info-section {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .label {
            width: 150px;
            font-weight: bold;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            padding: 10px 5px;
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
        }
        .items-table td {
            border: 1px solid #ccc;
            padding: 8px 5px;
            font-size: 10pt;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        .total-section {
            margin-top: 10px;
            text-align: right;
            font-size: 12pt;
        }
        .total-amount {
            color: #d32f2f;
            font-weight: bold;
        }
        
        .footer-signatures {
            margin-top: 50px;
            width: 100%;
        }
        .signature-box {
            width: 33.33%;
            text-align: center;
            float: left;
        }
        .signature-box p {
            margin: 0;
            font-size: 10pt;
        }
        .signature-space {
            height: 80px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>TỜ TRÌNH MUA SẮM TÀI SẢN</h1>
        <p>Mã số: {{ $requisition->code }}</p>
        <p>Ngày tạo: {{ $requisition->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="label">Tiêu đề:</td>
                <td>{{ $requisition->title }}</td>
            </tr>
            <tr>
                <td class="label">Người đề xuất:</td>
                <td>{{ $requisition->requester->name }}</td>
            </tr>
            <tr>
                <td class="label">Phòng ban:</td>
                <td>{{ $requisition->department ?? 'Chưa xác định' }}</td>
            </tr>
            <tr>
                <td class="label">Ngày cần hàng:</td>
                <td>{{ $requisition->needed_date ? \Carbon\Carbon::parse($requisition->needed_date)->format('d/m/Y') : 'Chưa xác định' }}</td>
            </tr>
            <tr>
                <td class="label">Mô tả chi tiết:</td>
                <td>{{ $requisition->description ?? '(Không có mô tả)' }}</td>
            </tr>
        </table>
    </div>

    <h3 style="font-size: 12pt; margin-bottom: 10px;">DANH MỤC THIẾT BỊ / TÀI SẢN</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th width="30">STT</th>
                <th>Tên tài sản / Model</th>
                <th width="60">Đơn vị</th>
                <th width="40">SL</th>
                <th width="100">Đơn giá dự kiến</th>
                <th width="120">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($requisition->items ?? [] as $index => $item)
                @php 
                    $subtotal = ($item['quantity'] ?? 0) * ($item['estimate'] ?? 0);
                    $total += $subtotal;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td class="text-center">{{ $item['unit'] ?? 'Cái' }}</td>
                    <td class="text-center">{{ $item['quantity'] }}</td>
                    <td class="text-right">{{ number_format($item['estimate'] ?? 0, 0, ',', '.') }} ₫</td>
                    <td class="text-right text-bold">{{ number_format($subtotal, 0, ',', '.') }} ₫</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <span>Tổng cộng dự toán (tạm tính): </span>
        <span class="total-amount">{{ number_format($total, 0, ',', '.') }} ₫</span>
    </div>

    <div class="footer-signatures">
        <div class="signature-box">
            <p class="text-bold">Người lập phiếu</p>
            <p>(Ký, ghi rõ họ tên)</p>
            <div class="signature-space"></div>
            <p class="text-bold">{{ $requisition->requester->name }}</p>
        </div>
        <div class="signature-box">
            <p class="text-bold">Trưởng phòng ban</p>
            <p>(Ký, ghi rõ họ tên)</p>
            <div class="signature-space"></div>
        </div>
        <div class="signature-box">
            <p class="text-bold">Ban Giám đốc</p>
            <p>(Ký, ghi rõ họ tên)</p>
            <div class="signature-space"></div>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
