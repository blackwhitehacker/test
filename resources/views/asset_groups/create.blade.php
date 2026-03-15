<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-lg text-gray-800 uppercase tracking-tight">
            {{ __('Thêm nhanh Danh mục & Tài sản') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="card-premium p-0 overflow-hidden">
            <form action="{{ route('asset_groups.store') }}" method="POST" class="p-8 space-y-12">
                @csrf
                
                <div class="space-y-8">
                    <div class="flex items-center space-x-3 border-b pb-4">
                        <div class="w-1.5 h-6 bg-enterprise-red"></div>
                        <h3 class="font-black text-sm uppercase tracking-widest text-gray-800">Nhập liệu theo phân cấp (4 Cấp)</h3>
                    </div>

                    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Cấp 1: Loại -->
                            <div>
                                <label class="label-premium !text-[11px]">1. Loại tài sản <span class="text-enterprise-red">*</span></label>
                                <select name="category_id" required class="input-premium py-2 text-sm">
                                    <option value="">-- Chọn loại --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }} ({{ $cat->tracking_type == 'quantity' ? 'tính theo số lượng' : 'tính theo mã thiết bị' }})
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

                            <!-- Cấp 4: Tài sản cụ thể -->
                            <div>
                                <label class="label-premium !text-[11px]">4. Tài sản cụ thể (Tùy chọn)</label>
                                <input type="text" name="asset_name" value="{{ old('asset_name') }}"
                                       class="input-premium py-2 text-sm" placeholder="Ghế xoay phòng họp...">
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2 text-[10px] text-gray-400 italic">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Nhóm tài sản, dòng tài sản và tài sản cụ thể là làm dưới dạng fill in còn cái kia thì chọn.</span>
                        </div>
                    </div>
                </div>

                <!-- Global Actions -->
                <div class="flex items-center justify-end space-x-6 pt-10 border-t border-gray-100 mt-12">
                    <a href="{{ route('asset_groups.index') }}" class="text-[11px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-800 transition-colors">Hủy và quay lại</a>
                    <button type="submit" class="btn-enterprise px-16 py-4 shadow-2xl shadow-red-900/40">
                        XÁC NHẬN LƯU HỆ THỐNG
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
