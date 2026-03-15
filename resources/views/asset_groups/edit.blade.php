<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-lg text-gray-800 uppercase tracking-tight">
            {{ __('Chỉnh sửa thông tin Phân cấp') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="card-premium p-0 overflow-hidden">
            <form action="{{ route('asset_groups.update', $assetGroup) }}" method="POST" class="p-8 space-y-8">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div class="flex items-center space-x-3 border-b pb-4">
                        <div class="w-1.5 h-6 bg-enterprise-red"></div>
                        <h3 class="font-black text-sm uppercase tracking-widest text-gray-800">Cập nhật nội dung danh mục</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="name" class="label-premium !text-xs">Tên danh mục <span class="text-enterprise-red">*</span></label>
                            <input type="text" name="name" id="name" required value="{{ old('name', $assetGroup->name) }}"
                                   class="input-premium text-sm py-2" placeholder="VD: Bàn ghế, Máy tính, Ghế xoay...">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="label-premium !text-xs">Vị trí hiện tại trong hệ thống</label>
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center space-x-2 text-xs font-black uppercase tracking-widest text-gray-400">
                                @if($assetGroup->parent && $assetGroup->parent->parent)
                                    <span>{{ $assetGroup->parent->parent->name }}</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    <span>{{ $assetGroup->parent->name }}</span>
                                    <svg class="w-3 h-3 text-enterprise-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    <span class="text-enterprise-red">{{ $assetGroup->name }}</span>
                                @elseif($assetGroup->parent)
                                    <span>{{ $assetGroup->parent->name }}</span>
                                    <svg class="w-3 h-3 text-enterprise-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    <span class="text-enterprise-red">{{ $assetGroup->name }}</span>
                                @else
                                    <span class="text-enterprise-red">{{ $assetGroup->name }} (Cấp gốc)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end space-x-4 pt-8 border-t mt-8">
                    <a href="{{ route('asset_groups.index') }}" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors">Hủy bỏ</a>
                    <button type="submit" class="btn-enterprise px-12 py-3 shadow-lg shadow-red-900/20">
                        LƯU THAY ĐỔI
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
