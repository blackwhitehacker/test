<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Biên bản thanh lý {{ $record->code }}</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; line-height: 1.4; color: #000; }
        .header { text-align: center; margin-bottom: 15px; }
        .header p { margin: 1px 0; font-weight: bold; font-size: 10px; }
        .header h1 { margin: 10px 0 5px 0; font-size: 20px; text-transform: uppercase; }
        .header .doc-id { color: #666; font-size: 10px; margin-bottom: 2px; }
        
        .section-title { font-weight: bold; text-transform: uppercase; margin-top: 12px; font-size: 11px; }
        .section-title span { text-decoration: underline; }
        
        .info-table { width: 100%; margin: 5px 0; border-collapse: collapse; }
        .info-table td { padding: 2px 0; vertical-align: top; }
        .info-label { width: 130px; font-weight: bold; }
        
        table.items { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.items th { border: 1px solid #000; padding: 6px 4px; background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        table.items td { border: 1px solid #000; padding: 6px 4px; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .footer { margin-top: 25px; width: 100%; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-table td { width: 33%; text-align: center; vertical-align: top; }
        .sig-date { font-style: italic; margin-bottom: 5px; font-size: 10px; }
        
        .notice { margin-top: 15px; font-size: 9px; font-style: italic; color: #666; border-top: 1px dashed #ccc; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <p>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
        <p>Độc lập - Tự do - Hạnh phúc</p>
        <div style="margin: 5px auto; width: 140px; border-bottom: 1px solid #000;"></div>
        <h1>BIÊN BẢN THANH LÝ TÀI SẢN</h1>
        <div class="doc-id">Số: <strong>TL-{{ $record->code }}</strong></div>
        <div class="doc-id">Mã hệ thống: <strong>YC-{{ $record->code }}</strong></div>
    </div>

    <p style="margin-bottom: 15px;">Hôm nay, ngày ...... tháng ...... năm ......, Hội đồng thanh lý tài sản Công ty đã họp và thực hiện việc thanh lý các tài sản sau:</p>

    <div class="section-title"><span>THÀNH PHẦN HỘI ĐỒNG</span></div>
    <table class="info-table">
        <tr>
            <td class="info-label">Trưởng hội đồng:</td>
            <td><strong>Ban Giám đốc</strong></td>
        </tr>
        <tr>
            <td class="info-label">Thành viên:</td>
            <td>Phòng Hành chính - Quản trị tài sản</td>
        </tr>
    </table>

    <div class="section-title"><span>DANH MỤC TÀI SẢN THANH LÝ</span></div>
    <table class="items">
        <thead>
            <tr>
                <th width="30">STT</th>
                <th>TÊN TÀI SẢN / MÔ TẢ</th>
                <th width="50">SỐ LƯỢNG</th>
                <th width="120" class="text-center">GIÁ TRỊ THU HỒI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->items as $index => $item)
                <tr>
                    <td class="text-center font-bold">{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight: bold;">{{ $item->name }}</div>
                        @if($item->asset)
                            <div style="font-size: 9px; color: #444;">Mã TS: {{ $item->asset->code }}</div>
                        @endif
                    </td>
                    <td class="text-center font-bold">{{ $item->quantity }}</td>
                    <td class="text-center font-bold">{{ number_format($record->recovery_value) }} VNĐ</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">KẾT QUẢ VÀ GHI CHÚ</div>
    <div style="margin-top: 8px; border: 1px solid #eee; padding: 10px;">
        <p><strong>- Tổng thu hồi:</strong> {{ number_format($record->recovery_value) }} VNĐ</p>
        <p><strong>- Tình trạng sau thanh lý:</strong> Tài sản đã được bán/hủy theo đúng quy định. Toàn bộ hồ sơ tài sản đã được xóa khỏi danh mục tài sản đang sử dụng/tồn kho.</p>
        <p><strong>- Ghi chú bổ sung:</strong> {{ $record->liquidation_notes ?: 'Không có.' }}</p>
    </div>

    <div class="notice">
        * Biên bản này là chứng từ căn cứ để hạch toán giảm tài sản cố định và ghi nhận thu nhập từ thanh lý.
    </div>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td>
                    <p class="sig-date">Ngày ...... tháng ...... năm ......</p>
                    <p style="font-weight: bold; text-transform: uppercase;">NGƯỜI LẬP BIÊN BẢN</p>
                    <div style="height: 50px;"></div>
                    <p style="font-weight: bold; text-transform: uppercase;">{{ $record->creator->name ?? 'Admin System' }}</p>
                </td>
                <td>
                    <p class="sig-date">Ngày ...... tháng ...... năm ......</p>
                    <p style="font-weight: bold; text-transform: uppercase;">PHÒNG KẾ TOÁN</p>
                    <div style="height: 50px;"></div>
                    <p style="font-weight: bold; text-transform: uppercase;">XÁC NHẬN THU TIỀN</p>
                </td>
                <td>
                    <p class="sig-date">Ngày ...... tháng ...... năm ......</p>
                    <p style="font-weight: bold; text-transform: uppercase;">BAN GIÁM ĐỐC</p>
                    <div style="height: 50px;"></div>
                    <p style="font-weight: bold; text-transform: uppercase;">PHÊ DUYỆT</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
