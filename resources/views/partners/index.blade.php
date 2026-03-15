<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-black text-2xl text-gray-900 tracking-tighter uppercase italic">
                    {{ __('Danh mục Đối tác & Nhà cung cấp') }}
                </h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1 italic">Quản lý mạng lưới cung ứng & Thông tin liên hệ tập trung</p>
            </div>
            <a href="{{ route('partners.create') }}" class="btn-enterprise py-3 px-8 shadow-xl">
                + THÊM ĐỐI TÁC MỚI
            </a>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in duration-700">
        <!-- Search & Filter Area -->
        <div class="card-enterprise p-8">
            <form action="{{ route('partners.index') }}" method="GET" class="flex flex-col md:flex-row gap-6">
                <div class="flex-1 relative group">
                    <label class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 block mb-2 italic">Tra cứu thông tin nhà thầu</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="enterprise-input py-3 !pl-10 text-sm italic" 
                               placeholder="Tìm mã số, thương hiệu, số điện thoại, MST...">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="bg-gray-900 text-white px-10 py-3 rounded-xl text-[10px] font-black tracking-[0.2em] uppercase hover:bg-black transition shadow-lg italic">
                        TRUY XUẤT
                    </button>
                    @if(request('search'))
                        <a href="{{ route('partners.index') }}" class="px-6 py-3 text-[10px] font-black text-gray-500 hover:text-gray-900 flex items-center shadow-sm border border-gray-100 rounded-xl bg-gray-50 transition-all uppercase tracking-widest italic">
                            HỦY LỌC
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-enterprise overflow-hidden border-t-0 shadow-2xl">
            <div class="px-8 py-6 bg-gray-900 flex justify-between items-center">
                <h3 class="font-black text-[10px] uppercase tracking-[0.3em] italic text-[#E11D48]">Cơ sở dữ liệu đối tác chiến lược</h3>
                <div class="flex space-x-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-white/20"></div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="pl-8 !py-5">Định Danh</th>
                            <th>Thông Tin Thương Hiệu</th>
                            <th>Mã Số Thuế</th>
                            <th>Liên Hệ Trực Tiếp</th>
                            <th>Đại Diện Giao Dịch</th>
                            <th class="pr-8 text-right">Điều Phối</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($partners as $partner)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="pl-8 py-6">
                                    <span class="text-[10px] font-black text-[#E11D48] uppercase bg-red-50 px-3 py-1 rounded shadow-sm italic">{{ $partner->code }}</span>
                                </td>
                                <td class="py-6">
                                    <div class="text-[13px] font-black text-gray-900 tracking-tighter uppercase italic group-hover:text-[#E11D48] transition-colors leading-tight">{{ $partner->name }}</div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $partner->email ?? 'N/A' }}</div>
                                </td>
                                <td class="py-6">
                                    <span class="text-[11px] font-mono font-black text-gray-600 tracking-tighter italic bg-gray-50 px-3 py-1 rounded">{{ $partner->tax_code ?? '---' }}</span>
                                </td>
                                <td class="py-6">
                                    <span class="text-[11px] font-black text-gray-700 tracking-widest italic uppercase">{{ $partner->phone ?? '---' }}</span>
                                </td>
                                <td class="py-6 font-medium">
                                    <span class="text-[10px] font-black text-gray-500 italic uppercase underline decoration-gray-200 decoration-2 underline-offset-4">{{ $partner->contact_person ?? '---' }}</span>
                                </td>
                                <td class="pr-8 py-6 text-right">
                                    <div class="flex justify-end space-x-4">
                                        <a href="{{ route('partners.show', $partner) }}" class="p-2 text-gray-400 hover:text-gray-900 transition-colors" title="Xem chi tiết">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <a href="{{ route('partners.edit', $partner) }}" class="p-2 text-gray-400 hover:text-[#E11D48] transition-colors" title="Chỉnh sửa">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 7.5-7.5z"></path></svg>
                                        </a>
                                        <form action="{{ route('partners.destroy', $partner) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-300 hover:text-red-600 transition-colors" onclick="return confirm('Xác nhận xóa bỏ đối tác này khỏi hệ thống?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-32 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <div class="p-8 bg-gray-50 rounded-3xl mb-6 shadow-inner">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <p class="text-gray-400 font-black text-[11px] uppercase tracking-[0.3em] italic">Không phát hiện dữ liệu đối tác</p>
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
