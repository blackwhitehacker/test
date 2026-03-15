<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('inventory_requests.show', $inventoryRequest) }}" class="p-2 bg-gray-100 rounded-full hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-gray-900 uppercase tracking-tight">
                    Chỉnh sửa phiếu yêu cầu {{ $inventoryRequest->type == 'inbound' ? 'nhập kho' : 'xuất kho' }}: <span class="text-enterprise-red">{{ $inventoryRequest->code }}</span>
                </h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Hệ thống quản lý kho & Phê duyệt</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="{ 
        items: {{ Js::from($inventoryRequest->items) }},
        addItem() {
            this.items = [...this.items, { asset_id: '', name: '', quantity: 1, specification: '', price: 0 }];
        },
        removeItem(index) {
            this.items = this.items.filter((_, i) => i !== index);
        },
        shipment_id: '{{ old('shipment_id', $inventoryRequest->shipment_id) }}',
        assets: {{ Js::from($assets) }}
    }">
        <form action="{{ route('inventory_requests.update', $inventoryRequest) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex items-center space-x-3 bg-gray-50/30">
                    <div class="w-2 h-6 bg-enterprise-red rounded-full"></div>
                    <h3 class="text-sm font-black uppercase tracking-widest text-gray-800">Thông tin phiếu yêu cầu</h3>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <input type="hidden" name="type" value="{{ $inventoryRequest->type }}">

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">
                            {{ $inventoryRequest->type == 'inbound' ? 'Nguồn nhập hàng' : 'Mục đích xuất kho' }} <span class="text-red-600">*</span>
                        </label>
                        <select name="source_type" required class="input-premium py-2 text-sm">
                            @if($inventoryRequest->type == 'inbound')
                                <option value="purchase" {{ old('source_type', $inventoryRequest->source_type) == 'purchase' ? 'selected' : '' }}>Từ mua sắm mới</option>
                                <option value="transfer" {{ old('source_type', $inventoryRequest->source_type) == 'transfer' ? 'selected' : '' }}>Từ điều chuyển nội bộ</option>
                            @else
                                <option value="allocation" {{ old('source_type', $inventoryRequest->source_type) == 'allocation' ? 'selected' : '' }}>Cấp phát cho nhân viên</option>
                                <option value="repair" {{ old('source_type', $inventoryRequest->source_type) == 'repair' ? 'selected' : '' }}>Gửi đi sửa chữa</option>
                                <option value="disposal" {{ old('source_type', $inventoryRequest->source_type) == 'disposal' ? 'selected' : '' }}>Thanh lý tài sản</option>
                            @endif
                            <option value="other" {{ old('source_type', $inventoryRequest->source_type) == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>

                    @if($inventoryRequest->type == 'inbound')
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Liên kết Lô hàng (nếu có)</label>
                        <select name="shipment_id" x-model="shipment_id" class="input-premium py-2 text-sm">
                            <option value="">-- Không liên kết --</option>
                            @foreach($shipments as $shipment)
                                <option value="{{ $shipment->id }}" {{ old('shipment_id', $inventoryRequest->shipment_id) == $shipment->id ? 'selected' : '' }}>
                                    {{ $shipment->code }} - {{ $shipment->contract->contract_number ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Người nhận / Đơn vị nhận</label>
                        <input type="text" name="receiver" value="{{ old('receiver', $inventoryRequest->receiver) }}" class="input-premium py-2 text-sm" placeholder="Nhập tên người hoặc phòng ban nhận...">
                    </div>
                    @endif

                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Ghi chú / Lý do</label>
                        <textarea name="notes" rows="2"
                                  class="block w-full border-gray-200 focus:border-enterprise-red focus:ring-enterprise-red rounded-2xl shadow-sm text-sm"
                                  placeholder="Mô tả chi tiết mục đích...">{{ old('notes', $inventoryRequest->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Goods Management Section -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-gray-800">Danh sách hàng hóa bổ sung</h3>
                    </div>
                    <button type="button" @click="addItem()" class="inline-flex items-center text-[10px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-800 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Thêm thủ công
                    </button>
                </div>
                
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                    @if($inventoryRequest->type == 'outbound')
                                    <th class="pb-4 w-1/4">Chọn tài sản</th>
                                    @endif
                                    <th class="pb-4 w-1/3">Tên hàng hóa <span class="text-red-500">*</span></th>
                                    <th class="pb-4">Quy cách</th>
                                    <th class="pb-4 w-24 text-center">SL <span class="text-red-500">*</span></th>
                                    <th class="pb-4 w-40">Đơn giá dự kiến</th>
                                    <th class="pb-4 w-12"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="group border-b border-gray-50/50">
                                        @if($inventoryRequest->type == 'outbound')
                                        <td class="py-4 pr-4">
                                            <select :name="`items[${index}][asset_id]`" x-model="item.asset_id"
                                                    @change="const asset = assets.find(a => a.id == item.asset_id); if(asset) { item.name = asset.name; item.specification = asset.group_name || ''; item.price = asset.purchase_price; }"
                                                    class="block w-full border-transparent focus:border-enterprise-red focus:ring-enterprise-red rounded-xl text-sm bg-gray-50 group-hover:bg-white transition-all font-bold">
                                                <option value="">-- Chọn tài sản --</option>
                                                @foreach($assets as $asset)
                                                    <option value="{{ $asset['id'] }}">{{ $asset['code'] }} - {{ $asset['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        @endif
                                        <td class="py-4 pr-4">
                                            <input type="text" :name="`items[${index}][name]`" x-model="item.name" required
                                                   class="block w-full border-transparent focus:border-enterprise-red focus:ring-enterprise-red rounded-xl text-sm font-bold bg-gray-50 group-hover:bg-white transition-all"
                                                   placeholder="Nhập tên hàng hóa...">
                                        </td>
                                        <td class="py-4 pr-4">
                                            <input type="text" :name="`items[${index}][specification]`" x-model="item.specification"
                                                   class="block w-full border-transparent focus:border-enterprise-red focus:ring-enterprise-red rounded-xl text-sm bg-gray-50 group-hover:bg-white transition-all font-medium"
                                                   placeholder="VD: Cái, hộp, bộ...">
                                        </td>
                                        <td class="py-4 pr-4">
                                            <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" required min="1"
                                                   class="block w-full border-transparent focus:border-enterprise-red focus:ring-enterprise-red rounded-xl text-sm bg-gray-50 group-hover:bg-white transition-all text-center font-black">
                                        </td>
                                        <td class="py-4 pr-4">
                                            <div class="relative">
                                                <input type="number" :name="`items[${index}][price]`" x-model="item.price" min="0"
                                                       class="block w-full border-transparent focus:border-enterprise-red focus:ring-enterprise-red rounded-xl text-sm bg-gray-50 group-hover:bg-white transition-all pr-8 font-bold">
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold italic">đ</span>
                                            </div>
                                        </td>
                                        <td class="py-4 text-center">
                                            <button type="button" @click="removeItem(index)" class="text-gray-300 hover:text-red-600 transition-colors p-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    
                    <div x-show="items.length === 0" class="text-center py-8 bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-100 mt-4">
                        <p class="text-sm text-gray-400 italic font-medium">Bạn có thể bấm "Thêm thủ công" để nhập danh sách hàng hóa.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic">
                    <span class="text-red-500">*</span> Cập nhật thông tin sẽ thay thế danh sách hàng hóa hiện tại.
                </p>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('inventory_requests.show', $inventoryRequest) }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors">
                        Quay lại
                    </a>
                    <button type="submit" class="bg-gray-900 hover:bg-black text-white px-10 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-gray-900/10 transition-all transform hover:-translate-y-0.5">
                        Cập nhật thay đổi
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
