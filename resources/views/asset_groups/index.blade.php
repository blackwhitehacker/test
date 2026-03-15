<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h2 class="font-black text-2xl text-gray-900 tracking-tighter uppercase italic">
                    {{ __('Hệ thống Danh mục Phân cấp') }}
                </h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1 italic">Quản lý cấu trúc tài sản đa tầng & Phân loại nghiệp vụ tập trung</p>
            </div>
            
            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 w-full md:w-auto">
                <form action="{{ route('asset_groups.index') }}" method="GET" class="relative group">
                    <label class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 block mb-2 italic">Tra cứu phân loại nhanh</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search ?? '' }}" 
                               placeholder="Tìm tên mã, nhóm, tài sản..."
                               class="w-full md:w-72 enterprise-input py-2.5 !pl-10 text-xs italic">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>

                <div class="flex items-end">
                    <a href="{{ route('asset_groups.create') }}" class="btn-enterprise py-3 px-8 shadow-xl">
                        + KHỞI TẠO DANH MỤC
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in duration-1000">
        <div class="card-enterprise overflow-hidden border-t-0 shadow-2xl">
            <div class="px-8 py-6 bg-gray-900 flex justify-between items-center">
                <h3 class="font-black text-[10px] uppercase tracking-[0.3em] italic text-[#E11D48]">Ma trận phân cấp tài sản 4 cấp độ</h3>
                <div class="flex space-x-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48] animate-pulse"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-white/20"></div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="pl-8 !py-5 uppercase text-[10px] tracking-[0.2em] text-gray-400 italic">01. Loại Hình</th>
                            <th class="uppercase text-[10px] tracking-[0.2em] text-gray-400 italic">02. Nhóm Ngành</th>
                            <th class="uppercase text-[10px] tracking-[0.2em] text-gray-400 italic">03. Dòng Sản Phẩm</th>
                            <th class="uppercase text-[10px] tracking-[0.2em] text-[#E11D48] italic">04. Cấu Cấu Hình Tài Sản</th>
                            <th class="pr-8 text-right uppercase text-[10px] tracking-[0.2em] text-gray-400 italic whitespace-nowrap">Điều Phối</th>
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
                                                <div class="text-[11px] font-black text-gray-500 uppercase tracking-tight italic">{{ $level1->name }}</div>
                                            </td>
                                            <td class="py-5 border-l border-gray-50">
                                                <div class="text-[11px] font-black text-gray-500 uppercase tracking-tight italic px-4">{{ $level2->name }}</div>
                                            </td>
                                            <td class="py-5 border-l border-gray-50">
                                                <div class="text-[11px] font-black text-gray-400 uppercase tracking-tight italic px-4">{{ $level3->name }}</div>
                                            </td>
                                            <td class="py-5 border-l border-gray-50 px-4">
                                                <div class="text-[10px] italic text-gray-300 flex items-center uppercase font-black tracking-widest">
                                                    <span class="w-1 h-3 bg-gray-100 mr-2 rounded-full"></span>
                                                    Dữ liệu trống
                                                </div>
                                            </td>
                                            <td class="pr-8 py-5 text-right">
                                                <a href="{{ route('asset_groups.edit', $level3) }}" class="text-[10px] font-black text-gray-900 border-b-2 border-transparent hover:border-[#E11D48] hover:text-[#E11D48] tracking-[0.2em] transition-all italic uppercase">Hiệu chỉnh</a>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($level3->assets as $asset)
                                            <tr class="hover:bg-red-50/30 transition-all group">
                                                <td class="pl-8 py-6">
                                                    <div class="text-[11px] font-black text-gray-900 uppercase tracking-tight italic">{{ $level1->name }}</div>
                                                </td>
                                                <td class="py-6 border-l border-red-50">
                                                    <div class="text-[11px] font-black text-gray-700 uppercase tracking-tight italic px-4">{{ $level2->name }}</div>
                                                </td>
                                                <td class="py-6 border-l border-red-50">
                                                    <div class="text-[11px] font-black text-gray-600 uppercase tracking-tight italic px-4">{{ $level3->name }}</div>
                                                </td>
                                                <td class="py-6 border-l border-red-50 px-4">
                                                    <div class="text-[13px] font-black text-gray-900 uppercase tracking-tighter italic group-hover:text-[#E11D48] transition-colors leading-tight">{{ $asset->name }}</div>
                                                    <div class="text-[9px] text-gray-400 font-black uppercase tracking-[0.2em] mt-1 shadow-sm inline-block">
                                                        SER_ID: <span class="text-[#E11D48]">{{ $asset->serial_number ?? '---' }}</span>
                                                    </div>
                                                </td>
                                                <td class="pr-8 py-6 text-right whitespace-nowrap">
                                                    <div class="flex justify-end items-center space-x-6">
                                                        <a href="{{ route('assets.edit', $asset) }}" class="text-[10px] font-black text-gray-900 hover:text-[#E11D48] tracking-widest uppercase transition-colors italic group-hover:underline decoration-2">Sửa</a>
                                                        <a href="{{ route('assets.show', $asset) }}" class="text-[10px] font-black text-gray-400 hover:text-gray-900 tracking-widest uppercase transition-colors italic">Chi tiết</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach

                                @if($level2->children->count() == 0)
                                    <tr class="hover:bg-gray-50/50 transition-all group opacity-60">
                                        <td class="pl-8 py-5"><div class="text-[11px] font-black text-gray-400 uppercase italic">{{ $level1->name }}</div></td>
                                        <td class="py-5 border-l border-gray-50"><div class="text-[11px] font-black text-gray-400 uppercase italic px-4">{{ $level2->name }}</div></td>
                                        <td colspan="2" class="py-5 border-l border-gray-50 px-4"><div class="text-[10px] italic text-gray-300 uppercase font-black tracking-widest">Cấu trúc dòng hàng chưa khả định</div></td>
                                        <td class="pr-8 py-5 text-right">
                                            <a href="{{ route('asset_groups.edit', $level2) }}" class="text-[10px] font-black text-gray-400 hover:text-gray-900 tracking-widest uppercase italic transition-all">SỬA</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @if($level1->children->count() == 0)
                                <tr class="bg-gray-50/30 group opacity-50">
                                    <td class="pl-8 py-6"><div class="text-[11px] font-black text-gray-400 uppercase italic">{{ $level1->name }}</div></td>
                                    <td colspan="3" class="py-6 border-l border-gray-50 px-4"><div class="text-[10px] italic text-gray-300 uppercase font-black tracking-[0.3em]">Danh mục loại hình rỗng</div></td>
                                    <td class="pr-8 py-6 text-right">
                                        <a href="{{ route('asset_groups.edit', $level1) }}" class="text-[10px] font-black text-gray-400 hover:text-gray-900 tracking-widest uppercase italic">SỬA</a>
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
                                        <p class="text-gray-400 font-black text-[11px] uppercase tracking-[0.4em] italic leading-loose">Hệ thống chưa phát hiện cấu trúc phân loại<br>danh mục tài sản cơ sở</p>
                                        <a href="{{ route('asset_groups.create') }}" class="mt-10 btn-enterprise px-12 py-3 shadow-xl uppercase italic tracking-widest text-[10px]">TẠO PHÂN CẤP ĐẦU TIÊN</a>
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
