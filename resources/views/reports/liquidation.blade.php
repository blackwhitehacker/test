<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 tracking-tighter uppercase">
                {{ __('Báo cáo thanh lý tài sản') }}
            </h2>
            <a href="{{ route('reports.export_liquidation') }}" class="btn-enterprise-danger">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                XUẤT PDF (A4)
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card-enterprise p-6 bg-white border-l-4 border-[#E11D48] shadow-sm">
                <div class="text-[10px] uppercase tracking-widest text-[#E11D48] font-bold mb-2">Tổng giá trị thu hồi</div>
                <div class="text-3xl font-bold tracking-tighter text-gray-900">{{ number_format($requests->sum('recovery_value')) }}<span class="text-sm ml-1 text-gray-400">₫</span></div>
            </div>
            <div class="card-enterprise p-6">
                <div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Số lượng tài sản đã thanh lý</div>
                <div class="text-3xl font-bold tracking-tighter text-gray-900">{{ $requests->flatMap->items->count() }}</div>
            </div>
        </div>

        <div class="card-enterprise overflow-hidden">
            <div class="px-6 py-4 bg-white flex justify-between items-center border-b border-gray-100">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.1em] text-[#E11D48]">Danh sách tài sản đã thanh lý hoàn tất</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th class="pl-6">Mã Yêu cầu</th>
                            <th>Tài sản</th>
                            <th class="!text-right">Giá trị thu hồi</th>
                            <th class="!text-center">Ngày hoàn tất</th>
                            <th class="pr-6">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($requests as $request)
                            @foreach($request->items as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="pl-6 py-4 font-bold text-[#E11D48]">{{ $request->code }}</td>
                                    <td class="py-4">
                                        <div class="font-bold text-gray-900">{{ $item->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $item->asset->code ?? '---' }}</div>
                                    </td>
                                    <td class="py-4 !text-right font-mono font-bold text-gray-900">{{ number_format($request->recovery_value / $request->items->count()) }}đ</td>
                                    <td class="py-4 !text-center text-xs font-bold text-gray-400">{{ $request->updated_at->format('d/m/Y') }}</td>
                                    <td class="pr-6 py-4 text-xs italic text-gray-500">{{ $request->liquidation_notes ?? '---' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
