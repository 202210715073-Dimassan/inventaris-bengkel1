<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mo Gerzz Inventory</title>
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
                            primary: '#6366F1',      // Modern Indigo
                            primaryHover: '#4F46E5', // Indigo 600
                            secondary: '#0ea5e9',    // Sky 500
                            teal: '#14b8a6',         // Teal 500
                            dark: '#0f172a',         // Slate 900
                            light: '#f8fafc',        // Slate 50
                            panel: '#ffffff',        // White
                            border: '#e2e8f0'        // Slate 200
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8fafc;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.03);
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-8px); }
            40%, 80% { transform: translateX(8px); }
        }
        .animate-shake {
            animation: shake 0.4s ease-in-out;
        }
        @keyframes breathe {
            0%, 100% { transform: scale(1); box-shadow: 0 10px 25px rgba(99,102,241,0.2); }
            50% { transform: scale(1.04); box-shadow: 0 15px 30px rgba(99,102,241,0.35); }
        }
        .animate-breathe {
            animation: breathe 3s ease-in-out infinite;
        }

        /* Fallback CSS if Tailwind CDN fails to load */
        .flex { display: flex !important; }
        .items-center { align-items: center !important; }
        .justify-center { justify-content: center !important; }
        .min-h-screen { min-height: 100vh !important; }
        .p-4 { padding: 1rem !important; }
        .w-full { width: 100% !important; }
        .max-w-md { max-width: 28rem !important; }
        .text-center { text-align: center !important; }
        .mb-8 { margin-bottom: 2rem !important; }
        .mb-4 { margin-bottom: 1rem !important; }
        .inline-flex { display: inline-flex !important; }
        .w-20 { width: 5rem !important; }
        .h-20 { height: 5rem !important; }
        .rounded-full { border-radius: 9999px !important; }
        .overflow-hidden { overflow: hidden !important; }
        .border-4 { border-width: 4px !important; }
        .border-white { border-color: #fff !important; }
        .object-cover { object-fit: cover !important; }
        .text-2xl { font-size: 1.5rem !important; font-weight: 800 !important; }
        .text-xs { font-size: 0.75rem !important; }
        .font-black { font-weight: 900 !important; }
        .tracking-tight { letter-spacing: -0.025em !important; }
        .text-slate-800 { color: #1e293b !important; }
        .text-slate-400 { color: #94a3b8 !important; }
        .text-slate-500 { color: #64748b !important; }
        .mt-1 { margin-top: 0.25rem !important; }
        .uppercase { text-transform: uppercase !important; }
        .font-bold { font-weight: 700 !important; }
        .rounded-3xl { border-radius: 1.5rem !important; }
        .p-8 { padding: 2rem !important; }
        .space-y-5 > * + * { margin-top: 1.25rem !important; }
        .block { display: block !important; }
        .mb-1\.5 { margin-bottom: 0.375rem !important; }
        .relative { position: relative !important; }
        .absolute { position: absolute !important; }
        .inset-y-0 { top: 0 !important; bottom: 0 !important; }
        .left-0 { left: 0 !important; }
        .pl-3\.5 { padding-left: 0.875rem !important; }
        .pointer-events-none { pointer-events: none !important; }
        .h-4 { height: 1rem !important; }
        .w-4 { width: 1rem !important; }
        .pl-10 { padding-left: 2.5rem !important; }
        .px-4 { padding-left: 1rem !important; padding-right: 1rem !important; }
        .py-3 { padding-top: 0.75rem !important; padding-bottom: 0.75rem !important; }
        .bg-white { background-color: #fff !important; }
        .border-slate-200 { border: 1px solid #e2e8f0 !important; }
        .rounded-xl { border-radius: 0.75rem !important; }
        .justify-between { justify-content: space-between !important; }
        .shadow-md { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06) !important; }
        .bg-brand-primary { background-color: #6366F1 !important; }
        .text-white { color: #fff !important; }
        .text-sm { font-size: 0.875rem !important; }
        .text-slate-650 { color: #dc2626 !important; }
        .bg-red-50 { background-color: #fef2f2 !important; }
        .border-red-100 { border: 1px solid #fee2e2 !important; }
        .text-red-650 { color: #b91c1c !important; }
        .p-3\.5 { padding: 0.875rem !important; }
        .list-disc { list-style-type: disc !important; }
        .list-inside { list-style-position: inside !important; }
        .space-y-0.5 > * + * { margin-top: 0.125rem !important; }
        .cursor-pointer { cursor: pointer !important; }
        .select-none { user-select: none !important; }
        .ml-2 { margin-left: 0.5rem !important; }
        .pt-1 { padding-top: 0.25rem !important; }
        .pt-2 { padding-top: 0.5rem !important; }
        .mt-8 { margin-top: 2rem !important; }
        .mb-2 { margin-bottom: 0.5rem !important; }
        .mb-6 { margin-bottom: 1.5rem !important; }
        
        .fixed { position: fixed !important; }
        .top-0 { top: 0 !important; }
        .h-full { height: 100% !important; }
        .-z-10 { z-index: -10 !important; }
        .top-\[-10\%\] { top: -10% !important; }
        .left-\[-10\%\] { left: -10% !important; }
        .w-\[40\%\] { width: 40% !important; }
        .h-\[40\%\] { height: 40% !important; }
        .bg-brand-primary\/10 { background-color: rgba(99, 102, 241, 0.1) !important; }
        .bg-brand-secondary\/10 { background-color: rgba(14, 165, 233, 0.1) !important; }
        .mix-blend-multiply { mix-blend-mode: multiply !important; }
        .filter { filter: blur(120px) !important; }
        .opacity-20 { opacity: 0.2 !important; }
        .opacity-15 { opacity: 0.15 !important; }
        .bottom-\[-10\%\] { bottom: -10% !important; }
        .right-\[-10\%\] { right: -10% !important; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 text-slate-600 font-sans antialiased relative overflow-hidden">

    <!-- Tailwind Load Error Alert -->
    <div id="tailwind-warning" style="display: none; position: fixed; top: 0; left: 0; right: 0; background-color: #fffbeb; border-bottom: 1px solid #fef3c7; color: #b45309; padding: 0.75rem 1rem; text-align: center; font-size: 0.875rem; z-index: 9999; font-weight: 500; font-family: system-ui, -apple-system, sans-serif; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        ⚠️ <strong>Peringatan Sistem:</strong> Halaman web gagal memuat script styling online (Tailwind CDN). Tampilan disederhanakan secara offline agar Anda tetap dapat login.
    </div>

    <!-- Decorative background elements -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-100 rounded-full mix-blend-multiply filter blur-[120px] opacity-20"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-sky-100 rounded-full mix-blend-multiply filter blur-[120px] opacity-15"></div>
    </div>

    <div class="w-full max-w-md">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full overflow-hidden mb-4 border-4 border-white animate-breathe">
                <img src="{{ asset('logo.jpg') }}" alt="Mo Gerzz Logo" class="w-full h-full object-cover">
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800">Mo Gerzz</h1>
            <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest font-bold">Inventory System</p>
        </div>

        <!-- Login Form -->
        <div class="glass-panel rounded-3xl p-8 {{ $errors->any() ? 'animate-shake' : '' }}">
            <h2 class="text-lg font-bold text-slate-800 mb-2">Selamat Datang</h2>
            <p class="text-xs text-slate-500 mb-6">Silakan masuk dengan akun Admin Bengkel.</p>

            @if ($errors->any())
                <div class="mb-5 bg-red-50 border border-red-100 text-red-650 p-3.5 rounded-xl text-xs font-medium">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Email Admin</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required class="block w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all sm:text-sm" placeholder="admin@mogerzz.com" value="{{ old('email') }}">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-400">Password</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all sm:text-sm" placeholder="••••••••">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded text-brand-primary focus:ring-brand-primary border-slate-200 cursor-pointer">
                        <label for="remember" class="ml-2 block text-xs text-slate-500 font-semibold cursor-pointer select-none">
                            Ingat saya di perangkat ini
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-brand-primary hover:bg-brand-primaryHover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-brand-primary transition-all duration-200 transform hover:-translate-y-0.5 active:scale-[0.98]">
                        Masuk Dashboard
                    </button>
                </div>
            </form>
        </div>
        
        <p class="text-center text-[10px] text-slate-400 mt-8 font-semibold tracking-wider uppercase">
            &copy; {{ date('Y') }} Mo Gerzz Vespa. All rights reserved.
        </p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof tailwind === 'undefined') {
                const warnBanner = document.getElementById('tailwind-warning');
                if (warnBanner) {
                    warnBanner.style.display = 'block';
                }
                document.body.style.paddingTop = '4rem';
            }
        });
    </script>
</body>
</html>
