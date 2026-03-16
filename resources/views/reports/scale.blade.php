<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 tracking-tighter uppercase">
                {{ __('Báo cáo quy mô tài sản') }}
            </h2>
            <a href="{{ route('reports.export_scale') }}" class="btn-enterprise-danger">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                XUẤT PDF (A4)
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card-enterprise p-6 bg-white border-l-4 border-[#E11D48] shadow-sm">
                <div class="text-[10px] uppercase tracking-widest text-[#E11D48] font-bold mb-2">Tổng giá trị tài sản</div>
                <div class="text-3xl font-bold tracking-tighter text-gray-900">{{ number_format($groups->sum(fn($g) => $g->assets->sum('purchase_price'))) }}<span class="text-sm ml-1 text-gray-400">₫</span></div>
            </div>
            <div class="card-enterprise p-6">
                <div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Tổng số lượng loại tài sản</div>
                <div class="text-3xl font-bold tracking-tighter text-gray-900">{{ $groups->count() }}</div>
            </div>
            <div class="card-enterprise p-6">
                <div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Tổng số lượng tài sản cá biệt</div>
                <div class="text-3xl font-bold tracking-tighter text-gray-900">{{ $groups->sum('assets_count') }}</div>
            </div>
        </div>

        <div class="card-enterprise overflow-hidden">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th class="pl-6">Tên danh mục</th>
                        <th class="!text-center">Số lượng</th>
                        <th class="!text-right">Giá trị đầu tư</th>
                        <th class="pr-6 !text-center">Tỷ trọng</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php $totalPrice = $groups->sum(fn($g) => $g->assets->sum('purchase_price')); @endphp
                    @foreach($groups as $group)
                        @php $groupPrice = $group->assets->sum('purchase_price'); @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="pl-6 py-4 font-bold text-gray-900">{{ $group->name }}</td>
                            <td class="py-4 !text-center font-bold text-gray-500">{{ $group->assets_count }}</td>
                            <td class="py-4 !text-right font-mono font-bold text-gray-900">{{ number_format($groupPrice) }}đ</td>
                            <td class="pr-6 py-4 !text-center">
                                <span class="text-[10px] font-bold text-gray-400 uppercase">
                                    {{ $totalPrice > 0 ? round(($groupPrice / $totalPrice) * 100, 1) : 0 }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
