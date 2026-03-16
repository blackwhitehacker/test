<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 tracking-tighter uppercase">
            Cấu hình hồ sơ công ty
        </h2>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 font-bold shadow-sm rounded-r-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-4xl">
        <div class="card-enterprise overflow-hidden border-t-0">
            <div class="px-8 py-4 bg-white flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48] shadow-[0_0_8px_rgba(225,29,72,0.8)]"></div>
                    <h3 class="font-bold text-[9px] uppercase tracking-[0.2em] text-gray-900 leading-none">Thông tin Bên mua (Chủ đầu tư)</h3>
                </div>
            </div>

            <div class="p-8">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-8 pb-4 border-b border-gray-100">
                    Thông tin này sẽ tự động hiển thị trên tất cả các hợp đồng và văn bản chính thức của hệ thống.
                </p>

                <form action="{{ route('settings.update') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 block">Tên đầy đủ của đơn vị (Bên A)</label>
                            <input type="text" name="company_name" 
                                   value="{{ $settings['company_name'] ?? '' }}" 
                                   class="enterprise-input py-3 text-xs" 
                                   placeholder="VD: CÔNG TY QUẢN LÝ TÀI SẢN ENTERPRISE">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 block">Địa chỉ trụ sở chính</label>
                            <input type="text" name="company_address" 
                                   value="{{ $settings['company_address'] ?? '' }}" 
                                   class="enterprise-input py-3 text-xs" 
                                   placeholder="Số nhà, Tên đường, Quận/Huyện, Tỉnh/Thành phố">
                        </div>

                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 block">Mã số thuế</label>
                            <input type="text" name="company_tax_code" 
                                   value="{{ $settings['company_tax_code'] ?? '' }}" 
                                   class="enterprise-input py-3 text-xs" 
                                   placeholder="10 chữ số">
                        </div>

                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 block">Số điện thoại liên hệ</label>
                            <input type="text" name="company_phone" 
                                   value="{{ $settings['company_phone'] ?? '' }}" 
                                   class="enterprise-input py-3 text-xs" 
                                   placeholder="Số hotline hoặc lễ tân">
                        </div>

                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 block">Người đại diện pháp luật</label>
                            <input type="text" name="company_representative" 
                                   value="{{ $settings['company_representative'] ?? '' }}" 
                                   class="enterprise-input py-3 text-xs" 
                                   placeholder="Họ và tên">
                        </div>

                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 block">Chức vụ</label>
                            <input type="text" name="company_role" 
                                   value="{{ $settings['company_role'] ?? '' }}" 
                                   class="enterprise-input py-3 text-xs" 
                                   placeholder="VD: Giám đốc, Tổng giám đốc">
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-8 mt-8">
                        <button type="submit" class="btn-enterprise-danger py-3 px-10 shadow-lg shadow-red-100 uppercase tracking-widest text-[11px] font-bold">
                            LƯU CẤU HÌNH HỒ SƠ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
