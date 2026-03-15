<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800">
                Chỉnh sửa lô hàng: {{ $shipment->code }}
            </h2>
            <form action="{{ route('shipments.destroy', $shipment) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lô hàng này?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-sm uppercase tracking-widest flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Xóa lô hàng
                </button>
            </form>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto pb-12">
        <div class="card-premium">
            <form action="{{ route('shipments.update', $shipment) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Left Column: Selection & Status -->
                    <div class="space-y-6 border-r pr-10">
                        <h3 class="font-bold text-gray-700 border-b pb-2 uppercase text-xs tracking-wider">Thông tin vận chuyển</h3>
                        
                        <div>
                            <label class="enterprise-label">Hợp đồng mua sắm</label>
                            <div class="p-3 bg-gray-50 rounded border border-gray-200 text-sm font-bold text-gray-700">
                                [{{ $shipment->contract->code }}] {{ $shipment->contract->contract_number }} - {{ $shipment->contract->partner->name }}
                            </div>
                            <input type="hidden" name="contract_id" value="{{ $shipment->contract_id }}">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="enterprise-label">Ngày giao hàng <span class="text-red-600">*</span></label>
                                <input type="date" name="delivery_date" required value="{{ $shipment->delivery_date->format('Y-m-d') }}" class="enterprise-input">
                            </div>
                            <div>
                                <label class="enterprise-label">Trạng thái <span class="text-red-600">*</span></label>
                                <select name="status" required class="enterprise-input">
                                    <option value="pending" {{ $shipment->status == 'pending' ? 'selected' : '' }}>Chờ giao</option>
                                    <option value="delivered" {{ $shipment->status == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                                    <option value="received" {{ $shipment->status == 'received' ? 'selected' : '' }}>Đã nhận hàng (Chờ nhập kho)</option>
                                    <option value="inventoried" {{ $shipment->status == 'inventoried' ? 'selected' : '' }}>Đã vào kho</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="enterprise-label">Người nhận hàng / Phụ trách</label>
                            <input type="text" name="receiver_name" list="users_list" required value="{{ old('receiver_name', $shipment->receiver_name) }}"
                                   class="enterprise-input" placeholder="Nhập tên người nhận hàng...">
                            <datalist id="users_list">
                                @foreach($users as $user)
                                    <option value="{{ $user->name }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div>
                            <label class="enterprise-label">Ghi chú (Note)</label>
                            <textarea name="note" rows="3" class="enterprise-input" placeholder="Thông tin thêm về bên vận chuyển, biển số xe...">{{ $shipment->note }}</textarea>
                        </div>
                    </div>

                    <!-- Right Column: Item List (Dynamic with Alpine.js) -->
                    <div class="space-y-6" x-data="{ 
                        items: {{ json_encode($shipment->items ?? []) }},
                        addItem() {
                            this.items.push({ name: '', ordered_qty: 0, delivered_qty: 1, unit: 'Cái' });
                        },
                        removeItem(index) {
                            this.items.splice(index, 1);
                        }
                    }">
                        <div class="flex items-center justify-between border-b pb-2">
                            <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wider">Danh mục hàng hóa giao nhận</h3>
                            <button type="button" @click="addItem()" class="text-[10px] font-bold text-red-600 hover:text-red-700 uppercase tracking-widest flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Thêm hàng ngoài
                            </button>
                        </div>
                        
                        <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm space-y-3 relative group hover:border-red-200 transition-all">
                                    <button type="button" @click="removeItem(index)" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"></path></svg>
                                    </button>
                                    
                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Tên hàng hóa / Thiết bị</label>
                                            <input type="text" :name="'items['+index+'][name]'" x-model="item.name" required
                                                   class="w-full border-gray-200 rounded text-sm font-bold focus:ring-red-500 focus:border-red-500" 
                                                   placeholder="Ví dụ: Laptop Dell XPS 15">
                                        </div>
                                        
                                        <div class="grid grid-cols-3 gap-2">
                                            <div>
                                                <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Đã đặt</label>
                                                <input type="number" :name="'items['+index+'][ordered_qty]'" x-model="item.ordered_qty" required min="0"
                                                       class="w-full border-gray-200 rounded text-xs font-bold focus:ring-red-500 focus:border-red-500 text-center">
                                            </div>
                                            <div>
                                                <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">ĐVT</label>
                                                <input type="text" :name="'items['+index+'][unit]'" x-model="item.unit"
                                                       class="w-full border-gray-200 rounded text-xs font-bold focus:ring-red-500 focus:border-red-500 text-center">
                                            </div>
                                            <div>
                                                <label class="text-[10px] font-bold text-red-400 uppercase block mb-1">S.L Giao</label>
                                                <input type="number" :name="'items['+index+'][delivered_qty]'" x-model="item.delivered_qty" required min="0"
                                                       class="w-full border-red-200 rounded text-sm font-black text-red-600 focus:ring-red-500 focus:border-red-500 text-center">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <div x-show="items.length === 0" class="flex flex-col items-center justify-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-xs text-gray-400 italic">Chưa có hàng hóa nào được thêm vào lô hàng.</p>
                                <button type="button" @click="addItem()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-md text-xs font-bold uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200">
                                    Thêm hàng ngay
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-6 pt-10 border-t mt-12">
                    <a href="{{ route('shipments.show', $shipment) }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 uppercase tracking-widest">Hủy bỏ</a>
                    <button type="submit" class="btn-enterprise py-4 px-12 text-base shadow-lg shadow-red-200">
                        CẬP NHẬT LÔ HÀNG
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
