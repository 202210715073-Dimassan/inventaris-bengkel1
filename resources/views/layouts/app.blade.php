<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Mo Gerzz Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            primary: '#6366F1',
                            primaryHover: '#4F46E5',
                            secondary: '#0ea5e9',
                            teal: '#14b8a6',
                            dark: '#0f172a',
                            light: '#f8fafc',
                            panel: '#ffffff',
                            border: '#e2e8f0'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* ========== BASE ========== */
        body {
            background-color: #f8fafc;
        }

        /* ========== SIDEBAR CORE ========== */
        #sidebar {
            width: 16rem;
            min-width: 16rem;
            flex-shrink: 0;
            transition: width 0.3s cubic-bezier(0.4,0,0.2,1),
                        min-width 0.3s cubic-bezier(0.4,0,0.2,1),
                        transform 0.3s cubic-bezier(0.4,0,0.2,1);
            z-index: 40;
        }

        /* Desktop collapsed sidebar */
        #sidebar.collapsed {
            width: 4.5rem;
            min-width: 4.5rem;
        }
        #sidebar.collapsed .sidebar-label,
        #sidebar.collapsed .logo-text,
        #sidebar.collapsed .user-detail,
        #sidebar.collapsed .nav-badge {
            display: none !important;
        }
        #sidebar.collapsed .sidebar-link,
        #sidebar.collapsed .sidebar-active {
            justify-content: center !important;
            padding-left: 0 !important;
            margin: 0 0.5rem !important;
        }
        #sidebar.collapsed .nav-icon {
            margin-right: 0 !important;
        }
        #sidebar.collapsed .logo-area {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        #sidebar.collapsed .collapse-chevron {
            transform: rotate(180deg);
        }
        #sidebar.collapsed .user-avatar-area {
            justify-content: center !important;
        }
        #sidebar.collapsed .collapse-toggle-btn {
            transform: translateX(50%);
        }

        /* ========== MOBILE SIDEBAR ========== */
        @media (max-width: 1023px) {
            #sidebar {
                position: fixed !important;
                top: 0;
                left: 0;
                bottom: 0;
                height: 100vh !important;
                width: 16rem !important;
                min-width: 16rem !important;
                transform: translateX(-100%);
                box-shadow: none;
                z-index: 50;
            }
            #sidebar.mobile-open {
                transform: translateX(0);
                box-shadow: 8px 0 40px rgba(15,23,42,0.15);
            }
        }

        /* ========== MOBILE OVERLAY ========== */
        #mobile-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.45);
            backdrop-filter: blur(3px);
            z-index: 49;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        #mobile-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        /* ========== SIDEBAR ACTIVE/LINK ========== */
        .sidebar-active {
            background: linear-gradient(135deg, rgba(99,102,241,0.10), rgba(99,102,241,0.04));
            color: #6366F1;
            border-radius: 0.75rem;
            margin: 0 0.75rem;
            padding-left: 1rem !important;
            font-weight: 600;
            position: relative;
        }
        .sidebar-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: #6366F1;
            border-radius: 0 4px 4px 0;
        }
        .sidebar-link {
            margin: 0 0.75rem;
            padding-left: 1rem !important;
            border-radius: 0.75rem;
            transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
        }
        .sidebar-link:hover {
            background: rgba(0,0,0,0.03);
            transform: translateX(2px);
        }



        /* ========== CARDS ========== */
        .card-premium {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.01);
            transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
        }
        .card-premium:hover {
            box-shadow: 0 10px 25px rgba(99,102,241,0.05), 0 4px 10px rgba(99,102,241,0.02);
            transform: translateY(-2px);
            border-color: #cbd5e1;
        }
        .card-static {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.01);
        }

        /* ========== ANIMATIONS ========== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); }
            to { transform: translateX(100%); }
        }
        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes toastIn {
            from { opacity: 0; transform: translateX(100px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes toastOut {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100px); }
        }
        @keyframes progressShrink {
            from { width: 100%; }
            to { width: 0%; }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }
        @keyframes breathe {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.03); }
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes ripple {
            0% { transform: scale(0); opacity: 0.4; }
            100% { transform: scale(2.5); opacity: 0; }
        }

        .animate-fade-in { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
        .animate-fade-in-1 { animation: fadeInUp 0.5s ease-out 0.1s forwards; opacity: 0; }
        .animate-fade-in-2 { animation: fadeInUp 0.5s ease-out 0.2s forwards; opacity: 0; }
        .animate-fade-in-3 { animation: fadeInUp 0.5s ease-out 0.3s forwards; opacity: 0; }
        .animate-slide-in { animation: slideInDown 0.3s ease-out forwards; }
        .animate-shake { animation: shake 0.5s ease-in-out; }
        .animate-breathe { animation: breathe 3s ease-in-out infinite; }
        .animate-spin-slow { animation: spin 1s linear infinite; }

        .skeleton {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 0.5rem;
        }

        /* ========== BUTTONS ========== */
        .btn-primary {
            background: #6366F1;
            color: #fff;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.15);
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            background: #4F46E5;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.25);
        }
        .btn-primary:active { transform: scale(0.98); }

        .btn-secondary {
            background: #fff;
            color: #475569;
            font-weight: 500;
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
            position: relative;
            overflow: hidden;
        }
        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        .btn-secondary:active { transform: scale(0.98); }

        /* Ripple effect for buttons */
        .ripple-effect {
            position: absolute;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.4);
            margin-top: -30px;
            margin-left: -30px;
            animation: ripple 0.6s linear;
            pointer-events: none;
        }

        /* ========== TABLE ========== */
        .table-row-hover {
            transition: all 0.15s ease;
        }
        .table-row-hover:hover {
            background: rgba(248,250,252,0.8);
            box-shadow: inset 3px 0 0 #6366F1;
        }

        /* ========== TOAST CONTAINER ========== */
        .toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none;
        }
        .toast-item {
            pointer-events: auto;
            animation: toastIn 0.4s ease-out forwards;
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            min-width: 280px;
            max-width: 400px;
            position: relative;
            overflow: hidden;
        }
        @media (max-width: 640px) {
            .toast-container { left: 1rem; right: 1rem; top: 1rem; }
            .toast-item { min-width: unset; max-width: 100%; }
        }
        .toast-item.closing {
            animation: toastOut 0.3s ease-in forwards;
        }
        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            animation: progressShrink 5s linear forwards;
        }

        /* ========== DRAWER ========== */
        .drawer-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.3);
            backdrop-filter: blur(4px);
            z-index: 50;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .drawer-overlay.active { opacity: 1; pointer-events: auto; }
        .drawer-panel {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            max-width: 28rem;
            background: #fff;
            z-index: 51;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
            box-shadow: -10px 0 30px rgba(0,0,0,0.08);
            overflow-y: auto;
        }
        /* Full width drawer on small screens */
        @media (max-width: 640px) {
            .drawer-panel {
                max-width: 100%;
            }
        }
        .drawer-panel.active { transform: translateX(0); }

        /* ========== HAMBURGER BUTTON ========== */
        .hamburger-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.625rem;
            border: 1px solid #e2e8f0;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .hamburger-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }
        @media (max-width: 1023px) {
            .hamburger-btn { display: flex; }
        }

        /* ========== SCROLLBAR ========== */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ========== MISC ========== */
        .dot-indicator {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }

        /* Tooltip for collapsed sidebar icons */
        #sidebar.collapsed .nav-tooltip {
            display: flex;
        }
        .nav-tooltip {
            display: none;
            position: absolute;
            left: calc(100% + 0.75rem);
            top: 50%;
            transform: translateY(-50%);
            background: #1e293b;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.35rem 0.75rem;
            border-radius: 0.5rem;
            white-space: nowrap;
            pointer-events: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 100;
        }
        .nav-tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: #1e293b;
        }

        /* ========== PAGE CONTENT PADDING RESPONSIVE ========== */
        .page-content {
            padding: 2rem;
        }
        @media (max-width: 768px) {
            .page-content { padding: 1.25rem 1rem; }
        }
        @media (max-width: 480px) {
            .page-content { padding: 1rem 0.75rem; }
        }
    </style>
</head>
<body class="text-slate-600 font-sans antialiased flex h-screen overflow-hidden">

    <!-- Tailwind Load Error Alert -->
    <div id="tailwind-warning" style="display: none; position: fixed; top: 0; left: 0; right: 0; background-color: #fffbeb; border-bottom: 1px solid #fef3c7; color: #b45309; padding: 0.75rem 1rem; text-align: center; font-size: 0.875rem; z-index: 9999; font-weight: 500; font-family: system-ui, -apple-system, sans-serif; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        ⚠️ <strong>Peringatan Sistem:</strong> Halaman web gagal memuat script styling online (Tailwind CDN). Tampilan disederhanakan secara offline agar Anda tetap dapat mengoperasikan dashboard.
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container"></div>

    <!-- Mobile Sidebar Overlay -->
    <div id="mobile-overlay" onclick="closeMobileSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="bg-white border-r border-brand-border flex flex-col h-full">

        <!-- Logo -->
        <div onclick="toggleSidebarCollapse()" class="h-16 flex items-center px-6 border-b border-brand-border logo-area flex-shrink-0 cursor-pointer hover:bg-slate-50 transition-colors" title="Toggle Sidebar">
            <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-brand-primary shadow-lg shadow-brand-primary/20 animate-breathe flex-shrink-0">
                <img src="{{ asset('logo.jpg') }}" alt="Mo Gerzz Logo" class="w-full h-full object-cover">
            </div>
            <div class="ml-3 logo-text overflow-hidden">
                <span class="text-slate-800 font-bold text-lg tracking-wide whitespace-nowrap">Mo Gerzz</span>
                <span class="block text-[10px] text-slate-400 -mt-1 tracking-widest uppercase whitespace-nowrap">Inventory</span>
            </div>
        </div>

        <!-- Nav Links -->
        <nav class="flex-1 py-6 space-y-1 overflow-y-auto overflow-x-hidden">
            <!-- Dashboard (Admin & Owner) -->
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'sidebar-active' : 'sidebar-link text-slate-500 hover:text-slate-800' }} relative flex items-center px-6 py-2.5 text-sm font-medium group">
                <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="sidebar-label">Dashboard</span>
                <span class="nav-tooltip">Dashboard</span>
            </a>

            @if(Auth::check() && Auth::user()->isAdmin())
            <!-- Master Barang (Khusus Admin) -->
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'sidebar-active' : 'sidebar-link text-slate-500 hover:text-slate-800' }} relative flex items-center px-6 py-2.5 text-sm font-medium group">
                <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <span class="sidebar-label">Master Barang</span>
                <span class="nav-tooltip">Master Barang</span>
            </a>
            <!-- Transaksi (Khusus Admin) -->
            <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.*') ? 'sidebar-active' : 'sidebar-link text-slate-500 hover:text-slate-800' }} relative flex items-center px-6 py-2.5 text-sm font-medium group">
                <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <span class="sidebar-label">Transaksi</span>
                <span class="nav-tooltip">Transaksi</span>
            </a>
            @endif

            <!-- Laporan Restock (Admin & Owner) -->
            <a href="{{ route('reports.restock') }}" class="{{ request()->routeIs('reports.*') ? 'sidebar-active' : 'sidebar-link text-slate-500 hover:text-slate-800' }} relative flex items-center px-6 py-2.5 text-sm font-medium group">
                <svg class="nav-icon w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span class="sidebar-label">Laporan Restock</span>
                @if(isset($lowStockCount) && $lowStockCount > 0)
                <span class="nav-badge ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $lowStockCount }}</span>
                @endif
                <span class="nav-tooltip">
                    Laporan Restock
                    @if(isset($lowStockCount) && $lowStockCount > 0)
                    <span class="ml-1 bg-red-500 text-white text-[10px] font-bold px-1 py-0.5 rounded-full">{{ $lowStockCount }}</span>
                    @endif
                </span>
            </a>
        </nav>

        <!-- User Info -->
        <div class="p-4 border-t border-brand-border flex-shrink-0">
            <div class="user-avatar-area flex items-center">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-primary to-indigo-600 flex items-center justify-center text-white font-bold text-sm uppercase shadow-sm flex-shrink-0">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="ml-3 flex-1 min-w-0 user-detail">
                    <p class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->name ?? 'Pengguna' }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="inline-block px-1.5 py-0.2 text-[10px] font-bold rounded uppercase tracking-wider {{ (Auth::user()->role ?? '') === 'owner' ? 'bg-amber-100 text-amber-700' : 'bg-indigo-100 text-indigo-700' }}">
                            {{ (Auth::user()->role ?? '') === 'owner' ? 'Owner' : 'Admin' }}
                        </span>
                        <span class="text-slate-300">•</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-slate-400 hover:text-brand-primary transition-colors duration-200">Logout →</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main id="main-content" class="flex-1 flex flex-col h-screen overflow-hidden relative min-w-0">
        <!-- Background Effects -->
        <div class="absolute top-[-10%] right-[-5%] w-[40%] h-[40%] bg-indigo-100/40 rounded-full mix-blend-multiply filter blur-[120px] opacity-20 pointer-events-none"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[30%] h-[30%] bg-teal-100/10 rounded-full mix-blend-multiply filter blur-[120px] opacity-10 pointer-events-none"></div>

        <!-- Header / Topbar -->
        <header class="h-16 flex items-center justify-between px-6 border-b border-brand-border/50 bg-slate-50/80 backdrop-blur-md z-10 flex-shrink-0 gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <!-- Hamburger (mobile) -->
                <button onclick="toggleMobileSidebar()" class="hamburger-btn" aria-label="Open menu">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <!-- Page Title -->
                <h1 class="text-lg font-semibold text-slate-800 truncate">@yield('title')</h1>
            </div>

            <div class="flex items-center space-x-2 flex-shrink-0">
                <!-- Notification Bell -->
                <div class="relative group">
                    <a href="{{ route('reports.restock') }}" class="flex items-center justify-center w-10 h-10 rounded-xl hover:bg-slate-100 transition-all duration-200" title="Laporan Restock">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        @if(isset($lowStockCount) && $lowStockCount > 0)
                            <span class="absolute top-1.5 right-1.5 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white animate-pulse"></span>
                        @endif
                    </a>
                </div>

                <!-- User avatar (mobile) -->
                <div class="lg:hidden">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-primary to-indigo-600 flex items-center justify-center text-white font-bold text-sm uppercase shadow-sm cursor-pointer" title="{{ Auth::user()->name ?? 'Admin' }}">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Scrollable -->
        <div class="flex-1 overflow-y-auto page-content z-10">
            @yield('content')
        </div>
    </main>

    <!-- Toast Notification Script -->
    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const isSuccess = type === 'success';
            toast.className = 'toast-item ' + (isSuccess ? 'bg-white border border-emerald-100' : 'bg-white border border-red-100');
            toast.innerHTML = `
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center ${isSuccess ? 'bg-emerald-50' : 'bg-red-50'}">
                    ${isSuccess
                        ? '<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                        : '<svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                    }
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold ${isSuccess ? 'text-emerald-800' : 'text-red-800'}">${isSuccess ? 'Berhasil!' : 'Error!'}</p>
                    <p class="text-xs text-slate-500 mt-0.5 break-words">${message}</p>
                </div>
                <button onclick="dismissToast(this.parentElement)" class="flex-shrink-0 text-slate-400 hover:text-slate-650 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="toast-progress ${isSuccess ? 'bg-emerald-400' : 'bg-red-400'}"></div>
            `;
            container.appendChild(toast);
            setTimeout(() => dismissToast(toast), 5000);
        }
        function dismissToast(el) {
            if (!el || el.classList.contains('closing')) return;
            el.classList.add('closing');
            setTimeout(() => el.remove(), 300);
        }
    </script>

    @if(session('success'))
    <script>document.addEventListener('DOMContentLoaded', () => showToast("{{ session('success') }}", 'success'));</script>
    @endif
    @if(session('error'))
    <script>document.addEventListener('DOMContentLoaded', () => showToast("{{ session('error') }}", 'error'));</script>
    @endif
    @if($errors->any())
        @foreach($errors->all() as $error)
        <script>document.addEventListener('DOMContentLoaded', () => showToast("{{ $error }}", 'error'));</script>
        @endforeach
    @endif

    @stack('scripts')

    <script>
        // ============================================================
        // SIDEBAR RESPONSIVE & INTERACTIVE SYSTEM
        // ============================================================

        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');

        // --- Desktop: Collapse / Expand ---
        function toggleSidebarCollapse() {
            const isCollapsed = sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed ? '1' : '0');
        }

        // --- Mobile: Open / Close ---
        function toggleMobileSidebar() {
            const isOpen = sidebar.classList.contains('mobile-open');
            if (isOpen) {
                closeMobileSidebar();
            } else {
                openMobileSidebar();
            }
        }
        function openMobileSidebar() {
            sidebar.classList.add('mobile-open');
            mobileOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeMobileSidebar() {
            sidebar.classList.remove('mobile-open');
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        // --- Restore desktop collapsed state from localStorage ---
        document.addEventListener('DOMContentLoaded', function() {
            const isMobile = window.innerWidth < 1024;
            if (!isMobile && localStorage.getItem('sidebarCollapsed') === '1') {
                sidebar.classList.add('collapsed');
            }
        });

        // --- ESC key: close overlays/drawers/modals ---
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileSidebar();
                if (typeof closeDrawer === 'function') closeDrawer();
                if (typeof closeDeleteModal === 'function') closeDeleteModal();
            }
        });

        // --- Close mobile sidebar on resize to desktop ---
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeMobileSidebar();
            }
        });

        // --- Ripple effect for buttons ---
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-primary, .btn-secondary');
            if (!btn) return;
            const rect = btn.getBoundingClientRect();
            const ripple = document.createElement('span');
            ripple.className = 'ripple-effect';
            ripple.style.left = (e.clientX - rect.left) + 'px';
            ripple.style.top = (e.clientY - rect.top) + 'px';
            btn.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });

        // --- Tailwind CDN check ---
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof tailwind === 'undefined') {
                const warnBanner = document.getElementById('tailwind-warning');
                if (warnBanner) {
                    warnBanner.style.display = 'block';
                }
            }
        });
    </script>
</body>
</html>
