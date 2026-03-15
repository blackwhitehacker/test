<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hợp đồng mua sắm {{ $contract->contract_number }}</title>
    <style>
        @page { margin: 1.2cm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11.5px; line-height: 1.4; color: #111; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { margin: 5px 0; text-transform: uppercase; font-size: 18px; color: #000; }
        .header p { margin: 2px 0; font-weight: bold; font-size: 11px; }
        
        .section-title { font-weight: bold; text-transform: uppercase; margin-top: 10px; text-decoration: underline; font-size: 11.5px; }
        .info-table { width: 100%; margin-top: 5px; border-collapse: collapse; }
        .info-table td { padding: 2px 0; vertical-align: top; }
        .info-label { width: 150px; font-weight: bold; color: #444; }
        
        table.items { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        table.items th { background-color: #f8f8f8; border: 1px solid #999; padding: 6px 4px; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        table.items td { border: 1px solid #999; padding: 6px 4px; font-size: 11px; word-wrap: break-word; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .footer { margin-top: 25px; width: 100%; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-table td { width: 50%; text-align: center; height: 80px; vertical-align: top; }
        .sig-date { font-style: italic; margin-bottom: 5px; font-size: 11px; }
        
        .legal-notice { margin-top: 15px; font-size: 10px; font-style: italic; color: #666; border-top: 1px dashed #ccc; pt: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <p>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
        <p style="font-size: 11px;">Độc lập - Tự do - Hạnh phúc</p>
        <div style="margin: 10px auto; width: 150px; border-bottom: 2px solid #000;"></div>
        <h1 style="margin-top: 25px; font-size: 24px; font-weight: bold;">HỢP ĐỒNG MUA SẮM</h1>
        <p style="font-size: 13px; margin: 5px 0;">Số: <strong>{{ $contract->contract_number }}</strong></p>
        <p style="font-size: 11px; color: #666;">Mã hệ thống: <strong>{{ $contract->code }}</strong></p>
    </div>

    <p>Hôm nay, ngày {{ now()->format('d') }} tháng {{ now()->format('m') }} năm {{ now()->format('Y') }}, tại văn phòng Công ty, chúng tôi gồm các bên:</p>

    <div class="section-title">BÊN A: BÊN MUA (CHỦ ĐẦU TƯ)</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Tên đơn vị:</td>
            <td style="font-weight: bold; text-transform: uppercase;">{{ $settings['company_name'] ?? 'CÔNG TY QUẢN LÝ TÀI SẢN ENTERPRISE' }}</td>
        </tr>
        <tr>
            <td class="info-label">Địa chỉ:</td>
            <td>{{ $settings['company_address'] ?? 'Số 123, Đường Thành Phố, Việt Nam' }}</td>
        </tr>
        @if(!empty($settings['company_tax_code']))
        <tr>
            <td class="info-label">Mã số thuế:</td>
            <td>{{ $settings['company_tax_code'] }}</td>
        </tr>
        @endif
        @if(!empty($settings['company_representative']))
        <tr>
            <td class="info-label">Đại diện:</td>
            <td>Ông/Bà <strong>{{ $settings['company_representative'] }}</strong> - Chức vụ: {{ $settings['company_role'] ?? 'Giám đốc' }}</td>
        </tr>
        @endif
    </table>

    <div class="section-title">BÊN B: BÊN BÁN (NHÀ CUNG CẤP)</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Tên doanh nghiệp:</td>
            <td style="font-weight: bold;">{{ $contract->partner->name }}</td>
        </tr>
        <tr>
            <td class="info-label">Mã đối tác:</td>
            <td>{{ $contract->partner->code ?? '-' }}</td>
        </tr>
    </table>

    <p>Cùng thống nhất ký kết hợp đồng mua sắm với các điều khoản chi tiết như sau:</p>

    <div class="section-title">ĐIỀU 1: NỘI DUNG VÀ GIÁ TRỊ HỢP ĐỒNG</div>
    <p>Bên A đồng ý mua và Bên B đồng ý bán các mặt hàng được liệt kê chi tiết dưới đây:</p>

    <table class="items">
        <thead>
            <tr>
                <th width="5%">STT</th>
                <th>Tên hàng hóa / Mô tả</th>
                <th width="10%">ĐVT</th>
                <th width="10%">Số lượng</th>
                <th width="15%" class="text-right">Đơn giá</th>
                <th width="15%" class="text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse($items as $index => $item)
                @php 
                    $subtotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                    $total += $subtotal;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item['name'] }}</strong>
                        @if(!empty($item['description']))
                            <br/><small>{{ $item['description'] }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $item['unit'] ?? 'Bộ' }}</td>
                    <td class="text-center">{{ number_format($item['quantity'] ?? 0) }}</td>
                    <td class="text-right">{{ number_format($item['price'] ?? 0) }}</td>
                    <td class="text-right font-bold">{{ number_format($subtotal) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center italic">Không có danh mục hàng hóa chi tiết.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f5f5f5;">
                <td colspan="5" class="text-right font-bold">TỔNG CỘNG GIÁ TRỊ (VNĐ):</td>
                <td class="text-right font-bold" style="font-size: 15px;">{{ number_format($total ?: $contract->value) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="section-title">ĐIỀU 2: ĐIỀU KHOẢN KỸ THUẬT VÀ BẢO HÀNH</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Thời hạn bảo hành:</td>
            <td>{{ $contract->warranty_months }} tháng tính từ ngày bàn giao.</td>
        </tr>
        <tr>
            <td class="info-label">Ngày hiệu lực:</td>
            <td>{{ $contract->signed_date ? $contract->signed_date->format('d/m/Y') : 'Ngay sau khi ký' }}</td>
        </tr>
        <tr>
            <td class="info-label">Ngày hết hạn:</td>
            <td>{{ $contract->expiration_date ? $contract->expiration_date->format('d/m/Y') : 'Theo tiến độ thực hiện' }}</td>
        </tr>
    </table>

    <div class="legal-notice">
        * Bản hợp đồng này được tạo tự động từ hệ thống quản lý tài sản Enterprise. Các bên cam kết thực hiện đúng các điều khoản đã thỏa thuận.
    </div>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td class="text-center">
                    <p class="sig-date">Ngày ...... tháng ...... năm ......</p>
                    <p style="font-weight: bold; text-transform: uppercase;">ĐẠI DIỆN BÊN B</p>
                    <p>(Ký, ghi rõ họ tên và đóng dấu)</p>
                </td>
                <td class="text-center">
                    <p class="sig-date">Ngày ...... tháng ...... năm ......</p>
                    <p style="font-weight: bold; text-transform: uppercase;">ĐẠI DIỆN BÊN A</p>
                    <p>(Ký, ghi rõ họ tên và đóng dấu)</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
