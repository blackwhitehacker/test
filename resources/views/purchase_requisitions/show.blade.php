<x-app-layout>
    <x-slot name="header">
        <div class="max-w-6xl mx-auto flex justify-between items-center py-2">
            <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase">
                {{ __('Hồ sơ Tờ trình mua sắm chi tiết') }}
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('purchase_requisitions.index') }}" class="bg-gray-50 hover:bg-gray-200 text-gray-400 hover:text-gray-900 p-2 rounded-lg transition-all duration-300 border border-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <a href="{{ route('purchase_requisitions.export', $requisition) }}" target="_blank" class="btn-enterprise-outline px-4 py-2 flex items-center">
                    PDF
                </a>
                @if($requisition->status == 'pending')
                    <a href="{{ route('purchase_requisitions.edit', $requisition) }}" class="btn-enterprise-danger px-4 py-2">
                        HIỆU CHỈNH
                    </a>
                @endif
                <form action="{{ route('purchase_requisitions.destroy', $requisition) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tờ trình này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-white hover:bg-red-50 text-red-600 border border-red-100 rounded-lg font-bold text-xs tracking-widest uppercase transition-all shadow-sm">
                        XÓA
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-6 duration-1000">
        <!-- Status & Appro        <!-- Status & Approval Banner -->
        @if($requisition->status == 'pending')
            <div class="card-enterprise p-4 bg-amber-50 border-amber-200">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-amber-900 uppercase">Chờ xét duyệt</h3>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <form action="{{ route('purchase_requisitions.approve', $requisition) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-gray-50 hover:bg-gray-200 text-gray-900 border border-gray-200 px-4 py-1.5 rounded-lg font-bold text-xs tracking-widest uppercase transition-all shadow-sm">
                                DUYỆT
                            </button>
                        </form>
                        <button type="button" @click="$dispatch('open-modal', 'reject-modal')" class="bg-white hover:bg-red-50 text-red-600 border border-red-100 px-4 py-1.5 rounded-lg font-bold text-xs tracking-widest uppercase transition-all shadow-sm">
                            TỪ CHỐI
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <div class="card-enterprise p-0">
                    <div class="flex justify-between items-center p-6 border-b border-gray-100 bg-gray-50/30">
                        <div class="min-w-0">
                            <h3 class="font-bold text-[10px] uppercase tracking-[0.2em] text-gray-500 mb-1">Nội dung chi tiết hồ sơ</h3>
                            <h1 class="text-xl font-bold text-gray-900 tracking-tight uppercase truncate leading-tight">{{ $requisition->title }}</h1>
                        </div>
                        <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-widest border shrink-0
                            @if($requisition->status == 'pending') bg-amber-50 text-amber-700 border-amber-200
                            @elseif($requisition->status == 'approved') bg-green-50 text-green-700 border-green-200
                            @else bg-red-50 text-red-700 border-red-200 @endif">
                            {{ $requisition->status == 'pending' ? 'CHỜ DUYỆT' : ($requisition->status == 'approved' ? 'ĐÃ DUYỆT' : 'TỪ CHỐI') }}
                        </span>
                    </div>
                    
                    <div class="p-6">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest block mb-2">Bản giải trình</label>
                        <div class="text-sm text-gray-700 bg-gray-50 p-4 rounded-lg border border-gray-100 whitespace-pre-line leading-relaxed">
                            {{ $requisition->description ?? 'Không có bản giải trình chi tiết.' }}
                        </div>
                    </div>

                    <div class="px-6 pb-6">
                        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                            <h3 class="font-bold text-[10px] uppercase tracking-[0.2em] text-gray-500">Danh mục hàng hóa đề xuất mua sắm</h3>
                        </div>
                        
                        <div class="overflow-x-auto rounded-lg border border-gray-100">
                            <table class="table-premium !border-0 text-sm">
                                <thead>
                                    <tr class="bg-gray-50/50 uppercase tracking-widest text-gray-400 text-[9px]">
                                        <th class="pl-4 py-3 text-left font-bold">Sản Phẩm Đề Xuất</th>
                                        <th class="text-center w-24 py-3 font-bold">Số Lượng</th>
                                        <th class="text-right w-32 py-3 font-bold">Dự Toán Đơn Giá</th>
                                        <th class="pr-4 text-right w-32 py-3 font-bold">Tổng Dự Toán</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($requisition->items ?? [] as $item)
                                        <tr class="group hover:bg-gray-50/50 transition-all">
                                            <td class="pl-4 py-4">
                                                <div class="text-[13px] font-bold text-gray-900 uppercase tracking-tight leading-tight group-hover:text-[#E11D48] transition-colors">{{ $item['name'] }}</div>
                                                <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Quy cách kỹ thuật tiêu chuẩn</div>
                                            </td>
                                            <td class="py-4 text-center">
                                                <span class="text-xs font-bold text-gray-900">{{ $item['quantity'] }}</span>
                                                <span class="text-[10px] font-bold text-gray-400 uppercase ml-0.5 tracking-wider">{{ $item['unit'] ?? 'Bộ' }}</span>
                                            </td>
                                            <td class="py-4 text-right">
                                                <div class="text-xs font-bold text-gray-500 font-mono tracking-tight">{{ number_format($item['estimate']) }}</div>
                                            </td>
                                            <td class="pr-4 py-4 text-right font-bold text-[#E11D48] text-sm tracking-tight text-nowrap">
                                                {{ number_format($item['quantity'] * $item['estimate']) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50/80 border-t border-gray-100">
                                    <tr class="font-bold">
                                        <td colspan="3" class="pl-4 py-3 text-right uppercase text-[11px] tracking-widest text-gray-400">Tổng kinh phí</td>
                                        <td class="pr-4 py-3 text-right text-base text-[#E11D48]">
                                            {{ number_format($requisition->estimated_cost) }} ₫
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Activity Log -->
                <div class="card-enterprise p-6 bg-gray-50/50 border-gray-100">
                    <h3 class="font-bold text-xs uppercase tracking-widest text-gray-900 mb-6 border-b border-gray-200 pb-4">Nhật ký xử lý</h3>
                    <div class="relative pl-6 space-y-6 before:absolute before:left-[11px] before:top-1 before:bottom-1 before:w-[2px] before:bg-gray-200">
                        @forelse($requisition->activityLogs as $log)
                            <div class="relative">
                                <div class="absolute -left-[19px] top-1 w-4 h-4 rounded-full border-2 border-white 
                                    @if($log->action == 'create') bg-blue-500
                                    @elseif($log->action == 'approve') bg-green-500
                                    @elseif($log->action == 'reject') bg-[#E11D48]
                                    @else bg-gray-700 @endif shadow-sm"></div>
                                
                                <p class="text-[11px] font-bold text-gray-900 uppercase">
                                    @if($log->action == 'create') KHỞI TẠO @elseif($log->action == 'approve') PHÊ DUYỆT @elseif($log->action == 'reject') TỪ CHỐI @else {{ strtoupper($log->action) }} @endif
                                </p>
                                <p class="text-[11px] text-gray-500 font-bold italic mt-0.5">Bởi: {{ $log->user->name }}</p>
                                
                                @if($log->action == 'reject' && isset($log->new_values['reason']))
                                    <div class="mt-2 p-2 bg-red-50 rounded border border-red-100 text-[11px] text-red-700 italic border-l-2 border-l-[#E11D48]">
                                        {{ $log->new_values['reason'] }}
                                    </div>
                                @endif

                                <span class="text-[10px] text-gray-400 mt-1 block font-bold uppercase tracking-widest">{{ $log->created_at->format('d/m H:i') }}</span>
                            </div>
                        @empty
                            <div class="text-center py-4 opacity-30">
                                <p class="text-[11px] font-bold uppercase tracking-widest">Trống</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <div class="card-premium p-8 relative overflow-hidden group shadow-sm">
                    <h3 class="font-bold text-xs uppercase tracking-widest text-[#E11D48] mb-6">Định danh hồ sơ</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Mã tờ trình</label>
                            <p class="text-lg font-bold text-gray-900 font-mono">{{ $requisition->code }}</p>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Cán bộ đề xuất</label>
                            <p class="text-xs font-bold text-gray-900 uppercase">{{ $requisition->requester->name }}</p>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Ngày lập</label>
                            <p class="text-xs text-gray-600 uppercase">{{ $requisition->created_at->format('d/m H:i') }}</p>
                        </div>
                        @if($requisition->needed_date)
                        <div class="pt-4 border-t border-gray-100">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Hạn cần hàng</label>
                            <p class="text-xs text-[#E11D48] font-bold uppercase">{{ \Carbon\Carbon::parse($requisition->needed_date)->format('d/m/Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card-enterprise p-8">
                    <h3 class="font-bold text-xs uppercase tracking-widest text-[#E11D48] mb-4 border-b border-gray-100 pb-3">Đính kèm</h3>
                   <div class="space-y-3">
                        @if(isset($requisition->attachments) && is_array($requisition->attachments) && count($requisition->attachments) > 0)
                            @foreach($requisition->attachments as $file)
                                <a href="{{ Storage::url($file['path']) }}" target="_blank" 
                                   class="flex items-center p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-[#E11D48] transition-all group">
                                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-gray-400 group-hover:text-[#E11D48] transition-colors shadow-sm mr-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-xs font-bold text-gray-900 truncate block uppercase">{{ $file['name'] ?? 'Đính kèm' }}</span>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="text-center py-6 opacity-30 select-none">
                                <p class="text-[11px] font-bold uppercase tracking-widest">Không có đính kèm</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
