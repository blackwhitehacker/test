<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end pb-2">
            <div>
                <h2 class="font-bold text-xl text-gray-900 tracking-tight uppercase leading-none">
                    Chỉnh sửa <span class="text-[#E11D48]">Đối tác</span>
                </h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-gray-400">Cập nhật thông tin hệ thống của: {{ $partner->name }}</span>
                </div>
            </div>
            <a href="{{ route('partners.index') }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 uppercase tracking-widest transition-colors mb-2">
                Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <form action="{{ route('partners.update', $partner) }}" method="POST" class="space-y-10 pb-20">
            @csrf
            @method('PUT')
            
            <!-- Thông tin cơ bản -->
            <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
                <div class="p-10 bg-white space-y-8">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-1.5 h-6 bg-[#E11D48] rounded-full"></div>
                            <h3 class="font-bold text-sm uppercase tracking-[0.1em] text-gray-800">Thông tin cơ sở định danh</h3>
                        </div>
                        <div class="text-[9px] font-bold text-[#E11D48] bg-red-50 px-3 py-1.5 rounded-lg uppercase tracking-widest italic">Partner v2.0</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="md:col-span-1 space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Mã đối tác</label>
                            <div class="enterprise-input bg-gray-50/80 font-bold text-[#E11D48] border-dashed py-3 italic text-center text-sm shadow-inner rounded-xl">{{ $partner->code }}</div>
                        </div>
                        <div class="md:col-span-3 space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Tên đối tác / Nhà cung cấp <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="name" required value="{{ old('name', $partner->name) }}" 
                                   class="enterprise-input py-3 text-[14px] font-bold italic" placeholder="Nhập tên pháp nhân đầy đủ...">
                            @error('name')<p class="text-red-500 text-[10px] font-bold uppercase mt-2 tracking-tight">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Mã số thuế</label>
                            <input type="text" name="tax_code" value="{{ old('tax_code', $partner->tax_code) }}" 
                                   class="enterprise-input py-3 text-[13px] font-bold" placeholder="MST hoặc số ĐKKD...">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Người liên hệ trực tiếp</label>
                            <input type="text" name="contact_person" value="{{ old('contact_person', $partner->contact_person) }}" 
                                   class="enterprise-input py-3 text-[13px] font-bold" placeholder="Người đại diện giao dịch...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin liên lạc -->
            <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
                <div class="p-10 bg-white space-y-8">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-1.5 h-6 bg-gray-900 rounded-full"></div>
                            <h3 class="font-bold text-sm uppercase tracking-[0.1em] text-gray-800">Phương thức giao tiếp & Trụ sở</h3>
                        </div>
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7 8.914L13 15l7 7M3 13h1m10 0h1m-7 8a9 9 0 1118 0 9 9 0 01-18 0z"></path></svg>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Số điện thoại hotline</label>
                            <input type="text" name="phone" value="{{ old('phone', $partner->phone) }}" 
                                   class="enterprise-input py-3 text-[13px] font-bold" placeholder="+84...">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Địa chỉ Email</label>
                            <input type="email" name="email" value="{{ old('email', $partner->email) }}" 
                                   class="enterprise-input py-3 text-[13px] font-bold" placeholder="contact@partner.com">
                        </div>
                        <div class="md:col-span-2 space-y-3">
                            <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Địa chỉ trụ sở pháp lý</label>
                            <textarea name="address" rows="3" class="enterprise-input py-3 text-[13px] font-bold" placeholder="Ghi rõ số nhà, tên đường, quận/huyện...">{{ old('address', $partner->address) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-8 pt-10 border-t border-gray-100">
                <a href="{{ route('partners.index') }}" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-gray-900 transition-colors">HỦY BỎ THAY ĐỔI</a>
                <button type="submit" class="btn-enterprise-danger !px-10 h-10 shadow-xl transform active:scale-95 transition-all text-[11px]">
                    <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    CẬP NHẬT THÔNG TIN
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
