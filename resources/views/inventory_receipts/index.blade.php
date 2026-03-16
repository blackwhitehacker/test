<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase">
                {{ __('Quản lý') }} {{ $type == 'inbound' ? __('vận hành nhập kho') : __('vận hành xuất kho') }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <div class="card-enterprise">
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/50 uppercase tracking-widest text-gray-400 text-[9px]">
                            <th class="px-6 py-4 font-bold border-b border-gray-100">Mã Phiếu Vận Hành</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100">Mã Yêu Cầu Gốc</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100">Nhân Sự Xử Lý</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100">Thời Điểm Xử Lý</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 text-center">Trạng Thái</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 text-right">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($receipts as $receipt)
                            <tr class="hover:bg-gray-50/80 transition-all duration-200 group">
                                <td class="px-6 py-5">
                                    <span class="text-[13px] font-bold text-[#E11D48] tracking-tight uppercase group-hover:underline">{{ $receipt->code }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-[11px] font-bold text-gray-700 bg-gray-100 px-2.5 py-1 rounded border border-gray-200 shadow-sm uppercase tracking-tight">{{ $receipt->request->code }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-lg bg-gray-900 flex items-center justify-center text-[10px] font-bold text-white shadow-lg">
                                            {{ strtoupper(substr($receipt->processor->name, 0, 1)) }}
                                        </div>
                                        <span class="text-[11px] font-bold text-gray-900 uppercase tracking-tight">{{ $receipt->processor->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-[11px] font-bold text-gray-500 uppercase tracking-tight">
                                    {{ $receipt->process_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($receipt->status == 'draft')
                                        <span class="badge-enterprise bg-amber-50 text-amber-700 border-amber-200">Lưu nháp</span>
                                    @elseif($receipt->status == 'confirmed')
                                        <span class="badge-enterprise bg-green-50 text-green-700 border-green-200">Hoàn thành</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end">
                                        <a href="{{ route('inventory_receipts.show', $receipt) }}" class="btn-enterprise-outline">
                                            <span>CHI TIẾT</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-2xl mb-4 flex items-center justify-center border-2 border-dashed border-gray-200">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Chưa có phiếu {{ $type == 'inbound' ? 'nhập kho' : 'xuất kho' }} nào</p>
                                        <a href="{{ route('inventory_requests.index', ['type' => $type, 'status' => 'approved']) }}" class="text-xs font-bold text-[#E11D48] hover:underline uppercase tracking-widest decoration-2 underline-offset-8 transition-all">
                                            Tạo phiếu mới từ danh sách yêu cầu →
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($receipts->hasPages())
            <div class="mt-8 card-enterprise p-4">
                {{ $receipts->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
