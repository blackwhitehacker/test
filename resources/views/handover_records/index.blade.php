<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-900 tracking-tighter uppercase italic">
                {{ __('Quản lý Biên bản bàn giao') }}
            </h2>
            <div class="flex gap-2">
                <span class="px-4 py-2 bg-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-gray-200 italic">
                    Hệ thống tự động khởi tạo khi duyệt cấp phát
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <!-- Table Area -->
        <div class="card-enterprise">
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th>Mã Biên bản</th>
                            <th>Yêu cầu gốc</th>
                            <th>Người nhận</th>
                            <th>Ngày bàn giao</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($records as $record)
                            <tr class="hover:bg-gray-50/80 transition-all duration-200 group">
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-gray-900 tracking-tight group-hover:text-blue-600 transition-colors uppercase italic">{{ $record->code }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Hệ thống</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <a href="{{ route('business_requests.show', $record->inventory_request_id) }}" class="text-xs font-bold text-gray-600 hover:text-blue-600 underline decoration-blue-100 underline-offset-4 decoration-2">
                                        {{ $record->inventoryRequest->code ?? 'N/A' }}
                                    </a>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-700">{{ $record->receiver_name }}</span>
                                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-tighter opacity-70">
                                            [ {{ $record->receiver_department ?: 'Phòng ban/Cá nhân' }} ]
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-xs font-bold text-gray-600 italic tracking-tighter">
                                        {{ \Carbon\Carbon::parse($record->handover_date)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @php
                                        $statusClasses = [
                                            'draft' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'signed' => 'bg-green-50 text-green-700 border-green-200',
                                            'cancelled' => 'bg-gray-100 text-gray-500 border-gray-200',
                                        ];
                                        $statusLabels = [
                                            'draft' => 'Chưa ký',
                                            'signed' => 'Đã ký',
                                            'cancelled' => 'Đã hủy',
                                        ];
                                    @endphp
                                    <span class="badge-enterprise {{ $statusClasses[$record->status] ?? 'bg-gray-50 text-gray-600' }}">
                                        {{ $statusLabels[$record->status] ?? $record->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('handover_records.show', $record) }}" 
                                           class="p-2 text-gray-400 hover:text-white hover:bg-gray-900 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md" 
                                           title="Xem & Ký">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 border-2 border-dashed border-gray-200">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <span class="text-sm font-black text-gray-400 uppercase tracking-widest italic">Hệ thống chưa ghi nhận biên bản nào</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($records->hasPages())
            <div class="mt-8 card-premium p-4">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
