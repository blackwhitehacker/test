<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Biên bản hoàn trả {{ $record->code }}</title>
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
        .signature-table td { width: 50%; text-align: center; vertical-align: top; }
        .sig-date { font-style: italic; margin-bottom: 5px; font-size: 10px; }
        
        .signed-seal {
            margin: 5px auto;
            border: 2px solid #E11D48;
            color: #E11D48;
            padding: 4px 8px;
            width: fit-content;
            transform: rotate(-10deg);
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        
        .notice { margin-top: 15px; font-size: 9px; font-style: italic; color: #666; border-top: 1px dashed #ccc; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <p>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
        <p>Độc lập - Tự do - Hạnh phúc</p>
        <div style="margin: 10px auto; width: 160px; border-bottom: 1px solid #000;"></div>
        <h1>BIÊN BẢN HOÀN TRẢ TÀI SẢN</h1>
        <div class="doc-id">Số: <strong>{{ $record->code }}</strong></div>
        <div class="doc-id">Mã hệ thống: <strong>YC-{{ $record->inventoryRequest->code }}</strong></div>
    </div>

    <p style="margin-bottom: 15px;">Hôm nay, ngày {{ date('d', strtotime($record->handover_date)) }} tháng {{ date('m', strtotime($record->handover_date)) }} năm {{ date('Y', strtotime($record->handover_date)) }}, tại trụ sở Công ty, chúng tôi gồm các bên:</p>

    <div class="section-title"><span>BÊN A: BÊN NHẬN (ĐẠI DIỆN HỆ THỐNG / KHO)</span></div>
    <table class="info-table">
        <tr>
            <td class="info-label">Tên đơn vị:</td>
            <td style="font-weight: bold; text-transform: uppercase;">CÔNG TY QUẢN LÝ TÀI SẢN ENTERPRISE</td>
        </tr>
        <tr>
            <td class="info-label">Đại diện:</td>
            <td><strong>{{ $record->creator->name ?? 'Admin System' }}</strong> - Chức vụ: Quản trị tài sản</td>
        </tr>
    </table>

    <div class="section-title"><span>BÊN B: BÊN HOÀN TRẢ (CÁ NHÂN / PHÒNG BAN)</span></div>
    <table class="info-table">
        <tr>
            <td class="info-label">Họ và tên / Đơn vị:</td>
            <td style="font-weight: bold; text-transform: uppercase;">{{ $record->receiver_name }}</td>
        </tr>
        <tr>
            <td class="info-label">Lý do hoàn trả:</td>
            <td>Thu hồi theo yêu cầu hệ thống / Điều động công tác</td>
        </tr>
    </table>

    <p style="margin-top: 20px;">Cùng thống nhất thực hiện việc hoàn trả tài sản với các điều khoản và danh mục chi tiết như sau:</p>

    <div class="section-title">ĐIỀU 1: DANH MỤC TÀI SẢN HOÀN TRẢ</div>
    <table class="items">
        <thead>
            <tr>
                <th width="30">STT</th>
                <th>TÊN TÀI SẢN / MÔ TẢ CHI TIẾT</th>
                <th width="50">ĐVT</th>
                <th width="50">SỐ LƯỢNG</th>
                <th width="120" class="text-center">TÌNH TRẠNG</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->inventoryRequest->items as $index => $item)
                <tr>
                    <td class="text-center font-bold">{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight: bold;">{{ $item->name }}</div>
                        @if($item->asset)
                            <div style="font-size: 10px; color: #444;">Mã TS: {{ $item->asset->code }}</div>
                        @endif
                        <div style="font-size: 10px; color: #444;">{{ $item->specification }}</div>
                    </td>
                    <td class="text-center">Cái</td>
                    <td class="text-center font-bold">{{ $item->quantity }}</td>
                    <td class="text-center font-bold uppercase">
                        @php
                            $aStatusConf = [
                                'safe' => 'Sử dụng tốt',
                                'damaged' => 'Cần sửa chữa',
                                'broken' => 'Hỏng/Thanh lý',
                            ];
                            echo $aStatusConf[$record->inventoryRequest->assessment_status] ?? 'Chưa xác định';
                        @endphp
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">ĐIỀU 2: XÁC NHẬN VÀ CAM KẾT</div>
    <div style="margin-top: 10px;">
        <p>1. <strong>Xác nhận:</strong> Bên A đã nhận đủ số lượng tài sản nêu trên từ Bên B.</p>
        <p>2. <strong>Kết luận đánh giá:</strong> Tài sản được đánh giá là: <strong>{{ strtoupper($aStatusConf[$record->inventoryRequest->assessment_status] ?? 'CHƯA XÁC ĐỊNH') }}</strong>.</p>
        <p>3. <strong>Giải quyết công nợ:</strong> Kể từ thời điểm ký biên bản này, Bên B được giải trừ trách nhiệm đối với danh mục tài sản nêu trên trong hồ sơ công nợ nội bộ.</p>
        <p>4. <strong>Ghi chú bổ sung:</strong> {{ $record->inventoryRequest->assessment_notes ?: 'Không có.' }}</p>
    </div>

    <div class="notice">
        * Biên bản này được tạo tự động từ hệ thống quản lý tài sản Enterprise. Các bên cam kết thực hiện đúng trách nhiệm.
    </div>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td>
                    <p class="sig-date">Ngày ...... tháng ...... năm ......</p>
                    <p style="font-weight: bold; text-transform: uppercase;">ĐẠI DIỆN BÊN B</p>
                    <p style="font-size: 9px;">(Ký và ghi rõ họ tên)</p>
                    <div style="height: 60px;"></div>
                    <p style="font-weight: bold; text-transform: uppercase;">{{ $record->receiver_name }}</p>
                </td>
                <td>
                    <p class="sig-date">Ngày ...... tháng ...... năm ......</p>
                    <p style="font-weight: bold; text-transform: uppercase;">ĐẠI DIỆN BÊN A</p>
                    <p style="font-size: 9px;">(Ký và ghi rõ họ tên)</p>
                    <div style="height: 60px; display: flex; align-items: center; justify-content: center;">
                        @if($record->status == 'signed')
                            <div class="signed-seal">ĐÃ XÁC NHẬN</div>
                        @endif
                    </div>
                    <p style="font-weight: bold; text-transform: uppercase;">{{ $record->creator->name ?? 'ADMIN SYSTEM' }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
