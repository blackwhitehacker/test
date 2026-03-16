<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 tracking-tighter uppercase">
            {{ __('Lịch sử thao tác') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Filter Card -->
        <div class="card-enterprise p-6">
            <form action="{{ route('activity_logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="enterprise-label">Người thực hiện</label>
                    <select name="user_id" class="enterprise-input" onchange="this.form.submit()">
                        <option value="">Tất cả người dùng</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="enterprise-label">Hành động</label>
                    <select name="action" class="enterprise-input" onchange="this.form.submit()">
                        <option value="">Tất cả hành động</option>
                        <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Khởi tạo bản ghi</option>
                        <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Cập nhật thông tin</option>
                        <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Xóa bản ghi</option>
                        <option value="approve" {{ request('action') == 'approve' ? 'selected' : '' }}>Phê duyệt</option>
                        <option value="reject" {{ request('action') == 'reject' ? 'selected' : '' }}>Từ chối</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Log List -->
        <div class="card-enterprise overflow-hidden border-t-0">
            <div class="px-8 py-4 bg-white flex justify-between items-center border-b border-gray-100">
                <h3 class="font-bold text-[10px] uppercase tracking-[0.1em] text-[#E11D48]">Nhật ký hoạt động hệ thống</h3>
                <span class="text-[10px] text-gray-400 font-bold uppercase">Log count: {{ $logs->total() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th class="pl-8">Thời gian</th>
                            <th>Người thực hiện</th>
                            <th>Hành động / Mô tả</th>
                            <th>Đối tượng</th>
                            <th class="pr-8">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 italic">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="pl-8 py-4">
                                    <div class="text-xs font-bold text-gray-900">{{ $log->created_at->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="py-4">
                                    <div class="text-xs font-bold text-gray-700">{{ $log->user->name ?? 'System' }}</div>
                                    <div class="text-[9px] text-gray-400 uppercase font-bold">{{ $log->user->role ?? 'N/A' }}</div>
                                </td>
                                <td class="py-4">
                                    @php
                                        $actionClass = match($log->action) {
                                            'create' => 'text-green-600',
                                            'update' => 'text-blue-600',
                                            'delete' => 'text-red-600',
                                            default => 'text-gray-600'
                                        };
                                    @endphp
                                    <div class="text-xs font-bold uppercase {{ $actionClass }} tracking-wider">{{ $log->action }}</div>
                                    <div class="text-[11px] text-gray-500 font-medium leading-relaxed">{{ $log->description }}</div>
                                </td>
                                <td class="py-4">
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ class_basename($log->model_type) }}</div>
                                    <div class="text-xs font-bold text-gray-900">ID: {{ $log->model_id }}</div>
                                </td>
                                <td class="pr-8 py-4 text-xs font-mono text-gray-400">
                                    {{ $log->ip_address }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-gray-100">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
