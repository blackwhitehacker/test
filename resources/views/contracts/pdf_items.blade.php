<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Danh mục hàng hóa hợp đồng {{ $contract->code }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 13px; line-height: 1.5; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ce1212; padding-bottom: 15px; }
        .header h1 { color: #ce1212; margin: 0; text-transform: uppercase; font-size: 20px; }
        .info-section { margin-bottom: 20px; display: table; width: 100%; }
        .info-item { display: table-row; }
        .info-label { display: table-cell; font-weight: bold; width: 150px; padding: 5px 0; }
        .info-value { display: table-cell; padding: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 10px; text-align: left; font-weight: bold; color: #ce1212; font-size: 11px; text-transform: uppercase; }
        td { border: 1px solid #dee2e6; padding: 10px; vertical-align: middle; }
        .text-right { text-align: right; }
        .footer { margin-top: 50px; }
        .signature { width: 100%; display: table; }
        .sig-box { display: table-cell; width: 50%; text-align: center; }
        .sig-label { font-weight: bold; margin-bottom: 80px; display: block; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Danh mục hàng hóa / Dịch vụ</h1>
        <p>Kèm theo Hợp đồng số: {{ $contract->contract_number }}</p>
    </div>

    <div class="info-section">
        <div class="info-item">
            <span class="info-label">Số hợp đồng:</span>
            <span class="info-value">{{ $contract->contract_number }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Mã hệ thống:</span>
            <span class="info-value">{{ $contract->code }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Đối tác:</span>
            <span class="info-value font-bold">{{ $contract->partner->name }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Ngày ký:</span>
            <span class="info-value">{{ $contract->signed_date ? $contract->signed_date->format('d/m/Y') : '...' }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">STT</th>
                <th>Tên hàng hóa / Mô tả chi tiết</th>
                <th width="10%">ĐVT</th>
                <th width="10%" class="text-right">Số lượng</th>
                <th width="15%" class="text-right">Đơn giá dự kiến</th>
                <th width="15%" class="text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($items as $index => $item)
                @php 
                    $price = $item['price'] ?? 0;
                    $quantity = $item['quantity'] ?? 1;
                    $subtotal = $price * $quantity;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item['name'] }}</strong>
                        @if(!empty($item['description']))
                            <br/><small style="color: #666 italic">{{ $item['description'] }}</small>
                        @endif
                    </td>
                    <td align="center">{{ $item['unit'] ?? 'Bộ' }}</td>
                    <td align="right">{{ number_format($quantity) }}</td>
                    <td align="right">{{ number_format($price) }}</td>
                    <td align="right"><strong>{{ number_format($subtotal) }}</strong></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa;">
                <td colspan="5" align="right"><strong>TỔNG CỘNG (VNĐ)</strong></td>
                <td align="right"><strong style="color: #ce1212;">{{ number_format($total) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="signature">
            <div class="sig-box">
                <span class="sig-label">ĐẠI DIỆN BÊN B</span>
                <p>(Ký, ghi rõ họ tên và đóng dấu)</p>
            </div>
            <div class="sig-box">
                <span class="sig-label">ĐẠI DIỆN BÊN A</span>
                <p>(Ký, ghi rõ họ tên và đóng dấu)</p>
            </div>
        </div>
    </div>
</body>
</html>
