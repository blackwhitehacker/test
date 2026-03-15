<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase">
                    Quản lý yêu cầu <span class="text-[#E11D48]">{{ $type == 'inbound' ? 'nhập kho' : 'xuất kho' }}</span>
                </h2>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Hệ thống phê duyệt & Điều phối vật tư tập trung</p>
            </div>
            <a href="{{ route('inventory_requests.create', ['type' => $type]) }}" class="btn-enterprise">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tạo phiếu {{ $type == 'inbound' ? 'nhập' : 'xuất' }} mới
            </a>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <!-- Status Tabs -->
        <div class="flex gap-2 p-1 bg-gray-100 rounded-xl w-fit border border-gray-200">
            @foreach(['all' => 'Tất cả', 'pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'rejected' => 'Từ chối', 'cancelled' => 'Đã hủy'] as $key => $label)
                <a href="{{ route('inventory_requests.index', ['status' => $key, 'type' => $type]) }}" 
                   class="px-4 py-1.5 rounded-lg text-[9px] font-bold uppercase tracking-wider transition-all duration-200
                   {{ $status == $key ? 'bg-[#E11D48] text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-white' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="card-enterprise overflow-hidden border-t-0 border-gray-100 shadow-xl">
            <div class="px-6 py-4 bg-gray-900 flex justify-between items-center border-b border-white/5">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48] shadow-[0_0_8px_rgba(225,29,72,0.8)]"></div>
                    <h3 class="font-bold text-[9px] uppercase tracking-[0.2em] text-white/90 leading-none">Phân hệ điều phối & Quản lý yêu cầu</h3>
                </div>
                <div class="flex gap-1.5">
                    <div class="w-1 h-1 rounded-full bg-white/20"></div>
                    <div class="w-1 h-1 rounded-full bg-white/10"></div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100">
                            <th class="w-32 pl-6 py-3.5 text-left">Mã Số Phiếu</th>
                            <th class="w-40 py-3.5 text-left">Phân Loại Nguồn</th>
                            <th class="py-3.5 text-left">Nhân Sự Đề Xuất</th>
                            <th class="w-48 py-3.5 text-left">Hợp Đồng/Lô Hàng</th>
                            <th class="w-32 py-3.5 text-center">Trạng Thái</th>
                            <th class="w-24 pr-6 py-3.5 text-right">Tác Vụ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($requests as $req)
                            <tr class="hover:bg-gray-50/50 transition-all duration-200 group">
                                <td class="pl-6 py-5 align-middle">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-[#E11D48] bg-red-50/50 px-2.5 py-1 rounded border border-red-100 shadow-sm w-fit">{{ $req->code }}</span>
                                        <span class="text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-1.5 ml-0.5">{{ $req->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-5 align-middle">
                                    <span class="text-[8px] font-bold uppercase tracking-widest px-2 py-1 bg-gray-100 text-gray-600 rounded border border-gray-200/50 shadow-sm">
                                        @if($req->source_type == 'purchase') MUA SẮM @elseif($req->source_type == 'transfer') ĐIỀU CHUYỂN @else NGHIỆP VỤ @endif
                                    </span>
                                </td>
                                <td class="py-5 align-middle">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 bg-gray-900 rounded-lg flex items-center justify-center text-[10px] font-bold text-white shadow-md ring-2 ring-white transition-transform group-hover:scale-110">
                                            {{ strtoupper(substr($req->requester->name, 0, 1)) }}
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-700 uppercase tracking-tight">{{ $req->requester->name }}</span>
                                    </div>
                                </td>
                                <td class="py-5 align-middle">
                                    @if($req->shipment)
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-bold text-gray-900 font-mono tracking-tight">{{ $req->shipment->code }}</span>
                                            <span class="text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Lô hàng liên kết</span>
                                        </div>
                                    @else
                                        <span class="text-[9px] font-bold text-gray-300 uppercase italic tracking-widest">N/A</span>
                                    @endif
                                </td>
                                <td class="py-5 text-center align-middle">
                                    @php
                                        $stConf = [
                                            'pending' => ['label' => 'CHỜ DUYỆT', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                            'approved' => ['label' => 'ĐÃ PHÊ DUYỆT', 'class' => 'bg-green-50 text-green-700 border-green-200'],
                                            'rejected' => ['label' => 'TỪ CHỐI', 'class' => 'bg-red-50 text-red-700 border-red-200'],
                                            'cancelled' => ['label' => 'ĐÃ HỦY', 'class' => 'bg-gray-100 text-gray-500 border-gray-200'],
                                        ];
                                        $st = $stConf[$req->status] ?? ['label' => strtoupper($req->status), 'class' => 'bg-gray-50 text-gray-700 border-gray-200'];
                                    @endphp
                                    <div class="flex justify-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider border {{ $st['class'] }}">
                                            {{ $st['label'] }}
                                        </span>
                                    </div>
                                </td>
                                <td class="pr-6 py-5 align-middle text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('inventory_requests.show', $req) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-[9px] font-bold text-gray-500 hover:text-[#E11D48] hover:bg-[#E11D48]/5 border border-transparent hover:border-[#E11D48]/10 uppercase tracking-widest transition-all">
                                            Chi tiết
                                        </a>
                                        
                                        @if($req->status == 'approved')
                                            @if(!$req->receipt)
                                                <form action="{{ route('inventory_requests.generate_receipt', $req) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-gray-900 text-white text-[9px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-widest hover:bg-[#E11D48] transition-all shadow-sm">
                                                        TẠO LỆNH
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('inventory_receipts.show', $req->receipt) }}" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-[9px] text-green-700 rounded-lg border border-green-200 hover:bg-green-600 hover:text-white transition-all font-bold uppercase tracking-widest shadow-sm">
                                                    THỰC THI
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-2xl mb-4 flex items-center justify-center border-2 border-dashed border-gray-200">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Hệ thống chưa ghi nhận yêu cầu nào</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($requests->hasPages())
            <div class="mt-8 card-premium p-4">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
