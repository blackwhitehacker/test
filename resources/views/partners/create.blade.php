<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-3xl text-gray-800 leading-tight">
                    Thêm mới <span class="text-enterprise-red">Đối tác</span>
                </h2>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Đăng ký nhà cung cấp mới vào hệ thống</p>
            </div>
            <a href="{{ route('partners.index') }}" class="text-gray-500 hover:text-gray-800 text-sm font-medium">
                ← Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('partners.store') }}" method="POST" class="space-y-6 pb-20">
            @csrf
            
            <div class="card-premium">
                <h3 class="font-bold text-lg mb-6 border-b pb-4 text-gray-800">Thông tin cơ bản</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="enterprise-label">Tên đối tác / Nhà cung cấp <span class="text-red-600">*</span></label>
                        <input type="text" name="name" required value="{{ old('name') }}" 
                               class="enterprise-input font-bold" placeholder="Nhập tên đầy đủ của công ty hoặc cá nhân...">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="enterprise-label">Mã số thuế</label>
                            <input type="text" name="tax_code" value="{{ old('tax_code') }}" 
                                   class="enterprise-input" placeholder="MST hoặc số Đăng ký kinh doanh...">
                        </div>
                        <div>
                            <label class="enterprise-label">Người liên hệ trực tiếp</label>
                            <input type="text" name="contact_person" value="{{ old('contact_person') }}" 
                                   class="enterprise-input" placeholder="Họ tên người đại diện/liên hệ...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-premium">
                <h3 class="font-bold text-lg mb-6 border-b pb-4 text-gray-800">Thông tin liên lạc</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="enterprise-label">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" 
                               class="enterprise-input" placeholder="Số điện thoại di động hoặc cố định...">
                    </div>
                    <div>
                        <label class="enterprise-label">Địa chỉ Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="enterprise-input" placeholder="Email liên hệ chính thức...">
                    </div>
                    <div class="md:col-span-2">
                        <label class="enterprise-label">Địa chỉ trụ sở</label>
                        <textarea name="address" rows="3" class="enterprise-input" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố...">{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('partners.index') }}" class="px-6 py-2 text-sm font-bold text-gray-600 hover:text-gray-900 flex items-center">Hủy bỏ</a>
                <button type="submit" class="btn-enterprise !px-12 !py-3">LƯU THÔNG TIN</button>
            </div>
        </form>
    </div>
</x-app-layout>
