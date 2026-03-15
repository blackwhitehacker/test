<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 tracking-tight uppercase">
                {{ __('Quản lý Tài sản Doanh nghiệp') }}
            </h2>
            <a href="{{ route('assets.create') }}" class="btn-enterprise">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                KHỞI TẠO TÀI SẢN
            </a>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <!-- Search & Filter Bar -->
        <div class="card-enterprise p-8">
            <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-6">Bộ lọc điều phối tài sản</h3>
            <form action="{{ route('assets.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <input type="hidden" name="status" value="{{ $status }}">
                
                <div class="md:col-span-1">
                    <label class="text-[9px] font-bold uppercase tracking-widest text-gray-500 mb-2 block">Từ khóa tìm kiếm</label>
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Tên, mã, serial..."
                               class="enterprise-input py-3 pl-10 text-xs">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="text-[9px] font-bold uppercase tracking-widest text-gray-500 mb-2 block">Phân loại danh mục</label>
                    <select name="group_id" class="enterprise-input py-3 text-sm font-bold">
                        <option value="">-- TOÀN BỘ DANH MỤC --</option>
                        @foreach($groups as $l1)
                            <optgroup label="{{ strtoupper($l1->name) }}">
                                @foreach($l1->children as $l2)
                                    @foreach($l2->children as $l3)
                                        <option value="{{ $l3->id }}" {{ request('group_id') == $l3->id ? 'selected' : '' }}>
                                            {{ $l2->name }} » {{ $l3->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end space-x-3">
                    <button type="submit" class="btn-enterprise py-3 px-8 flex-1 text-[11px] font-bold tracking-widest">TRUY VẤN</button>
                    <a href="{{ route('assets.index') }}" class="bg-gray-100 hover:bg-gray-900 border border-gray-200 text-gray-400 hover:text-white p-3 rounded-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </a>
                </div>
            </form>
        </div>

        <!-- Status Tabs -->
        <div class="flex gap-4 p-1.5 bg-gray-200/50 backdrop-blur-md rounded-2xl w-fit border border-gray-200">
            @foreach(['all' => 'Tất cả', 'inventory' => 'Trong kho', 'in_use' => 'Sử dụng', 'repairing' => 'Sửa chữa', 'liquidating' => 'Thanh lý'] as $key => $label)
                <a href="{{ route('assets.index', array_merge(request()->query(), ['status' => $key])) }}" 
                   class="px-6 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all duration-300 {{ $status == $key ? 'bg-gray-900 text-white shadow-xl scale-105' : 'text-gray-500 hover:text-gray-900 hover:bg-white/80' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="card-enterprise overflow-hidden border-t-0">
            <div class="px-8 py-6 bg-gray-900 flex justify-between items-center">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.1em] text-[#E11D48]">Danh sách thực thể tài sản</h3>
                <span class="text-[9px] font-bold text-white/50 uppercase tracking-widest">Hiển thị {{ $assets->count() }} kết quả</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="pl-8 !py-5">Loại & Nhóm</th>
                            <th>Thế hệ / Dòng</th>
                            <th>Định danh tài sản</th>
                            <th class="pr-8 text-right">Tình trạng</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($assets as $asset)
                            @php
                                $category = $asset->group->parent->parent ?? null;
                                $group = $asset->group->parent ?? null;
                                $line = $asset->group ?? null;
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="pl-8 py-6">
                                    <div class="text-[10px] font-bold text-gray-900 uppercase tracking-tight mb-1">{{ $category->name ?? 'N/A' }}</div>
                                    <div class="text-[11px] font-bold text-gray-400">{{ $group->name ?? 'N/A' }}</div>
                                </td>
                                <td class="py-6">
                                    <div class="text-[11px] font-bold text-gray-800 uppercase tracking-tight">{{ $line->name ?? 'N/A' }}</div>
                                </td>
                                <td class="py-6">
                                    <div class="text-sm font-bold text-gray-900 tracking-tight group-hover:text-[#E11D48] transition-colors">{{ $asset->name }}</div>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="text-[9px] font-bold text-[#E11D48] uppercase bg-red-50 px-1.5 py-0.5 rounded">{{ $asset->code }}</span>
                                        @if($category && $category->tracking_type == 'quantity')
                                            <span class="text-[9px] text-gray-400 font-bold uppercase">SL: {{ $asset->quantity }}</span>
                                        @else
                                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">SN: {{ $asset->serial_number ?? '---' }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="pr-8 py-6 text-right">
                                    <div class="flex flex-col items-end gap-3">
                                        @php
                                            $stClass = match($asset->status) {
                                                'inventory' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                'in_use' => 'bg-green-50 text-green-700 border-green-200 shadow-sm shadow-green-100',
                                                'repairing' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'liquidating' => 'bg-red-50 text-red-700 border-red-200',
                                                default => 'bg-gray-100 text-gray-700 border-gray-200'
                                            };
                                        @endphp
                                        <span class="badge-enterprise {{ $stClass }}">
                                            @switch($asset->status)
                                                @case('inventory') TRONG KHO @break
                                                @case('in_use') ĐANG SỬ DỤNG @break
                                                @case('repairing') SỬA CHỮA @break
                                                @case('liquidating') CHỜ THANH LÝ @break
                                                @default {{ strtoupper($asset->status) }}
                                            @endswitch
                                        </span>
                                        <div class="flex gap-4 opacity-0 group-hover:opacity-100 transition-all">
                                            <a href="{{ route('assets.edit', $asset) }}" class="text-[10px] font-bold text-gray-900 border-b-2 border-transparent hover:border-[#E11D48] hover:text-[#E11D48] tracking-widest transition-all">SỬA</a>
                                            <a href="{{ route('assets.show', $asset) }}" class="text-[10px] font-bold text-gray-900 border-b-2 border-transparent hover:border-[#E11D48] hover:text-[#E11D48] tracking-widest transition-all">XEM</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-8 bg-gray-50 rounded-3xl mb-6 shadow-inner">
                                            <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <p class="text-gray-400 font-bold text-[11px] uppercase tracking-[0.1em] italic">Hệ thống chưa ghi nhận tài sản phù hợp</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($assets->hasPages())
                <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/50">
                    {{ $assets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
