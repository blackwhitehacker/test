<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-gray-900 tracking-tighter uppercase italic">
                {{ __('Chi tiết yêu cầu') }}: <span class="text-[#E11D48]">{{ $request->code }}</span>
            </h2>
            <div class="flex gap-3 items-center">
                <a href="{{ route('business_requests.index') }}" class="btn-enterprise-outline scale-90">
                    ← Quay lại
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 animate-in fade-in slide-in-from-bottom-6 duration-1000">
        <div class="lg:col-span-2 space-y-8">
            <div class="card-enterprise p-8">
                <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-6 uppercase">
                    <h3 class="font-black text-sm tracking-widest text-[#E11D48]">Danh sách tài sản đề xuất</h3>
                    <span class="text-[10px] font-black text-gray-400">{{ count($request->items) }} mục</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="table-premium">
                        <thead>
                            <tr class="text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                <th class="pb-4">Tài sản</th>
                                <th class="pb-4 text-center">Số lượng</th>
                                <th class="pb-4 text-right">Dự toán tạm tính</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($request->items as $item)
                                <tr class="group">
                                    <td class="py-5">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-gray-900 group-hover:text-[#E11D48] transition-colors uppercase tracking-tight">{{ $item->name }}</span>
                                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">{{ $item->specification }}</span>
                                            @if($item->asset)
                                                <span class="text-[9px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full w-fit mt-2 font-black italic">Mã: {{ $item->asset->code }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-5 text-center">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-900 text-white font-black text-sm shadow-lg shadow-gray-200">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="py-5 text-right">
                                        <div class="text-sm font-black text-gray-900 italic underline decoration-[#E11D48] decoration-2 underline-offset-4">
                                            {{ number_format($item->price) }} <span class="text-[10px] font-bold text-gray-400">VNĐ</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-enterprise p-8">
                <div class="flex items-center gap-4 mb-6 border-b border-gray-100 pb-6">
                    <div class="p-3 bg-gray-900 rounded-2xl">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    </div>
                    <h3 class="font-black text-sm uppercase tracking-widest text-[#E11D48]">Ghi chú & Giải trình lý do</h3>
                </div>
                <div class="p-6 bg-gray-50/50 rounded-2xl border-l-4 border-gray-900 text-sm text-gray-800 leading-relaxed italic font-medium shadow-inner">
                    {{ $request->notes ?: 'Hệ thống chưa ghi nhận ghi chú bổ sung cho yêu cầu này.' }}
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="card-enterprise p-8 bg-gray-900 text-white overflow-hidden relative">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#E11D48] rounded-full blur-3xl opacity-20"></div>
                
                <h3 class="font-black text-sm mb-8 border-b border-white/10 pb-6 text-[#E11D48] uppercase tracking-widest relative z-10">Thông tin tổng quát</h3>
                <div class="space-y-6 relative z-10">
                    <div class="flex flex-col">
                        <label class="text-[10px] font-black uppercase text-gray-500 tracking-widest mb-1.5 italic">Phân hệ nghiệp vụ</label>
                        <p class="text-xs font-black uppercase tracking-widest bg-white/5 p-3 rounded-xl border border-white/10">
                            @if($request->source_type == 'allocation') [ QUẢN LÝ CẤP PHÁT ]
                            @elseif($request->source_type == 'repair') [ QUẢN LÝ SỬA CHỮA ]
                            @elseif($request->source_type == 'recall') [ QUẢN LÝ THU HỒI ]
                            @elseif($request->source_type == 'liquidation') [ QUẢN LÝ THANH LÝ ]
                            @else [ NGHIỆP VỤ KHÁC ] @endif
                        </p>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-[10px] font-black uppercase text-gray-500 tracking-widest mb-1.5 italic">Đối tượng hưởng quyền</label>
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-[#E11D48] animate-pulse"></div>
                            <p class="text-sm font-black text-white italic">
                                {{ $request->target_name }}
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-between items-end border-t border-white/5 pt-6 mt-4">
                        <div class="flex flex-col">
                            <label class="text-[10px] font-black uppercase text-gray-500 mb-1 italic">Trạng thái phê duyệt</label>
                            @php
                                $statusConf = [
                                    'pending' => ['label' => 'ĐANG CHỜ DUYỆT', 'class' => 'text-amber-400 bg-amber-400/10 border-amber-400/20'],
                                    'approved' => ['label' => 'ĐÃ PHÊ DUYỆT', 'class' => 'text-green-400 bg-green-400/10 border-green-400/20'],
                                    'rejected' => ['label' => 'ĐÃ TỪ CHỐI', 'class' => 'text-red-400 bg-red-400/10 border-red-400/20'],
                                    'completed' => ['label' => 'ĐÃ HOÀN THÀNH', 'class' => 'text-blue-400 bg-blue-400/10 border-blue-400/20'],
                                    'cancelled' => ['label' => 'ĐÃ HỦY BỎ', 'class' => 'text-gray-400 bg-gray-400/10 border-gray-400/20'],
                                ];
                                $st = $statusConf[$request->status] ?? ['label' => strtoupper($request->status), 'class' => 'text-gray-400'];
                            @endphp
                            <span class="px-3 py-1.5 rounded-lg text-[9px] font-black border {{ $st['class'] }} w-fit tracking-widest">
                                {{ $st['label'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            @if($request->source_type == 'recall')
                <div class="card-enterprise p-8 bg-gray-50/50 border-gray-200 border-2">
                    <div class="flex items-center gap-4 mb-8 border-b border-gray-200 pb-6 uppercase">
                        <div class="p-3 bg-gray-900 rounded-2xl shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <h3 class="font-black text-sm tracking-widest text-gray-900 italic">Đánh giá kỹ thuật (Phòng kỹ thuật)</h3>
                    </div>

                    @if(!$request->assessment_status && $request->status == 'pending')
                        <form action="{{ route('business_requests.assessment', $request) }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-3 gap-4">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="assessment_status" value="safe" class="hidden peer" required>
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 bg-white peer-checked:border-green-600 peer-checked:bg-green-50 transition-all text-center">
                                        <p class="text-[10px] font-black uppercase text-gray-400 peer-checked:text-green-600 tracking-widest mb-1">Tình trạng</p>
                                        <p class="text-sm font-black text-gray-900">Sử dụng tốt</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="assessment_status" value="damaged" class="hidden peer">
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 bg-white peer-checked:border-amber-600 peer-checked:bg-amber-50 transition-all text-center">
                                        <p class="text-[10px] font-black uppercase text-gray-400 peer-checked:text-amber-600 tracking-widest mb-1">Tình trạng</p>
                                        <p class="text-sm font-black text-gray-900">Cần sửa chữa</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="assessment_status" value="broken" class="hidden peer">
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 bg-white peer-checked:border-red-600 peer-checked:bg-red-50 transition-all text-center">
                                        <p class="text-[10px] font-black uppercase text-gray-400 peer-checked:text-red-600 tracking-widest mb-1">Tình trạng</p>
                                        <p class="text-sm font-black text-gray-900">Hỏng/Thanh lý</p>
                                    </div>
                                </label>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest italic pl-1">Ghi chú đánh giá kỹ thuật</label>
                                <textarea name="assessment_notes" rows="3" class="w-full rounded-2xl border-gray-100 bg-white text-sm font-bold placeholder:text-gray-300 focus:border-[#E11D48] transition-all" placeholder="Nhập chi tiết tình trạng máy, linh kiện hỏng..."></textarea>
                            </div>
                            <button type="submit" class="w-full py-4 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#E11D48] shadow-lg shadow-gray-200 transition-all italic">
                                Xác nhận kết quả đánh giá kỹ thuật
                            </button>
                        </form>
                    @else
                        <div class="space-y-4">
                            @php
                                $aStatusConf = [
                                    'safe' => ['label' => 'SỬ DỤNG TỐT', 'class' => 'text-green-600 bg-green-50 border-green-200'],
                                    'damaged' => ['label' => 'CẦN SỬA CHỮA', 'class' => 'text-amber-600 bg-amber-50 border-amber-200'],
                                    'broken' => ['label' => 'HỎNG / THANH LÝ', 'class' => 'text-red-600 bg-red-50 border-red-200'],
                                ];
                                $ast = $aStatusConf[$request->assessment_status] ?? ['label' => 'CHƯA ĐÁNH GIÁ', 'class' => 'text-gray-400 bg-gray-50'];
                            @endphp
                            <div class="p-6 rounded-2xl border {{ $ast['class'] }} flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Kết quả đánh giá</p>
                                    <p class="text-sm font-black">{{ $ast['label'] }}</p>
                                </div>
                                <svg class="w-8 h-8 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="p-4 bg-white rounded-xl border border-gray-100 italic text-xs font-bold text-gray-600">
                                {{ $request->assessment_notes ?: 'Không có ghi chú đánh giá kỹ thuật bổ sung.' }}
                            </div>

                            @if($request->status == 'pending')
                                <div class="flex gap-3 pt-4">
                                    <form action="{{ route('business_requests.approve', $request) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full py-4 bg-green-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-green-700 shadow-lg shadow-green-200 italic">
                                            Duyệt thu hồi & Tạo BBHT
                                        </button>
                                    </form>
                                    <form action="{{ route('business_requests.reject', $request) }}" method="POST" class="flex-shrink-0">
                                        @csrf
                                        <button type="submit" class="px-6 py-4 bg-red-50 text-red-700 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-100 border border-red-200">
                                            Từ chối
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            @if($request->source_type == 'repair')
                <div class="card-enterprise bg-gray-50/50 border-gray-200 border-2 overflow-hidden">
                    <div class="p-8 border-b border-gray-200 flex items-center justify-between uppercase">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gray-900 rounded-2xl shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <h3 class="font-black text-sm tracking-widest text-gray-900 italic">Quản lý sửa chữa tài sản</h3>
                        </div>
                        @if($request->repair_status)
                            <span class="px-4 py-1 bg-gray-900 text-white text-[9px] font-black rounded-full uppercase tracking-widest">
                                {{ 
                                    $request->repair_status == 'repairing' ? 'Đang sửa chữa' : 
                                    ($request->repair_status == 'completed' ? 'Đã hoàn thành' : 'Không sửa được') 
                                }}
                            </span>
                        @endif
                    </div>

                    <div class="p-8">
                        @if($request->status == 'approved' || ($request->status == 'completed' && $request->source_type == 'repair'))
                            <form action="{{ route('business_requests.repair_update', $request) }}" method="POST" class="space-y-6">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Trạng thái sửa chữa</label>
                                        <select name="repair_status" class="w-full bg-white border-2 border-gray-100 rounded-xl px-4 py-3 font-bold text-sm focus:border-gray-900 transition-all outline-none appearance-none">
                                            <option value="repairing" {{ $request->repair_status == 'repairing' ? 'selected' : '' }}>Đang sửa chữa (In Progress)</option>
                                            <option value="completed" {{ $request->repair_status == 'completed' ? 'selected' : '' }}>Hoàn thành sửa chữa (Fixed)</option>
                                            <option value="unfixable" {{ $request->repair_status == 'unfixable' ? 'selected' : '' }}>Không thể sửa chữa (Unfixable)</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Chi phí sửa chữa (VNĐ)</label>
                                        <input type="number" name="repair_cost" value="{{ $request->repair_cost }}" placeholder="Ví dụ: 500000" class="w-full bg-white border-2 border-gray-100 rounded-xl px-4 py-3 font-bold text-sm focus:border-gray-900 transition-all outline-none">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Ghi chú sửa chữa (Chi tiết linh kiện, lỗi...)</label>
                                    <textarea name="repair_notes" rows="3" class="w-full bg-white border-2 border-gray-100 rounded-xl px-4 py-3 font-bold text-sm focus:border-gray-900 transition-all outline-none resize-none" placeholder="Nhập ghi chú chi tiết tại đây...">{{ $request->repair_notes }}</textarea>
                                </div>

                                <div class="flex justify-end pt-4 gap-3">
                                    @if($request->repair_status == 'unfixable')
                                        <a href="{{ route('business_requests.export_liquidation', $request) }}" class="px-6 py-4 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#E11D48] transition-all flex items-center gap-3 shadow-lg shadow-gray-200">
                                            Xuất biên bản thanh lý
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </a>
                                    @endif
                                    <button type="submit" class="px-8 py-4 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#E11D48] shadow-lg shadow-gray-200 transition-all flex items-center gap-3">
                                        Lưu thông tin sửa chữa
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </div>
                            </form>
                            
                            @if($request->repair_status == 'unfixable')
                                <div class="mt-8 p-6 bg-red-50 border-2 border-red-100 rounded-2xl flex items-center gap-6 animate-in fade-in zoom-in duration-500">
                                    <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-red-600 uppercase tracking-widest italic">Cảnh báo hệ thống</p>
                                        <p class="text-sm font-bold text-red-900 mt-1">Tài sản này được đánh giá không thể sửa chữa. Bạn nên thực hiện quy trình **Thanh lý tài sản**.</p>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic">Đang chờ quản trị viên duyệt yêu cầu sửa chữa...</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($request->source_type == 'allocation')
                <div class="card-enterprise p-8 bg-gray-50/50 border-gray-200 border-2" x-data="{ compliance: null, loading: false }">
                    <div class="flex items-center justify-between mb-8 border-b border-gray-200 pb-6 uppercase">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gray-900 rounded-2xl shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <h3 class="font-black text-sm tracking-widest text-gray-900 italic">Đánh giá định mức cấp phát</h3>
                        </div>
                        @if($request->status == 'pending')
                            <button @click="loading = true; fetch('{{ route('business_requests.check_compliance', $request) }}').then(r => r.json()).then(data => { compliance = data; loading = false; })" 
                                    class="px-4 py-2 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#E11D48] transition-all flex items-center gap-2">
                                <span x-show="!loading">Kiểm tra ngay</span>
                                <span x-show="loading" class="animate-spin opacity-50">/</span>
                            </button>
                        @endif
                    </div>

                    <div x-show="compliance" class="space-y-4 animate-in fade-in slide-in-from-top-2">
                        <template x-for="item in compliance.compliance" :key="item.group">
                            <div class="p-4 bg-white rounded-xl border flex justify-between items-center shadow-sm" :class="item.exceeds ? 'border-red-200 bg-red-50/30' : 'border-green-100 bg-green-50/30'">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-wider mb-1 italic" :class="item.exceeds ? 'text-red-500' : 'text-green-600'" x-text="item.group"></p>
                                    <p class="text-[11px] font-bold text-gray-900 flex gap-4">
                                        <span>Định mức: <strong x-text="item.standard"></strong></span>
                                        <span>Đã giữ: <strong x-text="item.current"></strong></span>
                                        <span>Yêu cầu: <strong x-text="item.requesting"></strong></span>
                                    </p>
                                </div>
                                <div>
                                    <span x-show="item.exceeds" class="px-3 py-1 bg-red-600 text-white text-[9px] font-black rounded-lg uppercase tracking-widest">Vi phạm định mức</span>
                                    <span x-show="!item.exceeds" class="px-3 py-1 bg-green-600 text-white text-[9px] font-black rounded-lg uppercase tracking-widest italic">Trong định mức</span>
                                </div>
                            </div>
                        </template>

                        <div x-show="compliance && '{{ $request->status }}' == 'pending'" class="mt-8 space-y-3">
                            <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-100 mb-4 animate-pulse">
                                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest italic">Hành động quản trị</p>
                                <p class="text-[11px] font-bold text-blue-800 mt-1">Duyệt yêu cầu sẽ tự động tạo Biên bản bàn giao (BBBG) và cập nhật trạng thái tài sản.</p>
                            </div>
                            
                            <div class="flex gap-3">
                                <form action="{{ route('business_requests.approve', $request) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-4 bg-green-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-green-700 shadow-lg shadow-green-200 italic">
                                        Phê duyệt & Tạo BBBG
                                    </button>
                                </form>
                                <form action="{{ route('business_requests.reject', $request) }}" method="POST" class="flex-shrink-0" onsubmit="return confirm('Bạn có chắc muốn từ chối yêu cầu này?')">
                                    @csrf
                                    <button type="submit" class="px-6 py-4 bg-red-50 text-red-700 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-100 border border-red-200">
                                        Từ chối
                                    </button>
                                </form>
                            </div>

                            <div x-show="compliance.is_exceeded" class="mt-4 pt-4 border-t border-gray-200 flex gap-3">
                                <form action="{{ route('business_requests.escalate', $request) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-3 bg-amber-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-amber-700 shadow-lg shadow-amber-200 italic">
                                        Gửi Giám đốc (Ngoài định mức)
                                    </button>
                                </form>
                                <a href="{{ route('purchase_requisitions.create', ['source_request' => $request->code]) }}" 
                                   class="flex-1 py-3 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-800 text-center shadow-lg shadow-gray-200">
                                    Đề xuất mua sắm
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($request->source_type == 'liquidation')
                <div class="card-enterprise bg-gray-50/50 border-gray-200 border-2 overflow-hidden mb-8">
                    <div class="p-8 border-b border-gray-200 flex items-center justify-between uppercase">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gray-900 rounded-2xl shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </div>
                            <h3 class="font-black text-sm tracking-widest text-gray-900 italic">Quản lý thanh lý tài sản</h3>
                        </div>
                    </div>

                    <div class="p-8">
                        @if($request->status == 'pending' || $request->status == 'pending_director')
                            <div class="flex gap-3">
                                <form action="{{ route('business_requests.approve', $request) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-4 bg-green-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-green-700 shadow-lg shadow-green-200 italic">
                                        Phê duyệt thanh lý
                                    </button>
                                </form>
                                <form action="{{ route('business_requests.reject', $request) }}" method="POST" class="flex-shrink-0">
                                    @csrf
                                    <button type="submit" class="px-6 py-4 bg-red-50 text-red-700 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-100 border border-red-200">
                                        Từ chối
                                    </button>
                                </form>
                            </div>
                        @elseif($request->status == 'approved' || $request->status == 'completed')
                            <form action="{{ route('business_requests.liquidation_update', $request) }}" method="POST" class="space-y-6">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest italic">Giá trị thu hồi (VNĐ)</label>
                                        <input type="number" name="recovery_value" value="{{ $request->recovery_value }}" placeholder="Ví dụ: 1500000" class="w-full bg-white border-2 border-gray-100 rounded-xl px-4 py-3 font-bold text-sm focus:border-gray-900 transition-all outline-none" {{ $request->status == 'completed' ? 'readonly' : '' }}>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest italic">Liên kết biên bản</label>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('business_requests.export_liquidation', $request) }}" class="flex-1 py-3.5 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#E11D48] text-center shadow-lg transition-all">
                                                Xuất biên bản thanh lý PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest italic">Ghi chú thanh lý</label>
                                    <textarea name="liquidation_notes" rows="3" class="w-full bg-white border-2 border-gray-100 rounded-xl px-4 py-3 font-bold text-sm focus:border-gray-900 transition-all outline-none resize-none" placeholder="Nhập ghi chú chi tiết tại đây..." {{ $request->status == 'completed' ? 'readonly' : '' }}>{{ $request->liquidation_notes }}</textarea>
                                </div>

                                @if($request->status == 'approved')
                                    <div class="flex justify-end pt-4">
                                        <button type="submit" class="px-8 py-4 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-green-600 shadow-lg shadow-gray-200 transition-all flex items-center gap-3 italic">
                                            Hoàn thành & Đóng yêu cầu
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </div>
                                @endif
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            @if($request->status == 'approved' && $request->handoverRecord)
                <div class="card-enterprise p-8 bg-blue-50/50 border-blue-100 border-2">
                    <div class="flex items-center gap-4 mb-6 border-b border-blue-100 pb-6">
                        <div class="p-3 bg-blue-600 rounded-2xl shadow-lg shadow-blue-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="font-black text-sm uppercase tracking-widest text-blue-700">
                            {{ $request->source_type == 'recall' ? 'Biên bản hoàn trả tài sản' : 'Biên bản bàn giao tài sản' }}
                        </h3>
                    </div>
                    <div class="space-y-4">
                        <div class="p-4 bg-white rounded-xl border border-blue-100 flex justify-between items-center shadow-sm">
                            <div>
                                <p class="text-[10px] font-black uppercase text-blue-400 tracking-wider mb-1 italic">Mã số biên bản</p>
                                <p class="text-xs font-black text-blue-900 uppercase tracking-tighter italic underline decoration-blue-200">{{ $request->handoverRecord->code }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('handover_records.show', $request->handoverRecord) }}" class="px-5 py-2.5 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-700 transition-all shadow-md shadow-blue-200">
                                    Chi tiết & Hoàn thiện
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card-enterprise p-8">
                <h3 class="font-black text-sm mb-8 border-b border-gray-100 pb-6 text-[#E11D48] uppercase tracking-widest">Nhật ký hệ thống</h3>
                <div class="relative">
                    <div class="absolute left-2.5 top-0 bottom-0 w-px bg-gray-100"></div>
                    <div class="space-y-8 relative">
                        @forelse($request->activityLogs as $log)
                            <div class="flex gap-6 pl-1 group">
                                <div class="w-5 h-5 rounded-full bg-white border-4 border-gray-900 shrink-0 z-10 group-hover:border-[#E11D48] transition-colors shadow-sm"></div>
                                <div class="pb-1 translate-y-[-2px]">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[11px] font-black text-gray-900 group-hover:text-[#E11D48] uppercase">{{ $log->user->name ?? 'SYSTEM' }}</span>
                                        <span class="text-[9px] font-bold text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-gray-600 font-medium leading-relaxed">{{ $log->description }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center py-6 text-center">
                                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Chưa có nhật ký</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
