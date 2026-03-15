<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 tracking-tight uppercase">
                {{ __('Quản lý Hợp đồng Kinh tế') }}
            </h2>
            <a href="{{ route('contracts.create') }}" class="btn-enterprise">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                KHỞI TẠO HỢP ĐỒNG
            </a>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <!-- Search & Filter Area -->
        <div class="card-enterprise p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                <form action="{{ route('contracts.index') }}" method="GET" class="w-full md:w-1/2">
                    <label class="text-[9px] font-bold uppercase tracking-widest text-gray-500 mb-2 block">Tra cứu số hiệu / đối tác</label>
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="enterprise-input py-3 pl-10 text-sm italic" 
                               placeholder="Tìm kiếm số hiệu, đối tác...">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
                
                <div class="flex items-center space-x-4 bg-gray-50 px-6 py-3 rounded-2xl border border-gray-100">
                    <span class="text-[9px] font-bold uppercase text-gray-400 tracking-widest">Cơ sở dữ liệu:</span>
                    <span class="text-xs font-bold text-gray-900 tracking-tight underline decoration-[#E11D48] decoration-2">{{ $contracts->total() }} văn bản hợp đồng</span>
                </div>
            </div>
        </div>

        <!-- Table Area -->
        <div class="card-enterprise overflow-hidden border-t-0">
            <div class="px-8 py-6 bg-gray-900 flex justify-between items-center">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.1em] text-[#E11D48]">Danh mục hợp đồng hiện hành</h3>
                <div class="flex gap-2">
                    <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                    <div class="w-2 h-2 rounded-full bg-[#E11D48]"></div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="pl-8 !py-5">Mã Ref</th>
                            <th>Số hiệu & Đối tác</th>
                            <th class="text-right">Giá trị văn bản</th>
                            <th class="text-center">Thời điểm ký</th>
                            <th class="text-center">Tình trạng</th>
                            <th class="pr-8 text-right">Điều phối</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($contracts as $contract)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="pl-8 py-6">
                                    <span class="text-[10px] font-bold text-[#E11D48] uppercase bg-red-50 px-2 py-0.5 rounded shadow-sm">{{ $contract->code }}</span>
                                </td>
                                <td class="py-6">
                                    <div class="text-sm font-bold text-gray-900 tracking-tight mb-1">{{ $contract->contract_number }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-hover:text-gray-600 transition-colors">{{ $contract->partner->name }}</div>
                                </td>
                                <td class="py-6 text-right">
                                    <div class="text-base font-bold text-gray-900 font-mono tracking-tight">{{ number_format($contract->value) }}<span class="text-[10px] ml-1 text-[#E11D48]">₫</span></div>
                                </td>
                                <td class="py-6 text-center">
                                    <span class="text-[11px] font-bold text-gray-600 italic tracking-widest">
                                        {{ $contract->signed_date ? $contract->signed_date->format('d/m/Y') : '---' }}
                                    </span>
                                </td>
                                <td class="py-6 text-center">
                                    @php
                                        $stClass = match($contract->status) {
                                            'active' => 'bg-green-50 text-green-700 border-green-200',
                                            'liquidating' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'liquidated' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            default => 'bg-red-50 text-red-700 border-red-200'
                                        };
                                    @endphp
                                    <span class="badge-enterprise {{ $stClass }}">
                                        @if($contract->status == 'active') ĐANG HIỆU LỰC
                                        @elseif($contract->status == 'liquidating') ĐANG THANH LÝ
                                        @elseif($contract->status == 'liquidated') ĐÃ THANH LÝ
                                        @else ĐÃ HỦY @endif
                                    </span>
                                </td>
                                <td class="pr-8 py-6 text-right">
                                    <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                        <a href="{{ route('contracts.show', $contract) }}" class="text-[10px] font-bold text-gray-900 border-b-2 border-transparent hover:border-[#E11D48] hover:text-[#E11D48] tracking-widest transition-all">CHI TIẾT</a>
                                        <a href="{{ route('contracts.edit', $contract) }}" class="text-[10px] font-bold text-gray-900 border-b-2 border-transparent hover:border-blue-600 hover:text-blue-600 tracking-widest transition-all">SỬA</a>
                                        <form action="{{ route('contracts.destroy', $contract) }}" method="POST" class="inline" onsubmit="return confirm('Xác nhận xóa hợp đồng này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[10px] font-bold text-gray-400 border-b-2 border-transparent hover:border-black hover:text-black tracking-widest transition-all">XÓA</button>
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
