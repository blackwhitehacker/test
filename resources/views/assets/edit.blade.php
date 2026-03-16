<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end pb-2">
            <div>
                <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase leading-none">
                    Chỉnh sửa <span class="text-[#E11D48]">Tài sản</span>
                </h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-gray-400">Cập nhật thông số kỹ thuật & Lịch sử vận hành của: {{ $asset->name }}</span>
                </div>
            </div>
            <a href="{{ route('assets.index') }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 uppercase tracking-widest transition-colors mb-2">
                Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8 px-4 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <form action="{{ route('assets.update', $asset) }}" method="POST" class="space-y-10 pb-20"
              x-data="{ 
                trackingType: '{{ $asset->group && $asset->group->parent && $asset->group->parent->parent ? $asset->group->parent->parent->tracking_type : 'serialized' }}',
                updateTracking(el) {
                    const selected = el.options[el.selectedIndex];
                    this.trackingType = selected.dataset.tracking || 'serialized';
                }
              }">
            @csrf
            @method('PUT')
            
            <!-- Cấu trúc phân cấp tài sản -->
            <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
                <div class="p-10 bg-white space-y-10">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-1.5 h-6 bg-[#E11D48] rounded-full"></div>
                            <h3 class="font-bold text-sm uppercase tracking-[0.1em] text-gray-800">Cấu trúc phân cấp thực thể (Hệ thống 4 cấp)</h3>
                        </div>
                        <div class="text-[9px] font-bold text-[#E11D48] bg-red-50 px-3 py-1.5 rounded-lg uppercase tracking-widest italic border border-red-100 italic">Sync ID: {{ $asset->id }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">1. Loại tài sản <span class="text-[#E11D48]">*</span></label>
                            <select name="category_id" required class="enterprise-input py-3 text-[13px] font-bold" @change="updateTracking($el)">
                                <option value="">-- Chọn loại --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" 
                                            data-tracking="{{ $cat->tracking_type }}"
                                            {{ ($asset->group->parent->parent_id ?? $asset->group->parent_id ?? $asset->group_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">2. Nhóm tài sản <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="group_name" required list="groups_list" 
                                   value="{{ old('group_name', $asset->group->parent->name ?? '') }}"
                                   class="enterprise-input py-3 text-[13px] font-bold italic" placeholder="Bàn ghế, Máy tính...">
                            <datalist id="groups_list">
                                @foreach($existingGroups as $gName)
                                    <option value="{{ $gName }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">3. Dòng tài sản <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="line_name" required list="lines_list" 
                                   value="{{ old('line_name', $asset->group->name ?? '') }}"
                                   class="enterprise-input py-3 text-[13px] font-bold italic" placeholder="Ghế xoay, Dell Optiplex...">
                            <datalist id="lines_list">
                                @foreach($existingLines as $lName)
                                    <option value="{{ $lName }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">4. Tên sản phẩm cụ thể <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="name" required value="{{ old('name', $asset->name) }}"
                                   class="enterprise-input py-3 text-[13px] font-bold" placeholder="Nhập tên chi tiết...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin Tài chính & Khấu hao -->
            <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
                <div class="p-10 bg-white space-y-10">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                            <h3 class="font-bold text-sm uppercase tracking-[0.1em] text-gray-800">Thông tin Tài chính & Khấu hao định kỳ</h3>
                        </div>
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 01-18 0z"></path></svg>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Nguyên giá (VNĐ) <span class="text-[#E11D48]">*</span></label>
                            <input type="number" name="purchase_price" required min="0" value="{{ old('purchase_price', $asset->purchase_price) }}"
                                   class="enterprise-input py-3 text-[14px] font-bold text-gray-900" placeholder="10.000.000">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Giá trị thu hồi dự kiến <span class="text-[#E11D48]">*</span></label>
                            <input type="number" name="recovery_value" required min="0" value="{{ old('recovery_value', $asset->recovery_value) }}"
                                   class="enterprise-input py-3 text-[14px] font-bold text-gray-600" placeholder="0">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Thời gian sử dụng (Tháng) <span class="text-[#E11D48]">*</span></label>
                            <input type="number" name="usage_months" required min="1" value="{{ old('usage_months', $asset->usage_months) }}"
                                   class="enterprise-input py-3 text-[14px] font-bold text-blue-600" placeholder="12, 24, 36...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nhà cung cấp & Bảo hành -->
            <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
                <div class="p-10 bg-white space-y-10">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-1.5 h-6 bg-green-600 rounded-full"></div>
                            <h3 class="font-bold text-sm uppercase tracking-[0.1em] text-gray-800">Quản trị Nhà cung cấp & Bảo hành</h3>
                        </div>
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Nhà cung cấp / Đối tác</label>
                            <input type="text" name="partner_name" list="partners_list" value="{{ old('partner_name', $asset->partner->name ?? '') }}"
                                   class="enterprise-input py-3 text-[13px] font-bold uppercase" placeholder="Tên đơn vị cung ứng...">
                            <datalist id="partners_list">
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->name }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Ngày nhập kho thực tế</label>
                            <input type="date" name="purchase_date" value="{{ old('purchase_date', $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : date('Y-m-d')) }}"
                                   class="enterprise-input py-3 text-[13px] font-bold">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Hạn bảo hành hệ thống</label>
                            <input type="date" name="warranty_expiry" value="{{ old('warranty_expiry', $asset->warranty_expiry ? $asset->warranty_expiry->format('Y-m-d') : '') }}"
                                   class="enterprise-input py-3 text-[13px] font-bold text-green-700">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Định danh & Trạng thái -->
            <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
                <div class="p-10 bg-white space-y-10">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-1.5 h-6 bg-gray-900 rounded-full"></div>
                            <h3 class="font-bold text-sm uppercase tracking-[0.1em] text-gray-800">Thông số kỹ thuật & Định danh vận hành</h3>
                        </div>
                        <div class="text-[9px] font-bold text-gray-300 bg-gray-50 px-3 py-1.5 rounded-lg uppercase tracking-widest italic leading-none border border-gray-100 italic">Code: {{ $asset->code }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <div x-show="trackingType === 'serialized'" class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Số Serial / IMEI / Mã vạch</label>
                            <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}"
                                   class="enterprise-input py-3 text-[13px] font-bold italic" placeholder="SN123456...">
                        </div>
                        <div x-show="trackingType === 'quantity'" class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Số lượng hiện tại</label>
                            <input type="number" name="quantity" min="1" value="{{ old('quantity', $asset->quantity) }}"
                                   class="enterprise-input py-3 text-[13px] font-bold">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Model / Ký hiệu nhà máy</label>
                            <input type="text" name="model" value="{{ old('model', $asset->model) }}"
                                   class="enterprise-input py-3 text-[13px] font-bold" placeholder="Pro Gen 12, Latitide 5480...">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Vị trí lưu trữ / Khu vực</label>
                            <input type="text" name="location" value="{{ old('location', $asset->location) }}"
                                   class="enterprise-input py-3 text-[13px] font-bold" placeholder="Kho tổng, Phòng IT, Tầng 5...">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Trạng thái vận hành <span class="text-[#E11D48]">*</span></label>
                            <select name="status" required class="enterprise-input py-3 text-[13px] font-bold uppercase shadow-sm">
                                <option value="inventory" {{ old('status', $asset->status) == 'inventory' ? 'selected' : '' }}>TRONG KHO</option>
                                <option value="in_use" {{ old('status', $asset->status) == 'in_use' ? 'selected' : '' }}>ĐANG SỬ DỤNG</option>
                                <option value="repairing" {{ old('status', $asset->status) == 'repairing' ? 'selected' : '' }}>ĐANG SỬA CHỮA</option>
                                <option value="liquidating" {{ old('status', $asset->status) == 'liquidating' ? 'selected' : '' }}>CHỜ THANH LÝ</option>
                                <option value="liquidated" {{ old('status', $asset->status) == 'liquidated' ? 'selected' : '' }}>ĐÃ THANH LÝ</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-3 pt-4">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Mô tả đặc tính kỹ thuật sản phẩm</label>
                        <textarea name="specs" rows="3" class="enterprise-input py-3 text-[13px] font-bold italic" placeholder="Chip M1, RAM 16GB, SSD 512GB...">{{ old('specs', $asset->specs) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Ghi chú & Hồ sơ -->
            <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
                <div class="p-10 bg-white space-y-10">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-1.5 h-6 bg-yellow-500 rounded-full"></div>
                            <h3 class="font-bold text-sm uppercase tracking-[0.1em] text-gray-800">Ghi chú & Hồ sơ kỹ thuật hiện hữu</h3>
                        </div>
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Nhật ký bổ sung / Lưu ý quản trị</label>
                            <textarea name="notes" rows="4" class="enterprise-input py-3 text-[13px] font-bold italic" placeholder="Lịch sử bảo trì, tình trạng khi nhận, các đặc điểm nhận dạng khác...">{{ old('notes', $asset->notes) }}</textarea>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Tệp đính kèm hệ thống</label>
                            <div class="p-6 bg-gray-50/50 rounded-[2rem] border border-gray-100 shadow-inner">
                                @if($asset->attachments && count($asset->attachments) > 0)
                                    <div class="space-y-3 mb-6">
                                        @foreach($asset->attachments as $file)
                                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200 shadow-sm">
                                                <span class="text-[10px] font-bold text-gray-600 truncate max-w-[200px]">{{ basename($file) }}</span>
                                                <button type="button" class="text-[10px] font-bold text-[#E11D48] hover:text-red-700 uppercase tracking-widest">Xóa bỏ</button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="flex justify-center flex-col items-center">
                                    <svg class="h-8 w-8 text-gray-200 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest italic leading-none">Tải lên tài liệu kỹ thuật mới</p>
                                    <input name="temp_attachments[]" type="file" class="sr-only" multiple disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Actions -->
            <div class="flex items-center justify-end space-x-8 pt-10 border-t border-gray-100 mt-12">
                <a href="{{ route('assets.index') }}" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-gray-900 transition-colors">HỦY BỎ THAY ĐỔI</a>
                <button type="submit" class="btn-enterprise-danger !px-10 h-10 shadow-xl transform active:scale-95 transition-all text-[11px]">
                    <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    LƯU CẬP NHẬT HỆ THỐNG
                </button>
            </div>
        </form>
    </div>

    <script>
        function calculateDepreciation() {
            const priceInput = document.querySelector('input[name="purchase_price"]');
            const recoveryInput = document.querySelector('input[name="recovery_value"]');
            const monthsInput = document.querySelector('input[name="usage_months"]');
            
            if (!priceInput || !recoveryInput || !monthsInput) return;

            const price = parseFloat(priceInput.value) || 0;
            const recovery = parseFloat(recoveryInput.value) || 0;
            const months = parseInt(monthsInput.value) || 1;
            
            const monthly = (price - recovery) / Math.max(1, months);
            
            console.log('Current Monthly Depreciation:', monthly.toLocaleString('vi-VN') + ' VNĐ');
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input[name="purchase_price"], input[name="recovery_value"], input[name="usage_months"]').forEach(el => {
                el.addEventListener('input', calculateDepreciation);
            });
            calculateDepreciation(); // Initial call
        });
    </script>
</x-app-layout>
