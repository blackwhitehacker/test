<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center py-2">
            <h2 class="font-bold text-xl text-gray-900 tracking-tighter uppercase">
                {{ __('Hồ sơ Tài sản Chiến lược') }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('assets.edit', $asset) }}" class="flex items-center h-8 px-4 bg-gray-900 text-white rounded-lg text-[10px] font-bold uppercase tracking-widest hover:bg-black transition-all shadow-sm">
                    <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Cập nhật
                </a>
                <a href="{{ route('assets.index') }}" class="flex items-center h-8 px-4 bg-white border border-gray-200 text-gray-400 hover:text-gray-900 hover:border-gray-900 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all italic">
                    Quay lại
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <!-- Main Header Card -->
        <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-sm">
            <div class="px-8 py-8 flex flex-col md:flex-row md:items-center justify-between gap-8 bg-white border-b border-gray-100 italic">
                <div class="flex items-center gap-8 text-gray-900">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center border border-gray-100 shadow-sm shrink-0">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest rounded-lg shadow-sm">{{ $asset->code }}</span>
                            @php
                                $stClass = match($asset->status) {
                                    'inventory' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                    'in_use' => 'bg-green-500/10 text-green-500 border-green-500/20',
                                    'repairing' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                    'liquidating' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
                                    'liquidated' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                    default => 'bg-gray-500/10 text-gray-500 border-gray-500/20'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest border {{ $stClass }}">
                                @switch($asset->status)
                                    @case('inventory') TRONG KHO @break
                                    @case('in_use') ĐANG SỬ DỤNG @break
                                    @case('repairing') ĐANG SỬA CHỮA @break
                                    @case('liquidating') CHỜ THANH LÝ @break
                                    @case('liquidated') ĐÃ THANH LÝ @break
                                    @default {{ strtoupper($asset->status) }}
                                @endswitch
                            </span>
                        </div>
                        <h1 class="text-xl font-bold text-gray-900 tracking-tight mb-1 uppercase">{{ $asset->name }}</h1>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                            {{ $asset->group->parent->parent->name ?? 'N/A' }} 
                            <span class="mx-2 text-gray-200">/</span> 
                            {{ $asset->group->parent->name ?? '' }} 
                            <span class="mx-2 text-gray-200">/</span> 
                            {{ $asset->group->name ?? '' }}
                        </p>
                    </div>
                </div>
                
                <div class="flex md:flex-col items-end gap-1 text-right">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-gray-400">Nguyên giá tài sản</span>
                    <span class="text-2xl font-bold text-gray-900 font-mono tracking-tighter">{{ number_format($asset->purchase_price, 0, ',', '.') }}<span class="text-xs ml-1 text-gray-300 font-sans">VNĐ</span></span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Details Column -->
            <div class="lg:col-span-2 space-y-8">
                <div class="card-enterprise p-6">
                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-gray-500 border-b border-gray-100 pb-4 mb-6">Thông số định danh & Kỹ thuật</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block">Model / Thương hiệu</label>
                            <span class="text-xs font-bold text-gray-900 uppercase tracking-tight">{{ $asset->model ?? '---' }}</span>
                        </div>
                        <div class="space-y-1">
                            @if($asset->group && $asset->group->parent && $asset->group->parent->parent && $asset->group->parent->parent->tracking_type == 'quantity')
                                <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block">Trữ lượng vận hành</label>
                                <span class="text-xs font-bold text-[#E11D48] uppercase">{{ $asset->quantity }} Units</span>
                            @else
                                <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block">Số Serial / IMEI</label>
                                <span class="text-xs font-mono font-bold text-gray-900 tracking-wider">{{ $asset->serial_number ?? '---' }}</span>
                            @endif
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block">Vị trí hiện tại</label>
                            <span class="text-xs font-bold text-gray-900 uppercase">{{ $asset->location ?? 'TRUNG TÂM PHÂN PHỐI' }}</span>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block">Đối tác hỗ trợ</label>
                            <span class="text-xs font-bold text-gray-900 uppercase underline decoration-gray-100 decoration-2 underline-offset-4">{{ $asset->partner->name ?? 'CHƯA GHI NHẬN' }}</span>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block">Ngày nhập kho</label>
                            <span class="text-xs font-bold text-gray-900 tracking-widest">{{ $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : '---' }}</span>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block">Hạn bảo hành</label>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold tracking-widest {{ $asset->warranty_expiry && $asset->warranty_expiry->isPast() ? 'text-red-500' : 'text-gray-900' }}">
                                    {{ $asset->warranty_expiry ? $asset->warranty_expiry->format('d/m/Y') : 'KHÔNG CÓ' }}
                                </span>
                                @if($asset->warranty_expiry && $asset->warranty_expiry->isPast())
                                    <span class="text-[8px] font-bold uppercase bg-red-100 text-red-600 px-2 py-0.5 rounded">HẾT HẠN</span>
                                @endif
                            </div>
                        </div>
                        <div class="md:col-span-2 pt-4">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-2">Cấu hình kỹ thuật</label>
                            <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-100 text-[11px] text-gray-700 leading-relaxed font-medium italic">
                                {!! nl2br(e($asset->specs ?? 'Thông số kỹ thuật đang được bộ phận chuyên môn cập nhật.')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History Table -->
                <div class="card-enterprise p-6">
                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-gray-900 border-b border-gray-100 pb-4 mb-6 italic">Lịch sử điều phối & Biến động</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[8px] font-bold uppercase tracking-widest text-gray-400 border-b border-gray-50">
                                    <th class="pb-3 px-2">Thời gian</th>
                                    <th class="pb-3 px-2">Tác vụ</th>
                                    <th class="pb-3 px-2">Đối tượng</th>
                                    <th class="pb-3 px-2">Trạng thái</th>
                                    <th class="pb-3 px-2 text-right">Mã hồ sơ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($asset->movementHistory->take(5) as $history)
                                    <tr class="text-[10px] hover:bg-gray-50/50 transition-colors">
                                        <td class="py-4 px-2 font-bold text-gray-500 italic">{{ $history->created_at->format('d/m/Y') }}</td>
                                        <td class="py-4 px-2 text-gray-900 font-bold uppercase">{{ $history->request->source_type ?? 'KHÁC' }}</td>
                                        <td class="py-4 px-2 font-bold text-gray-900 uppercase">
                                            {{ $history->request->handoverRecord->receiver_name ?? $history->request->target_name ?? 'HỆ THỐNG' }}
                                        </td>
                                        <td class="py-4 px-2">
                                            <span class="px-2 py-0.5 rounded-full bg-green-50 text-green-700 font-bold border border-green-100 text-[8px] tracking-widest uppercase">Hoàn tất</span>
                                        </td>
                                        <td class="py-4 px-2 text-right font-mono text-gray-400 font-bold">{{ $history->request->code ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-gray-300 font-bold italic uppercase tracking-widest">Chưa ghi nhận biến động</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Side Column -->
            <div class="space-y-8">
                <div class="card-enterprise p-6 border-l-4 border-[#E11D48] shadow-sm">
                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-[#E11D48] mb-6">Tài chính & Khấu hao</h3>
                    <div class="space-y-6">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <label class="text-[8px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Giá trị hiện tại</label>
                            <span class="text-xl font-bold text-gray-900 font-mono tracking-tighter">{{ number_format($asset->remaining_value, 0, ',', '.') }}<span class="text-[10px] ml-1 text-gray-300 font-sans uppercase">VNĐ</span></span>
                        </div>

                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <label class="text-[8px] font-bold uppercase tracking-widest text-gray-400">Tiến trình</label>
                                <span class="text-[9px] font-bold text-[#E11D48] tracking-widest">{{ $asset->months_elapsed }} / {{ $asset->usage_months }} THÁNG</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-[#E11D48] h-full rounded-full transition-all duration-1000" style="width: {{ min(100, ($asset->months_elapsed / max(1, $asset->usage_months)) * 100) }}%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                            <div>
                                <label class="text-[8px] font-bold uppercase tracking-widest text-gray-400 block mb-0.5">Giá trị thu hồi</label>
                                <span class="text-[10px] font-bold text-gray-900">{{ number_format($asset->recovery_value, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <label class="text-[8px] font-bold uppercase tracking-widest text-gray-400 block mb-0.5">Khấu hao tháng</label>
                                <span class="text-[10px] font-bold text-[#E11D48]">{{ number_format($asset->monthly_depreciation, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-enterprise p-6 shadow-sm">
                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-gray-400 mb-6 border-b border-gray-100 pb-4">Đơn vị quản lý</h3>
                    <div class="flex items-center gap-4">
                        @if($asset->user)
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center font-bold text-gray-400 text-sm uppercase">
                                {{ substr($asset->user->name ?? 'S', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-gray-900 uppercase tracking-tight">{{ $asset->user->name }}</p>
                                <p class="text-[8px] font-bold text-[#E11D48] uppercase tracking-widest mt-0.5">Nhân sự nhận</p>
                            </div>
                        @elseif($asset->assigned_department)
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-gray-900 uppercase tracking-tight">{{ $asset->assigned_department }}</p>
                                <p class="text-[8px] font-bold text-blue-600 uppercase tracking-widest mt-0.5">Phòng ban</p>
                            </div>
                        @else
                            <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-gray-400 uppercase italic">Sẵn dụng</p>
                                <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest mt-0.5">Trong kho</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-enterprise p-6 bg-yellow-50/20 border-yellow-100 shadow-sm">
                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-yellow-600 mb-4 italic">Ghi chú hệ thống</h3>
                    <p class="text-[10px] text-gray-500 italic leading-relaxed font-medium">
                        {{ $asset->notes ?? 'Chưa ghi nhận lưu ý bổ sung.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
