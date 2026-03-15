<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 py-2">
            <div class="flex items-center space-x-4">
                <a href="{{ route('inventory_receipts.index', ['type' => $receipt->type]) }}" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-900 hover:text-white transition-all duration-300 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase">
                    {{ __('Xử lý phiếu') }} <span class="text-[#E11D48]">{{ $receipt->type == 'inbound' ? __('nhập kho') : __('xuất kho') }}</span>: {{ $receipt->code }}
                </h2>
            </div>

            <div class="flex items-center gap-2">
                @if($receipt->status == 'draft')
                    <button form="receipt-form" formaction="{{ route('inventory_receipts.save_items', $receipt) }}" type="submit" class="p-2 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition-all font-bold text-[10px] uppercase tracking-widest">
                        {{ __('Lưu tạm') }}
                    </button>
                    @php
                        $confirmMsg = $receipt->type == 'inbound' 
                            ? 'Xác nhận nhập kho chính thức?'
                            : 'Xác nhận xuất kho chính thức?';
                    @endphp
                    <button form="receipt-form" formaction="{{ route('inventory_receipts.confirm', $receipt) }}" type="submit" onclick="return confirm('{{ $confirmMsg }}')" class="btn-enterprise">
                        {{ __('Hoàn thành') }}
                    </button>
                @else
                    <span class="badge-enterprise bg-green-50 text-green-700 border-green-200 py-2 px-6 text-[10px] font-bold">
                        ĐÃ QUYẾT TOÁN {{ $receipt->type == 'inbound' ? 'NHẬP' : 'XUẤT' }}
                    </span>
                    <a href="{{ route('inventory_receipts.export', $receipt) }}" target="_blank" class="px-4 py-2 bg-gray-900 hover:bg-black text-white font-bold text-[10px] tracking-widest rounded-lg transition-all duration-300 shadow-sm uppercase">
                        Xuất phiếu
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-1000">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-3 space-y-8">
                <form id="receipt-form" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- Evaluation Notes -->
                    <div class="card-enterprise p-6">
                        <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-4">
                            <div class="p-2 bg-gray-900 rounded-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="font-bold text-[10px] uppercase tracking-widest text-[#E11D48]">
                                {{ $receipt->type == 'inbound' ? 'Ghi chú nhập kho' : 'Ghi chú xuất kho' }}
                            </h3>
                        </div>
                        <textarea name="evaluation_notes" rows="3" 
                                class="enterprise-input min-h-[100px] text-xs"
                                {{ $receipt->status != 'draft' ? 'readonly' : '' }}
                                placeholder="Ghi chú chi tiết... ">{{ $receipt->evaluation_notes }}</textarea>
                    </div>

                    @foreach($receipt->items as $itemIndex => $item)
                        <div class="card-enterprise overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                                <h3 class="text-[10px] font-bold text-gray-900 tracking-widest uppercase"><span class="text-[#E11D48] mr-1">#{{ $itemIndex + 1 }}</span> {{ $item['name'] }}</h3>
                                <span class="badge-enterprise bg-gray-900 text-white border-none py-1 px-3 text-[9px] font-bold">SL: {{ $item['quantity'] }}</span>
                            </div>
                            
                            <div class="p-0">
                                <div class="overflow-x-auto">
                                    <table class="table-premium !border-0 text-xs">
                                        <thead>
                                            <tr class="text-[9px] font-bold uppercase text-gray-400 tracking-widest bg-gray-50/30">
                                                <th class="w-12 pl-6">STT</th>
                                                <th>Mã tài sản</th>
                                                <th>Nhóm</th>
                                                <th>Số S/N</th>
                                                <th class="w-32 text-center pr-6">Tình trạng</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($item['details'] as $detailIndex => $detail)
                                                <tr class="group hover:bg-gray-50/50 transition-colors">
                                                    <td class="pl-6 py-4 font-bold text-[10px] text-gray-400 group-hover:text-[#E11D48]">
                                                        {{ str_pad($detailIndex + 1, 2, '0', STR_PAD_LEFT) }}
                                                    </td>
                                                    <td class="py-4">
                                                        <input type="text" name="items[{{ $itemIndex }}][details][{{ $detailIndex }}][asset_code]" 
                                                            value="{{ $detail['asset_code'] }}" required
                                                            class="enterprise-input font-bold tracking-tight text-xs {{ $receipt->status != 'draft' ? 'bg-gray-50 border-none' : '' }}"
                                                            {{ $receipt->status != 'draft' ? 'disabled' : '' }}>
                                                    </td>
                                                    <td class="py-4">
                                                        <select name="items[{{ $itemIndex }}][details][{{ $detailIndex }}][group_id]" required
                                                                class="enterprise-input font-bold text-[10px] h-[36px] {{ $receipt->status != 'draft' ? 'bg-gray-50 border-none' : '' }}"
                                                                {{ $receipt->status != 'draft' ? 'disabled' : '' }}>
                                                            <option value="">-- Nhóm --</option>
                                                            @foreach($assetGroups as $group)
                                                                <option value="{{ $group->id }}" {{ ($detail['group_id'] ?? '') == $group->id ? 'selected' : '' }}>
                                                                    {{ $group->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="py-4">
                                                        <input type="text" name="items[{{ $itemIndex }}][details][{{ $detailIndex }}][serial]" 
                                                            value="{{ $detail['serial'] }}"
                                                            class="enterprise-input font-mono text-[10px] {{ $receipt->status != 'draft' ? 'bg-gray-50 border-none' : '' }}"
                                                            {{ $receipt->status != 'draft' ? 'disabled' : '' }}
                                                            placeholder="S/N...">
                                                    </td>
                                                    <td class="py-4 pr-6 text-center">
                                                        <select name="items[{{ $itemIndex }}][details][{{ $detailIndex }}][condition]" 
                                                                class="enterprise-input font-bold uppercase text-[9px] tracking-widest text-center h-[36px] {{ $receipt->status != 'draft' ? 'bg-gray-50 border-none' : '' }}"
                                                                {{ $receipt->status != 'draft' ? 'disabled' : '' }}>
                                                            <option value="new" {{ ($detail['condition'] ?? '') == 'new' ? 'selected' : '' }}>MỚI</option>
                                                            <option value="used" {{ ($detail['condition'] ?? '') == 'used' ? 'selected' : '' }}>CŨ</option>
                                                            <option value="broken" {{ ($detail['condition'] ?? '') == 'damaged' ? 'selected' : '' }}>HỎNG</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>

            <!-- Sidebar Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="card-enterprise p-6 bg-gray-900 text-white relative">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#E11D48] mb-6 border-b border-white/10 pb-4">Liên kết nguồn</h3>
                    <div class="space-y-6">
                        <div class="flex flex-col gap-1">
                            <label class="text-[9px] font-bold uppercase text-gray-500 tracking-widest">Yêu cầu liên kết</label>
                            <a href="{{ route('inventory_requests.show', $receipt->request) }}" class="text-[10px] font-bold text-white hover:text-[#E11D48] transition-colors bg-white/5 border border-white/10 p-2 rounded-lg flex items-center justify-between group">
                                {{ $receipt->request->code }}
                                <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[9px] font-bold uppercase text-gray-500 tracking-widest">Người thụ hưởng</label>
                            <p class="text-xs font-bold">{{ $receipt->request->requester->name }}</p>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[9px] font-bold uppercase text-gray-500 tracking-widest">Thời gian duyệt</label>
                            <p class="text-xs font-bold text-gray-400">{{ $receipt->request->updated_at->format('H:i - d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-enterprise p-6 text-center">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-4">Trạng thái hồ sơ</p>
                    @if($receipt->status == 'draft')
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-700 rounded-lg border border-amber-200 text-[10px] font-bold">
                            <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                            SOẠN THẢO
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 rounded-lg border border-green-200 text-[10px] font-bold">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            HOÀN TẤT
                        </div>
                    @endif
                </div>
                <!-- Activity Logs -->
                <div class="card-enterprise p-6">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#E11D48] mb-6 border-b border-gray-100 pb-4">Tiến độ xử lý</h3>
                    @if($receipt->activityLogs->count() > 0)
                        <div class="relative">
                            <div class="absolute left-1.5 top-0 bottom-0 w-px bg-gray-100"></div>
                            <div class="space-y-6 relative">
                                @foreach($receipt->activityLogs as $log)
                                    <div class="flex gap-4 group">
                                        <div class="w-3 h-3 rounded-full bg-white border-2 border-gray-900 shrink-0 z-10 mt-1 shadow-sm"></div>
                                        <div class="min-w-0">
                                            <p class="text-[10px] font-bold text-gray-900 uppercase truncate">{{ $log->user->name }}</p>
                                            <p class="text-[9px] text-gray-500 font-bold mt-0.5 leading-tight">{{ $log->description }}</p>
                                            <p class="text-[8px] text-gray-400 font-bold mt-1 tracking-widest">{{ $log->created_at->format('d/m H:i') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="py-6 text-center italic border-2 border-dashed border-gray-100 rounded-2xl">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Chưa có bản ghi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

