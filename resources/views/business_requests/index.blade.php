<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-2 gap-6">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 tracking-tight uppercase leading-none mb-3">
                    Yêu cầu nghiệp vụ
                    <span class="text-gray-400">HỆ THỐNG</span>
                </h2>
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 italic">Quản lý và xử lý luồng phê duyệt yêu cầu tài sản tập trung</span>
                </div>
            </div>
            
            <a href="{{ route('business_requests.create') }}" 
               class="bg-[#E11D48] text-white px-6 h-10 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all hover:bg-[#BE123C] shadow-lg shadow-red-900/10 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                GỬI YÊU CẦU MỚI
            </a>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in duration-700">
        <!-- Search & Tab Area (Basic Style) -->
        <div class="card-enterprise p-8 bg-white border-l-4 border-[#E11D48] shadow-sm italic">
            <div class="flex flex-col gap-8">
                <!-- Tabs -->
                <div class="flex flex-wrap gap-2 border-b border-gray-100 pb-6">
                    @php
                        $tabs = [
                            '' => 'Tất cả',
                            'allocation' => 'Cấp phát',
                            'repair' => 'Sửa chữa',
                            'recall' => 'Thu hồi',
                            'liquidation' => 'Thanh lý',
                        ];
                    @endphp
                    @foreach($tabs as $tabKey => $tabLabel)
                        <a href="{{ route('business_requests.index', $tabKey ? ['type' => $tabKey] : []) }}" 
                           class="px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all border
                           {{ ($type == $tabKey || (!$type && !$tabKey)) ? 'bg-gray-900 text-white border-gray-900 shadow-md transform -translate-y-0.5' : 'bg-gray-50 text-gray-400 border-gray-100 hover:bg-white hover:text-gray-900' }}">
                            {{ $tabLabel }}
                        </a>
                    @endforeach
                </div>

                <!-- Search -->
                <form action="{{ route('business_requests.index') }}" method="GET" class="flex flex-col md:flex-row gap-8 items-end">
                    @if($type) <input type="hidden" name="type" value="{{ $type }}"> @endif
                    <div class="flex-1 relative group w-full">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500 block mb-3">Tra cứu thông tin yêu cầu</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   class="enterprise-input py-3 !pl-10 text-[13px] font-bold italic" 
                                   placeholder="Tìm mã phiếu, tên đối tượng, nội dung...">
                            <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-gray-900 text-white px-10 h-11 rounded-xl text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-black transition shadow-lg italic">
                            TRUY XUẤT
                        </button>
                        @if(request('search'))
                            <a href="{{ route('business_requests.index', $type ? ['type' => $type] : []) }}" class="px-6 h-11 text-[10px] font-bold text-gray-400 hover:text-gray-900 flex items-center shadow-sm border border-gray-100 rounded-xl bg-gray-50 transition-all uppercase tracking-widest italic">
                                HỦY LỌC
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Matrix Table -->
        <div class="card-enterprise overflow-hidden border-l-4 border-[#E11D48] shadow-2xl bg-white">
            <div class="px-8 py-6 flex justify-between items-center border-b border-gray-100 italic">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.2em] text-gray-500">CƠ SỞ DỮ LIỆU YÊU CẦU NGHIỆP VỤ HỆ THỐNG</h3>
                <div class="flex space-x-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48] animate-pulse"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-gray-200"></div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 uppercase tracking-widest text-gray-400 text-[9px]">
                            <th class="pl-8 py-4 font-bold text-left">Mã phiếu</th>
                            <th class="py-4 font-bold text-left">Phân loại</th>
                            <th class="py-4 font-bold text-left">Đối tượng thụ hưởng</th>
                            <th class="py-4 font-bold text-left">Nhân sự yêu cầu</th>
                            <th class="py-4 font-bold text-center">Trạng thái</th>
                            <th class="pr-8 py-4 font-bold text-right">Điều phối</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($requests as $req)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="pl-8 py-6">
                                    <span class="text-[10px] font-bold text-[#E11D48] uppercase bg-red-50 px-3 py-1 rounded shadow-sm">{{ $req->code }}</span>
                                    <div class="text-[9px] text-gray-400 font-bold mt-1 uppercase">{{ $req->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="py-6">
                                    <span class="inline-flex items-center text-[10px] font-black uppercase tracking-wider
                                        @if($req->source_type == 'allocation') text-blue-600
                                        @elseif($req->source_type == 'repair') text-amber-600
                                        @elseif($req->source_type == 'recall') text-purple-600
                                        @elseif($req->source_type == 'liquidation') text-rose-600
                                        @else text-gray-500 @endif">
                                        {{ $req->source_type == 'allocation' ? 'CẤP PHÁT' : ($req->source_type == 'repair' ? 'SỬA CHỮA' : ($req->source_type == 'recall' ? 'THU HỒI' : ($req->source_type == 'liquidation' ? 'THANH LÝ' : 'KHÁC'))) }}
                                    </span>
                                </td>
                                <td class="py-6">
                                    <div class="flex flex-col">
                                        <span class="text-[12px] font-bold text-gray-900 uppercase">{{ $req->target_name }}</span>
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">
                                            {{ $req->target_type == 'individual' ? 'CÁ NHÂN' : ($req->target_type == 'department' ? 'PHÒNG BAN' : 'TRUNG TÂM') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded bg-gray-900 flex items-center justify-center text-[10px] font-bold text-white shadow-sm">
                                            {{ strtoupper(substr($req->requester->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="text-[11px] font-bold text-gray-500 uppercase">{{ $req->requester->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="py-6 text-center">
                                    @php
                                        $colors = [
                                            'pending' => 'bg-amber-50 text-amber-500 border-amber-200',
                                            'approved' => 'bg-green-50 text-green-600 border-green-200',
                                            'rejected' => 'bg-red-50 text-red-600 border-red-200',
                                            'completed' => 'bg-blue-50 text-blue-600 border-blue-200',
                                            'cancelled' => 'bg-gray-100 text-gray-400 border-gray-200',
                                        ];
                                        $labels = [
                                            'pending' => 'CHỜ DUYỆT',
                                            'approved' => 'ĐÃ DUYỆT',
                                            'rejected' => 'TỪ CHỐI',
                                            'completed' => 'HOÀN THÀNH',
                                            'cancelled' => 'ĐÃ HỦY',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-dashed {{ $colors[$req->status] ?? 'bg-gray-50 text-gray-400' }}">
                                        {{ $labels[$req->status] ?? strtoupper($req->status) }}
                                    </span>
                                </td>
                                <td class="pr-8 py-6 text-right whitespace-nowrap">
                                    <div class="flex justify-end items-center space-x-6">
                                        <a href="{{ route('business_requests.show', $req) }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 tracking-widest uppercase transition-all italic underline decoration-gray-200 underline-offset-4 decoration-2">CHI TIẾT</a>
                                        @if($req->status == 'pending')
                                            <a href="{{ route('business_requests.edit', $req) }}" class="text-[10px] font-bold text-gray-400 hover:text-blue-600 tracking-widest uppercase transition-all italic underline decoration-gray-200 underline-offset-4 decoration-2">CẬP NHẬT</a>
                                            <form action="{{ route('business_requests.cancel', $req) }}" method="POST" class="inline" onsubmit="return confirm('Xác nhận xóa bỏ yêu cầu này?')">
                                                @csrf
                                                <button type="submit" class="text-[10px] font-bold text-gray-300 hover:text-red-600 tracking-widest uppercase transition-all italic">HỦY BỎ</button>
                                            </form>
                                        @endif
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
                                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.4em]">Hệ thống chưa ghi nhận yêu cầu</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($requests->hasPages())
                <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/50">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
