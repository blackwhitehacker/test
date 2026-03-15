<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 uppercase tracking-tight">Định mức cấp phát tài sản</h2>
            <a href="{{ route('allocation_standards.create') }}" class="enterprise-btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Thêm định mức mới
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Search and Filters -->
        <div class="card-premium p-6">
            <form action="{{ route('allocation_standards.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-wider">Đối tượng</label>
                    <select name="target_type" class="enterprise-input">
                        <option value="">Tất cả đối tượng</option>
                        <option value="individual">Cá nhân</option>
                        <option value="department">Phòng ban</option>
                        <option value="center">Trung tâm</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-wider">Nhóm tài sản</label>
                    <select name="group_id" class="enterprise-input">
                        <option value="">Tất cả nhóm</option>
                        @foreach(\App\Models\AssetGroup::all() as $group)
                            <option value="{{ $group->id }}">[{{ $group->code }}] - {{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-1 tracking-wider">Tìm kiếm</label>
                    <input type="text" name="search" placeholder="Tên đối tượng..." class="enterprise-input">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="enterprise-btn-dark flex-1">Lọc dữ liệu</button>
                    <a href="{{ route('allocation_standards.index') }}" class="enterprise-btn-secondary px-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </a>
                </div>
            </form>
        </div>

        <!-- standards Table -->
        <div class="card-premium overflow-hidden">
            <div class="bg-gray-900 px-6 py-4 flex justify-between items-center">
                <h3 class="text-white text-xs font-bold uppercase tracking-widest">Danh sách định mức cấu hình</h3>
                <span class="text-gray-400 text-[10px] font-bold">{{ $standards->total() }} bản ghi</span>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-wider">Đối tượng</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-wider">Tên đối tượng/Phòng ban</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-wider">Nhóm tài sản</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-wider text-center">Định mức</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-wider">Ghi chú</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 tracking-wider text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($standards as $standard)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 align-middle">
                                @php
                                    $typeClasses = [
                                        'individual' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'department' => 'bg-purple-50 text-purple-600 border-purple-100',
                                        'center' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    ];
                                    $typeLabels = [
                                        'individual' => 'Cá nhân',
                                        'department' => 'Phòng ban',
                                        'center' => 'Trung tâm',
                                    ];
                                @endphp
                                <span class="text-[10px] font-black uppercase px-2 py-1 rounded-md border {{ $typeClasses[$standard->target_type] ?? 'bg-gray-50 text-gray-500 border-gray-100' }}">
                                    {{ $typeLabels[$standard->target_type] ?? $standard->target_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <div class="text-xs font-bold text-gray-800">{{ $standard->target_name }}</div>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <span class="text-[11px] font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $standard->assetGroup->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 align-middle text-center">
                                <span class="text-xs font-black text-[#E11D48]">{{ $standard->limit_quantity }}</span>
                                <span class="text-[10px] text-gray-400 font-bold ml-1">tài sản</span>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <p class="text-[11px] text-gray-400 line-clamp-1 truncate max-w-[200px]">{{ $standard->notes ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 align-middle text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('allocation_standards.edit', $standard) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('allocation_standards.destroy', $standard) }}" method="POST" onsubmit="return confirm('Xóa định mức này?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <p class="text-gray-400 font-bold text-sm">Chưa có định mức cấp phát nào được cấu hình.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $standards->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
