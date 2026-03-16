<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end pb-2">
            <div>
                <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase leading-none">
                    Khởi tạo <span class="text-[#E11D48]">Tài sản mới</span>
                </h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-gray-400">Đăng ký thực thể tài sản mới vào hệ thống quản trị rủi ro</span>
                </div>
            </div>
            <a href="{{ route('assets.index') }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 uppercase tracking-widest transition-colors mb-2">
                Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8 px-4 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <form action="{{ route('assets.store') }}" method="POST" class="space-y-10 pb-20"
              x-data="{ 
                trackingType: 'serialized',
                updateTracking(el) {
                    const selected = el.options[el.selectedIndex];
                    this.trackingType = selected.dataset.tracking || 'serialized';
                }
              }">
            @csrf
            
            <!-- Cấu trúc phân cấp tài sản -->
            <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
                <div class="p-10 bg-white space-y-10">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-1.5 h-6 bg-[#E11D48] rounded-full"></div>
                            <h3 class="font-bold text-sm uppercase tracking-[0.1em] text-gray-800">Cấu trúc phân cấp thực thể (Hệ thống 4 cấp)</h3>
                        </div>
                        <div class="text-[9px] font-bold text-[#E11D48] bg-red-50 px-3 py-1.5 rounded-lg uppercase tracking-widest italic border border-red-100">Hierarchy v4.0</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">1. Loại tài sản <span class="text-[#E11D48]">*</span></label>
                            <select name="category_id" required class="enterprise-input py-3 text-[13px] font-bold" @change="updateTracking($el)">
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

                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">2. Nhóm tài sản <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="group_name" required list="groups_list" value="{{ old('group_name') }}"
                                   class="enterprise-input py-3 text-[13px] font-bold italic" placeholder="Bàn ghế, Máy tính...">
                            <datalist id="groups_list">
                                @foreach($existingGroups as $gName)
                                    <option value="{{ $gName }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">3. Dòng tài sản <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="line_name" required list="lines_list" value="{{ old('line_name') }}"
                                   class="enterprise-input py-3 text-[13px] font-bold italic" placeholder="Ghế xoay, Dell Optiplex...">
                            <datalist id="lines_list">
                                @foreach($existingLines as $lName)
                                    <option value="{{ $lName }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">4. Tên sản phẩm cụ thể <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="name" required value="{{ old('name') }}"
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
                            <input type="number" name="purchase_price" required min="0" value="{{ old('purchase_price', 0) }}"
                                   class="enterprise-input py-3 text-[14px] font-bold text-gray-900" placeholder="10.000.000">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Giá trị thu hồi dự kiến <span class="text-[#E11D48]">*</span></label>
                            <input type="number" name="recovery_value" required min="0" value="{{ old('recovery_value', 0) }}"
                                   class="enterprise-input py-3 text-[14px] font-bold text-gray-600" placeholder="0">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Thời gian sử dụng (Tháng) <span class="text-[#E11D48]">*</span></label>
                            <input type="number" name="usage_months" required min="1" value="{{ old('usage_months', 12) }}"
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
                            <input type="text" name="partner_name" list="partners_list" value="{{ old('partner_name') }}"
                                   class="enterprise-input py-3 text-[13px] font-bold uppercase" placeholder="Tên đơn vị cung ứng...">
                            <datalist id="partners_list">
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->name }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Ngày nhập kho thực tế</label>
                            <input type="date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}"
                                   class="enterprise-input py-3 text-[13px] font-bold">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Hạn bảo hành hệ thống</label>
                            <input type="date" name="warranty_expiry" value="{{ old('warranty_expiry') }}"
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
                        <div class="text-[9px] font-bold text-gray-400 bg-gray-100 px-3 py-1.5 rounded-lg uppercase tracking-widest italic leading-none">Operational Data</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <div x-show="trackingType === 'serialized'" class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Số Serial / IMEI / Mã vạch</label>
                            <input type="text" name="serial_number" value="{{ old('serial_number') }}"
                                   class="enterprise-input py-3 text-[13px] font-bold italic" placeholder="SN123456...">
                        </div>
                        <div x-show="trackingType === 'quantity'" class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Số lượng nhập kho</label>
                            <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}"
                                   class="enterprise-input py-3 text-[13px] font-bold">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Model / Ký hiệu nhà máy</label>
                            <input type="text" name="model" value="{{ old('model') }}"
                                   class="enterprise-input py-3 text-[13px] font-bold" placeholder="Pro Gen 12, Latitide 5480...">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Vị trí lưu trữ hiện tại</label>
                            <input type="text" name="location" value="{{ old('location') }}"
                                   class="enterprise-input py-3 text-[13px] font-bold" placeholder="Kho tổng, Phòng IT, Tầng 5...">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Trạng thái khởi tạo <span class="text-[#E11D48]">*</span></label>
                            <select name="status" required class="enterprise-input py-3 text-[13px] font-bold uppercase">
                                <option value="inventory" {{ old('status') == 'inventory' ? 'selected' : '' }}>TRONG KHO</option>
                                <option value="in_use" {{ old('status') == 'in_use' ? 'selected' : '' }}>ĐANG SỬ DỤNG</option>
                                <option value="repairing" {{ old('status') == 'repairing' ? 'selected' : '' }}>ĐANG SỬA CHỮA</option>
                                <option value="liquidating" {{ old('status') == 'liquidating' ? 'selected' : '' }}>CHỜ THANH LÝ</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-3 pt-4">
                        <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Chi tiết kỹ thuật / Cấu hình thiết bị</label>
                        <textarea name="specs" rows="3" class="enterprise-input py-3 text-[13px] font-bold italic" placeholder="CPU i7, RAM 16GB, SSD 512GB...">{{ old('specs') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Ghi chú & Hồ sơ -->
            <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
                <div class="p-10 bg-white space-y-10">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-1.5 h-6 bg-yellow-500 rounded-full"></div>
                            <h3 class="font-bold text-sm uppercase tracking-[0.1em] text-gray-800">Ghi chú nghiệp vụ & Hồ sơ đính kèm</h3>
                        </div>
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Ghi chú hướng dẫn / Tình trạng đặc biệt</label>
                            <textarea name="notes" rows="4" class="enterprise-input py-3 text-[13px] font-bold italic" placeholder="Lưu ý về nguồn gốc, bàn giao, hoặc các đặc điểm nhận dạng khác...">{{ old('notes') }}</textarea>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Hồ sơ thực thể (Ảnh/PDF)</label>
                            <div class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-gray-100 border-dashed rounded-[2rem] hover:border-[#E11D48] transition-all group bg-gray-50/50 shadow-inner">
                                <div class="space-y-2 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-200 group-hover:text-[#E11D48] transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                        <label class="relative cursor-pointer rounded-md font-black text-[#E11D48] hover:underline focus-within:outline-none">
                                            <span>TẢI TỆP LÊN</span>
                                            <input name="temp_attachments[]" type="file" class="sr-only" multiple disabled>
                                        </label>
                                        <p class="pl-1">HOẶC KÉO THẢ VÀO ĐÂY</p>
                                    </div>
                                    <p class="text-[8px] text-gray-300 italic font-bold tracking-tighter uppercase">Chức năng upload khả dụng sau khi cấu hình Storage Server</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Actions -->
            <div class="flex items-center justify-end space-x-8 pt-10 border-t border-gray-100 mt-12">
                <a href="{{ route('assets.index') }}" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-gray-900 transition-colors">HỦY BỎ CHIẾN DỊCH</a>
                <button type="submit" class="btn-enterprise-danger !px-10 h-10 shadow-xl transform active:scale-95 transition-all text-[11px]">
                    <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    XÁC NHẬN LƯU TÀI SẢN
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
            
            console.log('Depreciation Calculator:', monthly.toLocaleString('vi-VN') + ' VNĐ/tháng');
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input[name="purchase_price"], input[name="recovery_value"], input[name="usage_months"]').forEach(el => {
                el.addEventListener('input', calculateDepreciation);
            });
        });
    </script>
</x-app-layout>
