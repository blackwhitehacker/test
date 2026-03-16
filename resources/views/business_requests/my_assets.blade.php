<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 uppercase tracking-tighter">Tài sản của tôi</h2>
            <div class="flex gap-4">
                <a href="{{ route('business_requests.create', ['type' => 'repair']) }}" class="enterprise-btn-secondary">
                    Yêu cầu sửa chữa
                </a>
                <a href="{{ route('business_requests.create', ['type' => 'allocation']) }}" class="enterprise-btn-primary">
                    Yêu cầu cấp phát
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="card-premium overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Mã tài sản</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Tên tài sản</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Nhóm</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Trạng thái</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase text-gray-400 tracking-wider">Ngày nhận</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($assets as $asset)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold font-mono text-gray-400 group-hover:text-black transition-colors">{{ $asset->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-bold text-gray-800">{{ $asset->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[11px] font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $asset->group->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'in_use' => 'bg-green-50 text-green-600 border-green-100',
                                        'repairing' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'lost' => 'bg-red-50 text-red-600 border-red-100',
                                    ];
                                    $statusLabels = [
                                        'in_use' => 'Đang sử dụng',
                                        'repairing' => 'Đang sửa chữa',
                                        'lost' => 'Đã mất/hỏng',
                                    ];
                                @endphp
                                <span class="text-[10px] font-bold uppercase px-2 py-1 rounded-md border {{ $statusClasses[$asset->status] ?? 'bg-gray-50 text-gray-500 border-gray-100' }}">
                                    {{ $statusLabels[$asset->status] ?? $asset->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-gray-400">
                                {{ $asset->purchase_date ? \Carbon\Carbon::parse($asset->purchase_date)->format('d/m/Y') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <p class="text-gray-400 font-bold text-sm">Bạn chưa được bàn giao tài sản nào.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 bg-gray-50">
                {{ $assets->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
