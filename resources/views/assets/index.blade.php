<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end pb-2 gap-6">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 tracking-tight uppercase leading-none mb-2">
                    {{ __('Quản lý Tài sản Doanh nghiệp') }}
                </h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-gray-400 italic">Kiểm soát và quản trị danh mục tài sản hiện hữu trong hệ sinh thái</span>
                </div>
            </div>
            
            <a href="{{ route('assets.create') }}" 
               class="bg-[#E11D48] text-white px-4 h-9 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all hover:bg-[#BE123C] shadow-lg shadow-red-900/10 flex items-center justify-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>KHỞI TẠO TÀI SẢN MỚI</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <!-- Search & Filter Bar -->
        <div class="card-enterprise p-8 bg-white">
            <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#E11D48] mb-6 italic underline decoration-red-100 underline-offset-8">Bộ lọc điều phối tài sản v2.0</h3>
            <form action="{{ route('assets.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <input type="hidden" name="status" value="{{ $status }}">
                
                <div class="md:col-span-1">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2 block">Từ khóa định danh</label>
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Tên, mã, serial..."
                               class="enterprise-input py-3 !pl-10 text-[13px] font-bold italic">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2 block">Phân loại danh mục tập trung</label>
                    <select name="group_id" class="enterprise-input py-3 text-[13px] font-bold">
                        <option value="">-- TOÀN BỘ DANH MỤC HỆ THỐNG --</option>
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
                    <button type="submit" class="bg-gray-900 text-white py-3 px-8 flex-1 text-[10px] font-bold tracking-[0.2em] rounded-xl shadow-lg hover:bg-black transition-all transform active:scale-95 italic uppercase">TRUY VẤN</button>
                    <a href="{{ route('assets.index') }}" class="bg-gray-100 border border-gray-200 text-gray-400 hover:text-[#E11D48] p-3 rounded-xl transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </a>
                </div>
            </form>
        </div>

        <!-- Status Tabs -->
        <div class="flex gap-4 p-1.5 bg-gray-200/50 backdrop-blur-md rounded-2xl w-fit border border-gray-200 mx-auto md:mx-0 shadow-inner">
            @foreach(['all' => 'Tất cả', 'inventory' => 'Trong kho', 'in_use' => 'Sử dụng', 'repairing' => 'Sửa chữa', 'liquidating' => 'Chờ thanh lý', 'liquidated' => 'Đã thanh lý'] as $key => $label)
                <a href="{{ route('assets.index', array_merge(request()->query(), ['status' => $key])) }}" 
                   class="px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all duration-300 {{ $status == $key ? 'bg-gray-900 text-white shadow-xl scale-105' : 'text-gray-500 hover:text-gray-900 hover:bg-white/80' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="card-enterprise overflow-hidden border-t-0 shadow-2xl">
            <div class="px-8 py-6 bg-white flex justify-between items-center border-b border-gray-100">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.2em] text-gray-500">Danh sách thực thể tài sản hiện hữu</h3>
                <span class="text-[9px] font-bold text-gray-300 uppercase tracking-widest italic">Hiển thị {{ $assets->count() }} kết quả đồng bộ</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium" style="table-layout: fixed !important; width: 100% !important; min-width: 1000px;">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 uppercase text-gray-400 text-[9px] tracking-widest">
                            <th class="px-8 py-4 font-bold !text-left" style="width: 20%;">Loại & Nhóm</th>
                            <th class="px-6 py-4 font-bold !text-left" style="width: 15%;">Thế hệ / Dòng</th>
                            <th class="px-6 py-4 font-bold !text-left" style="width: 30%;">Định danh tài sản</th>
                            <th class="px-6 py-4 font-bold !text-center" style="width: 15%;">Tình trạng</th>
                            <th class="px-8 py-4 font-bold !text-right" style="width: 20%;">Điều phối</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($assets as $asset)
                            @php
                                $category = $asset->group->parent->parent ?? null;
                                $group = $asset->group->parent ?? null;
                                $line = $asset->group ?? null;
                            @endphp
                            <tr class="hover:bg-gray-50/20 transition-all group">
                                <td class="px-8 py-6 align-middle !text-left">
                                    <div class="text-[12px] font-bold text-gray-900 uppercase tracking-tight mb-1">{{ $category->name ?? 'N/A' }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest italic">{{ $group->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-6 align-middle !text-left">
                                    <div class="text-[12px] font-bold text-gray-800 uppercase tracking-tight">{{ $line->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-6 align-middle !text-left">
                                    <div class="text-[14px] font-bold text-gray-900 tracking-tight group-hover:text-[#E11D48] transition-colors uppercase leading-tight">{{ $asset->name }}</div>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="text-[9px] font-bold text-[#E11D48] uppercase bg-red-50 px-2 py-0.5 rounded shadow-sm border border-red-100">{{ $asset->code }}</span>
                                        @if($category && $category->tracking_type == 'quantity')
                                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">SL: {{ $asset->quantity }}</span>
                                        @else
                                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest italic">SN: {{ $asset->serial_number ?? '---' }}</span>
                                        @endif
                                        @if($asset->isDepreciationEndingSoon())
                                            <div class="ml-auto flex items-center gap-1.5" title="Sắp hết khấu hao (< 3 tháng)">
                                                <svg class="w-3 h-3 text-[#E11D48] animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-6 !text-center align-middle">
                                    @php
                                        $stClass = match($asset->status) {
                                            'inventory' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'in_use' => 'bg-green-50 text-green-700 border-green-200 shadow-sm shadow-green-100',
                                            'repairing' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'liquidating' => 'bg-orange-50 text-orange-700 border-orange-200',
                                            'liquidated' => 'bg-red-50 text-red-700 border-red-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200'
                                        };
                                    @endphp
                                    <span class="badge-enterprise {{ $stClass }} !text-[9px] !px-4 !py-1">
                                        @switch($asset->status)
                                            @case('inventory') TRONG KHO @break
                                            @case('in_use') ĐANG SỬ DỤNG @break
                                            @case('repairing') SỬA CHỮA @break
                                            @case('liquidating') CHỜ THANH LÝ @break
                                            @case('liquidated') ĐÃ THANH LÝ @break
                                            @default {{ strtoupper($asset->status) }}
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-8 py-6 !text-right align-middle whitespace-nowrap">
                                    <div class="flex justify-end items-center space-x-6">
                                        <a href="{{ route('assets.show', $asset) }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 tracking-widest uppercase transition-all">Chi tiết</a>
                                        <a href="{{ route('assets.edit', $asset) }}" class="text-[10px] font-bold text-gray-900 hover:text-[#E11D48] tracking-widest uppercase transition-all">Sửa</a>
                                        <form action="{{ route('assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Xác nhận xóa tài sản này?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-[10px] font-bold text-gray-400 hover:text-red-600 tracking-widest uppercase transition-all">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-44 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <div class="p-10 bg-gray-50 rounded-[2.5rem] mb-8 shadow-inner">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <p class="text-gray-400 font-bold text-[11px] uppercase tracking-[0.4em] italic leading-loose">Hệ thống chưa ghi nhận<br>thực thể tài sản nào</p>
                                        <a href="{{ route('assets.create') }}" class="mt-10 btn-enterprise-danger px-12 py-3 shadow-xl uppercase italic tracking-widest text-[10px]">TẠO TÀI SẢN ĐẦU TIÊN</a>
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
