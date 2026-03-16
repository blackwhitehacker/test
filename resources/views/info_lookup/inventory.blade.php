<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 uppercase tracking-tighter">
                    Tra cứu thông tin tồn kho
                </h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Danh sách tài sản đang sẵn sàng cấp phát/sử dụng</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Search Filters -->
        <div class="card-enterprise p-8">
            <form action="{{ route('info_lookup.inventory') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="md:col-span-2">
                    <label class="text-[9px] font-bold uppercase tracking-widest text-gray-500 mb-2 block">Tìm kiếm nhanh</label>
                    <input type="text" name="search" value="{{ $search }}" 
                           class="enterprise-input py-3 text-xs" placeholder="Nhập mã hoặc tên tài sản...">
                </div>
                <div>
                    <label class="text-[9px] font-bold uppercase tracking-widest text-gray-500 mb-2 block">Nhóm tài sản</label>
                    <select name="group_id" class="enterprise-input py-3 text-sm font-bold">
                        <option value="">-- TẤT CẢ NHÓM --</option>
                        @foreach($groups as $category)
                            <optgroup label="{{ strtoupper($category->name) }}">
                                @foreach($category->children as $group)
                                    @foreach($group->children as $line)
                                        <option value="{{ $line->id }}" {{ $groupId == $line->id ? 'selected' : '' }}>
                                            {{ $line->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="btn-enterprise-danger py-3 px-6 flex-1 text-[11px] font-bold tracking-widest">
                        TRUY VẤN
                    </button>
                    <a href="{{ route('info_lookup.inventory') }}" class="bg-gray-100 hover:bg-gray-900 border border-gray-200 text-gray-400 hover:text-white p-3 rounded-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </a>
                </div>
            </form>
        </div>

        <!-- Inventory List -->
        <div class="card-enterprise overflow-hidden border-t-0">
            <div class="px-8 py-4 bg-white flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48] shadow-[0_0_8px_rgba(225,29,72,0.8)]"></div>
                    <h3 class="font-bold text-[9px] uppercase tracking-[0.2em] text-gray-900 leading-none">Thực thể tài sản tồn kho (Sẵn sàng)</h3>
                </div>
            </div>
            <div class="p-0">
                @if($assets->total() > 0)
                    <div class="overflow-x-auto">
                        <table class="table-premium">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100">
                                    <th class="pl-8 !py-5">Mã TS</th>
                                    <th>Tên tài sản</th>
                                    <th>Nhóm/Loại</th>
                                    <th class="!text-right">Số lượng tồn</th>
                                    <th class="pr-8 !text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($assets as $asset)
                                    <tr class="hover:bg-gray-50/50 transition-colors group">
                                        <td class="pl-8 py-5">
                                            <span class="text-[10px] font-bold text-[#E11D48] italic">{{ $asset->code }}</span>
                                        </td>
                                        <td class="py-5">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-gray-900 tracking-tighter group-hover:text-[#E11D48] transition-colors">{{ $asset->name }}</span>
                                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $asset->model ?: 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-5">
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-bold text-gray-900 uppercase tracking-tighter">{{ $asset->group->name }}</span>
                                                <span class="text-[9px] font-bold text-gray-400 uppercase italic">{{ $asset->group->parent->name ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-5 !text-right">
                                            <span class="px-3 py-1 bg-green-50 text-green-700 rounded-lg text-[11px] font-bold border border-green-100 shadow-sm">
                                                {{ number_format($asset->quantity ?: 1) }}
                                            </span>
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
                    <div class="p-32 text-center text-gray-300 italic text-[11px] font-bold uppercase tracking-[0.2em]">
                        Không có dữ liệu tồn kho khả dụng cho bộ lọc này.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
