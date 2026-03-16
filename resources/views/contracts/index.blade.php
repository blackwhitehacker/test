<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end pb-2">
            <div>
                <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase leading-none">
                    {{ __('Hệ thống Văn bản Hợp đồng') }}
                </h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-gray-400">Quản lý vòng đời hợp đồng cung ứng</span>
                </div>
            </div>
            <a href="{{ route('contracts.create') }}" class="!bg-[#E11D48] !text-white px-5 py-2.5 rounded-lg font-bold text-[10px] uppercase tracking-wider h-fit flex items-center shadow-lg shadow-red-900/10 transform transition-transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span class="ml-2">TẠO HỢP ĐỒNG MỚI</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <!-- Search & Filter Area -->
        <div class="card-enterprise p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                <form action="{{ route('contracts.index') }}" method="GET" class="w-full md:w-1/2">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 mb-2 block">Tra cứu số hiệu / đối tác</label>
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="enterprise-input py-3 pl-10 text-sm" 
                               placeholder="Tìm kiếm số hiệu, đối tác...">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
                
                <div class="flex items-center space-x-4 bg-gray-50 px-6 py-3 rounded-2xl border border-gray-100">
                    <span class="text-[11px] font-bold uppercase text-gray-400 tracking-widest">Cơ sở dữ liệu:</span>
                    <span class="text-[12px] font-bold text-gray-900 tracking-tighter underline decoration-[#E11D48] decoration-2">{{ $contracts->total() }} văn bản hợp đồng</span>
                </div>
            </div>
        </div>

        <div class="card-enterprise overflow-hidden border-t-0 border-gray-100 shadow-xl">
            <div class="px-6 py-4 bg-white flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                    <h3 class="font-bold text-[11px] uppercase tracking-[0.2em] text-gray-500 leading-none">Danh mục hợp đồng cung ứng hiện hành</h3>
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
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-left" style="width: 26%;">Số Hiệu & Đối Tác</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-right" style="width: 18%;">Giá Trị Văn Bản</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-center" style="width: 16%;">Thời Điểm Ký</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-center" style="width: 15%;">Tình Trạng</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-center" style="width: 15%;">Điều Phối</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($contracts as $contract)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="px-6 py-5 align-middle !text-left" style="width: 10%;">
                                    <span class="text-[10px] font-bold text-[#E11D48] bg-red-50/50 px-2.5 py-1 rounded border border-red-100 shadow-sm">{{ $contract->code }}</span>
                                </td>
                                <td class="px-6 py-5 align-middle !text-left" style="width: 26%;">
                                    <div class="text-[14px] font-bold text-gray-900 mb-0.5 uppercase group-hover:text-[#E11D48] transition-colors leading-tight">{{ $contract->contract_number }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase">{{ $contract->partner->name }}</div>
                                </td>
                                <td class="px-6 py-5 !text-right align-middle" style="width: 18%;">
                                    <div class="text-xs font-bold text-gray-900 font-mono">{{ number_format($contract->value) }} <span class="text-gray-400 font-sans ml-0.5">₫</span></div>
                                </td>
                                <td class="px-6 py-5 !text-center align-middle" style="width: 16%;">
                                    <span class="text-[11px] font-bold text-gray-600">
                                        {{ $contract->signed_date ? $contract->signed_date->format('d/m/Y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 !text-center align-middle" style="width: 15%;">
                                    <div class="flex justify-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider leading-tight
                                            @if($contract->status == 'active') bg-green-50 text-green-700 border border-green-200
                                            @elseif($contract->status == 'liquidating') bg-amber-50 text-amber-700 border border-amber-200
                                            @elseif($contract->status == 'liquidated') bg-blue-50 text-blue-700 border border-blue-200
                                            @else bg-red-50 text-red-700 border border-red-200 @endif">
                                            @if($contract->status == 'active') ĐANG HIỆU LỰC
                                            @elseif($contract->status == 'liquidating') ĐANG THANH LÝ
                                            @elseif($contract->status == 'liquidated') ĐÃ THANH LÝ
                                            @else ĐÃ HỦY @endif
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 !text-center align-middle" style="width: 15%;">
                                    <div class="td-actions">
                                        <a href="{{ route('contracts.show', $contract) }}" class="btn-enterprise-outline">
                                            <span>CHI TIẾT</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                        <form action="{{ route('contracts.destroy', $contract) }}" method="POST" class="inline" onsubmit="return confirm('Xác nhận xóa hợp đồng này?')">
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
                                    <p class="text-gray-400 font-bold text-[11px] uppercase tracking-[0.1em] italic">Không tìm thấy dữ liệu hợp đồng nào phù hợp</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contracts->hasPages())
            <div class="mt-8 card-enterprise p-4">
                {{ $contracts->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
