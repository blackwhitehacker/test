<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 tracking-tighter uppercase">
            {{ __('Tạo tài khoản mới') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="card-enterprise p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <!-- Thông tin cơ bản -->
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-bold text-[#E11D48] uppercase tracking-[2px] border-b border-gray-100 pb-2">Thông tin cơ bản</h3>
                        
                        <div>
                            <label class="enterprise-label">Họ và tên <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="enterprise-input" placeholder="Nguyễn Văn A">
                            @error('name') <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="enterprise-label">Email công việc <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="enterprise-input" placeholder="email@company.com">
                            @error('email') <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="enterprise-label">Mật khẩu <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required class="enterprise-input" placeholder="••••••••">
                            @error('password') <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="enterprise-label">Xác nhận mật khẩu <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required class="enterprise-input" placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Vai trò và Định danh -->
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-bold text-[#E11D48] uppercase tracking-[2px] border-b border-gray-100 pb-2">Phân quyền & Định danh</h3>
                        
                        <div>
                            <label class="enterprise-label">Vai trò hệ thống <span class="text-red-500">*</span></label>
                            <select name="role" required class="enterprise-input">
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Nhân viên</option>
                                <option value="asset_manager" {{ old('role') == 'asset_manager' ? 'selected' : '' }}>Quản lý tài sản</option>
                                <option value="director" {{ old('role') == 'director' ? 'selected' : '' }}>Giám đốc</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
                            </select>
                            @error('role') <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="enterprise-label">Mã nhân viên</label>
                            <input type="text" name="employee_code" value="{{ old('employee_code') }}" class="enterprise-input" placeholder="NV-001">
                            @error('employee_code') <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="enterprise-label">Phòng ban</label>
                            <input type="text" name="department" value="{{ old('department') }}" class="enterprise-input" placeholder="Phòng Hành chính">
                        </div>

                        <div>
                            <label class="enterprise-label">Trung tâm / Cơ sở</label>
                            <input type="text" name="center" value="{{ old('center') }}" class="enterprise-input" placeholder="Trụ sở chính">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('users.index') }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 tracking-[0.2em] transition-colors py-3 px-6">HỦY BỎ</a>
                <button type="submit" class="btn-enterprise-danger">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    TẠO TÀI KHOẢN NGAY
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
