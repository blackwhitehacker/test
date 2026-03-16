<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 tracking-tighter uppercase">
                {{ __('Chi tiết Biên bản bàn giao') }}: <span class="text-blue-600">{{ $record->code }}</span>
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('handover_records.index') }}" class="btn-enterprise-outline scale-90">
                    ← Quay lại
                </a>
                @if($record->status == 'draft')
                    <form action="{{ route('handover_records.sign', $record) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-enterprise-danger bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200 uppercase italic">
                            Ký xác nhận hệ thống
                        </button>
                    </form>
                @endif
                <a href="{{ route('handover_records.export', $record) }}" class="p-2.5 bg-[#E11D48] text-white rounded-xl hover:bg-red-700 transition-all font-bold text-[10px] uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-red-200">
                    Tải file PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-10 animate-in fade-in slide-in-from-bottom-6 duration-1000 print:shadow-none print:p-0">
        <!-- Paper Logic -->
        <div class="bg-white p-12 shadow-2xl rounded-sm border border-gray-100 relative overflow-hidden print:border-none print:shadow-none">
            <!-- Decorative Header for System View -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-blue-600/5 rounded-bl-full print:hidden"></div>
            
            <div class="flex justify-between items-start mb-12 relative z-10">
                <div class="space-y-1">
                    <h3 class="text-xl font-bold uppercase tracking-tighter text-gray-900">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</h3>
                    <p class="text-[11px] font-bold text-gray-600 uppercase tracking-widest text-center">Độc lập - Tự do - Hạnh phúc</p>
                    <div class="w-32 h-0.5 bg-gray-900 mx-auto mt-2"></div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 italic">Số hiệu văn bản</p>
                    <p class="text-sm font-bold text-blue-600 uppercase italic">{{ $record->code }}</p>
                </div>
            </div>

            <h1 class="text-4xl font-bold text-center mb-16 tracking-tighter uppercase italic decoration-blue-600 underline-offset-8 decoration-4">BIÊN BẢN BÀN GIAO TÀI SẢN</h1>

            <!-- Parties Information -->
            <div class="grid grid-cols-2 gap-12 mb-16">
                <div class="space-y-6">
                    <h4 class="font-bold text-[10px] uppercase tracking-widest text-blue-600 border-b border-blue-50 pb-2 italic">Bên bàn giao (Bên A)</h4>
                    <div class="space-y-4 text-sm">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Họ và tên</span>
                            <span class="font-bold text-gray-900 uppercase italic">{{ $record->creator->name ?? 'Người quản trị hệ thống' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Chức vụ</span>
                            <span class="font-bold text-gray-700 uppercase italic">Quản lý tài sản</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-6">
                    <h4 class="font-bold text-[10px] uppercase tracking-widest text-blue-600 border-b border-blue-50 pb-2 italic">Bên nhận (Bên B)</h4>
                    <div class="space-y-4 text-sm">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Họ và tên / Đơn vị</span>
                            <span class="font-bold text-gray-900 uppercase italic">{{ $record->receiver_name }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Bộ phận / Vị trí</span>
                            <span class="font-bold text-gray-700 uppercase italic">{{ $record->receiver_department ?: 'N/A' }} / {{ $record->receiver_position ?: 'Nhân sự' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-12">
                <p class="text-sm text-gray-700 leading-relaxed mb-6 italic font-medium">
                    Hôm nay, ngày {{ \Carbon\Carbon::parse($record->handover_date)->format('d') }} tháng {{ \Carbon\Carbon::parse($record->handover_date)->format('m') }} năm {{ \Carbon\Carbon::parse($record->handover_date)->format('Y') }}, tại trụ sở Công ty, chúng tôi tiến hành bàn giao các trang thiết bị/tài sản sau đây:
                </p>
                
                <table class="w-full border-collapse border border-gray-900">
                    <thead class="bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest">
                        <tr>
                            <th class="border border-gray-900 p-3 text-left">STT</th>
                            <th class="border border-gray-900 p-3 text-left">Mã tài sản</th>
                            <th class="border border-gray-900 p-3 text-left">Tên tài sản / Thông số</th>
                            <th class="border border-gray-900 p-3 text-center">SL</th>
                            <th class="border border-gray-900 p-3 text-right">Giá trị (VNĐ)</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @php $totalValue = 0; @endphp
                        @foreach($record->inventoryRequest->items as $index => $item)
                            @php $totalValue += ($item->price * $item->quantity); @endphp
                            <tr>
                                <td class="border border-gray-900 p-3 text-center font-bold">{{ $index + 1 }}</td>
                                <td class="border border-gray-900 p-3 font-bold text-blue-600">{{ $item->asset->code ?? 'N/A' }}</td>
                                <td class="border border-gray-900 p-3">
                                    <div class="flex flex-col">
                                        <span class="font-bold uppercase italic">{{ $item->name }}</span>
                                        <span class="text-[10px] text-gray-500 font-bold uppercase">{{ $item->specification }}</span>
                                    </div>
                                </td>
                                <td class="border border-gray-900 p-3 text-center font-bold">{{ $item->quantity }}</td>
                                <td class="border border-gray-900 p-3 text-right font-bold italic">{{ number_format($item->price) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td colspan="4" class="border border-gray-900 p-3 text-right uppercase tracking-widest text-[10px]">Tổng giá trị (Công nợ bàn giao):</td>
                            <td class="border border-gray-900 p-3 text-right text-blue-600 italic underline decoration-blue-200 underline-offset-4">{{ number_format($totalValue) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mb-16 space-y-6">
                @if($record->status == 'draft')
                    <form action="{{ route('handover_records.update', $record) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <label class="font-bold text-[10px] uppercase tracking-widest text-[#E11D48] italic">Ghi chú & Điều khoản bổ sung</label>
                            <textarea name="notes" rows="4" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-6 text-sm italic font-medium focus:border-blue-500 outline-none transition-all shadow-inner" placeholder="Nhập ghi chú hoặc điều khoản bàn giao cụ thể tại đây...">{{ $record->notes }}</textarea>
                            <div class="flex gap-4">
                                <button type="submit" class="px-8 py-3 bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-black transition-all shadow-lg shadow-gray-200">
                                    Lưu nội dung biên bản
                                </button>
                                <a href="{{ route('handover_records.index') }}" class="px-8 py-3 bg-white border-2 border-gray-100 text-gray-500 text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all">
                                    Xong & Quay lại
                                </a>
                            </div>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-gray-800 leading-relaxed font-medium italic">
                        <strong class="text-blue-600 uppercase italic">Ghi chú:</strong> {{ $record->notes ?: 'Tài sản được bàn giao trong tình trạng hoạt động tốt, mới 100% hoặc theo hiện trạng kho.' }}
                    </p>
                @endif
                <p class="text-sm text-gray-800 leading-relaxed font-medium italic">
                    Bên B cam kết sử dụng tài sản đúng mục đích, bảo quản và chịu trách nhiệm bồi thường nếu để xảy ra hư hỏng, mất mát do lỗi chủ quan. Tổng giá trị tài sản trên được ghi nhận vào <strong class="uppercase text-[#E11D48] italic">Công nợ tài sản</strong> của Bên B.
                </p>
            </div>

            <!-- Signatures -->
            <div class="grid grid-cols-2 gap-12 text-center mt-24">
                <div class="space-y-20">
                    <div class="space-y-1">
                        <h5 class="text-[11px] font-bold uppercase tracking-widest text-gray-900">Người bàn giao</h5>
                        <p class="text-[9px] text-gray-400 font-bold italic">(Ký và ghi rõ họ tên)</p>
                    </div>
                    @if($record->status == 'signed')
                        <div class="relative inline-block">
                             <div class="text-blue-600 font-bold text-xl border-4 border-blue-600 p-4 rounded-xl rotate-[-12deg] uppercase tracking-tighter animate-in zoom-in duration-500">
                                ĐÃ PHÊ DUYỆT
                             </div>
                             <p class="text-[10px] font-bold text-blue-400 mt-2 uppercase tracking-widest italic">{{ $record->signed_at }}</p>
                        </div>
                    @endif
                </div>
                <div class="space-y-20">
                    <div class="space-y-1">
                        <h5 class="text-[11px] font-bold uppercase tracking-widest text-gray-900">Người nhận tài sản</h5>
                        <p class="text-[9px] text-gray-400 font-bold italic">(Ký và ghi rõ họ tên)</p>
                    </div>
                     @if($record->status == 'signed')
                        <p class="font-bold text-gray-900 uppercase italic underline decoration-gray-200 underline-offset-8">{{ $record->receiver_name }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-enterprise p-8 bg-gray-50 border border-dashed border-gray-300 print:hidden text-center">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Nhật ký tác động trên biên bản</p>
            <div class="flex justify-center gap-10">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Khởi tạo: {{ $record->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($record->signed_at)
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Phê duyệt hệ thống: {{ \Carbon\Carbon::parse($record->signed_at)->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
