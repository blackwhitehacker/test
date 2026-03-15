@php
    /** @var \App\Models\InventoryRequest $inventoryRequest */
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 py-2">
            <div class="flex items-center space-x-4">
                <a href="{{ route('inventory_requests.index', ['type' => $inventoryRequest->type]) }}" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-900 hover:text-white transition-all duration-300 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase">
                        Yêu cầu <span class="text-[#E11D48]">{{ $inventoryRequest->type == 'inbound' ? 'nhập kho' : 'xuất kho' }}</span>: {{ $inventoryRequest->code }}
                    </h2>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Hệ thống phê duyệt & Điều phối vật tư</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                @if($inventoryRequest->status == 'pending')
                    <a href="{{ route('inventory_requests.edit', [$inventoryRequest, 'type' => $inventoryRequest->type]) }}" class="p-2 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition-all font-bold text-[10px] uppercase tracking-widest">
                        Sửa
                    </a>
                    <form action="{{ route('inventory_requests.approve', $inventoryRequest) }}" method="POST" onsubmit="return confirm('Phê duyệt phiếu yêu cầu này?')">
                        @csrf
                        <button type="submit" class="btn-enterprise">
                            Duyệt hồ sơ
                        </button>
                    </form>
                    <button @click="$dispatch('open-modal', 'reject-request')" class="p-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-600 hover:text-white transition-all font-bold text-[10px] uppercase tracking-widest border border-red-100">
                        Từ chối
                    </button>
                    <form action="{{ route('inventory_requests.cancel', $inventoryRequest) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy phiếu này?')">
                        @csrf
                        <button type="submit" class="p-2 bg-white text-gray-400 rounded-lg hover:text-gray-900 transition-all font-bold text-[10px] uppercase tracking-widest underline decoration-2 underline-offset-4">
                            Hủy
                        </button>
                    </form>
                @endif

                @if($inventoryRequest->status == 'approved')
                    @if(!$inventoryRequest->receipt)
                        <form action="{{ route('inventory_requests.generate_receipt', $inventoryRequest) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-enterprise">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Khởi tạo lệnh {{ $inventoryRequest->type == 'inbound' ? 'nhập' : 'xuất' }} kho
                            </button>
                        </form>
                    @else
                        <a href="{{ route('inventory_receipts.show', $inventoryRequest->receipt) }}" class="btn-enterprise bg-green-600">
                            Thực thi lệnh nghiệp vụ
                        </a>
                    @endif
                @endif

                @if(in_array($inventoryRequest->status, ['pending', 'cancelled', 'rejected']))
                    <form action="{{ route('inventory_requests.destroy', $inventoryRequest) }}" method="POST" onsubmit="return confirm('Xóa vĩnh viễn phiếu yêu cầu này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-gray-300 hover:text-red-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v2m3 3h.01"></path></svg>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 animate-in fade-in slide-in-from-bottom-8 duration-1000">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-10">
            <!-- Basic Info Card -->
            <div class="card-enterprise">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div class="flex items-center space-x-2">
                        <div class="w-1 h-4 bg-[#E11D48] rounded-full"></div>
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-900">Tổng quan hồ sơ</h3>
                    </div>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Lập ngày: {{ $inventoryRequest->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Phân loại nguồn</p>
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <p class="text-xs font-bold text-gray-900 uppercase">
                                    @if($inventoryRequest->type == 'inbound')
                                        @if($inventoryRequest->source_type == 'purchase') <span class="text-[#E11D48]">●</span> Mua sắm mới @elseif($inventoryRequest->source_type == 'transfer') <span class="text-blue-600">●</span> Điều chuyển nội bộ @else Khác @endif
                                    @else
                                        @if($inventoryRequest->source_type == 'allocation') <span class="text-[#E11D48]">●</span> Cấp phát nhân viên @elseif($inventoryRequest->source_type == 'repair') <span class="text-amber-600">●</span> Gửi sửa chữa @elseif($inventoryRequest->source_type == 'disposal') <span class="text-gray-900">●</span> Thanh lý @else Khác @endif
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Người đề xuất</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-900 flex items-center justify-center text-xs font-bold text-white shadow-sm">
                                    {{ strtoupper(substr($inventoryRequest->requester->name, 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-900">{{ $inventoryRequest->requester->name }}</span>
                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Hệ thống</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @if($inventoryRequest->type == 'outbound' && isset($inventoryRequest->receiver))
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Đơn vị nhận</p>
                            <p class="text-xs font-bold text-gray-900 p-3 bg-gray-50 rounded-xl border border-gray-100">"{{ $inventoryRequest->receiver }}"</p>
                        </div>
                        @endif

                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nội dung</p>
                            <div class="p-4 bg-gray-900 text-white rounded-xl text-xs leading-relaxed border-l-2 border-[#E11D48]">
                                {{ $inventoryRequest->notes ?: 'Không có ghi chú.' }}
                            </div>
                        </div>
                    </div>

                    @if($inventoryRequest->status == 'rejected')
                        <div class="md:col-span-2">
                            <div class="bg-red-50 border-2 border-dashed border-red-100 p-4 rounded-xl">
                                <p class="text-[9px] font-bold text-red-600 uppercase tracking-widest mb-1">Lý do từ chối</p>
                                <p class="text-xs font-bold text-red-900">"{{ $inventoryRequest->rejection_reason }}"</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Items Details -->
            <div class="card-enterprise overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-900 text-white">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <h3 class="text-[10px] font-bold uppercase tracking-widest">Danh mục vật tư</h3>
                    </div>
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Trình mặt {{ $inventoryRequest->items->count() }} mục</span>
                </div>
                <div class="p-0">
                    @if($inventoryRequest->items->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="table-premium !border-0 font-sans">
                                <thead>
                                    <tr class="bg-gray-50/50">
                                        <th class="pl-6 py-3">Danh mục & Quy cách</th>
                                        <th class="text-center">SL</th>
                                        <th class="text-center">Liên kết</th>
                                        <th class="text-right">Đơn giá</th>
                                        <th class="text-right pr-6">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @php $total = 0; @endphp
                                    @foreach($inventoryRequest->items as $item)
                                        @php 
                                            $subtotal = $item->quantity * $item->price;
                                            $total += $subtotal;
                                        @endphp
                                        <tr class="group hover:bg-gray-50/50 transition-colors text-xs">
                                            <td class="py-4 pl-6">
                                                <p class="font-bold text-gray-900 uppercase group-hover:text-[#E11D48] transition-colors leading-tight">{{ $item->name }}</p>
                                                <p class="text-[9px] text-gray-400 font-bold mt-0.5">Quy cách: {{ $item->specification ?? '-' }}</p>
                                            </td>
                                            <td class="py-4 text-center">
                                                <span class="font-bold text-gray-900">{{ number_format($item->quantity) }}</span>
                                            </td>
                                            <td class="py-4 text-center">
                                                @if($item->asset_id && $item->asset)
                                                    <a href="{{ route('assets.show', $item->asset) }}" class="inline-flex items-center px-2 py-0.5 bg-white border border-gray-200 text-gray-900 rounded-lg text-[9px] font-bold hover:bg-gray-900 hover:text-white transition-all shadow-sm">
                                                        {{ $item->asset->code }}
                                                    </a>
                                                @else
                                                    <span class="text-[9px] text-gray-300 font-bold">N/A</span>
                                                @endif
                                            </td>
                                            <td class="py-4 text-right text-gray-600">{{ number_format($item->price) }} đ</td>
                                            <td class="py-4 text-right pr-6">
                                                <span class="font-bold text-gray-900">{{ number_format($subtotal) }} đ</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50/80">
                                    <tr>
                                        <td colspan="4" class="py-5 text-right text-[9px] font-bold text-gray-400 uppercase tracking-widest pl-6">Tổng giá trị dự kiến:</td>
                                        <td class="py-5 text-right pr-6">
                                            <span class="text-lg font-bold text-[#E11D48] tracking-tight">{{ number_format($total) }} đ</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-20 px-8 border-t border-gray-100">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl mx-auto mb-4 flex items-center justify-center border-2 border-dashed border-gray-200">
                                <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Hồ sơ rỗng</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Shipment Info (Optional Link) -->
            @if($inventoryRequest->shipment)
                <div class="card-enterprise p-4 bg-gray-900 text-white group flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/10 rounded-lg">
                            <svg class="w-4 h-4 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold uppercase text-gray-500 tracking-widest">Lô hàng liên kết</p>
                            <h4 class="text-xs font-bold leading-tight">{{ $inventoryRequest->shipment->code }}</h4>
                        </div>
                    </div>
                    <a href="{{ route('shipments.show', $inventoryRequest->shipment) }}" class="btn-enterprise scale-90">
                        Chi tiết
                    </a>
                </div>
            @endif
        </div>

        <!-- Sidebar: Status & History -->
        <div class="space-y-10">
            <!-- Status Card -->
            <div class="card-enterprise p-6 text-center">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-4">Trạng thái phê duyệt</p>
                
                <div class="relative z-10">
                    @if($inventoryRequest->status == 'pending')
                        <span class="badge-enterprise bg-amber-50 text-amber-700 border-amber-200 py-2 px-6 text-[10px] font-bold">CHỜ PHÊ DUYỆT</span>
                    @elseif($inventoryRequest->status == 'approved')
                        <span class="badge-enterprise bg-green-50 text-green-700 border-green-200 py-2 px-6 text-[10px] font-bold shadow-sm">ĐÃ PHÊ DUYỆT</span>
                        
                        @php
                            $receipt = \App\Models\InventoryReceipt::where('request_id', $inventoryRequest->id)->first();
                        @endphp
                        @if($receipt)
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-3">Lệnh nghiệp vụ</p>
                                <a href="{{ route('inventory_receipts.show', $receipt) }}" class="btn-enterprise w-full text-center">
                                    Thực thi {{ $inventoryRequest->type == 'inbound' ? 'nhập' : 'xuất' }}
                                </a>
                            </div>
                        @endif
                    @elseif($inventoryRequest->status == 'rejected')
                        <span class="badge-enterprise bg-red-50 text-red-700 border-red-200 py-2 px-6 text-[10px] font-bold">TỪ CHỐI</span>
                    @else
                        <span class="badge-enterprise bg-gray-900 text-white border-none py-2 px-6 text-[10px] font-bold">{{ strtoupper($inventoryRequest->status) }}</span>
                    @endif
                </div>
            </div>

            <!-- History Timeline -->
            <div class="card-enterprise overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-900 text-white">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-[#E11D48]">Nhật ký thao tác</h3>
                </div>
                <div class="p-6">
                    <div class="relative">
                        <div class="absolute left-2 top-0 bottom-0 w-px bg-gray-100"></div>
                        <div class="space-y-6 relative">
                            @foreach($inventoryRequest->activityLogs as $log)
                                <div class="flex items-start gap-4">
                                    <div class="w-4 h-4 rounded-full bg-white border-2 border-gray-900 z-10 shrink-0 mt-0.5 shadow-sm"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] font-bold text-gray-900 uppercase">
                                            {{ $log->user->name }}
                                        </p>
                                        <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">
                                            @if($log->action == 'create') Khởi tạo @elseif($log->approve_action == 'approve') Phê duyệt @elseif($log->action == 'reject') Từ chối @else Cập nhật @endif
                                        </p>
                                        <p class="text-[8px] text-gray-400 mt-1">{{ $log->created_at->format('H:i - d/m/Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($inventoryRequest->activityLogs->count() == 0)
                        <div class="py-10 text-center border-2 border-dashed border-gray-100 rounded-2xl">
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Chưa có bản ghi hoạt động</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <x-modal name="reject-request" focusable>
        <form action="{{ route('inventory_requests.reject', $inventoryRequest) }}" method="POST" class="p-10 card-enterprise">
            @csrf
            <div class="flex items-center gap-4 mb-8 border-b border-gray-100 pb-6">
                <div class="p-3 bg-red-50 rounded-2xl">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 8 8 0 0118 0z"></path></svg>
                </div>
                <h2 class="font-black text-2xl text-gray-900 tracking-tighter uppercase italic">
                    Từ chối phê duyệt
                </h2>
            </div>
            
            <p class="mb-6 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-loose">
                Hành động này sẽ bác bỏ yêu cầu và thông báo cho người lập đề xuất. Vui lòng cung cấp lý do cụ thể bên dưới.
            </p>

            <div class="mb-8">
                <label for="reason" class="sr-only">Lý do từ chối</label>
                <textarea id="reason" name="reason" rows="4" required
                          class="enterprise-input min-h-[140px]"
                          placeholder="Mô tả chi tiết lý do bác bỏ hồ sơ này..."></textarea>
            </div>

            <div class="flex justify-end items-center gap-6 pt-6 border-t border-gray-100">
                <button type="button" x-on:click="$dispatch('close')" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 transition-colors">
                    Quay lại
                </button>
                <button type="submit" class="btn-enterprise bg-red-600 hover:bg-red-700">
                    Xác nhận bác bỏ hồ sơ
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
