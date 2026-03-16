<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end pb-2">
            <div>
                <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase leading-none">
                    {{ __('Chỉnh sửa thông tin Phân cấp') }}
                </h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-gray-400">Cập nhật và điều chỉnh cấu trúc thực thể tài sản</span>
                </div>
            </div>
            <a href="{{ route('asset_groups.index') }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 uppercase tracking-widest transition-colors mb-2">
                Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
            <form action="{{ route('asset_groups.update', $assetGroup) }}" method="POST" class="p-10 space-y-10 bg-white">
                @csrf
                @method('PUT')
                
                <div class="space-y-8">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-1.5 h-6 bg-[#E11D48] rounded-full"></div>
                            <h3 class="font-bold text-sm uppercase tracking-[0.1em] text-gray-800">Cập nhật nội dung danh mục</h3>
                        </div>
                        <div class="text-[9px] font-bold text-[#E11D48] bg-red-50 px-3 py-1.5 rounded-lg uppercase tracking-widest italic">
                            ID: #{{ str_pad($assetGroup->id, 5, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-10">
                        <div class="space-y-3">
                            <label for="name" class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Tên danh mục <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="name" id="name" required value="{{ old('name', $assetGroup->name) }}"
                                   class="enterprise-input py-3 text-[14px] font-bold italic" placeholder="VD: Bàn ghế, Máy tính, Ghế xoay...">
                            @error('name')<p class="text-red-500 text-[10px] font-bold uppercase mt-2 tracking-tight">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Vị trí hiện tại trong hệ thống</label>
                            <div class="p-6 bg-gray-50/80 rounded-2xl border border-gray-100 flex items-center space-x-4 text-[11px] font-bold uppercase tracking-[0.15em] text-gray-400">
                                @if($assetGroup->parent && $assetGroup->parent->parent)
                                    <span class="hover:text-gray-600 transition-colors">{{ $assetGroup->parent->parent->name }}</span>
                                    <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                    <span class="hover:text-gray-600 transition-colors">{{ $assetGroup->parent->name }}</span>
                                    <svg class="w-3.5 h-3.5 text-[#E11D48]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                    <span class="text-[#E11D48] bg-white px-3 py-1.5 rounded-lg shadow-sm border border-red-50">{{ $assetGroup->name }}</span>
                                @elseif($assetGroup->parent)
                                    <span class="hover:text-gray-600 transition-colors">{{ $assetGroup->parent->name }}</span>
                                    <svg class="w-3.5 h-3.5 text-[#E11D48]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                    <span class="text-[#E11D48] bg-white px-3 py-1.5 rounded-lg shadow-sm border border-red-50">{{ $assetGroup->name }}</span>
                                @else
                                    <span class="text-[#E11D48] bg-white px-3 py-1.5 rounded-lg shadow-sm border border-red-50 font-black italic">{{ $assetGroup->name }} (CẤP GỐC - LOẠI TÀI SẢN)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-8 pt-10 border-t border-gray-100 mt-12">
                    <a href="{{ route('asset_groups.index') }}" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-gray-900 transition-colors">HỦY BỎ THAY ĐỔI</a>
                    <button type="submit" class="btn-enterprise-danger !px-8 h-10 shadow-xl transform active:scale-95 transition-all text-[11px]">
                        <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        LƯU CẬP NHẬT HỆ THỐNG
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
