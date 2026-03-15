<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800">
            Cấu hình hồ sơ công ty
        </h2>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 font-bold shadow-sm rounded-r-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-4xl">
        <div class="card-premium">
            <div class="border-b pb-4 mb-8">
                <h3 class="font-bold text-lg text-gray-800 uppercase tracking-widest">Thông tin Bên mua (Chủ đầu tư)</h3>
                <p class="text-sm text-gray-500 mt-1">Thông tin này sẽ tự động hiển thị trên tất cả các hợp đồng và văn bản chính thức của hệ thống.</p>
            </div>

            <form action="{{ route('settings.update') }}" method="POST" class="space-y-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label class="label-premium">Tên đầy đủ của đơn vị (Bên A)</label>
                        <input type="text" name="company_name" 
                               value="{{ $settings['company_name'] ?? '' }}" 
                               class="input-premium" 
                               placeholder="VD: CÔNG TY QUẢN LÝ TÀI SẢN ENTERPRISE">
                    </div>

                    <div class="md:col-span-2">
                        <label class="label-premium">Địa chỉ trụ sở chính</label>
                        <input type="text" name="company_address" 
                               value="{{ $settings['company_address'] ?? '' }}" 
                               class="input-premium" 
                               placeholder="Số nhà, Tên đường, Quận/Huyện, Tỉnh/Thành phố">
                    </div>

                    <div>
                        <label class="label-premium">Mã số thuế</label>
                        <input type="text" name="company_tax_code" 
                               value="{{ $settings['company_tax_code'] ?? '' }}" 
                               class="input-premium" 
                               placeholder="10 chữ số">
                    </div>

                    <div>
                        <label class="label-premium">Số điện thoại liên hệ</label>
                        <input type="text" name="company_phone" 
                               value="{{ $settings['company_phone'] ?? '' }}" 
                               class="input-premium" 
                               placeholder="Số hotline hoặc lễ tân">
                    </div>

                    <div>
                        <label class="label-premium">Người đại diện pháp luật</label>
                        <input type="text" name="company_representative" 
                               value="{{ $settings['company_representative'] ?? '' }}" 
                               class="input-premium" 
                               placeholder="Họ và tên">
                    </div>

                    <div>
                        <label class="label-premium">Chức vụ</label>
                        <input type="text" name="company_role" 
                               value="{{ $settings['company_role'] ?? '' }}" 
                               class="input-premium" 
                               placeholder="VD: Giám đốc, Tổng giám đốc">
                    </div>
                </div>

                <div class="border-t pt-8">
                    <button type="submit" class="btn-enterprise py-3 px-8 shadow-lg shadow-red-100">
                        LƯU CẤU HÌNH HỒ SƠ
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
