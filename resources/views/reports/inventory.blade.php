<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 tracking-tighter uppercase">
                {{ __('Báo cáo tồn kho') }}
            </h2>
            <a href="{{ route('reports.export_inventory') }}" class="btn-enterprise-danger">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                XUẤT PDF (A4)
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="card-enterprise p-6">
            <form action="{{ route('reports.inventory') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2 block">Loại tài sản</label>
                    <select name="group_id" class="enterprise-input text-xs" onchange="this.form.submit()">
                        <option value="">Tất cả các loại</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="card-enterprise overflow-hidden">
            <div class="px-6 py-4 bg-white flex justify-between items-center border-b border-gray-100">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.1em] text-[#E11D48]">Danh sách tài sản đang lưu kho</h3>
                <span class="text-[10px] text-gray-400 font-bold uppercase">Tổng số: {{ $assets->total() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th class="pl-6">Mã TS</th>
                            <th>Tên tài sản</th>
                            <th>Loại/Nhóm</th>
                            <th>Ngày nhập</th>
                            <th class="pr-6 !text-right">Giá trị nhập</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($assets as $asset)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="pl-6 py-4 font-bold text-[#E11D48]">{{ $asset->code }}</td>
                                <td class="py-4 font-bold text-gray-900">{{ $asset->name }}</td>
                                <td class="py-4 text-xs font-bold text-gray-500">{{ $asset->group->name ?? '---' }}</td>
                                <td class="py-4 text-xs font-bold text-gray-900">{{ $asset->created_at->format('d/m/Y') }}</td>
                                <td class="pr-6 py-4 !text-right font-mono font-bold text-gray-900">{{ number_format($asset->purchase_price) }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">
                {{ $assets->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
