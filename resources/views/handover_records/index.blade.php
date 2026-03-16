<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-2 gap-6">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 tracking-tight uppercase leading-none mb-3">
                    Biên bản bàn giao
                    <span class="text-gray-400">HỆ THỐNG</span>
                </h2>
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 italic">Tự động khởi tạo và lưu trữ hồ sơ bàn giao tài sản tập trung</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in duration-700">
        <!-- Search Area (Basic Style) -->
        <div class="card-enterprise p-8 bg-white border-l-4 border-[#E11D48] shadow-sm italic">
            <form action="{{ route('handover_records.index') }}" method="GET" class="flex flex-col md:flex-row gap-8">
                <div class="flex-1 relative group">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500 block mb-3">Tra cứu thông tin biên bản</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="enterprise-input py-3 !pl-10 text-[13px] font-bold italic" 
                               placeholder="Tìm mã biên bản, tên người nhận, mã yêu cầu...">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="bg-gray-900 text-white px-10 h-11 rounded-xl text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-black transition shadow-lg italic">
                        TRUY XUẤT
                    </button>
                    @if(request('search'))
                        <a href="{{ route('handover_records.index') }}" class="px-6 h-11 text-[10px] font-bold text-gray-400 hover:text-gray-900 flex items-center shadow-sm border border-gray-100 rounded-xl bg-gray-50 transition-all uppercase tracking-widest italic">
                            HỦY LỌC
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="card-enterprise overflow-hidden border-l-4 border-[#E11D48] shadow-2xl bg-white">
            <div class="px-8 py-6 flex justify-between items-center border-b border-gray-100 italic">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.2em] text-gray-500">CƠ SỞ DỮ LIỆU BIÊN BẢN BÀN GIAO TÀI SẢN</h3>
                <div class="flex space-x-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48] animate-pulse"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-gray-200"></div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 uppercase tracking-widest text-gray-400 text-[9px]">
                            <th class="pl-8 py-4 font-bold text-left">Định danh hồ sơ</th>
                            <th class="py-4 font-bold text-left">Nguồn gốc yêu cầu</th>
                            <th class="py-4 font-bold text-left">Nhân sự tiếp nhận</th>
                            <th class="py-4 font-bold text-center">Thời điểm cấp phát</th>
                            <th class="py-4 font-bold text-center">Trạng thái</th>
                            <th class="pr-8 py-4 font-bold text-right">Điều phối</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($records as $record)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="pl-8 py-6">
                                    <span class="text-[10px] font-bold text-[#E11D48] uppercase bg-red-50 px-3 py-1 rounded shadow-sm">{{ $record->code }}</span>
                                </td>
                                <td class="py-6">
                                    <div class="text-[11px] font-bold text-gray-900 uppercase">YÊU CẦU: {{ $record->inventoryRequest->code ?? 'N/A' }}</div>
                                    <div class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter mt-1">HỆ THỐNG KHỞI TẠO</div>
                                </td>
                                <td class="py-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded bg-gray-900 flex items-center justify-center text-[10px] font-bold text-white shadow-sm">
                                            {{ strtoupper(substr($record->receiver_name, 0, 1)) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-bold text-gray-900 uppercase">{{ $record->receiver_name }}</span>
                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ $record->receiver_department ?: 'Tự do' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 text-center font-mono text-[11px] font-bold text-gray-500">
                                    {{ \Carbon\Carbon::parse($record->handover_date)->format('d/m/Y') }}
                                </td>
                                <td class="py-6 text-center">
                                    @php
                                        $colors = [
                                            'signed' => 'bg-green-50 text-green-600 border-green-200',
                                            'draft' => 'bg-amber-50 text-amber-500 border-amber-200',
                                            'cancelled' => 'bg-red-50 text-red-600 border-red-200',
                                        ];
                                        $labels = [
                                            'signed' => 'ĐÃ KÝ NHẬN',
                                            'draft' => 'CHƯA KÝ',
                                            'cancelled' => 'ĐÃ HỦY',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-dashed {{ $colors[$record->status] ?? 'bg-gray-50 text-gray-400' }}">
                                        {{ $labels[$record->status] ?? strtoupper($record->status) }}
                                    </span>
                                </td>
                                <td class="pr-8 py-6 text-right whitespace-nowrap">
                                    <div class="flex justify-end items-center space-x-6">
                                        <a href="{{ route('handover_records.show', $record) }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 tracking-widest uppercase transition-all italic underline decoration-gray-200 underline-offset-4 decoration-2">XEM & KÝ</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-44 text-center">
                                    <div class="flex flex-col items-center opacity-30 italic">
                                        <div class="p-10 bg-gray-50 rounded-[2.5rem] mb-8 shadow-inner text-gray-300">
                                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.4em]">Hệ thống chưa ghi nhận biên bản</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($records->hasPages())
                <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/50">
                    {{ $records->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
