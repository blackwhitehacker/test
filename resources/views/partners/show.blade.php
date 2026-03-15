<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="flex text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                    <a href="{{ route('partners.index') }}" class="hover:text-red-600 transition-colors">Danh mục đối tác</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-600">Hồ sơ đối tác</span>
                </nav>
                <h2 class="font-bold text-3xl text-gray-800 leading-tight">
                    Đối tác: <span class="text-enterprise-red">{{ $partner->name }}</span>
                </h2>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('partners.edit', $partner) }}" class="px-6 py-2 bg-white border border-gray-200 rounded text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition-all flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 7.5-7.5z"></path></svg>
                    CHỈNH SỬA
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-8 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar Info (1/3) -->
            <div class="space-y-8">
                <div class="card-premium !p-0 overflow-hidden">
                    <div class="bg-gray-800 p-6 text-center">
                        <div class="w-20 h-20 bg-enterprise-red rounded-2xl mx-auto flex items-center justify-center text-3xl font-bold text-white shadow-lg mb-4">
                            {{ substr($partner->name, 0, 1) }}
                        </div>
                        <h3 class="text-white font-bold text-lg leading-tight">{{ $partner->name }}</h3>
                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $partner->code }}</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Mã số thuế</label>
                            <p class="text-sm font-bold text-gray-800">{{ $partner->tax_code ?? '---' }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Số điện thoại</label>
                            <p class="text-sm font-bold text-gray-800">{{ $partner->phone ?? '---' }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Email</label>
                            <p class="text-sm font-bold text-gray-800">{{ $partner->email ?? '---' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content (2/3) -->
            <div class="lg:col-span-2 space-y-8">
                <div class="card-premium">
                    <h3 class="font-bold text-lg text-gray-800 mb-6 border-b pb-4 uppercase tracking-wider">Chi tiết hồ sơ</h3>
                    
                    <div class="grid grid-cols-1 gap-8">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase block mb-2">Người liên hệ trực tiếp</label>
                            <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-4">
                                    {{ substr($partner->contact_person ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-base font-bold text-gray-900">{{ $partner->contact_person ?? 'Chưa cập nhật' }}</p>
                                    <p class="text-xs text-gray-500 italic">Đại diện cho đối tác / Nhà cung cấp</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase block mb-2">Địa chỉ hành chính</label>
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $partner->address ?? 'Chưa cập nhật địa chỉ trụ sở.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-premium">
                    <h3 class="font-bold text-lg text-gray-800 mb-6 border-b pb-4 uppercase tracking-wider">Thống kê giao dịch</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                            <span class="text-2xl font-black text-gray-800">0</span>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mt-1">Hợp đồng</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                            <span class="text-2xl font-black text-gray-800">0</span>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mt-1">Lô hàng</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                            <span class="text-2xl font-black text-gray-800">0</span>
                            <p class="text-[10px] font-bold text-black uppercase mt-1 font-black">Tài sản</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
