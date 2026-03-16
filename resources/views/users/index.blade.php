<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-2 gap-6">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 tracking-tight uppercase leading-none mb-3">
                    Quản lý người dùng
                    <span class="text-gray-400">HỆ THỐNG</span>
                </h2>
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 italic">Quản trị nhân sự & Phân quyền truy cập tập trung</span>
                </div>
            </div>
            <a href="{{ route('users.create') }}" 
               class="bg-[#E11D48] text-white px-6 h-10 rounded-lg text-[11px] font-bold uppercase tracking-widest transition-all hover:bg-[#BE123C] shadow-lg shadow-red-900/10 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                THÊM TÀI KHOẢN MỚI
            </a>
        </div>
    </x-slot>

    <div class="space-y-10 animate-in fade-in duration-700">
        <!-- Search Area (Mirrored from Partners) -->
        <div class="card-enterprise p-8 bg-white border-l-4 border-[#E11D48] shadow-sm italic">
            <form action="{{ route('users.index') }}" method="GET" class="flex flex-col md:flex-row gap-8">
                <div class="flex-1 relative group">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-500 block mb-3">Tra cứu thông tin tài khoản</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="enterprise-input py-3 !pl-10 text-[13px] font-bold italic" 
                               placeholder="Tìm tên nhân viên, email, mã nhân viên, phòng ban...">
                        <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-gray-400 group-focus-within:text-[#E11D48] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="bg-gray-900 text-white px-10 h-11 rounded-xl text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-black transition shadow-lg italic">
                        TRUY XUẤT
                    </button>
                    @if(request('search'))
                        <a href="{{ route('users.index') }}" class="px-6 h-11 text-[10px] font-bold text-gray-400 hover:text-gray-900 flex items-center shadow-sm border border-gray-100 rounded-xl bg-gray-50 transition-all uppercase tracking-widest italic">
                            HỦY LỌC
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="card-enterprise overflow-hidden border-l-4 border-[#E11D48] shadow-2xl">
            <div class="px-8 py-6 bg-white flex justify-between items-center border-b border-gray-100 italic">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.2em] text-gray-500">CƠ SỞ DỮ LIỆU NHÂN SỰ & QUYỀN HẠN HỆ THỐNG</h3>
                <div class="flex space-x-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48] animate-pulse"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-gray-200"></div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 uppercase tracking-widest text-gray-400 text-[9px]">
                            <th class="pl-8 py-4 font-bold text-left">Nhân sự</th>
                            <th class="py-4 font-bold text-left">Định danh nhân sự</th>
                            <th class="py-4 font-bold text-center">Vai trò quản trị</th>
                            <th class="pr-8 py-4 font-bold text-right">Điều phối</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="pl-8 py-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-gray-900 flex items-center justify-center text-white font-bold mr-3 shadow-lg shadow-gray-200">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-[14px] font-bold text-gray-900 tracking-tight uppercase group-hover:text-[#E11D48] transition-colors">{{ $user->name }}</div>
                                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5 italic lowercase">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6">
                                    <span class="text-[10px] font-bold text-[#E11D48] uppercase bg-red-50 px-3 py-1 rounded shadow-sm">{{ $user->employee_code ?? '---' }}</span>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1.5 italic">{{ $user->department ?? 'Tự do' }}</div>
                                </td>
                                <td class="py-6 text-center">
                                    @php
                                        $roleMap = [
                                            'admin' => ['label' => 'QUẢN TRỊ VIÊN', 'class' => 'bg-gray-900 text-white'],
                                            'director' => ['label' => 'GIÁM ĐỐC', 'class' => 'bg-[#E11D48] text-white'],
                                            'asset_manager' => ['label' => 'QUẢN LÝ TÀI SẢN', 'class' => 'bg-amber-400 text-black'],
                                            'staff' => ['label' => 'NHÂN VIÊN', 'class' => 'bg-gray-100 text-gray-600'],
                                        ];
                                        $r = $roleMap[$user->role] ?? ['label' => strtoupper($user->role), 'class' => 'bg-gray-50 text-gray-400'];
                                    @endphp
                                    <span class="px-4 py-1.5 rounded text-[9px] font-black uppercase tracking-widest {{ $r['class'] }} shadow-sm">
                                        {{ $r['label'] }}
                                    </span>
                                </td>
                                <td class="pr-8 py-6 text-right whitespace-nowrap">
                                    <div class="flex justify-end items-center space-x-6">
                                        <a href="{{ route('users.edit', $user) }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 tracking-widest uppercase transition-all italic underline decoration-gray-200 underline-offset-4 decoration-2">Cập nhật</a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Xác nhận xóa tài khoản này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[10px] font-bold text-gray-300 hover:text-red-600 tracking-widest uppercase transition-all italic">Xóa bỏ</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-32 text-center opacity-30 italic">
                                    <div class="text-[11px] font-bold uppercase tracking-[0.3em]">Hệ thống chưa ghi nhận tài khoản nhân sự</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
