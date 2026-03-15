<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 tracking-tight uppercase italic">
                {{ __('Quản lý') }} {{ $type == 'inbound' ? __('nhập kho') : __('xuất kho') }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <div class="card-enterprise">
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th>Mã phiếu</th>
                            <th>Mã yêu cầu</th>
                            <th>Người xử lý</th>
                            <th>Ngày xử lý</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($receipts as $receipt)
                            <tr class="hover:bg-gray-50/80 transition-all duration-200 group">
                                <td class="px-6 py-5">
                                    <span class="text-sm font-bold text-[#E11D48] tracking-tight group-hover:underline">{{ $receipt->code }}</span>
                                </td>

                                <td class="px-6 py-5">
                                    <span class="text-xs font-bold text-gray-700 bg-gray-100 px-2 py-1 rounded">{{ $receipt->request->code }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-[10px] font-bold text-white ring-2 ring-white shadow-sm">
                                            {{ strtoupper(substr($receipt->processor->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-bold text-gray-900">{{ $receipt->processor->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm font-bold text-gray-500">
                                    {{ $receipt->process_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($receipt->status == 'draft')
                                        <span class="badge-enterprise bg-amber-50 text-amber-700 border-amber-200">Lưu nháp</span>
                                    @elseif($receipt->status == 'confirmed')
                                        <span class="badge-enterprise bg-green-50 text-green-700 border-green-200">Hoàn thành</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('inventory_receipts.show', $receipt) }}" class="btn-enterprise-outline scale-90">
                                        Chi tiết
                                        <svg class="ml-2 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
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
