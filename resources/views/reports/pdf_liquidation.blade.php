@extends('reports.pdf_base')

@section('title', 'Báo cáo Thanh lý Tài sản')

@section('content')
<div style="margin-bottom: 10px; font-weight: bold; color: #b91c1c;">
    Tổng giá trị thu hồi: {{ number_format($requests->sum('recovery_value')) }}đ
</div>
<table>
    <thead>
        <tr>
            <th>Mã Yêu cầu</th>
            <th>Tên tài sản</th>
            <th>Mã tài sản</th>
            <th>Giá trị thu hồi</th>
            <th>Ngày hoàn tất</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requests as $request)
            @foreach($request->items as $item)
                <tr>
                    <td style="font-weight: bold;">{{ $request->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td style="text-align: center;">{{ $item->asset->code ?? '---' }}</td>
                    <td style="text-align: right;">{{ number_format($request->recovery_value / $request->items->count()) }}</td>
                    <td style="text-align: center;">{{ $request->updated_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endsection
