<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Danh sách lô hàng</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; line-height: 1.4; color: #111; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 5px 0; text-transform: uppercase; font-size: 16px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f2f2f2; border: 1px solid #ccc; padding: 8px 4px; font-weight: bold; text-transform: uppercase; font-size: 8px; text-align: left; }
        td { border: 1px solid #ccc; padding: 8px 4px; vertical-align: top; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .status-badge { padding: 2px 5px; border-radius: 3px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .footer { margin-top: 20px; font-size: 8px; font-style: italic; color: #666; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DANH SÁCH LÔ HÀNG GIAO NHẬN</h1>
        <p>Ngày trích xuất: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30px" class="text-center">STT</th>
                <th width="80px">Mã lô hàng</th>
                <th width="100px">Số hợp đồng</th>
                <th>Đối tác cung cấp</th>
                <th width="80px">Ngày giao</th>
                <th width="120px">Người nhận</th>
                <th width="80px" class="text-center">Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipments as $index => $shipment)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $shipment->code }}</strong></td>
                    <td>{{ $shipment->contract->contract_number }}</td>
                    <td>{{ $shipment->contract->partner->name }}</td>
                    <td>{{ $shipment->delivery_date ? $shipment->delivery_date->format('d/m/Y') : '---' }}</td>
                    <td>{{ $shipment->receiver_name ?? '---' }}</td>
                    <td class="text-center">
                        @php
                            $statusLabels = [
                                'pending' => 'Chờ giao',
                                'delivered' => 'Đã giao hàng',
                                'checked' => 'Đã kiểm tra',
                                'received' => 'Đã nhận hàng',
                                'inventoried' => 'Đã vào kho',
                            ];
                        @endphp
                        <span class="status-badge">
                            {{ $statusLabels[$shipment->status] ?? $shipment->status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Hệ thống Quản lý tài sản Enterprise - Trích xuất bởi: {{ Auth::user()->name }}
    </div>
</body>
</html>
