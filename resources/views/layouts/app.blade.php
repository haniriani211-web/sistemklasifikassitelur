<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Klasifikasi Telur C4.5') - Peternakan Rajadesa</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        amber: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        },
                        cream: {
                            50: '#fffdf7',
                            100: '#fffaed',
                            200: '#fff4d6',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #fcfbfa;
            color: #334155;
        }

        /* Cute Egg Animations */
        @keyframes eggWiggle {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-8deg); }
            75% { transform: rotate(8deg); }
        }

        @keyframes eggFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        .egg-wiggle:hover {
            animation: eggWiggle 0.6s ease-in-out infinite;
        }

        .egg-float {
            animation: eggFloat 3s ease-in-out infinite;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #fffbeb;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #fde68a;
            border-radius: 99px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #fcd34d;
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased min-h-screen flex flex-col bg-amber-50/30 text-slate-800">

    <div class="flex min-h-screen">
        <!-- Sidebar - Fresh Light Mode Theme -->
        <aside class="w-64 bg-white border-r border-amber-200/70 text-slate-800 flex flex-col shrink-0 shadow-sm z-30 transition-all duration-300">
            <!-- Brand Header -->
            <div class="p-5 border-b border-amber-100 flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-amber-400 via-amber-300 to-yellow-200 flex items-center justify-center text-amber-900 font-extrabold text-2xl shadow-md shadow-amber-300/40 border border-amber-200/80 egg-wiggle cursor-pointer relative">
                    <i class="fa-solid fa-egg text-amber-900"></i>
                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                    </span>
                </div>
                <div>
                    <h1 class="font-black text-lg text-slate-900 leading-tight flex items-center gap-1">
                        C4.5 Rajadesa 🥚
                    </h1>
                    <p class="text-[11px] text-amber-700 font-extrabold">Klasifikasi Kualitas Telur</p>
                </div>
            </div>

            <!-- User Profile Box -->
            <div class="p-3.5 mx-3 my-3 rounded-2xl bg-amber-50/80 border border-amber-200/70 flex items-center gap-3 shadow-xs">
                <div class="w-10 h-10 rounded-xl bg-amber-400 text-amber-950 flex items-center justify-center font-black text-base shadow-sm border border-amber-300">
                    <i class="fa-solid fa-face-smile text-amber-900"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold rounded-md {{ Auth::user()->isAdmin() ? 'bg-amber-200 text-amber-900 border border-amber-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-300' }}">
                        <i class="fa-solid {{ Auth::user()->isAdmin() ? 'fa-user-shield' : 'fa-feather' }}"></i>
                        {{ Auth::user()->isAdmin() ? 'Administrasi' : 'Pekerja Kandang' }}
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-2 space-y-1.5 overflow-y-auto custom-scrollbar">
                <div class="px-3 py-1 text-[10px] font-extrabold text-amber-700/80 uppercase tracking-wider">Menu Utama</div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('dashboard') || request()->is('/') ? 'bg-amber-400 text-amber-950 shadow-md shadow-amber-400/30' : 'text-slate-700 hover:bg-amber-100/60 hover:text-amber-900' }}">
                    <i class="fa-solid fa-house-chimney-window w-4 text-center text-sm"></i>
                    <span>Dashboard Utama</span>
                </a>

                <a href="{{ route('klasifikasi.create') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('klasifikasi/create') ? 'bg-amber-400 text-amber-950 shadow-md shadow-amber-400/30' : 'text-slate-700 hover:bg-amber-100/60 hover:text-amber-900' }}">
                    <i class="fa-solid fa-egg text-amber-600 w-4 text-center text-sm egg-wiggle"></i>
                    <span>Input Panen Telur</span>
                </a>

                <a href="{{ route('klasifikasi.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('klasifikasi') && !request()->is('klasifikasi/create') ? 'bg-amber-400 text-amber-950 shadow-md shadow-amber-400/30' : 'text-slate-700 hover:bg-amber-100/60 hover:text-amber-900' }}">
                    <i class="fa-solid fa-clipboard-list w-4 text-center text-sm"></i>
                    <span>Riwayat Hasil Klasifikasi</span>
                </a>

                @if(Auth::user()->isAdmin())
                    <div class="pt-4 px-3 py-1 text-[10px] font-extrabold text-amber-700/80 uppercase tracking-wider">Analisis C4.5 & Admin</div>

                    <a href="{{ route('c45.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('c45*') ? 'bg-amber-400 text-amber-950 shadow-md shadow-amber-400/30' : 'text-slate-700 hover:bg-amber-100/60 hover:text-amber-900' }}">
                        <i class="fa-solid fa-diagram-project w-4 text-center text-sm"></i>
                        <span>Algoritma C4.5 & Tree</span>
                    </a>

                    <a href="{{ route('dataset.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('dataset*') ? 'bg-amber-400 text-amber-950 shadow-md shadow-amber-400/30' : 'text-slate-700 hover:bg-amber-100/60 hover:text-amber-900' }}">
                        <i class="fa-solid fa-database w-4 text-center text-sm"></i>
                        <span>Dataset Latih</span>
                    </a>

                    <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('laporan*') ? 'bg-amber-400 text-amber-950 shadow-md shadow-amber-400/30' : 'text-slate-700 hover:bg-amber-100/60 hover:text-amber-900' }}">
                        <i class="fa-solid fa-print w-4 text-center text-sm"></i>
                        <span>Rekap & Laporan</span>
                    </a>

                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('users*') ? 'bg-amber-400 text-amber-950 shadow-md shadow-amber-400/30' : 'text-slate-700 hover:bg-amber-100/60 hover:text-amber-900' }}">
                        <i class="fa-solid fa-users-gear w-4 text-center text-sm"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                @endif
            </nav>

            <!-- Logout Footer -->
            <div class="p-3 border-t border-amber-100 bg-amber-50/40">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-all">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Navbar - Clean Light Glass -->
            <header class="h-16 border-b border-amber-200/60 glass-header sticky top-0 z-20 flex items-center justify-between px-6">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-extrabold text-sm border border-amber-200">
                        <i class="fa-solid fa-egg text-amber-600 egg-float"></i>
                    </span>
                    <h2 class="text-base font-extrabold text-slate-900">@yield('page-title', 'Dashboard')</h2>
                </div>
                
                <div class="flex items-center gap-3">
                    <span class="text-xs font-extrabold px-3 py-1.5 bg-amber-100/80 text-amber-900 rounded-full border border-amber-300 shadow-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-egg text-amber-600"></i>
                        Peternakan Rajadesa
                    </span>
                    <div class="text-xs text-slate-500 font-bold bg-white px-3 py-1.5 rounded-full border border-slate-200 shadow-xs">
                        <i class="fa-regular fa-calendar-check text-amber-600 me-1"></i> {{ date('d M Y') }}
                    </div>
                </div>
            </header>

            <!-- Main Scrollable Body -->
            <main class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-900 flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 font-bold text-base shadow-xs">
                                🥚
                            </div>
                            <p class="text-xs font-bold">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-300 text-rose-900 flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 font-bold text-base shadow-xs">
                                ⚠️
                            </div>
                            <p class="text-xs font-bold">{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="py-3.5 px-6 border-t border-amber-200/60 text-xs text-slate-500 flex flex-col sm:flex-row justify-between items-center bg-white gap-2">
                <p class="font-semibold text-slate-600">&copy; {{ date('Y') }} Sistem Klasifikasi C4.5 Telur 🥚 - Peternakan Ayam Petelur Rajadesa</p>
                <p class="text-slate-400 font-medium">Klasifikasi Fisik: Berat, Diameter, Kondisi & Warna Cangkang</p>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
