<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-900 tracking-tighter uppercase italic">
                {{ __('Yêu cầu nghiệp vụ') }}
            </h2>
            <a href="{{ route('business_requests.create') }}" class="btn-enterprise">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('Tạo yêu cầu mới') }}
            </a>
        </div>
    </x-slot>

    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <!-- Filter Tabs -->
        <div class="flex gap-3 p-1.5 bg-gray-200/50 backdrop-blur-md rounded-2xl w-fit border border-gray-200">
            @php
                $tabs = [
                    '' => 'Tất cả',
                    'allocation' => 'Cấp phát',
                    'repair' => 'Sửa chữa',
                    'recall' => 'Thu hồi',
                    'liquidation' => 'Thanh lý',
                ];
            @endphp
            @foreach($tabs as $tabKey => $tabLabel)
                <a href="{{ route('business_requests.index', $tabKey ? ['type' => $tabKey] : []) }}" 
                   class="px-8 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300 {{ ($type == $tabKey || (!$type && !$tabKey)) ? 'bg-[#E11D48] text-white shadow-lg shadow-[#E11D48]/30 scale-105' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                    {{ $tabLabel }}
                </a>
            @endforeach
        </div>

        <!-- Table Area -->
        <div class="card-enterprise">
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th>Mã phiếu</th>
                            <th>Loại yêu cầu</th>
                            <th>Đối tượng thụ hưởng</th>
                            <th>Người yêu cầu</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($requests as $req)
                            <tr class="hover:bg-gray-50/80 transition-all duration-200 group">
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-gray-900 tracking-tight group-hover:text-[#E11D48] transition-colors">{{ $req->code }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $req->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 rounded-lg text-[10px] font-black uppercase tracking-wider
                                        @if($req->source_type == 'allocation') text-blue-700
                                        @elseif($req->source_type == 'repair') text-amber-700
                                        @elseif($req->source_type == 'recall') text-purple-700
                                        @elseif($req->source_type == 'liquidation') text-rose-700
                                        @else text-gray-700 @endif">
                                        <span class="w-1.5 h-1.5 rounded-full mr-2 
                                            @if($req->source_type == 'allocation') bg-blue-500
                                            @elseif($req->source_type == 'repair') bg-amber-500
                                            @elseif($req->source_type == 'recall') bg-purple-500
                                            @elseif($req->source_type == 'liquidation') bg-rose-500
                                            @else bg-gray-500 @endif"></span>
                                        @if($req->source_type == 'allocation') Cấp phát
                                        @elseif($req->source_type == 'repair') Sửa chữa
                                        @elseif($req->source_type == 'recall') Thu hồi
                                        @elseif($req->source_type == 'liquidation') Thanh lý
                                        @else Khác @endif
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-700">{{ $req->target_name }}</span>
                                        <span class="text-[10px] font-black text-[#E11D48] uppercase tracking-tighter opacity-70">
                                            @if($req->target_type == 'individual') [ Cá nhân ]
                                            @elseif($req->target_type == 'department') [ Phòng ban ]
                                            @elseif($req->target_type == 'center') [ Trung tâm ]
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center">
                                        <div class="h-9 w-9 rounded-full bg-gray-900 border-2 border-white shadow-sm flex items-center justify-center text-xs font-black text-white ring-1 ring-gray-100">
                                            {{ strtoupper(substr($req->requester->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-bold text-gray-900 leading-none">{{ $req->requester->name ?? 'N/A' }}</div>
                                            <div class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-widest">Nhân sự</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'approved' => 'bg-green-50 text-green-700 border-green-200',
                                            'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                            'completed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'cancelled' => 'bg-gray-100 text-gray-500 border-gray-200',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Chờ duyệt',
                                            'approved' => 'Đã duyệt',
                                            'rejected' => 'Từ chối',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy',
                                        ];
                                    @endphp
                                    <span class="badge-enterprise {{ $statusClasses[$req->status] ?? 'bg-gray-50 text-gray-600' }}">
                                        {{ $statusLabels[$req->status] ?? $req->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('business_requests.show', $req) }}" 
                                           class="p-2 text-gray-400 hover:text-white hover:bg-gray-900 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md" 
                                           title="Xem chi tiết">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        @if($req->status == 'pending')
                                            <a href="{{ route('business_requests.edit', $req) }}" 
                                               class="p-2 text-gray-400 hover:text-white hover:bg-[#E11D48] rounded-xl transition-all duration-200 shadow-sm hover:shadow-md" 
                                               title="Chỉnh sửa">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('business_requests.cancel', $req) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn hủy yêu cầu này?')">
                                                @csrf
                                                <button type="submit" 
                                                        class="p-2 text-gray-400 hover:text-white hover:bg-red-800 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md" 
                                                        title="Hủy yêu cầu">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 border-2 border-dashed border-gray-200">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <span class="text-sm font-black text-gray-400 uppercase tracking-widest">Hệ thống chưa ghi nhận yêu cầu nào</span>
                                        <a href="{{ route('business_requests.create', ['type' => $type ?: 'allocation']) }}" 
                                           class="mt-4 text-xs font-black text-[#E11D48] uppercase tracking-widest hover:underline decoration-2 underline-offset-4">
                                            + Tạo phiếu yêu cầu đầu tiên
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($requests->hasPages())
            <div class="mt-8 card-premium p-4">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
