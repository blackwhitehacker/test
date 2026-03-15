<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Phiếu giao nhận hàng hóa {{ $shipment->code }}</title>
    <style>
        @page { margin: 1.2cm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11.5px; line-height: 1.4; color: #111; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { margin: 5px 0; text-transform: uppercase; font-size: 18px; color: #000; }
        .header p { margin: 2px 0; font-weight: bold; font-size: 11px; }
        
        .section-title { font-weight: bold; text-transform: uppercase; margin-top: 15px; text-decoration: underline; font-size: 11.5px; }
        .info-table { width: 100%; margin-top: 5px; border-collapse: collapse; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .info-label { width: 150px; font-weight: bold; color: #444; }
        
        table.items { width: 100%; border-collapse: collapse; margin-top: 15px; table-layout: fixed; }
        table.items th { background-color: #f8f8f8; border: 1px solid #999; padding: 6px 4px; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        table.items td { border: 1px solid #999; padding: 6px 4px; font-size: 11px; word-wrap: break-word; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .footer { margin-top: 30px; width: 100%; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-table td { width: 50%; text-align: center; height: 100px; vertical-align: top; }
        .sig-date { font-style: italic; margin-bottom: 5px; font-size: 11px; }
        
        .legal-notice { margin-top: 20px; font-size: 10px; font-style: italic; color: #666; border-top: 1px dashed #ccc; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <p>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
        <p>Độc lập - Tự do - Hạnh phúc</p>
        <div style="margin: 15px auto; width: 100px; border-bottom: 1px solid #000;"></div>
        <h1>PHIẾU GIAO NHẬN HÀNG HÓA</h1>
        <p>Số hiệu: {{ $shipment->code }}</p>
        <p>Ngày trích xuất: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section-title">THÔNG TIN ĐƠN VỊ NHẬN (BÊN A)</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Tên đơn vị:</td>
            <td style="font-weight: bold; text-transform: uppercase;">{{ $settings['company_name'] ?? 'CÔNG TY QUẢN LÝ TÀI SẢN ENTERPRISE' }}</td>
        </tr>
        <tr>
            <td class="info-label">Địa chỉ:</td>
            <td>{{ $settings['company_address'] ?? 'Số 123, Đường Thành Phố, Việt Nam' }}</td>
        </tr>
        <tr>
            <td class="info-label">Người nhận hàng:</td>
            <td><strong>{{ $shipment->receiver_name ?? '---' }}</strong></td>
        </tr>
    </table>

    <div class="section-title">THÔNG TIN ĐƠN VỊ GIAO (BÊN B)</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Tên nhà cung cấp:</td>
            <td style="font-weight: bold;">{{ $shipment->contract->partner->name }}</td>
        </tr>
        <tr>
            <td class="info-label">Theo hợp đồng số:</td>
            <td>{{ $shipment->contract->contract_number }} (Mã: {{ $shipment->contract->code }})</td>
        </tr>
        <tr>
            <td class="info-label">Ngày giao thực tế:</td>
            <td>{{ $shipment->delivery_date ? $shipment->delivery_date->format('d/m/Y') : '---' }}</td>
        </tr>
    </table>

    <div class="section-title">DANH MỤC HÀNG HÓA GIAO NHẬN</div>
    <table class="items">
        <thead>
            <tr>
                <th width="40px">STT</th>
                <th>Tên hàng hóa / Quy cách thiết bị</th>
                <th width="60px">ĐVT</th>
                <th width="80px" class="text-center">S.L Đặt</th>
                <th width="80px" class="text-center">S.L Giao</th>
                <th width="120px">Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipment->items ?? [] as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $item['name'] }}</strong></td>
                    <td class="text-center">{{ $item['unit'] ?? 'Cái' }}</td>
                    <td class="text-center">{{ number_format($item['ordered_qty']) }}</td>
                    <td class="text-center" style="font-weight: bold; font-size: 13px; color: #000;">{{ number_format($item['delivered_qty']) }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($shipment->note)
        <p style="margin-top: 15px; font-style: italic;"><strong>Ghi chú bổ sung:</strong> {{ $shipment->note }}</p>
    @endif

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td>
                    <p class="sig-date">Ngày ...... tháng ...... năm ......</p>
                    <p style="font-weight: bold; text-transform: uppercase;">ĐẠI DIỆN BÊN GIAO (BÊN B)</p>
                    <p>(Ký, ghi rõ họ tên)</p>
                </td>
                <td>
                    <p class="sig-date">Ngày ...... tháng ...... năm ......</p>
                    <p style="font-weight: bold; text-transform: uppercase;">ĐẠI DIỆN BÊN NHẬN (BÊN A)</p>
                    <p>(Ký, ghi rõ họ tên)</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="legal-notice">
        * Chứng từ này được trích xuất tự động từ hệ thống Quản lý tài sản Enterprise. Các bên có trách nhiệm kiểm tra và lưu giữ hồ sơ theo quy định.
    </div>
</body>
</html>
