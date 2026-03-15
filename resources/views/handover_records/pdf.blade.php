<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 20px;
            margin-bottom: 40px;
        }
        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-grid td {
            vertical-align: top;
            padding-bottom: 15px;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.items-table th, table.items-table td {
            border: 1px solid #000;
            padding: 8px;
        }
        table.items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
        }
        .signature-table {
            width: 100%;
            text-align: center;
        }
        .signature-box {
            height: 100px;
        }
        .signed-seal {
            border: 3px solid #E11D48;
            color: #E11D48;
            padding: 10px;
            display: inline-block;
            transform: rotate(-10deg);
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="font-weight: bold;">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</div>
        <div style="font-size: 11px;">Độc lập - Tự do - Hạnh phúc</div>
        <div style="width: 150px; border-bottom: 1px solid #000; margin: 5px auto;"></div>
    </div>

    <div style="text-align: right; margin-bottom: 20px;">
        <div>Số hiệu: <span style="font-weight: bold; color: #E11D48;">{{ $record->code }}</span></div>
        <div style="font-size: 10px; color: #666;">Ngày: {{ date('d/m/Y', strtotime($record->handover_date)) }}</div>
    </div>

    <div class="title" style="text-align: center;">BIÊN BẢN BÀN GIAO TÀI SẢN</div>

    <table class="info-grid">
        <tr>
            <td width="50%">
                <div class="section-title">Bên bàn giao (Bên A)</div>
                <div>Họ và tên: <span style="font-weight: bold;">{{ $record->creator->name ?? 'Người quản trị hệ thống' }}</span></div>
                <div>Chức vụ: Quản lý tài sản</div>
            </td>
            <td width="50%">
                <div class="section-title">Bên nhận (Bên B)</div>
                <div>Họ và tên / Đơn vị: <span style="font-weight: bold;">{{ $record->receiver_name }}</span></div>
                <div>Bộ phận / Vị trí: {{ $record->receiver_department ?: 'N/A' }} / {{ $record->receiver_position ?: 'Nhân sự' }}</div>
            </td>
        </tr>
    </table>

    <p>Hôm nay, ngày {{ date('d', strtotime($record->handover_date)) }} tháng {{ date('m', strtotime($record->handover_date)) }} năm {{ date('Y', strtotime($record->handover_date)) }}, tại trụ sở Công ty, chúng tôi tiến hành bàn giao các trang thiết bị/tài sản sau đây:</p>

    <table class="items-table">
        <thead>
            <tr>
                <th width="30">STT</th>
                <th width="80">Mã tài sản</th>
                <th>Tên tài sản / Thông số</th>
                <th width="40">SL</th>
                <th width="100">Giá trị (VNĐ)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalValue = 0; @endphp
            @foreach($record->inventoryRequest->items as $index => $item)
                @php $totalValue += ($item->price * $item->quantity); @endphp
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td align="center" style="font-weight: bold;">{{ $item->asset->code ?? 'N/A' }}</td>
                    <td>
                        <div style="font-weight: bold;">{{ $item->name }}</div>
                        <div style="font-size: 10px; color: #666;">{{ $item->specification }}</div>
                    </td>
                    <td align="center">{{ $item->quantity }}</td>
                    <td align="right">{{ number_format($item->price) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td colspan="4" align="right">TỔNG GIÁ TRỊ:</td>
                <td align="right">{{ number_format($totalValue) }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-bottom: 30px;">
        <div style="font-weight: bold;">Ghi chú:</div>
        <p style="font-style: italic;">{{ $record->notes ?: 'Tài sản được bàn giao trong tình trạng hoạt động tốt, mới 100% hoặc theo hiện trạng kho.' }}</p>
        <p>Bên B cam kết sử dụng tài sản đúng mục đích, bảo quản và chịu trách nhiệm bồi thường nếu để xảy ra hư hỏng, mất mát do lỗi chủ quan.</p>
    </div>

    <table class="signature-table">
        <tr>
            <td width="50%">
                <div style="font-weight: bold;">Đại diện Bên A</div>
                <div style="font-size: 10px; font-style: italic; margin-bottom: 20px;">(Ký và ghi rõ họ tên)</div>
                <div class="signature-box">
                    @if($record->status == 'signed')
                        <div class="signed-seal">ĐÃ XÁC NHẬN</div>
                    @endif
                </div>
                <div style="font-weight: bold; text-transform: uppercase;">{{ $record->creator->name ?? '' }}</div>
            </td>
            <td width="50%">
                <div style="font-weight: bold;">Đại diện Bên B</div>
                <div style="font-size: 10px; font-style: italic; margin-bottom: 20px;">(Ký và ghi rõ họ tên)</div>
                <div class="signature-box"></div>
                <div style="font-weight: bold; text-transform: uppercase;">{{ $record->receiver_name }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
