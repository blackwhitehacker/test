<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Asset Management</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50">
        <div class="flex min-h-screen" x-data="{ sidebarOpen: true }">
            <!-- Sidebar -->
            <aside class="sidebar-light transition-all duration-300 flex-shrink-0 relative flex flex-col print:hidden"
                   :class="sidebarOpen ? 'w-64' : 'w-20'">
                <div class="h-16 flex items-center px-6 bg-white border-b border-gray-100 flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="bg-[#E11D48] p-1.5 rounded-lg shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <span x-show="sidebarOpen" class="font-bold text-lg text-gray-900 tracking-widest uppercase">AMS</span>
                    </div>
                </div>

                <nav class="mt-4 px-3 space-y-1 overflow-y-auto flex-1 scrollbar-hide">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-[11px] font-bold uppercase tracking-widest rounded-xl transition-all duration-200 @if(request()->routeIs('dashboard')) text-[#E11D48] bg-gray-50 @else text-gray-500 hover:bg-gray-100 hover:text-gray-900 @endif group">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0 @if(request()->routeIs('dashboard')) text-[#E11D48] @else text-gray-400 group-hover:text-[#E11D48] @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span x-show="sidebarOpen">Bản tin quản trị</span>
                    </a>

                    <div x-data="{ open: @if(request()->is('purchase_requisitions*') || request()->is('contracts*') || request()->is('shipments*')) true @else false @endif }">
                        <button @click="open = !open" :class="sidebarOpen ? '' : 'justify-center'" class="w-full flex items-center justify-between px-4 py-3 text-[11px] font-bold uppercase tracking-widest rounded-xl transition-all duration-200 group @if(request()->is('purchase_requisitions*') || request()->is('contracts*') || request()->is('shipments*')) text-[#E11D48] bg-gray-50 @else text-gray-500 hover:bg-gray-100 hover:text-gray-900 @endif">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 flex-shrink-0 @if(request()->is('purchase_requisitions*') || request()->is('contracts*') || request()->is('shipments*')) text-[#E11D48] @else text-gray-400 group-hover:text-[#E11D48] @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <span x-show="sidebarOpen" class="ml-3">Quản lý mua sắm</span>
                            </div>
                            <svg x-show="sidebarOpen" class="w-4 h-4 transform transition-transform duration-200" :class="open ? 'rotate-180 {{ (request()->is('purchase_requisitions*') || request()->is('contracts*') || request()->is('shipments*')) ? 'text-[#E11D48]' : 'text-gray-400' }}' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open && sidebarOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="ml-9 mt-1 space-y-1">
                            <a href="{{ route('purchase_requisitions.index') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('purchase_requisitions*')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Tờ trình mua sắm</a>
                            <a href="{{ route('contracts.index') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('contracts*')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Hợp đồng mua sắm</a>
                            <a href="{{ route('shipments.index') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('shipments*')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Quản lý lô hàng</a>
                        </div>
                    </div>

                    <div x-data="{ open: @if(request()->is('partners*') || request()->is('asset_groups*') || request()->routeIs('assets.index')) true @else false @endif }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-[11px] font-bold uppercase tracking-widest rounded-xl transition-all duration-200 group @if(request()->is('partners*') || request()->is('asset_groups*') || request()->routeIs('assets.index')) text-[#E11D48] bg-gray-50 @else text-gray-500 hover:bg-gray-100 hover:text-gray-900 @endif">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 flex-shrink-0 @if(request()->is('partners*') || request()->is('asset_groups*') || request()->routeIs('assets.index')) text-[#E11D48] @else text-gray-400 group-hover:text-[#E11D48] @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                <span x-show="sidebarOpen" class="ml-3">Quản lý danh mục</span>
                            </div>
                            <svg x-show="sidebarOpen" class="w-4 h-4 transform transition-transform duration-200" :class="open ? 'rotate-180 {{ (request()->is('partners*') || request()->is('asset_groups*') || request()->routeIs('assets.index')) ? 'text-[#E11D48]' : 'text-gray-400' }}' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open && sidebarOpen" x-transition class="ml-9 mt-1 space-y-1">
                            <a href="{{ route('asset_groups.index') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('asset_groups*')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Danh mục tài sản</a>
                            <a href="{{ route('partners.index') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('partners*')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Danh mục đối tác</a>
                            <a href="{{ route('assets.index') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->routeIs('assets.index')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Tài sản</a>
                        </div>
                    </div>

                    <div x-data="{ open: @if(request()->is('inventory_requests*') || request()->is('inventory_receipts*') || request()->is('info_lookup*')) true @else false @endif }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-[11px] font-bold uppercase tracking-widest rounded-xl transition-all duration-200 group @if(request()->is('inventory_requests*') || request()->is('inventory_receipts*') || request()->is('info_lookup*')) text-[#E11D48] bg-gray-50 @else text-gray-500 hover:bg-gray-100 hover:text-gray-900 @endif">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 flex-shrink-0 @if(request()->is('inventory_requests*') || request()->is('inventory_receipts*') || request()->is('info_lookup*')) text-[#E11D48] @else text-gray-400 group-hover:text-[#E11D48] @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <span x-show="sidebarOpen" class="ml-3">Quản lý kho vận</span>
                            </div>
                            <svg x-show="sidebarOpen" class="w-4 h-4 transform transition-transform duration-200" :class="open ? 'rotate-180 {{ (request()->is('inventory_requests*') || request()->is('inventory_receipts*') || request()->is('info_lookup*')) ? 'text-[#E11D48]' : 'text-gray-400' }}' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open && sidebarOpen" x-transition class="ml-9 mt-1 space-y-1">
                            <a href="{{ route('inventory_requests.index', ['type' => 'inbound']) }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('inventory_requests*') && request('type') == 'inbound') text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Yêu cầu nhập kho</a>
                            <a href="{{ route('inventory_receipts.index', ['type' => 'inbound']) }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('inventory_receipts*') && request('type') == 'inbound') text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Quản lý nhập kho</a>
                            <a href="{{ route('inventory_requests.index', ['type' => 'outbound']) }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('inventory_requests*') && request('type') == 'outbound') text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Yêu cầu xuất kho</a>
                            <a href="{{ route('inventory_receipts.index', ['type' => 'outbound']) }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('inventory_receipts*') && request('type') == 'outbound') text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Quản lý xuất kho</a>
                            <div class="my-1 border-t border-gray-100"></div>
                            <a href="{{ route('info_lookup.assets') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('info_lookup/assets*')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Tra cứu theo nhân sự</a>
                            <a href="{{ route('info_lookup.inventory') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('info_lookup/inventory*')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Tra cứu tồn kho</a>
                        </div>
                    </div>

                    <div x-data="{ open: @if(request()->is('business_requests*') && (request()->routeIs('business_requests.my_assets') || request()->is('business_requests*type=*'))) true @else false @endif }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group @if(request()->is('business_requests*') && (request()->routeIs('business_requests.my_assets') || request()->is('business_requests*type=*'))) text-[#E11D48] bg-gray-50 @else text-gray-500 hover:bg-gray-100 hover:text-gray-900 @endif">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 flex-shrink-0 @if(request()->is('business_requests*') && (request()->routeIs('business_requests.my_assets') || request()->is('business_requests*type=*'))) text-[#E11D48] @else text-gray-400 group-hover:text-[#E11D48] @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span x-show="sidebarOpen" class="ml-3">Yêu cầu nghiệp vụ</span>
                            </div>
                            <svg x-show="sidebarOpen" class="w-4 h-4 transform transition-transform duration-200" :class="open ? 'rotate-180 {{ (request()->is('business_requests*') && (request()->routeIs('business_requests.my_assets') || request()->is('business_requests*type=*'))) ? 'text-[#E11D48]' : 'text-gray-400' }}' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open && sidebarOpen" x-transition class="ml-9 mt-1 space-y-1">
                            <a href="{{ route('business_requests.my_assets') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->routeIs('business_requests.my_assets')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Tài sản của tôi</a>
                            <a href="{{ route('business_requests.index', ['type' => 'allocation']) }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('business_requests*') && request('type') == 'allocation') text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Yêu cầu cấp phát</a>
                            <a href="{{ route('business_requests.index', ['type' => 'repair']) }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('business_requests*') && request('type') == 'repair') text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Yêu cầu sửa chữa</a>
                            <a href="{{ route('business_requests.index', ['type' => 'recall']) }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('business_requests*') && request('type') == 'recall') text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Yêu cầu thu hồi</a>
                            <a href="{{ route('business_requests.index', ['type' => 'liquidation']) }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('business_requests*') && request('type') == 'liquidation') text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Yêu cầu thanh lý</a>
                        </div>
                    </div>

                    <div x-data="{ open: @if(request()->is('allocation_standards*') || (request()->is('business_requests*') && !request()->routeIs('business_requests.my_assets'))) true @else false @endif }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group @if(request()->is('allocation_standards*') || (request()->is('business_requests*') && !request()->routeIs('business_requests.my_assets'))) text-[#E11D48] bg-gray-50 @else text-gray-500 hover:bg-gray-100 hover:text-gray-900 @endif">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 flex-shrink-0 @if(request()->is('allocation_standards*') || (request()->is('business_requests*') && !request()->routeIs('business_requests.my_assets'))) text-[#E11D48] @else text-gray-400 group-hover:text-[#E11D48] @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <span x-show="sidebarOpen" class="ml-3">Quản lý tài sản</span>
                            </div>
                            <svg x-show="sidebarOpen" class="w-4 h-4 transform transition-transform duration-200" :class="open ? 'rotate-180 {{ (request()->is('allocation_standards*') || (request()->is('business_requests*') && !request()->routeIs('business_requests.my_assets'))) ? 'text-[#E11D48]' : 'text-gray-400' }}' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open && sidebarOpen" x-transition class="ml-9 mt-1 space-y-1">
                            <a href="{{ route('allocation_standards.index') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->routeIs('allocation_standards.*')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Định mức cấp phát</a>
                            <a href="{{ route('business_requests.index', ['type' => 'allocation']) }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('business_requests*') && request('type') == 'allocation') text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Cấp phát tài sản</a>
                            <a href="{{ route('handover_records.index') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('handover_records*')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Biên bản bàn giao</a>
                            <a href="{{ route('business_requests.index', ['type' => 'recall']) }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('business_requests*') && request('type') == 'recall') text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Thu hồi tài sản</a>
                            <a href="{{ route('business_requests.index', ['type' => 'repair']) }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('business_requests*') && request('type') == 'repair') text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Sửa chữa tài sản</a>
                            <a href="{{ route('business_requests.index', ['type' => 'liquidation']) }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('business_requests*') && request('type') == 'liquidation') text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Thanh lý tài sản</a>
                        </div>
                    </div>

                    <!-- 6. Báo cáo thống kê -->
                    <div x-data="{ open: {{ request()->is('reports*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->is('reports*') ? 'text-[#E11D48] bg-gray-50' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('reports*') ? 'text-[#E11D48]' : 'text-gray-400 group-hover:text-[#E11D48]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span x-show="sidebarOpen" class="ml-3">Báo cáo - Thống kê</span>
                            </div>
                            <svg x-show="sidebarOpen" class="w-4 h-4 transform transition-transform duration-200" :class="open ? 'rotate-180 {{ request()->is('reports*') ? 'text-[#E11D48]' : 'text-gray-400' }}' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open && sidebarOpen" x-transition class="ml-9 mt-1 space-y-1">
                            <a href="{{ route('reports.assets') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->routeIs('reports.assets')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Báo cáo tài sản</a>
                            <a href="{{ route('reports.scale') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->routeIs('reports.scale')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Báo cáo quy mô</a>
                            <a href="{{ route('reports.inventory') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->routeIs('reports.inventory')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Báo cáo tồn kho</a>
                            <a href="{{ route('reports.procurement') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->routeIs('reports.procurement')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Báo cáo mua sắm</a>
                            <a href="{{ route('reports.liquidation') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->routeIs('reports.liquidation')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Báo cáo thanh lý</a>
                        </div>
                    </div>

                    <!-- 7. Hệ thống -->
                    <div x-data="{ open: {{ (request()->is('settings*') || request()->routeIs('users.*') || request()->routeIs('activity_logs.*')) ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group {{ (request()->is('settings*') || request()->routeIs('users.*') || request()->routeIs('activity_logs.*')) ? 'text-[#E11D48] bg-gray-50' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 flex-shrink-0 {{ (request()->is('settings*') || request()->routeIs('users.*') || request()->routeIs('activity_logs.*')) ? 'text-[#E11D48]' : 'text-gray-400 group-hover:text-[#E11D48]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span x-show="sidebarOpen" class="ml-3">Hệ thống</span>
                            </div>
                            <svg x-show="sidebarOpen" class="w-4 h-4 transform transition-transform duration-200" :class="open ? 'rotate-180 {{ (request()->is('settings*') || request()->routeIs('users.*') || request()->routeIs('activity_logs.*')) ? 'text-[#E11D48]' : 'text-gray-400' }}' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open && sidebarOpen" x-transition class="ml-9 mt-1 space-y-1">
                            <a href="{{ route('settings.index') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->is('settings*')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Cấu hình hệ thống</a>
                            <a href="{{ route('users.index') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->routeIs('users.index*')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Người dùng</a>
                            <a href="{{ route('activity_logs.index') }}" class="block px-3 py-2 text-xs font-bold rounded-lg transition-colors @if(request()->routeIs('activity_logs.index')) text-[#E11D48] @else text-gray-500 hover:text-gray-900 hover:bg-gray-50 @endif">Lịch sử thao tác</a>
                        </div>
                    </div>
                </nav>

                <div class="p-4 border-t border-gray-100 flex-shrink-0">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-bold text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span x-show="sidebarOpen" class="uppercase tracking-widest text-[10px]">Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Topbar -->
                <header class="bg-white h-16 flex items-center px-6 justify-between border-b border-gray-200 sticky top-0 z-20 print:hidden">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 -ml-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <div class="ml-4 h-6 border-l border-gray-200"></div>
                        <h2 class="ml-4 text-[13px] font-bold text-gray-900 tracking-[0.2em] uppercase leading-none mt-0.5">Quản trị tài sản</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center px-3 py-1.5 rounded-lg border border-gray-100 bg-gray-50 shadow-sm">
                            <div class="text-right mr-3 hidden sm:block">
                                <p class="text-[11px] font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-[9px] text-gray-400 uppercase tracking-widest font-bold">Quản trị viên</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-[#E11D48] flex items-center justify-center text-white font-bold text-sm shadow-md shadow-[#E11D48]/20">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Header Slot -->
                @if (isset($header))
                    <div class="bg-white border-b px-8 py-5 shadow-sm print:hidden">
                        {{ $header }}
                    </div>
                @endif

                <!-- Page Content -->
                <main class="p-6 overflow-y-auto bg-gray-50 flex-1">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-lg ring-1 ring-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-sm rounded-r-lg ring-1 ring-red-200">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 shadow-sm rounded-r-lg ring-1 ring-red-100">
                            <ul class="list-disc list-inside text-sm font-bold">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
