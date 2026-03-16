<x-app-layout>
    <x-slot name="header">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800">
                Tạo mới tờ trình mua sắm
            </h2>
            <a href="{{ route('purchase_requisitions.index') }}" class="text-gray-500 hover:text-gray-800 text-sm font-medium">
                ← Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto" x-data="{ 
        items: [{ name: '', unit: 'Cái', quantity: 1, estimate: 0 }],
        totalEstimate: 0,
        addAsset() { this.items.push({ name: '', unit: 'Cái', quantity: 1, estimate: 0 }) },
        removeAsset(index) { if(this.items.length > 1) this.items.splice(index, 1) },
        init() {
            this.$watch('items', () => {
                this.totalEstimate = this.items.reduce((sum, item) => sum + (item.quantity * item.estimate), 0);
            }, { deep: true });
        }
    }">
        <form action="{{ route('purchase_requisitions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 pb-20">
            @csrf
            
            <!-- General Information -->
            <div class="card-premium p-8">
                <h3 class="font-bold text-lg mb-6 border-b pb-4 text-gray-800">Thông tin chung</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="enterprise-label">Tiêu đề tờ trình <span class="text-red-600">*</span></label>
                        <input type="text" name="title" required value="{{ old('title') }}" class="enterprise-input" placeholder="Nhập tiêu đề đề xuất mua sắm...">
                    </div>
                    <div>
                        <label class="enterprise-label">Phòng ban đề xuất</label>
                        <input type="text" name="department" required value="{{ old('department') }}" class="enterprise-input" placeholder="Tên phòng ban...">
                    </div>
                    <div>
                        <label class="enterprise-label">Ngày cần tài sản</label>
                        <input type="date" name="needed_date" class="enterprise-input" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="md:col-span-2" x-data="{ 
                        partnerName: '{{ old('partner_name') }}',
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
                    }">
                        <label class="enterprise-label">Đối tác dự kiến (Nếu có)</label>
                        <div class="relative">
                            <input type="text" name="partner_name" x-model="partnerName" @input.debounce.300ms="searchPartners()"
                                   class="enterprise-input" placeholder="Nhập tên đối tác để tìm kiếm hoặc thêm mới...">
                            
                            <div x-show="partners.length > 0" class="absolute z-10 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1 max-h-48 overflow-y-auto">
                                <template x-for="p in partners" :key="p.id">
                                    <button type="button" @click="selectPartner(p)" class="w-full text-left px-4 py-2 hover:bg-gray-50 flex justify-between items-center transition-colors">
                                        <div>
                                            <div class="font-bold text-xs text-gray-800" x-text="p.name"></div>
                                            <div class="text-[9px] text-gray-400 uppercase font-bold" x-text="p.code"></div>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="enterprise-label">Mô tả / Giải trình nhu cầu</label>
                        <textarea name="description" rows="4" class="enterprise-input" placeholder="Chi tiết nhu cầu trang bị tài sản...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Items List -->
            <div class="card-premium p-8">
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h3 class="font-bold text-lg text-gray-800">Danh mục tài sản chi tiết</h3>
                    <button type="button" @click="addAsset()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm font-bold hover:bg-gray-700 transition">
                        + Thêm dòng
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <th class="pb-4 pr-4">Tên tài sản / Model</th>
                                <th class="pb-4 pr-4 text-center" width="100">Đơn vị</th>
                                <th class="pb-4 pr-4 text-center" width="100">SL</th>
                                <th class="pb-4 pr-4 text-right" width="150">Đơn giá dự kiến</th>
                                <th class="pb-4 text-right" width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td class="pb-4 pr-4">
                                        <input type="text" :name="'items['+index+'][name]'" x-model="item.name" required class="enterprise-input" placeholder="Tên thiết bị...">
                                    </td>
                                    <td class="pb-4 pr-4">
                                        <input type="text" :name="'items['+index+'][unit]'" x-model="item.unit" class="enterprise-input text-center">
                                    </td>
                                    <td class="pb-4 pr-4">
                                        <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" required min="1" class="enterprise-input text-center">
                                    </td>
                                    <td class="pb-4 pr-4">
                                        <input type="number" :name="'items['+index+'][estimate]'" x-model.number="item.estimate" required min="0" class="enterprise-input text-right">
                                    </td>
                                    <td class="pb-4 text-center">
                                        <button type="button" @click="removeAsset(index)" x-show="items.length > 1" class="text-red-400 hover:text-red-600 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 flex justify-end border-t pt-6">
                    <div class="text-right">
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-2">Tổng dự toán tạm tính (VNĐ):</label>
                        <input type="number" name="estimated_cost" x-model.number="totalEstimate" required min="0"
                               class="text-2xl font-bold text-red-600 border-b-2 border-dashed border-red-200 focus:border-red-500 focus:outline-none text-right bg-transparent w-64">
                        <p class="mt-2 text-[10px] text-gray-400 italic font-bold uppercase">* Tự động tính theo danh sách nhưng có thể chỉnh sửa tay</p>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="card-premium p-8">
                <h3 class="font-bold text-lg mb-6 border-b pb-4 text-gray-800">Tài liệu đính kèm</h3>
                <div class="border-2 border-dashed border-gray-200 rounded-lg p-8 text-center bg-gray-50 group hover:border-red-400 transition-colors relative cursor-pointer">
                    <input type="file" name="attachments[]" multiple class="absolute inset-0 opacity-0 cursor-pointer">
                    <svg class="w-12 h-12 mx-auto text-gray-300 group-hover:text-red-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    <p class="text-sm font-bold text-gray-600">Click hoặc kéo thả file vào đây để tải lên</p>
                    <p class="text-xs text-gray-400 mt-1">Hỗ trợ PDF, Word, Excel, Image (Max 10MB)</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4 pt-10 mt-12 border-t border-gray-100">
                <a href="{{ route('purchase_requisitions.index') }}" class="px-6 py-2.5 text-[11px] font-bold uppercase tracking-widest text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors flex items-center">Hủy bỏ</a>
                <button type="submit" class="!bg-[#E11D48] !text-white px-8 py-2.5 rounded-lg font-bold text-[11px] uppercase tracking-wider shadow-lg shadow-red-900/20 transform transition-transform hover:-translate-y-1">Gửi phê duyệt</button>
            </div>
        </form>
    </div>
</x-app-layout>
