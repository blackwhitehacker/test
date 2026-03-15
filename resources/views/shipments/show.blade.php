<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-900 tracking-tighter uppercase italic">
                {{ __('Hồ sơ Chứng từ Giao nhận') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('shipments.index') }}" class="bg-gray-100 hover:bg-gray-900 hover:text-white text-gray-400 p-3 rounded-xl transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <a href="{{ route('shipments.export', $shipment) }}" target="_blank" class="bg-gray-900 hover:bg-black text-white px-6 py-3 rounded-xl font-black text-[10px] tracking-[0.2em] uppercase transition-all shadow-lg flex items-center">
                    <svg class="w-4 h-4 mr-2 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h6z"></path></svg>
                    XUẤT PHIẾU GIAO NHẬN
                </a>
                <a href="{{ route('shipments.edit', $shipment) }}" class="btn-enterprise py-3 px-8 shadow-xl">
                    CẬP NHẬT LÔ HÀNG
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-6 duration-1000">
        <!-- Main Info Hero -->
        <div class="card-enterprise overflow-hidden border-t-0 p-0">
            <div class="px-10 py-10 flex flex-col md:flex-row md:items-center justify-between gap-10 bg-gray-900 border-b border-white/5">
                <div class="flex items-start gap-10">
                    <div class="w-24 h-24 bg-white/5 rounded-3xl flex items-center justify-center border border-white/10 shadow-2xl backdrop-blur-md relative overflow-hidden group">
                        <div class="absolute inset-0 bg-[#E11D48] opacity-0 group-hover:opacity-20 transition-opacity"></div>
                        <svg class="w-12 h-12 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <span class="px-4 py-1.5 bg-[#E11D48] text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-xl shadow-lg shadow-[#E11D48]/30 italic">{{ $shipment->code }}</span>
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    'delivered' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                    'checked' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                    'received' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                                    'inventoried' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                ];
                                $statusLabels = [
                                    'pending' => 'CHỜ BÀN GIAO',
                                    'delivered' => 'ĐÃ XUẤT XƯỞNG',
                                    'checked' => 'ĐANG KIỂM ĐỊNH',
                                    'received' => 'ĐÃ TIẾP NHẬN',
                                    'inventoried' => 'ĐÃ NHẬP KHO',
                                ];
                            @endphp
                            <span class="px-4 py-1.5 rounded-xl text-[11px] font-black uppercase tracking-[0.2em] border {{ $statusClasses[$shipment->status] ?? 'bg-white/5 text-white/40' }} backdrop-blur-sm">
                                {{ $statusLabels[$shipment->status] ?? strtoupper($shipment->status) }}
                            </span>
                        </div>
                        <h1 class="text-4xl font-black text-white tracking-tighter mb-2 italic uppercase underline decoration-[#E11D48] decoration-4 underline-offset-8">Vận đơn: {{ $shipment->code }}</h1>
                        <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.4em] mt-6 italic">Căn cứ theo hợp đồng kinh tế số: <a href="{{ route('contracts.show', $shipment->contract_id) }}" class="text-white hover:text-[#E11D48] transition-colors underline decoration-[#E11D48]/30">{{ $shipment->contract->contract_number }}</a></p>
                    </div>
                </div>
                
                <div class="flex md:flex-col items-end gap-3 text-right">
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-white/40 italic">Thời điểm giao nhận</span>
                    <span class="text-4xl font-black text-white font-mono tracking-tighter italic">
                        {{ $shipment->delivery_date ? $shipment->delivery_date->format('d/m/Y') : '---' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-10">
                <div class="card-enterprise overflow-hidden border-t-0 p-0">
                    <div class="px-10 py-6 bg-gray-900 border-b border-white/5 flex justify-between items-center">
                        <h3 class="font-black text-[10px] uppercase tracking-[0.3em] italic text-[#E11D48]">Danh mục hàng hóa bàn giao</h3>
                        <div class="flex gap-2">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            <span class="w-3 h-3 rounded-full bg-[#E11D48]"></span>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="table-premium">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="pl-10 !py-5">Sản phẩm / Diễn giải kỹ thuật</th>
                                    <th class="text-center">Số lượng HĐ</th>
                                    <th class="text-center text-[#E11D48]">Số lượng thực giao</th>
                                    <th class="pr-10 text-center">Đơn vị</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($shipment->items ?? [] as $item)
                                    <tr class="hover:bg-gray-50/50 transition-all group">
                                        <td class="pl-10 py-6">
                                            <div class="text-[13px] font-black text-gray-900 italic tracking-tight uppercase group-hover:text-[#E11D48] transition-colors">{{ $item['name'] }}</div>
                                            <div class="text-[10px] text-gray-400 mt-1 font-bold">Mã thiết bị: {{ strtoupper(substr(md5($item['name']), 0, 8)) }}</div>
                                        </td>
                                        <td class="py-6 text-center font-black text-gray-400 text-lg font-mono italic">{{ $item['ordered_qty'] }}</td>
                                        <td class="py-6 text-center border-x border-red-50">
                                            <div class="inline-flex flex-col items-center">
                                                <span class="text-2xl font-black text-[#E11D48] font-mono italic tracking-tighter">{{ $item['delivered_qty'] }}</span>
                                                <span class="text-[9px] font-black text-red-300 uppercase italic">Thực xuất</span>
                                            </div>
                                        </td>
                                        <td class="pr-10 py-6 text-center text-[10px] font-black text-gray-400 italic uppercase tracking-widest">{{ $item['unit'] ?? 'Bộ' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($shipment->note)
                        <div class="p-10 bg-gray-50/50 border-t border-gray-100">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-4 italic">Ghi chú vận hành bàn giao</h4>
                            <div class="p-6 bg-white border border-gray-100 rounded-3xl shadow-sm italic text-sm text-gray-600 leading-relaxed border-l-4 border-l-[#E11D48]">
                                "{{ $shipment->note }}"
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Activity Log -->
                <div class="card-enterprise p-10 bg-gray-50/50 border-gray-100">
                    <h3 class="font-black text-xs uppercase tracking-[0.3em] text-gray-900 mb-8 italic border-b border-gray-200 pb-6">Nhật ký tác động vật lý</h3>
                    <div class="relative pl-8 space-y-12 before:absolute before:left-[15px] before:top-2 before:bottom-2 before:w-[3px] before:bg-gray-200 before:rounded-full">
                        @forelse($shipment->activityLogs as $log)
                            <div class="relative group">
                                <div class="absolute -left-[23px] top-1 w-5 h-5 rounded-full border-4 border-white 
                                    @if($log->action == 'create') bg-blue-600 shadow-blue-100
                                    @elseif($log->action == 'update') bg-indigo-600 shadow-indigo-100
                                    @else bg-gray-900 shadow-gray-100 @endif shadow-lg transition-transform group-hover:scale-125 duration-300"></div>
                                
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-900 group-hover:text-[#E11D48] transition-colors italic">
                                        {{ strtoupper($log->description) }}
                                    </span>
                                </div>
                                <p class="text-[11px] font-bold text-gray-500 italic tracking-tighter uppercase">Nhân viên khởi tạo: <span class="font-black text-gray-900">{{ strtoupper($log->user->name) }}</span></p>
                                <span class="text-[9px] font-black text-gray-400 mt-2 block uppercase tracking-widest">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @empty
                            <div class="text-center py-10 opacity-20">
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] italic">Dữ liệu nhật ký trống</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-10">
                <div class="card-enterprise p-10 bg-gray-900 border-none relative overflow-hidden group">
                    <div class="absolute -right-20 -top-20 w-60 h-60 bg-[#E11D48] rounded-full blur-[100px] opacity-10 group-hover:opacity-20 transition-opacity duration-1000"></div>
                    
                    <h3 class="font-black text-xs uppercase tracking-[0.3em] text-[#E11D48] mb-10 italic">Thông tin hợp đồng gốc</h3>
                    <div class="space-y-8">
                        <div>
                            <label class="text-[9px] font-black uppercase tracking-[0.3em] text-white/30 block mb-3 italic">Mã hợp đồng xác thực</label>
                            <a href="{{ route('contracts.show', $shipment->contract_id) }}" class="text-lg font-black text-white hover:text-[#E11D48] transition-colors tracking-tighter italic underline decoration-[#E11D48] decoration-2 underline-offset-4">
                                {{ $shipment->contract->contract_number }}
                            </a>
                        </div>
                        <div>
                            <label class="text-[9px] font-black uppercase tracking-[0.3em] text-white/30 block mb-3 italic">Đối tác cung ứng</label>
                            <p class="text-sm font-black text-white uppercase italic tracking-widest">{{ $shipment->contract->partner->name }}</p>
                        </div>
                        <div class="pt-6 border-t border-white/5">
                            <label class="text-[9px] font-black uppercase tracking-[0.3em] text-white/30 block mb-3 italic">Tổng trị giá quyết toán</label>
                            <p class="text-2xl font-black text-[#E11D48] font-mono tracking-tighter italic">{{ number_format($shipment->contract->value) }} ₫</p>
                        </div>
                    </div>
                </div>

                <div class="card-enterprise p-10">
                    <h3 class="font-black text-xs uppercase tracking-[0.3em] text-[#E11D48] mb-10 italic border-b border-gray-100 pb-6">Đặc tính vận chuyển</h3>
                    <div class="space-y-8">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-400 block mb-1 italic">Ngày bàn giao vật lý</label>
                                <p class="text-sm font-black text-gray-900 tracking-widest italic">{{ $shipment->delivery_date ? $shipment->delivery_date->format('d/m/Y') : 'CHƯA XÁC ĐỊNH' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-[#E11D48] shadow-inner relative overflow-hidden group">
                                <span class="text-sm font-black italic">{{ substr($shipment->receiver_name ?? '?', 0, 1) }}</span>
                                <div class="absolute inset-0 bg-[#E11D48] opacity-10"></div>
                            </div>
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-400 block mb-1 italic">Nhân sự đại diện tiếp nhận</label>
                                <p class="text-sm font-black text-gray-900 tracking-tighter uppercase italic">{{ $shipment->receiver_name ?? 'CHƯA CẬP NHẬT' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
