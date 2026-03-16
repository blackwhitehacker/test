<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end pb-2 gap-6">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 tracking-tight uppercase leading-none mb-2">
                    {{ __('Danh mục Đối tác & Nhà cung cấp') }}
                </h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-gray-400 italic">Quản lý mạng lưới cung ứng & Thông tin liên hệ tập trung</span>
                </div>
            </div>
            
            <a href="{{ route('partners.create') }}" 
               class="bg-[#E11D48] text-white px-4 h-9 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all hover:bg-[#BE123C] shadow-lg shadow-red-900/10 flex items-center justify-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>THÊM ĐỐI TÁC CHIẾN LƯỢC</span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in duration-700">
        <!-- Search & Filter Area -->
        <div class="card-enterprise p-8 bg-white">
            <form action="{{ route('partners.index') }}" method="GET" class="flex flex-col md:flex-row gap-8">
                <div class="flex-1 relative group">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500 block mb-3">Tra cứu thông tin nhà thầu</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="enterprise-input py-3 !pl-10 text-[13px] font-bold italic" 
                               placeholder="Tìm mã số, thương hiệu, số điện thoại, MST...">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="bg-gray-900 text-white px-10 h-11 rounded-xl text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-black transition shadow-lg italic">
                        TRUY XUẤT
                    </button>
                    @if(request('search'))
                        <a href="{{ route('partners.index') }}" class="px-6 h-11 text-[10px] font-bold text-gray-400 hover:text-gray-900 flex items-center shadow-sm border border-gray-100 rounded-xl bg-gray-50 transition-all uppercase tracking-widest italic">
                            HỦY LỌC
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-enterprise overflow-hidden border-t-0 shadow-2xl">
            <div class="px-8 py-6 bg-white flex justify-between items-center border-b border-gray-100">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.2em] text-gray-500">Cơ sở dữ liệu đối tác chiến lược</h3>
                <div class="flex space-x-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48] animate-pulse"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-gray-200"></div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 uppercase tracking-widest text-gray-400 text-[9px]">
                            <th class="pl-8 !py-4 font-bold !text-left" style="width: 15%;">Định Danh</th>
                            <th class="py-4 font-bold !text-left" style="width: 25%;">Thông Tin Thương Hiệu</th>
                            <th class="py-4 font-bold !text-left" style="width: 15%;">Mã Số Thuế</th>
                            <th class="py-4 font-bold !text-left" style="width: 15%;">Liên Hệ Trực Tiếp</th>
                            <th class="py-4 font-bold !text-left" style="width: 15%;">Đại Diện Giao Dịch</th>
                            <th class="pr-8 py-4 font-bold !text-right" style="width: 15%;">Điều Phối</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($partners as $partner)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="pl-8 py-6">
                                    <span class="text-[10px] font-bold text-[#E11D48] uppercase bg-red-50 px-3 py-1 rounded shadow-sm">{{ $partner->code }}</span>
                                </td>
                                <td class="py-6">
                                    <div class="text-[14px] font-bold text-gray-900 tracking-tight uppercase group-hover:text-[#E11D48] transition-colors leading-tight">{{ $partner->name }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1 italic">{{ $partner->email ?? 'N/A' }}</div>
                                </td>
                                <td class="py-6">
                                    <span class="text-[12px] font-mono font-bold text-gray-600 tracking-tighter bg-gray-50 px-3 py-1 rounded">{{ $partner->tax_code ?? '---' }}</span>
                                </td>
                                <td class="py-6">
                                    <span class="text-[11px] font-bold text-gray-700 tracking-widest uppercase">{{ $partner->phone ?? '---' }}</span>
                                </td>
                                <td class="py-6 font-medium">
                                    <span class="text-[11px] font-bold text-gray-500 uppercase underline decoration-gray-200 decoration-2 underline-offset-4">{{ $partner->contact_person ?? '---' }}</span>
                                </td>
                                <td class="pr-8 py-6 text-right whitespace-nowrap">
                                    <div class="flex justify-end items-center space-x-6">
                                        <a href="{{ route('partners.show', $partner) }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 tracking-widest uppercase transition-all">Chi tiết</a>
                                        <a href="{{ route('partners.edit', $partner) }}" class="text-[10px] font-bold text-gray-900 hover:text-[#E11D48] tracking-widest uppercase transition-all">Sửa</a>
                                        <form action="{{ route('partners.destroy', $partner) }}" method="POST" onsubmit="return confirm('Xác nhận xóa bỏ đối tác này?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[10px] font-bold text-gray-400 hover:text-red-600 transition-colors uppercase tracking-widest">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-44 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <div class="p-10 bg-gray-50 rounded-[2.5rem] mb-8 shadow-inner">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <p class="text-gray-400 font-bold text-[11px] uppercase tracking-[0.4em] italic leading-loose">Hệ thống chưa phát hiện dữ liệu<br>đối tác chiến lược</p>
                                        <a href="{{ route('partners.create') }}" class="mt-10 btn-enterprise-danger px-12 py-3 shadow-xl uppercase italic tracking-widest text-[10px]">TẠO ĐỐI TÁC ĐẦU TIÊN</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($partners->hasPages())
                <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/50">
                    {{ $partners->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
