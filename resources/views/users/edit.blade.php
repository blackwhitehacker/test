<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 tracking-tighter uppercase">
            {{ __('Chỉnh sửa tài khoản') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="card-enterprise p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <!-- Thông tin cơ bản -->
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-bold text-[#E11D48] uppercase tracking-[2px] border-b border-gray-100 pb-2">Thông tin tài khoản</h3>
                        
                        <div>
                            <label class="enterprise-label">Họ và tên <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="enterprise-input">
                            @error('name') <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="enterprise-label">Email công việc <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="enterprise-input">
                            @error('email') <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-4 tracking-wider text-center">Đổi mật khẩu (Để trống nếu không đổi)</p>
                            <div class="space-y-4">
                                <div>
                                    <label class="enterprise-label">Mật khẩu mới</label>
                                    <input type="password" name="password" class="enterprise-input" placeholder="••••••••">
                                </div>
                                <div>
                                    <label class="enterprise-label">Xác nhận mật khẩu</label>
                                    <input type="password" name="password_confirmation" class="enterprise-input" placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vai trò và Định danh -->
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-bold text-[#E11D48] uppercase tracking-[2px] border-b border-gray-100 pb-2">Phân quyền & Chức vụ</h3>
                        
                        <div>
                            <label class="enterprise-label">Vai trò hệ thống <span class="text-red-500">*</span></label>
                            <select name="role" required class="enterprise-input">
                                <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Nhân viên</option>
                                <option value="asset_manager" {{ old('role', $user->role) == 'asset_manager' ? 'selected' : '' }}>Quản lý tài sản</option>
                                <option value="director" {{ old('role', $user->role) == 'director' ? 'selected' : '' }}>Giám đốc</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
                            </select>
                        </div>

                        <div>
                            <label class="enterprise-label">Mã nhân viên</label>
                            <input type="text" name="employee_code" value="{{ old('employee_code', $user->employee_code) }}" class="enterprise-input">
                        </div>

                        <div>
                            <label class="enterprise-label">Phòng ban</label>
                            <input type="text" name="department" value="{{ old('department', $user->department) }}" class="enterprise-input">
                        </div>

                        <div>
                            <label class="enterprise-label">Trung tâm / Cơ sở</label>
                            <input type="text" name="center" value="{{ old('center', $user->center) }}" class="enterprise-input">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('users.index') }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 tracking-[0.2em] transition-colors py-3 px-6">HỦY BỎ</a>
                <button type="submit" class="btn-enterprise-danger">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    CẬP NHẬT TÀI KHOẢN
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
