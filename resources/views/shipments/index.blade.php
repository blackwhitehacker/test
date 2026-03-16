<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase leading-none">
                {{ __('Quản lý Vận hành & Giao nhận') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('shipments.export_list') }}" class="!text-[#E11D48] !border-[#E11D48] px-4 py-2 rounded-lg font-bold text-[10px] uppercase tracking-wider hover:bg-red-50 transition-colors border flex items-center">
                    PDF
                </a>
                <a href="{{ route('shipments.create') }}" class="!bg-[#E11D48] !text-white px-5 py-2.5 rounded-lg font-bold text-[10px] uppercase tracking-wider h-fit flex items-center shadow-lg shadow-red-900/10 transform transition-transform hover:-translate-y-0.5">
                    KHỞI TẠO LÔ HÀNG
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <!-- Search & Info Area -->
        <div class="card-enterprise p-6 shadow-sm border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                <form action="{{ route('shipments.index') }}" method="GET" class="w-full md:w-1/2">
                    <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 block mb-2">Tra cứu vận đơn / Hợp đồng</label>
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="enterprise-input py-3 pl-10 text-sm" 
                               placeholder="Tìm mã lô hàng, số hợp đồng...">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
                
                <div class="flex items-center space-x-4 bg-gray-50 px-6 py-3 rounded-2xl border border-gray-100 shadow-inner">
                    <div class="text-right">
                        <span class="text-[11px] font-bold uppercase text-gray-400 tracking-widest block">Tổng số vận đơn:</span>
                        <span class="text-lg font-bold text-gray-900 tracking-tighter">{{ $shipments->total() }} Lô hàng</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Area -->
        <div class="card-enterprise overflow-hidden border-t-0 border-gray-100 shadow-xl">
            <div class="px-6 py-4 bg-white flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                    <h3 class="font-bold text-[10px] uppercase tracking-[0.2em] text-gray-500 leading-none">Theo dõi tiến độ giao nhận vận hành</h3>
                </div>
                <div class="flex gap-1.5">
                    <div class="w-1 h-1 rounded-full bg-gray-200"></div>
                    <div class="w-1 h-1 rounded-full bg-gray-100"></div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium" style="table-layout: fixed !important; width: 100% !important; min-width: 1000px;">
                    <thead>
                        <tr class="bg-gray-50/50 uppercase text-gray-400 text-[9px]">
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-left" style="width: 10%;">Mã Vận Đơn</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-left" style="width: 20%;">Hồ Sơ Hợp Đồng</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-left" style="width: 14%;">Đối Tác Vận Hành</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-left" style="width: 14%;">Thời Điểm Giao</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-left" style="width: 15%;">Xác Nhận Nhận</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-center" style="width: 12%;">Trạng Thái</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 !text-center" style="width: 15%;">Điều Phối</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($shipments as $shipment)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="px-6 py-5 align-middle !text-left" style="width: 10%;">
                                    <span class="text-[10px] font-bold text-[#E11D48] uppercase bg-red-50/50 px-2.5 py-1 rounded border border-red-100 shadow-sm">{{ $shipment->code }}</span>
                                </td>
                                <td class="px-6 py-5 align-middle !text-left" style="width: 20%;">
                                    <div class="text-[14px] font-bold text-gray-900 mb-0.5 uppercase group-hover:text-[#E11D48] transition-colors leading-tight">{{ $shipment->contract->contract_number }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase">{{ $shipment->contract->code }}</div>
                                </td>
                                <td class="px-6 py-5 align-middle !text-left" style="width: 14%;">
                                    <div class="text-[11px] font-bold text-gray-400 uppercase tracking-tight">{{ $shipment->contract->partner->name }}</div>
                                </td>
                                <td class="px-6 py-5 align-middle !text-left" style="width: 14%;">
                                    <span class="text-[11px] font-bold text-gray-600">
                                        {{ $shipment->delivery_date ? $shipment->delivery_date->format('d/m/Y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 align-middle !text-left" style="width: 15%;">
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-bold text-gray-900 uppercase tracking-tight">{{ $shipment->receiver_name ?? '---' }}</span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">Nhân viên thụ hưởng</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 !text-center align-middle" style="width: 12%;">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200 shadow-amber-50',
                                            'delivered' => 'bg-blue-50 text-blue-700 border-blue-200 shadow-blue-50',
                                            'checked' => 'bg-indigo-50 text-indigo-700 border-indigo-200 shadow-indigo-50',
                                            'received' => 'bg-cyan-50 text-cyan-700 border-cyan-200 shadow-cyan-50',
                                            'inventoried' => 'bg-green-50 text-green-700 border-green-200 shadow-green-50',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'CHỜ BÀN GIAO',
                                            'delivered' => 'ĐÃ XUẤT XƯỞNG',
                                            'checked' => 'ĐANG KIỂM ĐỊNH',
                                            'received' => 'ĐÃ TIẾP NHẬN',
                                            'inventoried' => 'ĐÃ NHẬP KHO',
                                        ];
                                    @endphp
                                    <div class="flex justify-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider leading-tight border {{ $statusClasses[$shipment->status] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                            {{ $statusLabels[$shipment->status] ?? strtoupper($shipment->status) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 !text-center align-middle" style="width: 15%;">
                                    <div class="td-actions">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="btn-enterprise-outline">
                                            <span>CHI TIẾT</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                        <form action="{{ route('shipments.destroy', $shipment) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lô hàng này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[11px] font-bold text-gray-300 hover:text-[#E11D48] uppercase transition-all hover:underline decoration-2 underline-offset-4">Xóa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-8 bg-gray-50 rounded-3xl mb-6 shadow-inner">
                                        <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">Không phát hiện dữ liệu lô hàng khả dụng</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($shipments->hasPages())
            <div class="mt-8 card-enterprise p-4">
                {{ $shipments->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
