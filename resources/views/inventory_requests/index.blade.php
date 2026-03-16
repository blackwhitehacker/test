<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-2 gap-6">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 tracking-tight uppercase leading-none mb-3">
                    Quản lý yêu cầu 
                    @if($type == 'all')
                        <span class="text-gray-400">TẤT CẢ</span>
                    @else
                        <span class="text-[#E11D48]">{{ $type == 'inbound' ? 'nhập kho' : 'xuất kho' }}</span>
                    @endif
                </h2>
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 italic">Hệ thống phê duyệt & Điều phối vật tư tập trung</span>
                </div>
            </div>
            @if($type != 'all')
                <a href="{{ route('inventory_requests.create', ['type' => $type]) }}" 
                   class="bg-[#E11D48] text-white px-6 h-10 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all hover:bg-[#BE123C] shadow-lg shadow-red-900/10 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    THÊM PHIẾU {{ $type == 'inbound' ? 'NHẬP' : 'XUẤT' }} MỚI
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in duration-700">
        <!-- Search & Filter Area (Mirrored from Partners) -->
        <div class="card-enterprise p-8 bg-white border-l-4 border-[#E11D48] shadow-sm italic">
            <form action="{{ route('inventory_requests.index') }}" method="GET" class="flex flex-col md:flex-row gap-8">
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="status" value="{{ $status }}">
                
                <div class="flex-1 relative group">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500 block mb-3">Tra cứu thông tin hồ sơ</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="enterprise-input py-3 !pl-10 text-[13px] font-bold italic" 
                               placeholder="Tìm mã số phiếu, nhân sự đề xuất, lô hàng liên kết...">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="flex items-end gap-3">
                    <button type="submit" class="bg-gray-900 text-white px-10 h-11 rounded-xl text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-black transition shadow-lg italic">
                        TRUY XUẤT
                    </button>
                    @if(request('search'))
                        <a href="{{ route('inventory_requests.index', ['type' => $type, 'status' => $status]) }}" class="px-6 h-11 text-[10px] font-bold text-gray-400 hover:text-gray-900 flex items-center shadow-sm border border-gray-100 rounded-xl bg-gray-50 transition-all uppercase tracking-widest italic">
                            HỦY LỌC
                        </a>
                    @endif
                </div>
            </form>

            <div class="flex flex-wrap gap-2 mt-8 pt-6 border-t border-gray-100">
                @foreach(['all' => 'Tất cả', 'pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'rejected' => 'Từ chối', 'cancelled' => 'Đã hủy'] as $key => $label)
                    <a href="{{ route('inventory_requests.index', ['status' => $key, 'type' => $type, 'search' => request('search')]) }}" 
                       class="px-6 py-2 rounded-lg text-[9px] font-bold uppercase tracking-widest transition-all
                       {{ $status == $key ? 'bg-gray-900 text-white shadow-md' : 'text-gray-400 hover:text-gray-900 hover:bg-gray-50 border border-gray-100' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Table Card (Mirrored from Partners) -->
        <div class="card-enterprise overflow-hidden border-l-4 border-[#E11D48] shadow-2xl">
            <div class="px-8 py-6 bg-white flex justify-between items-center border-b border-gray-100">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.2em] text-gray-500 italic">Cơ sở dữ liệu điều phối yêu cầu & quản lý vật tư</h3>
                <div class="flex space-x-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48] animate-pulse"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-gray-200"></div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 uppercase tracking-widest text-gray-400 text-[9px]">
                            <th class="pl-8 !py-4 font-bold !text-left">Định Danh</th>
                            <th class="py-4 font-bold !text-left">Nguồn Gốc</th>
                            @if($type == 'all')
                                <th class="py-4 font-bold !text-left">Phân Loại</th>
                            @endif
                            <th class="py-4 font-bold !text-left">Nhân Sự Đề Xuất</th>
                            <th class="py-4 font-bold !text-left">Hợp Đồng / Lô Hàng</th>
                            <th class="py-4 font-bold !text-center">Trạng Thái</th>
                            <th class="pr-8 py-4 font-bold !text-right">Điều Phối</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($requests as $req)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="pl-8 py-6">
                                    <span class="text-[10px] font-bold text-[#E11D48] uppercase bg-red-50 px-3 py-1 rounded shadow-sm">{{ $req->code }}</span>
                                    <div class="text-[8px] text-gray-400 font-bold uppercase mt-1 italic tracking-tighter">{{ $req->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="py-6">
                                    <div class="text-[12px] font-bold text-gray-900 uppercase">
                                        @if($req->source_type == 'purchase') MUA SẮM @elseif($req->source_type == 'transfer') ĐIỀU CHUYỂN @else NGHIỆP VỤ @endif
                                    </div>
                                </td>
                                @if($type == 'all')
                                    <td class="py-6">
                                        <span class="text-[10px] font-bold {{ $req->type == 'inbound' ? 'text-blue-600' : 'text-amber-600' }} uppercase italic">
                                            {{ $req->type == 'inbound' ? 'NHẬP KHO' : 'XUẤT KHO' }}
                                        </span>
                                    </td>
                                @endif
                                <td class="py-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-gray-900 flex items-center justify-center text-[10px] font-bold text-white shadow-sm">
                                            {{ strtoupper(substr($req->requester->name, 0, 1)) }}
                                        </div>
                                        <span class="text-[11px] font-bold text-gray-500 uppercase">{{ $req->requester->name }}</span>
                                    </div>
                                </td>
                                <td class="py-6">
                                    @if($req->shipment)
                                        <div class="text-[11px] font-bold text-gray-900 uppercase">{{ $req->shipment->code }}</div>
                                        <div class="text-[8px] text-gray-400 font-bold uppercase italic mt-0.5">Lô hàng liên kết</div>
                                    @else
                                        <span class="text-[10px] text-gray-300 italic">---</span>
                                    @endif
                                </td>
                                <td class="py-6 !text-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-dashed
                                        {{ $req->status == 'approved' ? 'bg-green-50 text-green-600 border-green-200' : ($req->status == 'pending' ? 'bg-amber-50 text-amber-500 border-amber-200' : 'bg-red-50 text-red-600 border-red-200') }}">
                                        {{ $req->status == 'approved' ? 'ĐÃ DUYỆT' : ($req->status == 'pending' ? 'CHỜ DUYỆT' : 'TỪ CHỐI') }}
                                    </span>
                                </td>
                                <td class="pr-8 py-6 text-right whitespace-nowrap">
                                    <div class="flex justify-end items-center space-x-6">
                                        <a href="{{ route('inventory_requests.show', $req) }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 tracking-widest uppercase transition-all italic">Chi tiết</a>
                                        
                                        @if($req->status == 'approved')
                                            @if(!$req->receipt)
                                                <form action="{{ route('inventory_requests.generate_receipt', $req) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-[10px] font-bold text-[#E11D48] hover:text-red-700 tracking-widest uppercase transition-all italic underline underline-offset-4 decoration-2">Tạo lệnh</button>
                                                </form>
                                            @else
                                                <a href="{{ route('inventory_receipts.show', $req->receipt) }}" class="text-[10px] font-bold text-green-600 hover:text-green-700 tracking-widest uppercase transition-all italic">Thực thi</a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $type == 'all' ? 7 : 6 }}" class="py-32 text-center opacity-30 italic">
                                    <div class="text-[11px] font-bold uppercase tracking-[0.3em]">Hệ thống chưa ghi nhận hồ sơ yêu cầu</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($requests->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
