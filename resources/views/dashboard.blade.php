<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-900 tracking-tighter uppercase italic">
            {{ __('Bảng điều khiển chiến lược (Dashboard)') }}
        </h2>
    </x-slot>

    <div class="space-y-10 animate-in fade-in slide-in-from-bottom-8 duration-1000">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Stat Card 1 -->
            <div class="card-enterprise p-8 flex items-center space-x-6 group hover:bg-gray-900 transition-all duration-500">
                <div class="p-4 bg-red-50 rounded-2xl group-hover:bg-[#E11D48] transition-colors">
                    <svg class="w-6 h-6 text-[#E11D48] group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-gray-500">Tổng tài sản</p>
                    <p class="text-3xl font-black text-gray-900 group-hover:text-white transition-colors italic">{{ \App\Models\Asset::count() }}</p>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="card-enterprise p-8 flex items-center space-x-6 group hover:bg-gray-900 transition-all duration-500">
                <div class="p-4 bg-blue-50 rounded-2xl group-hover:bg-blue-600 transition-colors">
                    <svg class="w-6 h-6 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-gray-500">Hợp đồng hiệu lực</p>
                    <p class="text-3xl font-black text-gray-900 group-hover:text-white transition-colors italic">{{ \App\Models\Contract::where('status', 'active')->count() }}</p>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="card-enterprise p-8 flex items-center space-x-6 group hover:bg-gray-900 transition-all duration-500">
                <div class="p-4 bg-amber-50 rounded-2xl group-hover:bg-amber-500 transition-colors">
                    <svg class="w-6 h-6 text-amber-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-gray-500">Yêu cầu chờ duyệt</p>
                    <p class="text-3xl font-black text-gray-900 group-hover:text-white transition-colors italic">{{ \App\Models\InventoryRequest::where('status', 'pending')->count() }}</p>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="card-enterprise p-8 flex items-center space-x-6 group hover:bg-gray-900 transition-all duration-500">
                <div class="p-4 bg-green-50 rounded-2xl group-hover:bg-green-600 transition-colors">
                    <svg class="w-6 h-6 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-gray-500">Lô hàng vận hành</p>
                    <p class="text-3xl font-black text-gray-900 group-hover:text-white transition-colors italic">{{ \App\Models\Shipment::whereIn('status', ['pending', 'delivered', 'received'])->count() }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Assets -->
            <div class="lg:col-span-2 card-enterprise overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-900 text-white flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest italic text-[#E11D48]">Tài sản mới cập nhật</h3>
                    <a href="{{ route('assets.index') }}" class="text-[10px] font-black uppercase tracking-[0.2em] hover:text-[#E11D48] transition-colors underline decoration-2 underline-offset-4">Toàn bộ kho</a>
                </div>
                <div class="p-0">
                    <table class="table-premium !border-0">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="pl-8">Mã định danh</th>
                                <th>Danh mục tài sản</th>
                                <th class="pr-8 text-right">Trạng thái vận hành</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach(\App\Models\Asset::take(5)->get() as $asset)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="py-5 pl-8 text-sm font-black text-[#E11D48] tracking-tight group-hover:underline">{{ $asset->code }}</td>
                                    <td class="py-5 text-sm font-bold text-gray-900">{{ $asset->name }}</td>
                                    <td class="py-5 pr-8 text-right">
                                        @php
                                            $stClass = match($asset->status) {
                                                'inventory' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                'in_use' => 'bg-green-50 text-green-700 border-green-200 shadow-sm shadow-green-100',
                                                default => 'bg-gray-100 text-gray-700 border-gray-200'
                                            };
                                        @endphp
                                        <span class="badge-enterprise {{ $stClass }}">
                                            {{ strtoupper($asset->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions & Warnings -->
            <div class="space-y-8">
                <!-- Warnings Widget -->
                @php
                    $warningAssets = \App\Models\Asset::depreciationWarning()->take(3)->get();
                @endphp
                @if($warningAssets->count() > 0)
                    <div class="card-enterprise p-8 bg-red-50/50 border-red-200 relative overflow-hidden">
                        <div class="absolute -right-6 -top-6 w-20 h-20 bg-red-100 rounded-full blur-2xl opacity-50"></div>
                        <div class="flex items-center gap-4 mb-8">
                            <div class="p-3 bg-red-600 rounded-xl shadow-lg shadow-red-900/40">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h3 class="font-black text-red-700 uppercase tracking-widest text-[10px] italic">Cảnh báo khấu hao</h3>
                        </div>
                        <div class="space-y-4">
                            @foreach($warningAssets as $wa)
                                <a href="{{ route('assets.show', $wa) }}" class="block p-5 bg-white rounded-2xl border border-red-100 hover:border-red-600 transition-all shadow-sm group">
                                    <p class="text-[9px] font-black text-red-600 uppercase tracking-widest mb-1 group-hover:underline italic">{{ $wa->code }}</p>
                                    <p class="text-sm font-black text-gray-900 line-clamp-1 mb-2">{{ $wa->name }}</p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] text-gray-400 font-bold uppercase">Thời gian còn lại:</span>
                                        <span class="text-[10px] text-red-600 font-black underline underline-offset-2">{{ $wa->remaining_months }} tháng</span>
                                    </div>
                                </a>
                            @endforeach
                            <a href="{{ route('assets.index', ['status' => 'in_use']) }}" class="block text-center text-[10px] font-black text-red-600 uppercase tracking-[0.3em] hover:underline pt-4 italic">Toàn bộ cảnh báo</a>
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="card-enterprise p-8">
                    <h3 class="font-black text-gray-900 uppercase tracking-widest text-[10px] mb-8 border-b border-gray-100 pb-6 italic">Gác điều phối nhanh</h3>
                    <div class="flex flex-col gap-4">
                        <a href="{{ route('assets.create') }}" class="btn-enterprise w-full justify-center py-4 bg-gray-900">
                             + KHỞI TẠO TÀI SẢN MỚI
                        </a>
                        <a href="{{ route('purchase_requisitions.create') }}" class="btn-enterprise-outline w-full justify-center py-4 border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white">
                            LẬP TỜ TRÌNH MUA SẮM
                        </a>
                        <a href="{{ route('inventory_requests.create', ['type' => 'inbound']) }}" class="btn-enterprise-outline w-full justify-center py-4 border-2 border-[#E11D48] text-[#E11D48] hover:bg-[#E11D48] hover:text-white">
                            YÊU CẦU NHẬP KHO VẬT TƯ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
