<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center py-2">
            <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase">
                {{ __('Hồ sơ Hợp đồng Kinh tế') }}
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('contracts.index') }}" class="bg-gray-100 hover:bg-gray-900 hover:text-white text-gray-400 p-2 rounded-lg transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <a href="{{ route('contracts.export', $contract) }}" class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-lg font-bold text-[10px] tracking-widest uppercase transition-all shadow-sm flex items-center">
                    PDF
                </a>
                <a href="{{ route('contracts.edit', $contract) }}" class="px-4 py-2 bg-[#E11D48] hover:bg-red-700 text-white rounded-lg font-bold text-[10px] tracking-widest uppercase transition-all shadow-sm">
                    CẬP NHẬT
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-6 duration-1000">
        <!-- Main Header Card -->
        <div class="card-enterprise overflow-hidden border-0 p-0">
            <div class="px-8 py-8 flex flex-col md:flex-row md:items-center justify-between gap-8 bg-gray-900">
                <div class="flex items-center gap-8">
                    <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 shadow-xl">
                        <svg class="w-8 h-8 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 bg-[#E11D48] text-white text-[10px] font-bold uppercase tracking-wider rounded-lg">{{ $contract->code }}</span>
                            @php
                                $stClass = match($contract->status) {
                                    'active' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                    'liquidating' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    'liquidated' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                    default => 'bg-red-500/10 text-red-400 border-red-500/20'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider border {{ $stClass }}">
                                @if($contract->status == 'active') ĐANG HIỆU LỰC
                                @elseif($contract->status == 'liquidating') ĐANG THANH LÝ
                                @elseif($contract->status == 'liquidated') ĐÃ THANH LÝ
                                @else ĐÃ HỦY @endif
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold text-white tracking-tight uppercase">{{ $contract->contract_number }}</h1>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-2">
                            Đối tác: <span class="text-white">{{ $contract->partner->name }}</span>
                        </p>
                    </div>
                </div>
                
                <div class="flex flex-col items-end gap-1">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-white/40">Tổng giá trị</span>
                    <span class="text-3xl font-bold text-[#E11D48] font-mono tracking-tight">
                        {{ number_format($contract->value) }}<span class="text-sm ml-1 text-white/30 font-sans">₫</span>
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <div class="card-enterprise p-8">
                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-[#E11D48] border-b border-gray-100 pb-4 mb-8">Thông tin chi tiết</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                        <div class="group">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Mã hệ thống</label>
                            <p class="text-xs font-bold text-gray-900 tracking-wider uppercase">{{ $contract->code }}</p>
                        </div>
                        <div class="group">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Thời hạn bảo hành</label>
                            <p class="text-xs font-bold text-gray-900 uppercase tracking-tight">{{ $contract->warranty_months }} <span class="text-[10px] text-gray-400 font-normal">tháng</span></p>
                        </div>

                        <div class="border-t border-gray-50 pt-6 group">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Ngày ký</label>
                            <p class="text-xs font-bold text-gray-900 tracking-wider">{{ $contract->signed_date ? $contract->signed_date->format('d/m/Y') : '-' }}</p>
                        </div>

                        <div class="border-t border-gray-50 pt-6 group">
                            <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Ngày đáo hạn</label>
                            <p class="text-xs font-bold {{ $contract->expiration_date && $contract->expiration_date->isPast() ? 'text-[#E11D48]' : 'text-gray-900' }} tracking-wider">
                                {{ $contract->expiration_date ? $contract->expiration_date->format('d/m/Y') : 'VÔ THỜI HẠN' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Goods List -->
                <div class="card-enterprise overflow-hidden">
                    @php 
                        $items = $contract->items ?? ($contract->requisition->items ?? []);
                        $hasItems = !empty($items);
                    @endphp

                    <div class="px-8 py-4 bg-gray-50 flex justify-between items-center border-b border-gray-100">
                        <h3 class="font-bold text-[10px] uppercase tracking-widest text-[#E11D48]">Danh mục hàng hóa</h3>
                        @if($hasItems)
                            <a href="{{ route('contracts.export_items', $contract) }}" class="text-[9px] font-bold text-gray-400 hover:text-[#E11D48] uppercase tracking-widest transition-all italic">
                                XUẤT FILE
                            </a>
                        @endif
                    </div>

                    @if($hasItems)
                        <div class="overflow-x-auto">
                            <table class="table-premium !border-0 text-xs">
                                <thead>
                                    <tr class="bg-gray-50/30">
                                        <th class="pl-8 !py-4">Sản phẩm</th>
                                        <th class="text-center w-20">ĐVT</th>
                                        <th class="text-right w-24">SL</th>
                                        <th class="text-right w-32">Đơn giá</th>
                                        <th class="pr-8 text-right w-32">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @php $total = 0; @endphp
                                    @foreach($items as $item)
                                        @php 
                                            $price = $item['price'] ?? 0;
                                            $quantity = $item['quantity'] ?? 0;
                                            $subtotal = $price * $quantity;
                                            $total += $subtotal;
                                        @endphp
                                        <tr class="hover:bg-gray-50/50 transition-all">
                                            <td class="pl-8 py-4">
                                                <div class="text-[11px] font-bold text-gray-900 uppercase tracking-tight">{{ $item['name'] }}</div>
                                                <div class="text-[9px] text-gray-400 mt-0.5">{{ $item['description'] ?? '-' }}</div>
                                            </td>
                                            <td class="py-4 text-center font-bold text-gray-400 text-[9px]">{{ strtoupper($item['unit'] ?? 'BỘ') }}</td>
                                            <td class="py-4 text-right font-bold text-gray-900 font-mono text-[10px]">{{ number_format($quantity) }}</td>
                                            <td class="py-4 text-right font-bold text-gray-900 font-mono text-[10px]">{{ number_format($price) }}</td>
                                            <td class="pr-8 py-4 text-right font-bold text-[#E11D48] font-mono text-[10px]">{{ number_format($subtotal) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50/80 border-t border-gray-100">
                                    <tr>
                                        <td colspan="4" class="pl-8 py-4 text-right font-bold text-gray-400 uppercase tracking-widest text-[9px]">Tổng cộng:</td>
                                        <td class="pr-8 py-4 text-right font-bold text-base text-[#E11D48] tracking-tight font-mono">{{ number_format($total) }} ₫</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="p-8 bg-gray-50 rounded-3xl mb-6 shadow-inner">
                                    <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Không có danh mục hàng hóa</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Linked Requisition -->
                @if($contract->requisition)
                <div class="card-enterprise p-8 border-gray-100">
                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-gray-900 border-b border-gray-100 pb-4 mb-6">Tờ trình liên kết</h3>
                    <a href="{{ route('purchase_requisitions.show', $contract->requisition) }}" 
                       class="flex items-center p-6 rounded-2xl bg-gray-50 border border-gray-200 hover:border-[#E11D48] transition-all group">
                        <div class="p-4 bg-gray-900 rounded-xl text-[#E11D48] mr-6 group-hover:bg-[#E11D48] group-hover:text-white transition-all shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-[9px] font-bold text-[#E11D48] tracking-widest mb-1 italic uppercase">{{ $contract->requisition->code }}</p>
                            <p class="text-base font-bold text-gray-900 tracking-tight uppercase">{{ $contract->requisition->title }}</p>
                            <p class="text-[9px] text-gray-400 mt-1 font-bold uppercase tracking-widest">Ký duyệt: {{ $contract->requisition->created_at->format('d/m/Y') }}</p>
                        </div>
                        <svg class="w-4 h-4 ml-auto text-gray-300 group-hover:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                @endif
            </div>

            <!-- Sidebar Details -->
            <div class="space-y-8">
                <!-- Action Box -->
                <div class="card-enterprise p-8 bg-gray-900 border-none relative overflow-hidden group">
                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-[#E11D48] mb-8">Lệnh điều động</h3>
                    <div class="space-y-3">
                        @if($contract->status == 'active')
                            <form action="{{ route('contracts.liquidate', $contract) }}" method="POST" onsubmit="return confirm('Xác nhận thanh lý?')">
                                @csrf
                                <button type="submit" class="w-full bg-white/5 hover:bg-white/10 text-white border border-white/10 px-4 py-3 rounded-xl font-bold text-[10px] tracking-widest hover:text-[#E11D48] hover:border-[#E11D48] transition-all flex items-center justify-center uppercase">
                                    XÁC NHẬN THANH LÝ
                                </button>
                            </form>

                            <form action="{{ route('contracts.cancel', $contract) }}" method="POST" onsubmit="return confirm('Xác nhận HỦY?')">
                                @csrf
                                <button type="submit" class="w-full bg-white/5 hover:bg-red-900/40 text-white/40 border border-white/5 px-4 py-3 rounded-xl font-bold text-[10px] tracking-widest hover:text-red-500 hover:border-red-900 transition-all flex items-center justify-center uppercase">
                                    HỦY BỎ VĂN KIỆN
                                </button>
                            </form>

                            <div class="pt-4 border-t border-white/10 mt-4">
                                <a href="{{ route('shipments.create', ['contract_id' => $contract->id]) }}" class="w-full bg-[#E11D48] text-white px-4 py-4 rounded-xl font-bold text-[10px] tracking-widest hover:bg-black transition-all flex items-center justify-center shadow-lg uppercase">
                                    KHỞI TẠO LÔ HÀNG
                                </a>
                            </div>
                        @endif

                        <form action="{{ route('contracts.destroy', $contract) }}" method="POST" onsubmit="return confirm('Xác nhận XÓA VĨNH VIỄN?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-white/20 hover:text-red-500 font-bold text-[8px] uppercase tracking-widest pt-4 flex items-center justify-center transition-colors">
                                TIÊU HỦY DỮ LIỆU
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Files Attachment -->
                <div class="card-enterprise p-8">
                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-[#E11D48] mb-6 border-b border-gray-100 pb-4">Tài liệu đính kèm</h3>
                    <div class="space-y-3">
                        @forelse($contract->files ?? [] as $file)
                            <a href="{{ Storage::url($file['path']) }}" target="_blank" 
                               class="flex items-center p-4 rounded-xl bg-gray-50 border border-gray-100 hover:border-[#E11D48] transition-all group">
                                <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center text-white mr-4 group-hover:bg-[#E11D48] transition-colors shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                </div>
                                <span class="text-[10px] font-bold text-gray-900 truncate uppercase">{{ $file['name'] }}</span>
                            </a>
                        @empty
                            <div class="text-center py-6 opacity-30">
                                <p class="text-[9px] font-bold uppercase tracking-widest">Trống</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Activity Log -->
                <div class="card-enterprise p-8 bg-gray-50/50">
                    <h3 class="font-bold text-[10px] uppercase tracking-widest text-gray-900 mb-6 border-b border-gray-200 pb-4">Nhật ký tác động</h3>
                    <div class="relative pl-6 space-y-8 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-[2px] before:bg-gray-200 before:rounded-full">
                        @forelse($contract->activityLogs as $log)
                            <div class="relative">
                                <div class="absolute -left-[19px] top-1 w-4 h-4 rounded-full border-2 border-white 
                                    @if($log->action == 'create') bg-blue-500
                                    @elseif($log->action == 'update') bg-amber-500
                                    @elseif($log->action == 'liquidate') bg-green-500
                                    @elseif($log->action == 'cancel') bg-[#E11D48]
                                    @else bg-gray-900 @endif shadow-sm"></div>
                                
                                <p class="text-[9px] font-bold text-gray-900 uppercase tracking-widest mb-1 truncate">
                                    @if($log->action == 'create') KHỞI TẠO
                                    @elseif($log->action == 'update') ĐIỀU CHỈNH
                                    @elseif($log->action == 'liquidate') THANH LÝ
                                    @elseif($log->action == 'cancel') HỦY BỎ
                                    @else {{ strtoupper($log->action) }} @endif
                                </p>
                                <p class="text-[9px] font-bold text-gray-500 italic">Bởi: {{ strtoupper($log->user->name) }}</p>
                                <span class="text-[8px] font-bold text-gray-400 mt-1 block uppercase tracking-widest">{{ $log->created_at->format('d/m H:i') }}</span>
                            </div>
                        @empty
                            <p class="text-[9px] font-bold text-gray-300 text-center uppercase tracking-widest">Trống</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
