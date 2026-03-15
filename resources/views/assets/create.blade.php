<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-lg text-gray-800 uppercase tracking-tight">
            {{ __('Thêm tài sản mới') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="card-premium p-0 overflow-hidden">
            <form action="{{ route('assets.store') }}" method="POST" class="p-8 space-y-12" 
                  x-data="{ 
                    trackingType: 'serialized',
                    updateTracking(el) {
                        const selected = el.options[el.selectedIndex];
                        this.trackingType = selected.dataset.tracking || 'serialized';
                    }
                  }">
                @csrf
                
                <div class="space-y-8">
                    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-6">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-enterprise-red mb-4">Cấu trúc phân cấp tài sản (Hệ thống 4 cấp)</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Cấp 1: Loại -->
                            <div>
                                <label class="label-premium !text-[11px]">1. Loại tài sản <span class="text-enterprise-red">*</span></label>
                                <select name="category_id" required class="input-premium py-2 text-sm" @change="updateTracking($el)">
                                    <option value="">-- Chọn loại --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" 
                                                data-tracking="{{ $cat->tracking_type }}"
                                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Cấp 2: Nhóm -->
                            <div>
                                <label class="label-premium !text-[11px]">2. Nhóm tài sản <span class="text-enterprise-red">*</span></label>
                                <input type="text" name="group_name" required list="groups_list" value="{{ old('group_name') }}"
                                       class="input-premium py-2 text-sm" placeholder="Bàn ghế, Máy tính...">
                                <datalist id="groups_list">
                                    @foreach($existingGroups as $gName)
                                        <option value="{{ $gName }}">
                                    @endforeach
                                </datalist>
                            </div>

                            <!-- Cấp 3: Dòng -->
                            <div>
                                <label class="label-premium !text-[11px]">3. Dòng tài sản <span class="text-enterprise-red">*</span></label>
                                <input type="text" name="line_name" required list="lines_list" value="{{ old('line_name') }}"
                                       class="input-premium py-2 text-sm" placeholder="Ghế xoay, Dell Optiplex...">
                                <datalist id="lines_list">
                                    @foreach($existingLines as $lName)
                                        <option value="{{ $lName }}">
                                    @endforeach
                                </datalist>
                            </div>

                            <!-- Cấp 4: Tên cụ thể -->
                            <div>
                                <label class="label-premium !text-[11px]">4. Tên tài sản cụ thể <span class="text-enterprise-red">*</span></label>
                                <input type="text" name="name" required value="{{ old('name') }}"
                                       class="input-premium py-2 text-sm" placeholder="Tên thiết bị...">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Thông tin Tài chính & Khấu hao -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 space-y-6 shadow-sm">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 mb-4">Thông tin Tài chính & Khấu hao</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="label-premium !text-[11px]">Nguyên giá (VNĐ) <span class="text-enterprise-red">*</span></label>
                                <input type="number" name="purchase_price" required min="0" value="{{ old('purchase_price', 0) }}"
                                       class="input-premium py-2 text-sm" placeholder="10.000.000">
                            </div>
                            <div>
                                <label class="label-premium !text-[11px]">Giá trị thu hồi <span class="text-enterprise-red">*</span></label>
                                <input type="number" name="recovery_value" required min="0" value="{{ old('recovery_value', 0) }}"
                                       class="input-premium py-2 text-sm" placeholder="0">
                            </div>
                            <div>
                                <label class="label-premium !text-[11px]">Thời gian sử dụng (Tháng) <span class="text-enterprise-red">*</span></label>
                                <input type="number" name="usage_months" required min="1" value="{{ old('usage_months', 12) }}"
                                       class="input-premium py-2 text-sm" placeholder="12, 24, 36...">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Bảo hành & Nhà cung cấp -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 space-y-6 shadow-sm">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-green-600 mb-4">Nhà cung cấp & Bảo hành</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="label-premium !text-[11px]">Nhà cung cấp</label>
                                <input type="text" name="partner_name" list="partners_list" value="{{ old('partner_name') }}"
                                       class="input-premium py-2 text-sm" placeholder="Công ty A, Nhà cung cấp B...">
                                <datalist id="partners_list">
                                    @foreach($partners as $partner)
                                        <option value="{{ $partner->name }}">
                                    @endforeach
                                </datalist>
                            </div>
                            <div>
                                <label class="label-premium !text-[11px]">Ngày mua / Nhập kho</label>
                                <input type="date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}"
                                       class="input-premium py-2 text-sm">
                            </div>
                            <div>
                                <label class="label-premium !text-[11px]">Hạn bảo hành</label>
                                <input type="date" name="warranty_expiry" value="{{ old('warranty_expiry') }}"
                                       class="input-premium py-2 text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Thông số & Nhận diện -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 space-y-6 shadow-sm">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-4">Thông số kỹ thuật & Định danh</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div x-show="trackingType === 'serialized'">
                                <label class="label-premium !text-[11px]">Số Serial / IMEI</label>
                                <input type="text" name="serial_number" value="{{ old('serial_number') }}"
                                       class="input-premium py-2 text-sm" placeholder="SN123456...">
                            </div>
                            <div x-show="trackingType === 'quantity'">
                                <label class="label-premium !text-[11px]">Số lượng nhập</label>
                                <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}"
                                       class="input-premium py-2 text-sm">
                            </div>
                            <div>
                                <label class="label-premium !text-[11px]">Model / Ký hiệu</label>
                                <input type="text" name="model" value="{{ old('model') }}"
                                       class="input-premium py-2 text-sm" placeholder="MacBook Pro 2021...">
                            </div>
                            <div>
                                <label class="label-premium !text-[11px]">Vị trí</label>
                                <input type="text" name="location" value="{{ old('location') }}"
                                       class="input-premium py-2 text-sm" placeholder="Kho A, Tầng 2...">
                            </div>
                            <div>
                                <label class="label-premium !text-[11px]">Trạng thái tài sản <span class="text-enterprise-red">*</span></label>
                                <select name="status" required class="input-premium py-2 text-sm">
                                    <option value="inventory" {{ old('status') == 'inventory' ? 'selected' : '' }}>Trong kho</option>
                                    <option value="in_use" {{ old('status') == 'in_use' ? 'selected' : '' }}>Đang sử dụng</option>
                                    <option value="repairing" {{ old('status') == 'repairing' ? 'selected' : '' }}>Đang sửa chữa</option>
                                    <option value="liquidating" {{ old('status') == 'liquidating' ? 'selected' : '' }}>Chờ thanh lý</option>
                                </select>
                            </div>
                        </div>
                </div>

                <!-- Global Actions -->
                <div class="flex items-center justify-end space-x-6 pt-10 border-t border-gray-100 mt-12">
                    <a href="{{ route('assets.index') }}" class="text-[11px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-800 transition-colors">Hủy và quay lại</a>
                    <button type="submit" class="btn-enterprise px-16 py-4 shadow-2xl shadow-red-900/40 transform transition-transform hover:-translate-y-1">
                        XÁC NHẬN LƯU TÀI SẢN
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
