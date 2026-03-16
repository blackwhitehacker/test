<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end pb-2 gap-6">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 tracking-tight uppercase leading-none mb-2">
                    {{ __('Hệ thống Danh mục Phân cấp') }}
                </h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-gray-400 italic">Quản lý cấu trúc tài sản đa tầng & Phân loại nghiệp vụ tập trung</span>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row items-stretch md:items-end gap-6 w-full md:w-auto">
                <form action="{{ route('asset_groups.index') }}" method="GET" class="relative group">
                    <label class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-400 block mb-2 italic">Tra cứu phân loại nhanh</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search ?? '' }}" 
                               placeholder="Tìm tên mã, nhóm, tài sản..."
                               class="w-full md:w-72 enterprise-input py-2.5 !pl-10 text-xs italic">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>

                <a href="{{ route('asset_groups.create') }}" 
                   class="bg-[#E11D48] text-white px-4 h-9 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all hover:bg-[#BE123C] shadow-lg shadow-red-900/10 flex items-center justify-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span>KHỞI TẠO DANH MỤC</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in duration-1000">
        <div class="card-enterprise overflow-hidden border-t-0 shadow-2xl">
            <div class="px-8 py-6 bg-white flex justify-between items-center border-b border-gray-100">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.2em] text-gray-500">Ma trận phân cấp tài sản 4 cấp độ</h3>
                <div class="flex space-x-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48] animate-pulse"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-gray-200"></div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/50 uppercase text-gray-400 text-[9px]">
                            <th class="px-6 py-4 font-bold !text-left" style="width: 20%;">Loại Tài Sản</th>
                            <th class="px-6 py-4 font-bold !text-left" style="width: 20%;">Nhóm Tài Sản</th>
                            <th class="px-6 py-4 font-bold !text-left" style="width: 20%;">Dòng Tài Sản</th>
                            <th class="px-6 py-4 font-bold !text-left" style="width: 25%;">Tài Sản Cụ Thể</th>
                            <th class="px-6 py-4 font-bold !text-right" style="width: 15%;">Điều Phối</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($groups as $level1)
                            @foreach($level1->children as $level2)
                                @foreach($level2->children as $level3)
                                    @php $hasAssets = $level3->assets->count() > 0; @endphp
                                    
                                    @if(!$hasAssets)
                                        <tr class="hover:bg-gray-50/50 transition-all group">
                                            <td class="pl-8 py-5">
                                                <div class="text-[11px] font-bold text-gray-500 uppercase tracking-tighter italic">{{ $level1->name }}</div>
                                            </td>
                                            <td class="py-5 border-l border-gray-50">
                                                <div class="text-[11px] font-bold text-gray-500 uppercase tracking-tighter italic px-4">{{ $level2->name }}</div>
                                            </td>
                                            <td class="py-5 border-l border-gray-50">
                                                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-tighter italic px-4">{{ $level3->name }}</div>
                                            </td>
                                            <td class="py-5 border-l border-gray-50 px-4">
                                                <div class="text-[10px] italic text-gray-300 flex items-center uppercase font-bold tracking-widest">
                                                    <span class="w-1 h-3 bg-gray-100 mr-2 rounded-full"></span>
                                                    Dữ liệu trống
                                                </div>
                                            </td>
                                            <td class="pr-8 py-5 text-right whitespace-nowrap">
                                                <div class="flex justify-end items-center space-x-6">
                                                    <a href="{{ route('asset_groups.edit', $level3) }}" class="text-[10px] font-bold text-gray-900 hover:text-[#E11D48] tracking-widest uppercase transition-all">Sửa</a>
                                                    <form action="{{ route('asset_groups.destroy', $level3) }}" method="POST" onsubmit="return confirm('Xác nhận xóa phân cấp này?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-[10px] font-bold text-gray-400 hover:text-red-600 transition-colors uppercase tracking-widest">Xóa</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($level3->assets as $asset)
                                            <tr class="hover:bg-red-50/30 transition-all group">
                                                <td class="pl-8 py-6">
                                                    <div class="text-[11px] font-bold text-gray-900 uppercase tracking-tight">{{ $level1->name }}</div>
                                                </td>
                                                <td class="py-6 border-l border-red-50">
                                                    <div class="text-[11px] font-bold text-gray-700 uppercase tracking-tight px-4">{{ $level2->name }}</div>
                                                </td>
                                                <td class="py-6 border-l border-red-50">
                                                    <div class="text-[11px] font-bold text-gray-600 uppercase tracking-tight px-4">{{ $level3->name }}</div>
                                                </td>
                                                <td class="py-6 border-l border-red-50 px-4">
                                                    <div class="text-[13px] font-bold text-gray-900 uppercase tracking-tight group-hover:text-[#E11D48] transition-colors leading-tight">{{ $asset->name }}</div>
                                                    <div class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-1 shadow-sm inline-block">
                                                        SER_ID: <span class="text-[#E11D48]">{{ $asset->serial_number ?? '---' }}</span>
                                                    </div>
                                                </td>
                                                <td class="pr-8 py-6 text-right whitespace-nowrap">
                                                    <div class="flex justify-end items-center space-x-6">
                                                        <a href="{{ route('assets.show', $asset) }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 tracking-widest uppercase transition-all">Chi tiết</a>
                                                        <a href="{{ route('assets.edit', $asset) }}" class="text-[10px] font-bold text-gray-900 hover:text-[#E11D48] tracking-widest uppercase transition-all">Sửa</a>
                                                        <form action="{{ route('assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Xác nhận xóa tài sản này? Tác vụ không thể hoàn tác.')" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-[10px] font-bold text-gray-400 hover:text-red-600 transition-colors uppercase tracking-widest">Xóa</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach

                                @if($level2->children->count() == 0)
                                    <tr class="hover:bg-gray-50/50 transition-all group opacity-60">
                                        <td class="pl-8 py-5"><div class="text-[11px] font-bold text-gray-400 uppercase italic">{{ $level1->name }}</div></td>
                                        <td class="py-5 border-l border-gray-50"><div class="text-[11px] font-bold text-gray-400 uppercase italic px-4">{{ $level2->name }}</div></td>
                                        <td colspan="2" class="py-5 border-l border-gray-50 px-4"><div class="text-[10px] italic text-gray-300 uppercase font-bold tracking-widest">Cấu trúc dòng hàng chưa khả định</div></td>
                                        <td class="pr-8 py-5 text-right whitespace-nowrap">
                                            <div class="flex justify-end items-center space-x-6">
                                                <a href="{{ route('asset_groups.edit', $level2) }}" class="text-[10px] font-bold text-gray-900 hover:text-[#E11D48] tracking-widest uppercase transition-all">Sửa</a>
                                                <form action="{{ route('asset_groups.destroy', $level2) }}" method="POST" onsubmit="return confirm('Xóa nhóm này sẽ xóa toàn bộ phân cấp con? Thao tác không thể hoàn tác.')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-[10px] font-bold text-gray-400 hover:text-red-600 transition-colors uppercase tracking-widest">Xóa</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @if($level1->children->count() == 0)
                                <tr class="bg-gray-50/30 group opacity-50">
                                    <td class="pl-8 py-6"><div class="text-[11px] font-bold text-gray-400 uppercase italic">{{ $level1->name }}</div></td>
                                    <td colspan="3" class="py-6 border-l border-gray-50 px-4"><div class="text-[10px] italic text-gray-300 uppercase font-bold tracking-[0.3em]">Danh mục loại hình rỗng</div></td>
                                    <td class="pr-8 py-6 text-right whitespace-nowrap">
                                        <div class="flex justify-end items-center space-x-6">
                                            <a href="{{ route('asset_groups.edit', $level1) }}" class="text-[10px] font-bold text-gray-900 hover:text-[#E11D48] tracking-widest uppercase transition-all">Sửa</a>
                                            <form action="{{ route('asset_groups.destroy', $level1) }}" method="POST" onsubmit="return confirm('Xóa loại tài sản này sẽ xóa toàn bộ các nhóm và dòng con? Thao tác không thể hoàn tác.')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[10px] font-bold text-gray-400 hover:text-red-600 transition-colors uppercase tracking-widest">Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="py-44 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <div class="p-10 bg-gray-50 rounded-[2.5rem] mb-8 shadow-inner">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <p class="text-gray-400 font-bold text-[11px] uppercase tracking-[0.4em] italic leading-loose">Hệ thống chưa phát hiện cấu trúc phân loại<br>danh mục tài sản cơ sở</p>
                                        <a href="{{ route('asset_groups.create') }}" class="mt-10 btn-enterprise-danger px-12 py-3 shadow-xl uppercase italic tracking-widest text-[10px]">TẠO PHÂN CẤP ĐẦU TIÊN</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
