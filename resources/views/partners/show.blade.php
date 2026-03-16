<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end pb-2 gap-6">
            <div>
                <nav class="flex text-[9px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-3 italic">
                    <a href="{{ route('partners.index') }}" class="hover:text-[#E11D48] transition-colors">Danh mục đối tác</a>
                    <span class="mx-2 text-gray-300">/</span>
                    <span class="text-gray-600">Hồ sơ thực thể</span>
                </nav>
                <h2 class="font-bold text-2xl text-gray-900 tracking-tight uppercase leading-none mb-2">
                    Đối tác: <span class="text-[#E11D48]">{{ $partner->name }}</span>
                </h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#E11D48]"></div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-gray-400">Kiểm soát thông tin hợp tác & Lịch sử giao dịch</span>
                </div>
            </div>
            
            <div class="flex gap-4">
                <a href="{{ route('partners.edit', $partner) }}" 
                   class="bg-white border border-gray-200 text-gray-900 px-4 h-9 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all hover:bg-gray-50 shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 7.5-7.5z"></path></svg>
                    <span>CHỈNH SỬA</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8 px-4 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-20">
            <!-- Sidebar Info (1/3) -->
            <div class="space-y-8">
                <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
                    <div class="bg-gray-900 p-8 text-center relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#E11D48]/10 rounded-full blur-3xl"></div>
                        <div class="w-24 h-24 bg-[#E11D48] rounded-[2rem] mx-auto flex items-center justify-center text-4xl font-black text-white shadow-2xl mb-6 transform hover:rotate-6 transition-transform">
                            {{ substr($partner->name, 0, 1) }}
                        </div>
                        <h3 class="text-white font-bold text-xl leading-tight uppercase tracking-tight">{{ $partner->name }}</h3>
                        <div class="mt-3 inline-block px-4 py-1.5 bg-white/5 backdrop-blur-md border border-white/10 rounded-full">
                            <span class="text-[10px] font-bold text-[#E11D48] uppercase tracking-[0.2em]">{{ $partner->code }}</span>
                        </div>
                    </div>
                    <div class="p-8 space-y-8 bg-white">
                        <div class="group">
                            <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block mb-2">Mã số thuế doanh nghiệp</label>
                            <p class="text-[13px] font-mono font-bold text-gray-900 bg-gray-50 p-3 rounded-xl border border-gray-100 group-hover:border-[#E11D48]/30 transition-colors">{{ $partner->tax_code ?? 'CHƯA CẬP NHẬT' }}</p>
                        </div>
                        <div class="group">
                            <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block mb-2">Đường dây nóng liên hệ</label>
                            <p class="text-[13px] font-bold text-gray-900 bg-gray-50 p-3 rounded-xl border border-gray-100 group-hover:border-[#E11D48]/30 transition-colors">{{ $partner->phone ?? 'CHƯA CẬP NHẬT' }}</p>
                        </div>
                        <div class="group">
                            <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block mb-2">Hộp thư điện tử chính thức</label>
                            <p class="text-[13px] font-bold text-gray-900 bg-gray-50 p-3 rounded-xl border border-gray-100 group-hover:border-[#E11D48]/30 transition-colors lowercase">{{ $partner->email ?? 'CHƯA CẬP NHẬT' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content (2/3) -->
            <div class="lg:col-span-2 space-y-10">
                <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
                    <div class="p-10 bg-white space-y-10">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-1.5 h-6 bg-[#E11D48] rounded-full"></div>
                                <h3 class="font-bold text-sm uppercase tracking-[0.1em] text-gray-800">Chi tiết hồ sơ đối tác</h3>
                            </div>
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-10">
                            <div class="space-y-4">
                                <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Đại diện pháp lý / Liên hệ giao dịch</label>
                                <div class="flex items-center p-6 bg-gray-50/80 rounded-[2rem] border border-gray-100 group">
                                    <div class="w-14 h-14 rounded-[1.2rem] bg-white shadow-xl shadow-blue-900/5 text-blue-600 flex items-center justify-center font-black text-xl mr-6 border border-blue-50">
                                        {{ substr($partner->contact_person ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-lg font-bold text-gray-900 uppercase tracking-tight">{{ $partner->contact_person ?? 'Chưa cập nhật danh tính' }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1 italic">Vị trí: Giám đốc kinh doanh / Người đại diện</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="text-[11px] font-bold uppercase tracking-widest text-gray-500 block">Địa chỉ trụ sở pháp lý</label>
                                <div class="p-6 bg-gray-50/80 rounded-[2rem] border border-gray-100">
                                    <p class="text-[14px] font-bold text-gray-700 leading-relaxed italic">{{ $partner->address ?? 'Chưa cập nhật địa chỉ trụ sở chính thức trong hồ sơ.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-enterprise overflow-hidden border-t-0 p-0 shadow-2xl">
                    <div class="p-10 bg-white">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-6 mb-10">
                            <div class="flex items-center space-x-3">
                                <div class="w-1.5 h-6 bg-gray-900 rounded-full"></div>
                                <h3 class="font-bold text-sm uppercase tracking-[0.1em] text-gray-800">Thống kê hoạt động thực tế</h3>
                            </div>
                            <div class="text-[9px] font-bold text-gray-300 uppercase tracking-widest italic leading-none">Activity Report v1.0</div>
                        </div>

                        <div class="grid grid-cols-3 gap-8">
                            <div class="bg-gray-50/50 p-8 rounded-[2rem] border border-gray-100 text-center group hover:bg-white hover:shadow-2xl transition-all duration-500">
                                <span class="text-4xl font-black text-gray-900 tracking-tighter group-hover:text-[#E11D48] transition-colors">0</span>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-3 underline decoration-gray-100 group-hover:decoration-[#E11D48]/20 transition-all underline-offset-8">Giao dịch HĐ</p>
                            </div>
                            <div class="bg-gray-50/50 p-8 rounded-[2rem] border border-gray-100 text-center group hover:bg-white hover:shadow-2xl transition-all duration-500">
                                <span class="text-4xl font-black text-gray-900 tracking-tighter group-hover:text-[#E11D48] transition-colors">0</span>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-3 underline decoration-gray-100 group-hover:decoration-[#E11D48]/20 transition-all underline-offset-8">Số lượng Lô hàng</p>
                            </div>
                            <div class="bg-gray-50/50 p-8 rounded-[2rem] border border-gray-100 text-center group hover:bg-white hover:shadow-2xl transition-all duration-500">
                                <span class="text-4xl font-black text-[#E11D48] tracking-tighter">0</span>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-3 italic">Tổng tài sản cấp</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
