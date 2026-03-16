<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase">
                {{ __('Tờ trình mua sắm') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('purchase_requisitions.create') }}" 
                   class="!bg-[#E11D48] !text-white px-5 py-2.5 rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-lg shadow-red-900/10 flex items-center space-x-2 transform transition-transform hover:-translate-y-0.5 h-fit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>KHỞI TẠO TỜ TRÌNH MỚI</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <!-- Search & Info area -->
        <div class="card-enterprise p-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <form action="{{ route('purchase_requisitions.index') }}" method="GET" class="md:col-span-7 lg:col-span-8">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 block mb-1.5">Tra cứu hồ sơ tờ trình</label>
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="enterprise-input py-2 pl-9 text-xs" 
                               placeholder="Tìm mã tờ trình, tiêu đề đề xuất...">
                        <svg class="w-3.5 h-3.5 absolute left-3 top-2.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
                
                <div class="md:col-span-5 lg:col-span-4 flex items-center justify-end space-x-4 bg-gray-50/80 px-5 py-2.5 rounded-xl border border-gray-100 shadow-sm self-end">
                    <div class="text-right border-r border-gray-200 pr-4">
                        <span class="text-[10px] font-bold uppercase text-gray-400 tracking-wider block leading-none mb-1">Đang chờ duyệt</span>
                        <span class="text-[14px] font-bold text-gray-800 tracking-tighter"><span class="text-[#E11D48]">{{ sprintf('%02d', $pendingCount) }}</span> Hồ sơ</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold uppercase text-gray-400 tracking-wider block leading-none mb-1">Đã quyết toán</span>
                        <span class="text-[14px] font-bold text-gray-800 tracking-tighter">{{ sprintf('%02d', $approvedCount) }} Hồ sơ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Area -->
        <div class="card-enterprise overflow-hidden border-t-0 border-gray-100 shadow-xl">
            <div class="px-6 py-4 bg-white flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                    <h3 class="font-bold text-[11px] uppercase tracking-[0.2em] text-gray-500 leading-none">Danh sách tờ trình mua sắm</h3>
                </div>
                <div class="flex gap-1.5">
                    <div class="w-1 h-1 rounded-full bg-gray-200"></div>
                    <div class="w-1 h-1 rounded-full bg-gray-100"></div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium" style="table-layout: fixed !important; width: 100% !important; min-width: 1000px;">
                    <thead>
                        <tr class="bg-gray-50/50 uppercase text-gray-400 text-[9px]">
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-left" style="width: 10%;">Mã Hồ Sơ</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-left" style="width: 24%;">Nội Dung Chi Tiết</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-left" style="width: 18%;">Người Khởi Tạo</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-right" style="width: 18%;">Dự Toán (VNĐ)</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-center" style="width: 15%;">Trạng Thái</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-center" style="width: 15%;">Tác Vụ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($requisitions as $requisition)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="px-6 py-5 align-middle !text-left" style="width: 10%;">
                                    <span class="text-[10px] font-bold text-[#E11D48] bg-red-50/50 px-2.5 py-1 rounded border border-red-100 shadow-sm">{{ $requisition->code }}</span>
                                </td>
                                <td class="px-6 py-5 align-middle !text-left" style="width: 24%;">
                                    <div class="text-[14px] font-bold text-gray-900 mb-0.5 uppercase group-hover:text-[#E11D48] transition-colors leading-tight">{{ $requisition->title }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase">{{ count($requisition->items ?? []) }} Danh mục hàng hóa</div>
                                </td>
                                <td class="px-6 py-5 align-middle !text-left" style="width: 18%;">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 bg-white rounded-lg flex items-center justify-center text-[10px] font-bold text-gray-400 shadow-sm border border-gray-100 ring-2 ring-white">
                                            {{ strtoupper(substr($requisition->requester->name, 0, 1)) }}
                                        </div>
                                        <span class="text-[11px] font-bold text-gray-400 uppercase">{{ $requisition->requester->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 !text-right align-middle" style="width: 18%;">
                                    <div class="text-xs font-bold text-gray-900 font-mono">{{ number_format($requisition->estimated_cost) }} <span class="text-gray-400 font-sans ml-0.5">₫</span></div>
                                </td>
                                <td class="px-6 py-5 !text-center align-middle" style="width: 15%;">
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
                                <td class="px-6 py-5 !text-center align-middle" style="width: 15%;">
                                    <div class="td-actions">
                                        <a href="{{ route('purchase_requisitions.show', $requisition) }}" class="btn-enterprise-outline">
                                            <span>CHI TIẾT</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                        <form action="{{ route('purchase_requisitions.destroy', $requisition) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tờ trình này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[11px] font-bold text-gray-300 hover:text-[#E11D48] uppercase transition-all hover:underline decoration-2 underline-offset-4">Xóa</button>
                                        </form>
                                    </div>
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
