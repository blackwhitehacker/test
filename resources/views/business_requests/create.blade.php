<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800">
                Tạo yêu cầu mới 
                @if($type == 'allocation') — Cấp phát
                @elseif($type == 'repair') — Sửa chữa
                @elseif($type == 'recall') — Thu hồi
                @elseif($type == 'liquidation') — Thanh lý
                @endif
            </h2>
            <a href="{{ route('business_requests.index', ['type' => $type]) }}" class="text-gray-500 hover:text-gray-800 text-sm font-medium">
                ← Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto" x-data="{ 
        type: '{{ $type }}',
        items: [{ asset_id: '', quantity: 1 }],
        addItem() { this.items.push({ asset_id: '', quantity: 1 }) },
        removeItem(index) { if(this.items.length > 1) this.items.splice(index, 1) }
    }">
        <form action="{{ route('business_requests.store') }}" method="POST" class="space-y-6 pb-20">
            @csrf
            <input type="hidden" name="source_type" :value="type">
            
            <div class="card-premium">
                <h3 class="font-bold text-lg mb-6 border-b pb-4 text-gray-800 tracking-tight">Thông tin yêu cầu</h3>
                <div class="space-y-6">
                    <div>
                        <label class="enterprise-label">Loại yêu cầu</label>
                        <div class="grid grid-cols-4 gap-2 p-1 bg-gray-50 rounded-xl">
                            <button type="button" @click="type = 'allocation'" 
                                :class="type === 'allocation' ? 'bg-white text-black shadow-sm' : 'text-gray-400'"
                                class="py-2.5 rounded-lg text-sm font-bold transition-all">Cấp phát</button>
                            <button type="button" @click="type = 'repair'" 
                                :class="type === 'repair' ? 'bg-white text-black shadow-sm' : 'text-gray-400'"
                                class="py-2.5 rounded-lg text-sm font-bold transition-all">Sửa chữa</button>
                            <button type="button" @click="type = 'recall'" 
                                :class="type === 'recall' ? 'bg-white text-black shadow-sm' : 'text-gray-400'"
                                class="py-2.5 rounded-lg text-sm font-bold transition-all">Thu hồi</button>
                            <button type="button" @click="type = 'liquidation'" 
                                :class="type === 'liquidation' ? 'bg-white text-black shadow-sm' : 'text-gray-400'"
                                class="py-2.5 rounded-lg text-sm font-bold transition-all">Thanh lý</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="enterprise-label">Đối tượng thụ hưởng</label>
                            <select name="target_type" required class="enterprise-input">
                                <option value="individual">Cá nhân</option>
                                <option value="department">Phòng ban</option>
                                <option value="center">Trung tâm</option>
                            </select>
                        </div>
                        <div>
                            <label class="enterprise-label">Tên đối tượng / Mã NV</label>
                            <input type="text" name="target_name" required class="enterprise-input" placeholder="Nhập tên hoặc mã nhân viên...">
                        </div>
                    </div>

                    <div>
                        <label class="enterprise-label">Ghi chú / Lý do đề xuất</label>
                        <textarea name="notes" rows="4" class="enterprise-input" placeholder="Nhập chi tiết lý do yêu cầu..."></textarea>
                    </div>
                </div>
            </div>

            <div class="card-premium">
                <div class="flex justify-between items-center mb-6 border-b pb-4 text-gray-800">
                    <h3 class="font-bold text-lg tracking-tight">Danh sách tài sản</h3>
                    <button type="button" @click="addItem()" class="text-enterprise-red text-sm font-bold hover:underline">+ Thêm dòng</button>
                </div>

                <div class="space-y-4">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-4 items-end bg-gray-50/50 p-4 rounded-xl border border-gray-100 group transition-all hover:bg-white hover:shadow-md">
                            <div class="flex-1">
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-1 block">Chọn tài sản</label>
                                <select :name="'items['+index+'][asset_id]'" x-model="item.asset_id" required class="enterprise-input">
                                    <option value="">-- Chọn tài sản --</option>
                                    @foreach($assets as $asset)
                                        <option value="{{ $asset->id }}">
                                            {{ $asset->code }} - {{ $asset->name }} 
                                            @if($type == 'recall') (Người dùng: {{ $asset->user->name ?? 'N/A' }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-24">
                                <label class="text-[10px] font-black uppercase text-gray-400 mb-1 block">Số lượng</label>
                                <input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity" min="1" required class="enterprise-input text-center">
                            </div>
                            <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="p-2.5 text-gray-300 hover:text-red-500 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('business_requests.index') }}" class="px-8 py-3 text-sm font-bold text-gray-500 hover:text-black transition-all">Hủy bỏ</a>
                <button type="submit" class="bg-black text-white px-12 py-3 rounded-xl text-sm font-bold hover:bg-gray-800 transition-all active:scale-95 shadow-xl shadow-gray-200">
                    Gửi yêu cầu
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
