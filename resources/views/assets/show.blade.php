<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center py-2">
            <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase">
                {{ __('Hồ sơ Tài sản Chiến lược') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('assets.edit', $asset) }}" class="btn-enterprise">
                    CẬP NHẬT
                </a>
                <a href="{{ route('assets.index') }}" class="px-4 py-2 bg-gray-900 hover:bg-black text-white font-bold text-[10px] tracking-widest rounded-lg transition-all duration-300 shadow-sm uppercase">
                    QUAY LẠI
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-6 duration-1000">
        <!-- Main Header Card -->
        <div class="card-enterprise overflow-hidden border-t-0 p-0">
            <div class="px-8 py-8 flex flex-col md:flex-row md:items-center justify-between gap-8 bg-gray-900">
                <div class="flex items-center gap-8">
                    <div class="w-20 h-20 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 shadow-xl backdrop-blur-md shrink-0">
                        <svg class="w-10 h-10 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <span class="px-3 py-1 bg-[#E11D48] text-white text-[10px] font-bold uppercase tracking-widest rounded-lg shadow-lg shadow-[#E11D48]/20">{{ $asset->code }}</span>
                            @php
                                $stClass = match($asset->status) {
                                    'inventory' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                    'in_use' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                    'repairing' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    'liquidating' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                    default => 'bg-gray-500/10 text-gray-400 border-gray-500/20'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest border {{ $stClass }}">
                                @switch($asset->status)
                                    @case('inventory') TRONG KHO @break
                                    @case('in_use') ĐANG SỬ DỤNG @break
                                    @case('repairing') ĐANG SỬA CHỮA @break
                                    @case('liquidating') CHỜ THANH LÝ @break
                                    @default {{ strtoupper($asset->status) }}
                                @endswitch
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold text-white tracking-tight mb-2 uppercase">{{ $asset->name }}</h1>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-4">
                            {{ $asset->group->parent->parent->name ?? 'N/A' }} 
                            <span class="mx-2 text-[#E11D48]">/</span> 
                            {{ $asset->group->parent->name ?? '' }} 
                            <span class="mx-2 text-[#E11D48]">/</span> 
                            {{ $asset->group->name ?? '' }}
                        </p>
                    </div>
                </div>
                
                <div class="flex md:flex-col items-end gap-2 text-right">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-white/40">Nguyên giá tài sản</span>
                    <span class="text-4xl font-bold text-[#E11D48] font-mono tracking-tight">{{ number_format($asset->purchase_price, 0, ',', '.') }}<span class="text-sm ml-1 text-white/30">VNĐ</span></span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Details Grid -->
            <div class="lg:col-span-2 space-y-8">
                <div class="card-enterprise p-6">
                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-[#E11D48] border-b border-gray-100 pb-4 mb-6">Thông số định danh & Kỹ thuật</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <div class="group">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1 group-hover:text-[#E11D48] transition-colors">Model / Thương hiệu</label>
                            <span class="text-xs font-bold text-gray-900 uppercase tracking-tight">{{ $asset->model ?? '---' }}</span>
                        </div>
                        <div class="group">
                            @if($asset->group && $asset->group->parent && $asset->group->parent->parent && $asset->group->parent->parent->tracking_type == 'quantity')
                                <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1 group-hover:text-[#E11D48] transition-colors">Trữ lượng vận hành</label>
                                <span class="text-xs font-bold text-[#E11D48] uppercase">{{ $asset->quantity }} đơn vị cơ sở</span>
                            @else
                                <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1 group-hover:text-[#E11D48] transition-colors">Số Serial / IMEI</label>
                                <span class="text-xs font-mono font-bold text-gray-900 tracking-wider">{{ $asset->serial_number ?? '---' }}</span>
                            @endif
                        </div>
                        <div class="group">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1 group-hover:text-[#E11D48] transition-colors">Vị trí hiện tại</label>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-900 border-b border-gray-100">{{ $asset->location ?? 'TRUNG TÂM PHÂN PHỐI' }}</span>
                            </div>
                        </div>
                        <div class="group">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1 group-hover:text-[#E11D48] transition-colors">Đối tác hỗ trợ</label>
                            <span class="text-xs font-bold text-gray-900 underline decoration-[#E11D48] decoration-2 underline-offset-4">{{ $asset->partner->name ?? 'CHƯA GHI NHẬN' }}</span>
                        </div>
                        <div class="group">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1 group-hover:text-[#E11D48] transition-colors">Ngày nhập kho</label>
                            <span class="text-xs font-bold text-gray-900 tracking-widest">{{ $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : '---' }}</span>
                        </div>
                        <div class="group">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1 group-hover:text-[#E11D48] transition-colors">Hạn bảo hành</label>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold tracking-widest {{ $asset->warranty_expiry && $asset->warranty_expiry->isPast() ? 'text-red-500' : 'text-gray-900' }}">
                                    {{ $asset->warranty_expiry ? $asset->warranty_expiry->format('d/m/Y') : 'KHÔNG CÓ' }}
                                </span>
                                @if($asset->warranty_expiry && $asset->warranty_expiry->isPast())
                                    <span class="text-[8px] font-bold uppercase bg-red-100 text-red-600 px-2 py-0.5 rounded">HẾT HẠN</span>
                                @endif
                            </div>
                        </div>
                        <div class="md:col-span-2 group">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-2 group-hover:text-[#E11D48] transition-colors">Cấu hình kỹ thuật</label>
                            <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-100 min-h-[80px] text-xs text-gray-700 leading-relaxed font-bold">
                                {!! nl2br(e($asset->specs ?? 'Thông số kỹ thuật đang được bộ phận chuyên môn cập nhật.')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-enterprise p-6 overflow-hidden relative">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-gray-50 rounded-full blur-3xl opacity-50"></div>
                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-[#E11D48] border-b border-gray-100 pb-4 mb-6">Nhật ký tác động</h3>
                    <div class="relative pl-6 space-y-8 before:absolute before:left-[11px] before:top-1 before:bottom-1 before:w-[2px] before:bg-gray-100 before:rounded-full">
                        @forelse($asset->activityLogs as $log)
                            <div class="relative group">
                                <span class="absolute -left-[18px] top-1 w-3.5 h-3.5 rounded-full border-2 border-white 
                                    @if($log->action == 'create') bg-green-600 @elseif($log->action == 'update') bg-blue-600 @elseif($log->action == 'delete') bg-red-600 @else bg-gray-900 @endif z-10"></span>
                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-gray-900 group-hover:text-[#E11D48] transition-colors">
                                        @switch($log->action)
                                            @case('create') KHỞI TẠO TÀI SẢN @break
                                            @case('update') CẬP NHẬT DỮ LIỆU @break
                                            @case('delete') XÓA TÀI SẢN @break
                                            @default {{ strtoupper($log->action) }}
                                        @endswitch
                                    </span>
                                    <span class="text-[8px] font-bold text-gray-400 tracking-widest">{{ $log->created_at->format('H:i - d/m/Y') }}</span>
                                </div>
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">
                                    {{ $log->user->name ?? 'HỆ THỐNG' }}
                                </p>
                                @if($log->action == 'update' && $log->new_values)
                                    <div class="mt-2 p-2 bg-gray-50/80 rounded-lg border border-gray-100 text-[8px] text-gray-400 font-bold tracking-widest uppercase">
                                        THAY ĐỔI: 
                                        @foreach($log->new_values as $key => $val)
                                            <span class="ml-2 text-gray-900">{{ $key }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-16 opacity-20">
                                <svg class="w-16 h-16 text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-[10px] font-black uppercase tracking-[0.4em]">Cơ sở dữ liệu nhật ký trống</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Financial Card -->
            <div class="space-y-8">
                <div class="card-enterprise p-6 bg-gray-900 border-none relative overflow-hidden group">
                    <div class="absolute -right-20 -bottom-20 w-60 h-60 bg-[#E11D48] rounded-full blur-[100px] opacity-10 group-hover:opacity-20 transition-opacity duration-1000"></div>
                    
                    @if($asset->isDepreciationEndingSoon())
                        <div class="absolute top-0 right-0 overflow-hidden w-32 h-32">
                            <div class="bg-[#E11D48] text-white text-[8px] font-black px-10 py-1.5 rotate-45 translate-x-10 translate-y-4 uppercase shadow-2xl tracking-[0.2em] italic">Cảnh báo</div>
                        </div>
                    @endif

                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-[#E11D48] mb-6">Tài chính & Khấu hao</h3>
                    <div class="space-y-6">
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/10 backdrop-blur-md">
                            <label class="text-[8px] font-bold uppercase tracking-widest text-white/40 block mb-1">Giá trị hiện tại</label>
                            <span class="text-2xl font-bold text-white font-mono tracking-tight">{{ number_format($asset->remaining_value, 0, ',', '.') }}<span class="text-[10px] ml-1 text-white/30">VNĐ</span></span>
                        </div>

                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <label class="text-[8px] font-bold uppercase tracking-widest text-white/40">Tiến trình</label>
                                <span class="text-[9px] font-bold text-[#E11D48] tracking-widest">{{ $asset->months_elapsed }} / {{ $asset->usage_months }} THÁNG</span>
                            </div>
                            <div class="w-full bg-white/5 rounded-full h-2 overflow-hidden border border-white/10">
                                <div class="bg-[#E11D48] h-full rounded-full transition-all duration-1000" style="width: {{ min(100, ($asset->months_elapsed / max(1, $asset->usage_months)) * 100) }}%"></div>
                            </div>
                            @if($asset->isDepreciationEndingSoon())
                                <p class="mt-3 text-[8px] font-bold text-[#E11D48] flex items-center gap-1.5 uppercase tracking-widest">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    GẦN HẾT KHẤU HAO
                                </p>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-6 border-t border-white/10">
                            <div>
                                <label class="text-[8px] font-bold uppercase tracking-widest text-white/30 block mb-0.5">Tổng đầu tư</label>
                                <span class="text-[10px] font-bold text-white">{{ number_format($asset->purchase_price, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <label class="text-[8px] font-bold uppercase tracking-widest text-white/30 block mb-0.5">Giá trị thu hồi</label>
                                <span class="text-[10px] font-bold text-white">{{ number_format($asset->recovery_value, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-white/10">
                            <label class="text-[8px] font-bold uppercase tracking-widest text-[#E11D48] block mb-1">Khấu hao tháng</label>
                            <span class="text-2xl font-bold text-[#E11D48] font-mono tracking-tight">{{ number_format($asset->monthly_depreciation, 0, ',', '.') }}<span class="text-[9px] ml-1 text-white/40 uppercase tracking-widest">đ / tháng</span></span>
                        </div>
                    </div>
                </div>

                <div class="card-enterprise p-6 bg-gray-50/50 border-gray-100">
                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-gray-900 mb-6 border-b border-gray-200 pb-4">Đơn vị sử dụng</h3>
                    <div class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-gray-200">
                        @if($asset->user)
                            <div class="w-10 h-10 bg-gray-900 rounded-xl flex items-center justify-center font-bold text-white text-base uppercase shrink-0">
                                {{ substr($asset->user->name ?? 'S', 0, 1) }}
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-900 block uppercase tracking-tight">{{ $asset->user->name }}</span>
                                <span class="text-[9px] font-bold text-[#E11D48] uppercase tracking-widest mt-0.5 block">Nhân viên</span>
                            </div>
                        @elseif($asset->assigned_department)
                            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-white shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-900 block uppercase tracking-tight">{{ $asset->assigned_department }}</span>
                                <span class="text-[9px] font-bold text-blue-600 uppercase tracking-widest mt-0.5 block">Phòng ban</span>
                            </div>
                        @else
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center font-bold text-gray-300 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-400 block uppercase tracking-tight">Sẵn sàng</span>
                                <span class="text-[9px] font-bold text-gray-300 uppercase tracking-widest mt-0.5 block">Trong kho</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
