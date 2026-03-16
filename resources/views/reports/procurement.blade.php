<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 tracking-tighter uppercase">
                {{ __('Báo cáo mua sắm tài sản') }}
            </h2>
            <a href="{{ route('reports.export_procurement') }}" class="btn-enterprise-danger">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                XUẤT PDF (A4)
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="card-enterprise p-6">
            <form action="{{ route('reports.procurement') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 block">Năm mua sắm</label>
                    <select name="year" class="enterprise-input text-xs" onchange="this.form.submit()">
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>Năm {{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </form>
        </div>

        <div class="card-enterprise overflow-hidden">
            <div class="px-6 py-4 bg-white flex justify-between items-center border-b border-gray-100">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.1em] text-[#E11D48]">Thống kê hợp đồng mua sắm năm {{ $year }}</h3>
                <span class="text-[10px] text-gray-400 font-bold uppercase">Tổng giá trị: {{ number_format($contracts->sum('value')) }}đ</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th class="pl-6">Số hiệu HĐ</th>
                            <th>Đối tác</th>
                            <th class="!text-right">Giá trị hợp đồng</th>
                            <th class="!text-center">Ngày ký</th>
                            <th class="pr-6 !text-center">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($contracts as $contract)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="pl-6 py-4 font-bold text-gray-900">{{ $contract->contract_number }}</td>
                                <td class="py-4 font-bold text-gray-500 text-xs">{{ $contract->partner->name ?? '---' }}</td>
                                <td class="py-4 !text-right font-mono font-bold text-gray-900">{{ number_format($contract->value) }}đ</td>
                                <td class="py-4 !text-center text-xs font-bold text-gray-400">{{ $contract->signed_date ? $contract->signed_date->format('d/m/Y') : '---' }}</td>
                                <td class="pr-6 py-4 !text-center">
                                    <span class="badge-enterprise {{ $contract->status == 'active' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-100 text-gray-500 border-gray-200' }}">
                                        {{ $contract->status == 'active' ? 'ĐANG HIỆU LỰC' : 'TẠM DỪNG' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
