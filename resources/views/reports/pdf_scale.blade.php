@extends('reports.pdf_base')

@section('title', 'Báo cáo Quy mô Tài sản')

@section('content')
<table>
    <thead>
        <tr>
            <th>Tên danh mục</th>
            <th>Số lượng tài sản</th>
            <th>Tổng giá trị ước tính (đ)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($groups as $group)
            <tr>
                <td style="font-weight: bold;">{{ $group->name }}</td>
                <td style="text-align: center;">{{ $group->assets_count }}</td>
                <td style="text-align: right;">{{ number_format($group->assets->sum('purchase_price')) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background-color: #f1f5f9; font-weight: bold;">
            <td>TỔNG CỘNG</td>
            <td style="text-align: center;">{{ $groups->sum('assets_count') }}</td>
            <td style="text-align: right;">{{ number_format($groups->sum(fn($g) => $g->assets->sum('purchase_price'))) }}</td>
        </tr>
    </tfoot>
</table>
@endsection
