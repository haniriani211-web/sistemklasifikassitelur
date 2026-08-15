<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Klasifikasi Telur C4.5 Peternakan Rajadesa</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes eggFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(4deg); }
        }
        .egg-float {
            animation: eggFloat 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-amber-50 via-yellow-50/50 to-orange-50 font-sans text-slate-800 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Decorative Glow Circles -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-amber-200/60 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-orange-200/50 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md bg-white/95 backdrop-blur-xl border border-amber-200/80 rounded-3xl p-8 shadow-xl shadow-amber-900/5 z-10">
        <!-- Cute Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-amber-400 via-amber-300 to-yellow-200 mx-auto flex items-center justify-center text-amber-950 font-black text-4xl shadow-lg shadow-amber-400/30 border border-amber-200 mb-4 egg-float relative">
                <i class="fa-solid fa-egg text-amber-900"></i>
                <span class="absolute -top-1 -right-1 text-base">✨</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center justify-center gap-1.5">
                C4.5 Rajadesa 🥚
            </h1>
            <p class="text-xs text-amber-800 font-extrabold mt-1">Klasifikasi Kelayakan Kualitas Telur Ayam</p>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed font-medium">Peternakan Ayam Petelur Rajadesa berdasarkan Karakteristik Fisik</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1">
                @foreach($errors->all() as $error)
                    <p><i class="fa-solid fa-triangle-exclamation me-1 text-rose-600"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 p-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 text-xs font-semibold">
                <i class="fa-solid fa-circle-info me-1 text-sky-600"></i> {{ session('info') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Email Akun</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-envelope text-sm text-amber-600"></i>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-10 pr-4 py-3 bg-amber-50/30 border border-slate-300 rounded-2xl text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all"
                        placeholder="admin@rajadesa.com">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-sm text-amber-600"></i>
                    </div>
                    <input type="password" name="password" id="password" required
                        class="w-full pl-10 pr-4 py-3 bg-amber-50/30 border border-slate-300 rounded-2xl text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center text-slate-600 font-semibold cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-amber-500 focus:ring-amber-400">
                    <span class="ms-2">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-amber-950 font-black rounded-2xl shadow-lg shadow-amber-400/30 transition-all text-sm flex items-center justify-center gap-2">
                <span>Masuk ke Sistem</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <!-- Quick Demo Login Buttons -->
        <div class="mt-8 pt-6 border-t border-slate-100">
            <p class="text-[11px] font-extrabold text-amber-800 text-center uppercase tracking-wider mb-3">⚡ Quick Demo Login</p>
            <div class="grid grid-cols-2 gap-2.5">
                <button type="button" onclick="fillAccount('admin@rajadesa.com', 'password')"
                    class="p-3 bg-amber-50/80 hover:bg-amber-100/80 border border-amber-200 rounded-2xl text-left transition-all group">
                    <span class="block text-xs font-black text-amber-900 group-hover:text-amber-950 flex items-center gap-1">
                        <i class="fa-solid fa-user-shield text-amber-600"></i> Administrasi
                    </span>
                    <span class="block text-[10px] text-slate-500 font-medium truncate mt-0.5">admin@rajadesa.com</span>
                </button>
                <button type="button" onclick="fillAccount('pekerja@rajadesa.com', 'password')"
                    class="p-3 bg-emerald-50/80 hover:bg-emerald-100/80 border border-emerald-200 rounded-2xl text-left transition-all group">
                    <span class="block text-xs font-black text-emerald-900 group-hover:text-emerald-950 flex items-center gap-1">
                        <i class="fa-solid fa-feather text-emerald-600"></i> Pekerja Kandang
                    </span>
                    <span class="block text-[10px] text-slate-500 font-medium truncate mt-0.5">pekerja@rajadesa.com</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        function fillAccount(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
