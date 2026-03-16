<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lập Phiếu Kho mới') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white overflow-hidden shadow-sm sm:rounded-lg border">
        <form action="{{ route('inventory_receipts.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Liên kết Yêu cầu kho (Đã duyệt) <span class="text-red-600">*</span></label>
                <select name="request_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-enterprise-red focus:border-enterprise-red sm:text-sm">
                    <option value="">-- Chọn yêu cầu --</option>
                    @foreach($requests as $req)
                        <option value="{{ $req->id }}" {{ old('request_id') == $req->id ? 'selected' : '' }}>
                            {{ $req->code }} ({{ $req->type == 'inbound' ? 'Nhập' : 'Xuất' }}) - {{ $req->requester->name }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500 italic">Chỉ các yêu cầu đã được phê duyệt mới được lập phiếu.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Ngày thực hiện <span class="text-red-600">*</span></label>
                <input type="date" name="process_date" required value="{{ old('process_date', date('Y-m-d')) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-enterprise-red focus:border-enterprise-red sm:text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Đánh giá / Ghi chú thực tế</label>
                <textarea name="evaluation_notes" rows="4"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-enterprise-red focus:border-enterprise-red sm:text-sm"
                          placeholder="Tình trạng hàng hóa, số lượng thực tế..."></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t mt-4">
                <a href="{{ route('inventory_receipts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Hủy bỏ</a>
                <button type="submit" class="btn-enterprise-danger uppercase tracking-widest text-xs font-bold px-8">
                    Hoàn tất lập phiếu
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
