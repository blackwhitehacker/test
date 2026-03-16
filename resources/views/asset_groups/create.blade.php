<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end pb-2">
            <div>
                <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase leading-none">
                    {{ __('Thêm nhanh Danh mục & Tài sản') }}
                </h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-gray-400">Kiến tạo cấu trúc phân loại tài sản 4 cấp độ</span>
                </div>
            </div>
            <a href="{{ route('asset_groups.index') }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 uppercase tracking-widest transition-colors mb-2">
                Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8 px-4 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
            <form action="{{ route('asset_groups.store') }}" method="POST" class="p-10 space-y-12 bg-white">
                @csrf
                
                <div class="space-y-10">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-1.5 h-6 bg-[#E11D48] rounded-full"></div>
                            <h3 class="font-bold text-sm uppercase tracking-[0.1em] text-gray-800">Nhập liệu phân cấp nghiệp vụ tập trung</h3>
                        </div>
                        <div class="text-[9px] font-bold text-gray-300 uppercase tracking-widest italic leading-none">Matrix Configuration v1.0</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <!-- Cấp 1: Loại -->
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">1. Loại tài sản <span class="text-[#E11D48]">*</span></label>
                            <select name="category_id" required class="enterprise-input py-3 text-[13px] font-bold">
                                <option value="">-- CHỌN LOẠI --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ strtoupper($cat->name) }} ({{ $cat->tracking_type == 'quantity' ? 'QUẢN LÝ SỐ LƯỢNG' : 'QUẢN LÝ MÃ THIẾT BỊ' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Cấp 2: Nhóm -->
                        <div class="space-y-3 border-l border-gray-50 md:pl-8">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">2. Nhóm tài sản <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="group_name" required list="groups_list" value="{{ old('group_name') }}"
                                   class="enterprise-input py-3 text-[13px] font-bold italic" placeholder="Bàn ghế, Máy tính...">
                            <datalist id="groups_list">
                                @foreach($existingGroups as $gName)
                                    <option value="{{ $gName }}">
                                @endforeach
                            </datalist>
                        </div>

                        <!-- Cấp 3: Dòng -->
                        <div class="space-y-3 border-l border-gray-50 md:pl-8">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">3. Dòng tài sản <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="line_name" required list="lines_list" value="{{ old('line_name') }}"
                                   class="enterprise-input py-3 text-[13px] font-bold italic" placeholder="Ghế xoay, Dell Optiplex...">
                            <datalist id="lines_list">
                                @foreach($existingLines as $lName)
                                    <option value="{{ $lName }}">
                                @endforeach
                            </datalist>
                        </div>

                        <!-- Cấp 4: Tài sản cụ thể -->
                        <div class="space-y-3 border-l border-gray-50 md:pl-8">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">4. Tài sản cụ thể (Tùy chọn)</label>
                            <input type="text" name="asset_name" value="{{ old('asset_name') }}"
                                   class="enterprise-input py-3 text-[13px] font-bold italic" placeholder="Ghế xoay phòng họp...">
                        </div>
                    </div>
                    
                    <div class="bg-red-50/50 p-4 rounded-xl border border-red-100 flex items-start space-x-3">
                        <svg class="w-4 h-4 text-[#E11D48] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide leading-relaxed italic">
                            Loại tài sản được chọn từ danh mục cơ sở. Nhóm, Dòng và Tài sản cụ thể hỗ trợ gợi ý (autocomplete) từ dữ liệu hiện hữu để đảm bảo tính nhất quán của hệ thống.
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-8 pt-10 border-t border-gray-100 mt-12">
                    <a href="{{ route('asset_groups.index') }}" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-gray-900 transition-colors">HỦY BỎ CHIẾN DỊCH</a>
                    <button type="submit" class="btn-enterprise-danger !px-8 h-10 shadow-xl transform active:scale-95 transition-all text-[11px]">
                        <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        XÁC NHẬN LƯU HỆ THỐNG
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
