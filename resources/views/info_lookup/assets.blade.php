<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 uppercase tracking-tight">
                    Tra cứu tài sản theo nhân sự
                </h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Tìm kiếm theo Mã nhân viên, Phòng ban hoặc Trung tâm</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Search Filters -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <form action="{{ route('info_lookup.assets') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div>
                    <label class="enterprise-label">Mã nhân viên</label>
                    <input type="text" name="employee_code" value="{{ $employeeCode }}" 
                           class="input-premium py-2.5" placeholder="VD: NV001...">
                </div>
                <div>
                    <label class="enterprise-label">Phòng ban</label>
                    <input type="text" name="department" value="{{ $department }}" 
                           class="input-premium py-2.5" placeholder="Tên phòng ban...">
                </div>
                <div>
                    <label class="enterprise-label">Trung tâm</label>
                    <input type="text" name="center" value="{{ $center }}" 
                           class="input-premium py-2.5" placeholder="Tên trung tâm...">
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 bg-gray-900 hover:bg-black text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Tìm kiếm
                    </button>
                    <a href="{{ route('info_lookup.assets') }}" class="px-6 py-2.5 bg-gray-100 text-gray-500 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-200 transition-all flex items-center justify-center">
                        Xóa lọc
                    </a>
                </div>
                <input type="hidden" name="search_all" value="1">
            </form>
        </div>

        <!-- Search Results -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
            <div class="p-8">
                @if($assets->total() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100">
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tài sản</th>
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Người đang dùng</th>
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase tracking-widest group">Nhân sự</th>
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($assets as $asset)
                                    <tr class="hover:bg-gray-50/50 transition-colors group">
                                        <td class="p-4">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-gray-900">{{ $asset->name }}</span>
                                                <span class="text-[10px] font-black text-enterprise-red italic">{{ $asset->code }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            @if($asset->user)
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-6 h-6 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] font-black text-gray-400">
                                                        {{ strtoupper(substr($asset->user->name, 0, 1)) }}
                                                    </div>
                                                    <span class="text-xs font-bold text-gray-700">{{ $asset->user->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-[10px] font-bold text-gray-300 italic uppercase">Chưa bàn giao</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            @if($asset->user)
                                                <div class="space-y-0.5">
                                                    <p class="text-[10px] font-black text-gray-900 uppercase tracking-tighter">Mã NV: {{ $asset->user->employee_code ?: 'N/A' }}</p>
                                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $asset->user->department ?: 'N/A' }}</p>
                                                    <p class="text-[10px] font-bold text-gray-400">{{ $asset->user->center ?: '' }}</p>
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="p-4 text-right">
                                            <a href="{{ route('assets.show', $asset) }}" class="inline-flex items-center text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-enterprise-red transition-colors">
                                                Chi tiết
                                                <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-8">
                        {{ $assets->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl mb-4 flex items-center justify-center border border-gray-100/50">
                                <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Không tìm thấy tài sản nào phù hợp</h4>
                            <p class="text-[10px] text-gray-300 mt-2 italic">Hãy thử nhập từ khóa tìm kiếm khác hoặc kiểm tra lại thông tin nhân sự.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
