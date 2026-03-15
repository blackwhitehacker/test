<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-900 tracking-tighter uppercase italic">
                {{ __('Quản lý Vận hành & Giao nhận') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('shipments.export_list') }}" class="bg-gray-900 hover:bg-black text-white px-6 py-3 rounded-xl font-black text-[10px] tracking-[0.2em] uppercase transition-all shadow-lg flex items-center">
                    <svg class="w-4 h-4 mr-2 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    XUẤT BÁO CÁO LÔ HÀNG
                </a>
                <a href="{{ route('shipments.create') }}" class="btn-enterprise py-3 px-8 shadow-xl">
                    KHỞI TẠO LÔ HÀNG
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <!-- Search & Info Area -->
        <div class="card-enterprise p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                <form action="{{ route('shipments.index') }}" method="GET" class="w-full md:w-1/2">
                    <label class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 block mb-2 italic">Tra cứu vận đơn / Hợp đồng</label>
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="enterprise-input py-3 pl-10 text-sm italic" 
                               placeholder="Tìm mã lô hàng, số hợp đồng...">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
                
                <div class="flex items-center space-x-6 bg-gray-50 px-8 py-4 rounded-3xl border border-gray-100 shadow-inner">
                    <div class="text-right">
                        <span class="text-[9px] font-black uppercase text-gray-400 tracking-widest italic block">Tổng số vận đơn:</span>
                        <span class="text-lg font-black text-gray-900 tracking-tighter italic underline decoration-[#E11D48] decoration-2">{{ $shipments->total() }} Lô hàng</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Area -->
        <div class="card-enterprise overflow-hidden border-t-0">
            <div class="px-8 py-6 bg-gray-900 flex justify-between items-center">
                <h3 class="font-black text-[10px] uppercase tracking-[0.3em] italic text-[#E11D48]">Theo dõi tiến độ giao hàng</h3>
                <div class="flex gap-2">
                    <div class="w-2 h-2 rounded-full bg-[#E11D48] animate-pulse"></div>
                    <div class="w-2 h-2 rounded-full bg-white/20"></div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="pl-8 !py-5">Mã Vận Đơn</th>
                            <th>Hồ Sơ Hợp Đồng</th>
                            <th>Đối Tác Vận Hành</th>
                            <th>Thời Điểm Giao</th>
                            <th>Xác Nhận Nhận</th>
                            <th class="text-center">Trạng Thái</th>
                            <th class="pr-8 text-right">Điều Phối</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($shipments as $shipment)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="pl-8 py-6">
                                    <span class="text-[10px] font-black text-[#E11D48] uppercase bg-red-50 px-3 py-1 rounded shadow-sm italic">{{ $shipment->code }}</span>
                                </td>
                                <td class="py-6">
                                    <div class="text-sm font-black text-gray-900 tracking-tighter mb-1">{{ $shipment->contract->contract_number }}</div>
                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $shipment->contract->code }}</div>
                                </td>
                                <td class="py-6">
                                    <div class="text-[11px] font-black text-gray-700 uppercase italic tracking-tight">{{ $shipment->contract->partner->name }}</div>
                                </td>
                                <td class="py-6">
                                    <span class="text-[11px] font-black text-gray-600 italic tracking-widest">
                                        {{ $shipment->delivery_date ? $shipment->delivery_date->format('d/m/Y') : '---' }}
                                    </span>
                                </td>
                                <td class="py-6">
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-black text-gray-900 tracking-tighter">{{ $shipment->receiver_name ?? '---' }}</span>
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest italic">Nhân viên thụ hưởng</span>
                                    </div>
                                </td>
                                <td class="py-6 text-center">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-100 text-amber-700 border-amber-200 shadow-amber-50',
                                            'delivered' => 'bg-blue-100 text-blue-700 border-blue-200 shadow-blue-50',
                                            'checked' => 'bg-indigo-100 text-indigo-700 border-indigo-200 shadow-indigo-50',
                                            'received' => 'bg-cyan-100 text-cyan-700 border-cyan-200 shadow-cyan-50',
                                            'inventoried' => 'bg-green-100 text-green-700 border-green-200 shadow-green-50',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'CHỜ BÀN GIAO',
                                            'delivered' => 'ĐÃ XUẤT XƯỞNG',
                                            'checked' => 'ĐANG KIỂM ĐỊNH',
                                            'received' => 'ĐÃ TIẾP NHẬN',
                                            'inventoried' => 'ĐÃ NHẬP KHO',
                                        ];
                                    @endphp
                                    <span class="badge-enterprise {{ $statusClasses[$shipment->status] ?? 'bg-gray-100 text-gray-600 border-gray-200' }}">
                                        {{ $statusLabels[$shipment->status] ?? strtoupper($shipment->status) }}
                                    </span>
                                </td>
                                <td class="pr-8 py-6 text-right">
                                    <a href="{{ route('shipments.show', $shipment) }}" class="text-[10px] font-black text-gray-900 border-b-2 border-transparent hover:border-[#E11D48] hover:text-[#E11D48] tracking-[0.2em] transition-all italic">CHI TIẾT VẬN ĐƠN</a>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-8 bg-gray-50 rounded-3xl mb-6 shadow-inner">
                                        <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <p class="text-gray-400 font-black text-[11px] uppercase tracking-[0.3em] italic">Không phát hiện dữ liệu lô hàng khả dụng</p>
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
