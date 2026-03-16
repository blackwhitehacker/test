<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 tracking-tighter uppercase">
                {{ __('Báo cáo tài sản') }}
            </h2>
            <a href="{{ route('reports.export_assets') }}" class="btn-enterprise-danger">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                XUẤT PDF (A4)
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="card-enterprise p-6">
            <form action="{{ route('reports.assets') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 block">Phòng ban</label>
                    <select name="department" class="enterprise-input text-xs" onchange="this.form.submit()">
                        <option value="">Tất cả phòng ban</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="card-enterprise overflow-hidden">
            <div class="px-6 py-4 bg-white flex justify-between items-center border-b border-gray-100">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.1em] text-[#E11D48]">Thống kê chi tiết tài sản</h3>
                <span class="text-[10px] text-gray-400 font-bold uppercase">Tổng số: {{ $assets->total() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th class="pl-6">Mã TS</th>
                            <th>Tên tài sản</th>
                            <th>Phòng ban</th>
                            <th>Người sử dụng</th>
                            <th class="!text-right">Giá mua</th>
                            <th class="pr-6 !text-center">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($assets as $asset)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="pl-6 py-4 font-bold text-[#E11D48]">{{ $asset->code }}</td>
                                <td class="py-4 font-bold text-gray-900">{{ $asset->name }}</td>
                                <td class="py-4 text-xs font-bold text-gray-500">{{ $asset->assigned_department ?? '---' }}</td>
                                <td class="py-4 text-xs font-bold text-gray-900">{{ $asset->user->name ?? '---' }}</td>
                                <td class="py-4 !text-right font-mono font-bold">{{ number_format($asset->purchase_price) }}đ</td>
                                <td class="pr-6 py-4 !text-center">
                                    @php
                                        $statusMap = [
                                            'inventory' => ['label' => 'Lưu kho', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                            'in_use' => ['label' => 'Đang sử dụng', 'class' => 'bg-green-50 text-green-700 border-green-200'],
                                            'liquidated' => ['label' => 'Thanh lý', 'class' => 'bg-gray-100 text-gray-500 border-gray-200'],
                                            'repair' => ['label' => 'Đang sửa chữa', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                            'broken' => ['label' => 'Hỏng', 'class' => 'bg-red-50 text-red-700 border-red-200'],
                                        ];
                                        $st = $statusMap[$asset->status] ?? ['label' => strtoupper($asset->status), 'class' => 'bg-gray-50 text-gray-600 border-gray-200'];
                                    @endphp
                                    <span class="badge-enterprise {{ $st['class'] }} !text-[9px] !px-2 !py-0.5">
                                        {{ $st['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">
                {{ $assets->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
