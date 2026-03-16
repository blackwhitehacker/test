@extends('reports.pdf_base')

@section('title', 'Báo cáo Tồn kho Tài sản')

@section('content')
<div style="margin-bottom: 10px; font-weight: bold; color: #1e40af;">
    Tổng số tài sản đang trong kho: {{ $assets->count() }}
</div>
<table>
    <thead>
        <tr>
            <th>Mã TS</th>
            <th>Tên tài sản</th>
            <th>Danh mục</th>
            <th>Ngày nhập kho</th>
            <th>Giá trị (đ)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assets as $asset)
            <tr>
                <td style="font-weight: bold;">{{ $asset->code }}</td>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->group->name ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $asset->created_at->format('d/m/Y') }}</td>
                <td style="text-align: right;">{{ number_format($asset->purchase_price) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
