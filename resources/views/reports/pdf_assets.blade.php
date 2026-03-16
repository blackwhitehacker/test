@extends('reports.pdf_base')

@section('title', 'Báo cáo Tổng hợp Tài sản')

@section('content')
<table>
    <thead>
        <tr>
            <th>Mã TS</th>
            <th>Tên tài sản</th>
            <th>Danh mục</th>
            <th>Đối tác</th>
            <th>Giá trị (đ)</th>
            <th>Trạng thái</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assets as $asset)
            <tr>
                <td style="font-weight: bold; color: #E11D48;">{{ $asset->code }}</td>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->group->name ?? 'N/A' }}</td>
                <td>{{ $asset->partner->name ?? 'N/A' }}</td>
                <td style="text-align: right;">{{ number_format($asset->purchase_price) }}</td>
                <td style="text-align: center;">
                    <span class="status-badge" style="background-color: {{ $asset->status == 'inventory' ? '#eff6ff' : '#f0fdf4' }}; color: {{ $asset->status == 'inventory' ? '#1d4ed8' : '#15803d' }};">
                        {{ strtoupper($asset->status) }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
