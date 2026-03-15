<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800">
            Chỉnh sửa hợp đồng: <span class="text-red-700">{{ $contract->contract_number }}</span>
        </h2>
    </x-slot>
    
    @if ($errors->any())
        <div class="max-w-5xl mx-auto mb-6">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800">Có lỗi xảy ra:</h3>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-5xl mx-auto">
        <div class="card-premium">
            <form action="{{ route('contracts.update', $contract) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b pb-8 mb-8" x-data="{ 
                    items: {{ json_encode($contract->items ?? []) }},
                    total: {{ $contract->value ?? 0 }},
                    addItem() {
                        this.items.push({ name: '', description: '', unit: 'Cái', quantity: 1, price: 0 });
                    },
                    removeItem(index) {
                        this.items.splice(index, 1);
                    },
                    init() {
                        this.$watch('items', () => {
                            this.total = this.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
                        }, { deep: true });
                    }
                }">
                    <!-- Basic Information -->
                    <div class="space-y-6">
                        <h3 class="font-bold text-gray-700 border-b pb-2 uppercase text-xs tracking-wider">Thông tin chung</h3>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="enterprise-label">Số hợp đồng <span class="text-red-600">*</span></label>
                                <input type="text" name="contract_number" required value="{{ old('contract_number', $contract->contract_number) }}"
                                       class="enterprise-input">
                            </div>
                        </div>

                        <div>
                            <label class="enterprise-label">Giá trị hợp đồng (VNĐ) <span class="text-red-600">*</span></label>
                            <input type="number" name="value" required min="0" x-model.number="total"
                                   class="enterprise-input font-bold text-red-600">
                            <p class="mt-1 text-[10px] text-gray-400 italic font-bold uppercase">* Có thể tự nhập hoặc để hệ thống tự tính theo danh mục hàng hóa bên dưới</p>
                        </div>

                        <div x-data="{ 
                            partnerName: '{{ old('partner_name', $contract->partner->name ?? '') }}',
                            partners: [],
                            async searchPartners() {
                                if (this.partnerName.length < 2) return;
                                const resp = await fetch(`/partners/lookup?q=${this.partnerName}`);
                                this.partners = await resp.json();
                            },
                            selectPartner(p) {
                                this.partnerName = p.name;
                                this.partners = [];
                            }
                        }" class="relative">
                            <label class="enterprise-label">Đối tác / Nhà cung cấp <span class="text-red-600">*</span></label>
                            <input type="text" name="partner_name" x-model="partnerName" @input.debounce.300ms="searchPartners()"
                                   required class="enterprise-input font-bold" placeholder="Nhập tên hoặc chọn từ gợi ý...">
                            
                            <div x-show="partners.length > 0" class="absolute z-50 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto">
                                <template x-for="p in partners" :key="p.id">
                                    <button type="button" @click="selectPartner(p)" class="w-full text-left px-4 py-2 hover:bg-gray-50 flex justify-between items-center transition-colors">
                                        <div>
                                            <div class="font-bold text-sm text-gray-800" x-text="p.name"></div>
                                            <div class="text-[10px] text-gray-400 uppercase" x-text="p.code"></div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-[10px] font-bold text-enterprise-red" x-text="p.contact_person || '---'"></div>
                                            <div class="text-[10px] text-gray-400" x-text="p.phone || ''"></div>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div>
                            <label class="enterprise-label">Liên kết Tờ trình mua sắm</label>
                            <select name="requisition_id" class="enterprise-input">
                                <option value="">-- Không liên kết --</option>
                                @foreach($requisitions as $requisition)
                                    <option value="{{ $requisition->id }}" {{ old('requisition_id', $contract->requisition_id) == $requisition->id ? 'selected' : '' }}>
                                        [{{ $requisition->code }}] {{ $requisition->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Goods List Management (Alpine.js) -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b pb-2">
                            <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wider">Danh mục hàng hóa / Dịch vụ</h3>
                            <button type="button" @click="addItem()" class="text-[10px] font-bold text-red-600 hover:text-red-700 uppercase tracking-widest flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Thêm mặt hàng
                            </button>
                        </div>

                        <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm space-y-3 relative group hover:border-red-200 transition-all">
                                    <button type="button" @click="removeItem(index)" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"></path></svg>
                                    </button>
                                    
                                    <div class="grid grid-cols-1 gap-2">
                                        <input type="text" :name="'items['+index+'][name]'" x-model="item.name" required
                                               class="w-full border-gray-200 rounded text-sm font-bold focus:ring-red-500 focus:border-red-500" 
                                               placeholder="Tên hàng hóa / Dịch vụ">
                                        
                                        <div class="grid grid-cols-3 gap-2">
                                            <div>
                                                <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Số lượng</label>
                                                <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" required min="1"
                                                       class="w-full border-gray-200 rounded text-xs font-bold focus:ring-red-500 focus:border-red-500">
                                            </div>
                                            <div>
                                                <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">ĐVT</label>
                                                <input type="text" :name="'items['+index+'][unit]'" x-model="item.unit"
                                                       class="w-full border-gray-200 rounded text-xs font-bold focus:ring-red-500 focus:border-red-500 text-center">
                                            </div>
                                            <div>
                                                <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Đơn giá</label>
                                                <input type="number" :name="'items['+index+'][price]'" x-model.number="item.price" required min="0"
                                                       class="w-full border-gray-200 rounded text-xs font-bold text-red-600 focus:ring-red-500 focus:border-red-500 text-right">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <div x-show="items.length === 0" class="flex flex-col items-center justify-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                                <p class="text-xs text-gray-400 italic">Chưa có hàng hóa nào. Hãy thêm để tính giá trị hợp đồng.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                    <!-- Terms & Files -->
                    <div class="space-y-6">
                        <h3 class="font-bold text-gray-700 border-b pb-2 uppercase text-xs tracking-wider">Điều khoản & Thời hạn</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="enterprise-label">Ngày ký kết</label>
                                <input type="date" name="signed_date" value="{{ old('signed_date', $contract->signed_date?->format('Y-m-d')) }}" class="enterprise-input">
                            </div>
                            <div>
                                <label class="enterprise-label">Ngày hết hạn</label>
                                <input type="date" name="expiration_date" value="{{ old('expiration_date', $contract->expiration_date?->format('Y-m-d')) }}" class="enterprise-input">
                            </div>
                        </div>
                        <div>
                            <label class="enterprise-label">Thời hạn bảo hành (Tháng)</label>
                            <input type="number" name="warranty_months" min="0" value="{{ old('warranty_months', $contract->warranty_months) }}"
                                   class="enterprise-input">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="font-bold text-gray-700 border-b pb-2 uppercase text-xs tracking-wider">Hồ sơ đính kèm</h3>
                        <div>
                            <div class="space-y-2 mb-4">
                                @forelse($contract->files ?? [] as $file)
                                    <div class="flex items-center justify-between p-2 rounded bg-gray-50 border border-gray-100">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            <span class="text-xs font-bold text-gray-700 truncate max-w-[200px]">{{ $file['name'] }}</span>
                                        </div>
                                        <span class="text-[10px] text-gray-400 uppercase font-bold text-right">Đã lưu</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-400 italic">Chưa có hồ sơ đính kèm.</p>
                                @endforelse
                            </div>

                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-red-400 transition-colors bg-gray-50">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-xs text-gray-600">
                                        <label for="files" class="relative cursor-pointer bg-white rounded-md font-bold text-red-600 hover:text-red-500">
                                            <span>Tải thêm tệp</span>
                                            <input id="files" name="files[]" type="file" class="sr-only" multiple>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-8 border-t mt-8">
                    <a href="{{ route('contracts.show', $contract) }}" class="text-sm font-bold text-gray-500 hover:text-gray-700">HỦY BỎ</a>
                    <button type="submit" class="btn-enterprise py-3 px-12">
                        CẬP NHẬT HỢP ĐỒNG
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
