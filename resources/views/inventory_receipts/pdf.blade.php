<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Phiếu Nhập Kho - {{ $receipt->code }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; line-height: 1.6; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0; color: #666; }
        
        .info-section { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-section td { padding: 5px 0; vertical-align: top; }
        .info-label { font-weight: bold; width: 150px; }
        
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .table th { background-color: #f9f9f9; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        
        .footer { margin-top: 50px; width: 100%; }
        .footer td { text-align: center; width: 33.33%; padding-bottom: 80px; }
        .footer .signature-label { font-weight: bold; margin-bottom: 60px; }
        
        .page-break { page-break-after: always; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-enterprise { color: #8b0000; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; text-transform: uppercase; border: 1px solid #ddd; }
        
        .details-table { width: 100%; margin-top: 5px; border-top: 1px dashed #eee; font-size: 10px; color: #666; }
        .details-table td { border: none !important; padding: 2px 5px !important; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $receipt->type == 'inbound' ? 'PHIẾU NHẬP KHO TÀI SẢN' : 'PHIẾU XUẤT KHO TÀI SẢN' }}</h1>
        <p>Mã số: <span class="font-bold text-enterprise">{{ $receipt->code }}</span></p>
        <p>Ngày lập: {{ $receipt->process_date->format('d/m/Y') }}</p>
    </div>

    <table class="info-section">
        <tr>
            <td class="info-label">Người xử lý:</td>
            <td>{{ $receipt->processor->name }}</td>
            <td class="info-label">Yêu cầu liên kết:</td>
            <td>{{ $receipt->request->code }}</td>
        </tr>
        <tr>
            <td class="info-label">Người yêu cầu:</td>
            <td>{{ $receipt->request->requester->name }}</td>
            <td class="info-label">Trạng thái:</td>
            <td>
                @if($receipt->status == 'confirmed')
                    {{ $receipt->type == 'inbound' ? 'ĐÃ NHẬP KHO' : 'ĐÃ XUẤT KHO' }}
                @else
                    ĐANG XỬ LÝ (LƯU NHÁP)
                @endif
            </td>
        </tr>
        @if($receipt->type == 'outbound' && $receipt->request->receiver)
        <tr>
            <td class="info-label">Người / Đơn vị nhận:</td>
            <td colspan="3"><span class="font-bold text-enterprise" style="font-size: 14px; text-decoration: underline;">{{ $receipt->request->receiver }}</span></td>
        </tr>
        @endif
        <tr>
            <td class="info-label">Ghi chú đánh giá:</td>
            <td colspan="3"><em>{{ $receipt->evaluation_notes ?: 'Không có ghi chú.' }}</em></td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 40px;">STT</th>
                <th>Tên TSCĐ / Thiết bị</th>
                <th>Quy cách / Thông số</th>
                <th style="width: 80px;">Số lượng</th>
                <th style="width: 100px;">Đơn giá</th>
                <th style="width: 120px;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($receipt->items as $index => $item)
                @php 
                    $subtotal = $item['quantity'] * ($item['price'] ?? 0);
                    $grandTotal += $subtotal;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <div class="font-bold">{{ $item['name'] }}</div>
                        @if(isset($item['details']) && count($item['details']) > 0)
                            <table class="details-table">
                                @foreach($item['details'] as $detail)
                                    <tr>
                                        <td style="width: 60px;">Mã: {{ $detail['asset_code'] }}</td>
                                        <td>SN: {{ $detail['serial'] ?: 'N/A' }}</td>
                                        <td style="text-align: right;">
                                            @php
                                                $conditionLabel = match($detail['condition'] ?? 'new') {
                                                    'new' => 'Mới',
                                                    'good' => 'Tốt',
                                                    'used' => 'Cũ',
                                                    'broken' => 'Hỏng',
                                                    default => 'N/A'
                                                };
                                            @endphp
                                            {{ $conditionLabel }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </td>
                    <td>{{ $item['specification'] ?? '-' }}</td>
                    <td style="text-align: center;">{{ number_format($item['quantity']) }}</td>
                    <td class="text-right">{{ number_format($item['price'] ?? 0) }}đ</td>
                    <td class="text-right font-bold">{{ number_format($subtotal) }}đ</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9;">
                <td colspan="5" class="text-right font-bold">TỔNG CỘNG GIÁ TRỊ {{ $receipt->type == 'inbound' ? 'NHẬP' : 'XUẤT' }} KHO:</td>
                <td class="text-right font-bold text-enterprise" style="font-size: 14px;">{{ number_format($grandTotal) }} đ</td>
            </tr>
        </tfoot>
    </table>

    <p style="margin-top: 20px; font-style: italic;">
        Bằng chữ: ...............................................................................................................................................
    </p>

    <table class="footer">
        <tr>
            <td>
                <p class="signature-label">Người lập phiếu</p>
                <p>(Ký, họ tên)</p>
            </td>
            <td>
                <p class="signature-label">Kế toán trưởng</p>
                <p>(Ký, họ tên)</p>
            </td>
            <td>
                <p class="signature-label">Thủ trưởng đơn vị</p>
                <p>(Ký, họ tên, đóng dấu)</p>
            </td>
        </tr>
    </table>
</body>
</html>
