<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 uppercase tracking-tight">
                    Tra cứu thông tin tồn kho
                </h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Danh sách tài sản đang sẵn sàng cấp phát/sử dụng</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Search Filters -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <form action="{{ route('info_lookup.inventory') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="md:col-span-2">
                    <label class="enterprise-label">Tìm kiếm nhanh</label>
                    <input type="text" name="search" value="{{ $search }}" 
                           class="input-premium py-2.5" placeholder="Nhập mã hoặc tên tài sản...">
                </div>
                <div>
                    <label class="enterprise-label">Nhóm tài sản</label>
                    <select name="group_id" class="input-premium py-2.5">
                        <option value="">-- Tất cả nhóm --</option>
                        @foreach($groups as $category)
                            <optgroup label="{{ $category->name }}">
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
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 bg-enterprise-red hover:bg-red-700 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-900/20 transition-all">
                        Lọc dữ liệu
                    </button>
                    <a href="{{ route('info_lookup.inventory') }}" class="px-6 py-2.5 bg-gray-100 text-gray-500 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-200 transition-all flex items-center justify-center">
                        Xóa
                    </a>
                </div>
            </form>
        </div>

        <!-- Inventory List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
            <div class="p-8">
                @if($assets->total() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100">
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Mã TS</th>
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tên tài sản</th>
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nhóm/Loại</th>
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Số lượng tồn</th>
                                    <th class="p-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($assets as $asset)
                                    <tr class="hover:bg-gray-50/50 transition-colors group">
                                        <td class="p-4">
                                            <span class="text-xs font-black text-enterprise-red italic">{{ $asset->code }}</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-gray-900">{{ $asset->name }}</span>
                                                <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $asset->model ?: 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-black text-gray-900 uppercase tracking-tighter">{{ $asset->group->name }}</span>
                                                <span class="text-[9px] font-bold text-gray-400 uppercase">{{ $asset->group->parent->name ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4 text-right">
                                            <span class="px-3 py-1 bg-green-50 text-green-700 rounded-lg text-xs font-black border border-green-100">
                                                {{ number_format($asset->quantity ?: 1) }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-right">
                                            <a href="{{ route('assets.show', $asset) }}" class="inline-flex items-center text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-enterprise-red transition-colors">
                                                Chi tiết
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
                    <div class="p-12 text-center text-gray-400 italic text-xs">
                        Hiện không có tài sản nào đang ở trạng thái tồn kho phù hợp với điều kiện lọc.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
