@extends('reports.pdf_base')

@section('title', 'Báo cáo Mua sắm Tài sản')

@section('content')
<table>
    <thead>
        <tr>
            <th>Số hiệu HĐ</th>
            <th>Đối tác</th>
            <th>Giá trị (đ)</th>
            <th>Ngày ký</th>
            <th>Trạng thái</th>
        </tr>
    </thead>
    <tbody>
        @foreach($contracts as $contract)
            <tr>
                <td style="font-weight: bold;">{{ $contract->contract_number }}</td>
                <td>{{ $contract->partner->name ?? 'N/A' }}</td>
                <td style="text-align: right;">{{ number_format($contract->value) }}</td>
                <td style="text-align: center;">{{ $contract->signed_date ? $contract->signed_date->format('d/m/Y') : '---' }}</td>
                <td style="text-align: center;">{{ strtoupper($contract->status) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background-color: #f1f5f9; font-weight: bold;">
            <td colspan="2">TỔNG GIÁ TRỊ ĐẦU TƯ</td>
            <td style="text-align: right;">{{ number_format($contracts->sum('value')) }}</td>
            <td colspan="2"></td>
        </tr>
    </tfoot>
</table>
@endsection
