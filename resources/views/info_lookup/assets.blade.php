<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 uppercase tracking-tighter">
                    Tra cứu tài sản theo nhân sự
                </h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Tìm kiếm theo Mã nhân viên, Phòng ban hoặc Trung tâm</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Search Filters -->
        <div class="card-enterprise p-8">
            <form action="{{ route('info_lookup.assets') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div>
                    <label class="text-[9px] font-bold uppercase tracking-widest text-gray-500 mb-2 block">Mã nhân viên</label>
                    <input type="text" name="employee_code" value="{{ $employeeCode }}" 
                           class="enterprise-input py-3 text-xs" placeholder="VD: NV001...">
                </div>
                <div>
                    <label class="text-[9px] font-bold uppercase tracking-widest text-gray-500 mb-2 block">Phòng ban</label>
                    <input type="text" name="department" value="{{ $department }}" 
                           class="enterprise-input py-3 text-xs" placeholder="Tên phòng ban...">
                </div>
                <div>
                    <label class="text-[9px] font-bold uppercase tracking-widest text-gray-500 mb-2 block">Trung tâm</label>
                    <input type="text" name="center" value="{{ $center }}" 
                           class="enterprise-input py-3 text-xs" placeholder="Tên trung tâm...">
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="btn-enterprise-danger py-3 px-6 flex-1 text-[11px] font-bold tracking-widest">
                        TRUY VẤN
                    </button>
                    <a href="{{ route('info_lookup.assets') }}" class="bg-gray-100 hover:bg-gray-900 border border-gray-200 text-gray-400 hover:text-white p-3 rounded-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </a>
                </div>
                <input type="hidden" name="search_all" value="1">
            </form>
        </div>

        <!-- Search Results -->
        <div class="card-enterprise overflow-hidden border-t-0">
            <div class="px-8 py-4 bg-white flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48] shadow-[0_0_8px_rgba(225,29,72,0.8)]"></div>
                    <h3 class="font-bold text-[9px] uppercase tracking-[0.2em] text-gray-900 leading-none">Thực thể tài sản theo nhân sự</h3>
                </div>
            </div>
            <div class="p-0">
                @if($assets->total() > 0)
                    <div class="overflow-x-auto">
                        <table class="table-premium">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100">
                                    <th class="pl-8 !py-5">Tài sản</th>
                                    <th>Người đang dùng</th>
                                    <th>Nhân sự</th>
                                    <th class="pr-8 !text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($assets as $asset)
                                    <tr class="hover:bg-gray-50/50 transition-colors group">
                                        <td class="pl-8 py-5">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-gray-900 tracking-tighter">{{ $asset->name }}</span>
                                                <span class="text-[10px] font-bold text-[#E11D48] italic">{{ $asset->code }}</span>
                                            </div>
                                        </td>
                                        <td class="py-5">
                                            @if($asset->user)
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 rounded-lg bg-gray-900 border-2 border-white shadow-sm flex items-center justify-center text-[11px] font-bold text-white">
                                                        {{ strtoupper(substr($asset->user->name, 0, 1)) }}
                                                    </div>
                                                    <span class="text-xs font-bold text-gray-900 tracking-tighter underline decoration-gray-200 decoration-1 underline-offset-4">{{ $asset->user->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-[10px] font-bold text-gray-300 italic uppercase tracking-widest">Chưa bàn giao</span>
                                            @endif
                                        </td>
                                        <td class="py-5">
                                            @if($asset->user)
                                                <div class="space-y-1">
                                                    <p class="text-[10px] font-bold text-gray-900 uppercase tracking-tighter">Mã NV: {{ $asset->user->employee_code ?: 'N/A' }}</p>
                                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest italic">{{ $asset->user->department ?: 'N/A' }}</p>
                                                </div>
                                            @else
                                                <span class="text-[10px] text-gray-200">---</span>
                                            @endif
                                        </td>
                                        <td class="pr-8 py-5 !text-right">
                                            <a href="{{ route('assets.show', $asset) }}" class="text-[10px] font-bold text-gray-900 border-b-2 border-transparent hover:border-[#E11D48] hover:text-[#E11D48] tracking-[0.2em] transition-all italic">CHI TIẾT</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($assets->hasPages())
                        <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/50">
                            {{ $assets->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl mb-6 flex items-center justify-center border border-gray-100/50 shadow-inner">
                                <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.3em] italic">Không tìm thấy tài sản nào phù hợp</h4>
                            <p class="text-[10px] text-gray-300 mt-3 italic underline decoration-gray-100">Hãy thử nhập từ khóa tìm kiếm khác hoặc kiểm tra lại thông tin nhân sự.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
