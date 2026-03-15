<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase">
                {{ __('Hệ thống Đề xuất & Tờ trình Mua sắm') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('purchase_requisitions.create') }}" class="btn-enterprise px-6">
                    + KHỞI TẠO TỜ TRÌNH
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <!-- Search & Info area -->
        <div class="card-enterprise p-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <form action="{{ route('purchase_requisitions.index') }}" method="GET" class="md:col-span-7 lg:col-span-8">
                    <label class="text-[9px] font-bold uppercase tracking-widest text-gray-400 block mb-1.5">Tra cứu hồ sơ tờ trình</label>
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="enterprise-input py-2 pl-9 text-xs" 
                               placeholder="Tìm mã tờ trình, tiêu đề đề xuất...">
                        <svg class="w-3.5 h-3.5 absolute left-3 top-2.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
                
                <div class="md:col-span-5 lg:col-span-4 flex items-center justify-end space-x-4 bg-gray-50/80 px-5 py-2.5 rounded-xl border border-gray-100 shadow-sm self-end">
                    <div class="text-right border-r border-gray-200 pr-4">
                        <span class="text-[8px] font-bold uppercase text-gray-400 tracking-wider block leading-none mb-1">Đang chờ duyệt</span>
                        <span class="text-sm font-bold text-[#E11D48] tracking-tight">04 Hồ sơ</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[8px] font-bold uppercase text-gray-400 tracking-wider block leading-none mb-1">Đã quyết toán</span>
                        <span class="text-sm font-bold text-gray-900 tracking-tight">{{ $requisitions->total() }} Hồ sơ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Area -->
        <div class="card-enterprise overflow-hidden border-t-0 border-gray-100 shadow-xl">
            <div class="px-6 py-4 bg-gray-900 flex justify-between items-center border-b border-white/5">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48] shadow-[0_0_8px_rgba(225,29,72,0.8)]"></div>
                    <h3 class="font-bold text-[9px] uppercase tracking-[0.2em] text-white/90 leading-none">Danh sách tờ trình mua sắm tập trung</h3>
                </div>
                <div class="flex gap-1.5">
                    <div class="w-1 h-1 rounded-full bg-white/20"></div>
                    <div class="w-1 h-1 rounded-full bg-white/10"></div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100">
                            <th class="w-32 pl-6 py-3.5 text-left">Mã Hồ Sơ</th>
                            <th class="py-3.5 text-left">Nội Dung Chi Tiết</th>
                            <th class="w-48 py-3.5 text-left">Người Khởi Tạo</th>
                            <th class="w-40 py-3.5 text-right font-mono">Dự Toán (VNĐ)</th>
                            <th class="w-36 py-3.5 text-center">Trạng Thái</th>
                            <th class="w-24 pr-6 py-3.5 text-right">Tác Vụ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($requisitions as $requisition)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="pl-6 py-5 align-middle">
                                    <span class="text-[10px] font-bold text-[#E11D48] bg-red-50/50 px-2.5 py-1 rounded border border-red-100 shadow-sm">{{ $requisition->code }}</span>
                                </td>
                                <td class="py-5 align-middle">
                                    <div class="text-[11px] font-bold text-gray-900 tracking-tight mb-0.5 uppercase group-hover:text-[#E11D48] transition-colors leading-tight">{{ $requisition->title }}</div>
                                    <div class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">{{ count($requisition->items ?? []) }} Danh mục hàng hóa</div>
                                </td>
                                <td class="py-5 align-middle">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 bg-gray-900 rounded-lg flex items-center justify-center text-[10px] font-bold text-white shadow-md ring-2 ring-white">
                                            {{ strtoupper(substr($requisition->requester->name, 0, 1)) }}
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-700 uppercase tracking-tight">{{ $requisition->requester->name }}</span>
                                    </div>
                                </td>
                                <td class="py-5 text-right align-middle pr-4">
                                    <div class="text-xs font-bold text-gray-900 font-mono tracking-tight">{{ number_format($requisition->estimated_cost) }} <span class="text-gray-400 font-sans ml-0.5">₫</span></div>
                                </td>
                                <td class="py-5 text-center align-middle">
                                    <div class="flex justify-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider
                                            @if($requisition->status == 'pending') bg-amber-50 text-amber-700 border border-amber-200
                                            @elseif($requisition->status == 'approved') bg-green-50 text-green-700 border border-green-200
                                            @else bg-red-50 text-red-700 border border-red-200 @endif">
                                            @if($requisition->status == 'pending') CHỜ DUYỆT
                                            @elseif($requisition->status == 'approved') ĐÃ DUYỆT
                                            @else TỪ CHỐI @endif
                                        </span>
                                    </div>
                                </td>
                                <td class="pr-6 py-5 text-right align-middle">
                                    <a href="{{ route('purchase_requisitions.show', $requisition) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-[9px] font-bold text-gray-500 hover:text-[#E11D48] hover:bg-[#E11D48]/5 border border-transparent hover:border-[#E11D48]/10 uppercase tracking-widest transition-all">
                                        Chi tiết
                                        <svg class="w-2.5 h-2.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-8 bg-gray-50 rounded-3xl mb-6 shadow-inner">
                                            <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-gray-400 font-bold text-[11px] uppercase tracking-widest">Không phát hiện dữ liệu tờ trình khả dụng</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($requisitions->hasPages())
            <div class="mt-8 card-enterprise p-4">
                {{ $requisitions->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
