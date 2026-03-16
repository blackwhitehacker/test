<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 tracking-tighter uppercase">
                {{ __('Hồ sơ Chứng từ Giao nhận') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('shipments.index') }}" class="bg-gray-50 hover:bg-gray-200 text-gray-400 hover:text-gray-900 p-2 rounded-lg transition-all duration-300 border border-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <a href="{{ route('shipments.export', $shipment) }}" target="_blank" class="btn-enterprise-outline px-6 py-2 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h6z"></path></svg>
                    XUẤT PHIẾU GIAO NHẬN
                </a>
                <a href="{{ route('shipments.edit', $shipment) }}" class="btn-enterprise-danger py-2 px-8">
                    CẬP NHẬT LÔ HÀNG
                </a>
                <form action="{{ route('shipments.destroy', $shipment) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lô hàng này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-white hover:bg-red-50 text-red-600 border border-red-100 rounded-lg font-bold text-xs tracking-widest uppercase transition-all shadow-sm">
                        XÓA LÔ HÀNG
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-6 duration-1000">
        <!-- Main Info Hero -->
        <div class="card-enterprise overflow-hidden border-gray-100 p-0 shadow-sm mb-8">
            <div class="px-8 py-8 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white">
                <div class="flex items-center gap-8">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center border border-gray-100 shadow-sm relative group">
                        <svg class="w-8 h-8 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <span class="px-4 py-1 bg-[#E11D48] text-white text-[11px] font-bold uppercase tracking-widest rounded-lg shadow-sm shadow-[#E11D48]/30">{{ $shipment->code }}</span>
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'delivered' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'checked' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                    'received' => 'bg-cyan-100 text-cyan-700 border-cyan-200',
                                    'inventoried' => 'bg-green-100 text-green-700 border-green-200',
                                ];
                                $statusLabels = [
                                    'pending' => 'CHỜ BÀN GIAO',
                                    'delivered' => 'ĐÃ XUẤT XƯỞNG',
                                    'checked' => 'ĐANG KIỂM ĐỊNH',
                                    'received' => 'ĐÃ TIẾP NHẬN',
                                    'inventoried' => 'ĐÃ NHẬP KHO',
                                ];
                            @endphp
                            <span class="px-4 py-1 rounded-lg text-[11px] font-bold uppercase tracking-widest border {{ $statusClasses[$shipment->status] ?? 'bg-gray-100 text-gray-500 border-gray-200' }}">
                                {{ $statusLabels[$shipment->status] ?? strtoupper($shipment->status) }}
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tighter uppercase">Vận đơn: {{ $shipment->code }}</h1>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1">Hợp đồng gốc: <a href="{{ route('contracts.show', $shipment->contract_id) }}" class="text-[#E11D48] hover:underline">{{ $shipment->contract->contract_number }}</a></p>
                    </div>
                </div>
                
                <div class="flex md:flex-col items-end gap-1 text-right">
                    <span class="text-[11px] font-bold uppercase tracking-widest text-gray-400">Thời điểm bàn giao</span>
                    <span class="text-2xl font-bold text-gray-800 tracking-tighter">
                        {{ $shipment->delivery_date ? $shipment->delivery_date->format('d/m/Y') : '---' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-10">
                <div class="card-enterprise overflow-hidden border-t-0 p-0">
                    <div class="px-8 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-xs uppercase tracking-widest text-gray-700">Danh mục hàng hóa bàn giao</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="table-premium">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="pl-10 !py-4">Sản phẩm / Diễn giải kỹ thuật</th>
                                    <th class="text-center">Số lượng HĐ</th>
                                    <th class="text-center text-[#E11D48]">Thực giao</th>
                                    <th class="pr-10 text-center">Đơn vị</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($shipment->items ?? [] as $item)
                                    <tr class="hover:bg-gray-50/50 transition-all group">
                                        <td class="pl-10 py-4">
                                            <div class="text-[13px] font-bold text-gray-900 tracking-tighter uppercase group-hover:text-[#E11D48] transition-colors">{{ $item['name'] }}</div>
                                            <div class="text-[10px] text-gray-400 mt-0.5 font-bold">Mã thiết bị: {{ strtoupper(substr(md5($item['name']), 0, 8)) }}</div>
                                        </td>
                                        <td class="py-4 text-center font-bold text-gray-400 text-lg font-mono">{{ $item['ordered_qty'] }}</td>
                                        <td class="py-4 text-center border-x border-red-50">
                                            <div class="inline-flex flex-col items-center">
                                                <span class="text-xl font-bold text-[#E11D48] font-mono tracking-tighter">{{ $item['delivered_qty'] }}</span>
                                                <span class="text-[10px] font-bold text-red-300 uppercase">Thực xuất</span>
                                            </div>
                                        </td>
                                        <td class="pr-10 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $item['unit'] ?? 'Bộ' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($shipment->note)
                        <div class="p-10 bg-gray-50/50 border-t border-gray-100">
                            <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">Ghi chú vận hành</h4>
                            <div class="p-4 bg-white border border-gray-100 rounded-xl text-sm text-gray-600 leading-relaxed shadow-sm">
                                "{{ $shipment->note }}"
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Activity Log -->
                <div class="card-enterprise p-8 bg-white border-gray-100">
                    <h3 class="font-bold text-xs uppercase tracking-widest text-gray-700 mb-8 border-b border-gray-100 pb-4">Nhật ký tác động</h3>
                    <div class="relative pl-8 space-y-12 before:absolute before:left-[15px] before:top-2 before:bottom-2 before:w-[3px] before:bg-gray-200 before:rounded-full">
                        @forelse($shipment->activityLogs as $log)
                            <div class="relative group">
                                <div class="absolute -left-[23px] top-1 w-5 h-5 rounded-full border-4 border-white 
                                    @if($log->action == 'create') bg-blue-600 shadow-blue-100
                                    @elseif($log->action == 'update') bg-indigo-600 shadow-indigo-100
                                    @else bg-gray-900 shadow-gray-100 @endif shadow-lg transition-transform group-hover:scale-125 duration-300"></div>
                                
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[11px] font-bold uppercase tracking-widest text-gray-900 group-hover:text-[#E11D48] transition-colors">
                                        {{ strtoupper($log->description) }}
                                    </span>
                                </div>
                                <p class="text-[11px] font-bold text-gray-500 tracking-tighter uppercase">Nhân viên khởi tạo: <span class="font-bold text-gray-900">{{ strtoupper($log->user->name) }}</span></p>
                                <span class="text-[10px] font-bold text-gray-400 mt-2 block uppercase tracking-widest">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @empty
                            <div class="text-center py-10 opacity-20">
                                <p class="text-[11px] font-bold uppercase tracking-widest">Dữ liệu nhật ký trống</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-10">
                <div class="card-premium p-8 relative overflow-hidden group shadow-sm border-gray-100">
                    <h3 class="font-bold text-xs uppercase tracking-widest text-gray-700 mb-8 pb-4 border-b border-gray-50">Hợp đồng gốc</h3>
                    <div class="space-y-8">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 block mb-3">Mã hợp đồng xác thực</label>
                            <a href="{{ route('contracts.show', $shipment->contract_id) }}" class="text-lg font-bold text-gray-900 hover:text-[#E11D48] transition-colors tracking-tighter underline decoration-[#E11D48] decoration-2 underline-offset-4">
                                {{ $shipment->contract->contract_number }}
                            </a>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 block mb-3">Đối tác cung ứng</label>
                            <p class="text-sm font-bold text-gray-900 uppercase tracking-widest">{{ $shipment->contract->partner->name }}</p>
                        </div>
                        <div class="pt-6 border-t border-gray-100">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 block mb-3">Tổng trị giá quyết toán</label>
                            <p class="text-2xl font-bold text-[#E11D48] font-mono tracking-tighter">{{ number_format($shipment->contract->value) }} ₫</p>
                        </div>
                    </div>
                </div>

                <div class="card-enterprise p-8 bg-white border-gray-100">
                    <h3 class="font-bold text-xs uppercase tracking-widest text-gray-700 mb-8 border-b border-gray-50 pb-4">Đặc tính vận chuyển</h3>
                    <div class="space-y-8">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                            </div>
                             <div>
                                <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Ngày bàn giao vật lý</label>
                                <p class="text-sm font-bold text-gray-900 tracking-widest">{{ $shipment->delivery_date ? $shipment->delivery_date->format('d/m/Y') : 'CHƯA XÁC ĐỊNH' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-[#E11D48] shadow-inner relative overflow-hidden group">
                                <span class="text-sm font-bold">{{ substr($shipment->receiver_name ?? '?', 0, 1) }}</span>
                                <div class="absolute inset-0 bg-[#E11D48] opacity-10"></div>
                            </div>
                             <div>
                                <label class="text-[11px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Nhân sự đại diện tiếp nhận</label>
                                <p class="text-sm font-bold text-gray-900 tracking-tighter uppercase">{{ $shipment->receiver_name ?? 'CHƯA CẬP NHẬT' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
