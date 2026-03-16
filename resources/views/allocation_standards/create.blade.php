<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('allocation_standards.index') }}" class="p-2 text-gray-400 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 uppercase tracking-tighter">Thêm định mức mới</h2>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8">
        <div class="card-premium p-8">
            <form action="{{ route('allocation_standards.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2 tracking-wider">Đối tượng áp dụng</label>
                        <select name="target_type" class="enterprise-input" required>
                            <option value="individual">Cá nhân</option>
                            <option value="department">Phòng ban</option>
                            <option value="center">Trung tâm</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2 tracking-wider">Tên đối tượng/Phòng ban</label>
                        <input type="text" name="target_name" placeholder="Ví dụ: Trưởng phòng, Phòng Kế toán..." class="enterprise-input" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2 tracking-wider">Nhóm tài sản</label>
                        <select name="asset_group_id" class="enterprise-input" required>
                            <option value="">Chọn nhóm tài sản</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">[{{ $group->code }}] - {{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2 tracking-wider">Hạn mức (Số lượng)</label>
                        <input type="number" name="limit_quantity" value="1" min="1" class="enterprise-input" required>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2 tracking-wider">Ghi chú</label>
                    <textarea name="notes" rows="4" placeholder="Nhập ghi chú chi tiết nếu có..." class="enterprise-input"></textarea>
                </div>

                <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('allocation_standards.index') }}" class="enterprise-btn-secondary px-8">Hủy bỏ</a>
                    <button type="submit" class="enterprise-btn-primary px-12">Lưu cấu hình</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
