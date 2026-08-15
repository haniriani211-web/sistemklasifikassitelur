@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Manajemen Pengguna Sistem')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Manajemen Pengguna Sistem</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola hak akses akun Administrasi dan Pekerja Kandang</p>
        </div>
        <button onclick="toggleModal('modalAddUser')" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center gap-1.5 shrink-0">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pengguna Baru</span>
        </button>
    </div>

    <!-- Users Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5">Nama Pengguna</th>
                        <th class="px-4 py-3.5">Email Akun</th>
                        <th class="px-4 py-3.5">Hak Akses Role</th>
                        <th class="px-4 py-3.5">Terdaftar Pada</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50/80 transition-all">
                            <td class="px-4 py-3.5 font-bold text-slate-900 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-amber-600">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span>{{ $user->name }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $user->email }}</td>
                            <td class="px-4 py-3.5">
                                @if($user->role === 'admin')
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase bg-amber-100 text-amber-900 border border-amber-200">
                                        <i class="fa-solid fa-user-shield me-1"></i> Administrasi
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-900 border border-emerald-200">
                                        <i class="fa-solid fa-helmet-safety me-1"></i> Pekerja Kandang
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-slate-500">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</td>
                            <td class="px-4 py-3.5 text-right">
                                @if($user->id !== Auth::id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-semibold transition-all">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] font-semibold text-slate-400 italic">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Add User -->
<div id="modalAddUser" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-extrabold text-slate-900 text-base">Tambah Pengguna Baru</h3>
            <button onclick="toggleModal('modalAddUser')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Nama Petugas / Admin"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Email</label>
                <input type="email" name="email" required placeholder="user@rajadesa.com"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase mb-1">Kata Sandi (Min. 6 Karakter)</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold">
            </div>

            <div>
                <label for="role" class="block text-xs font-bold text-slate-700 uppercase mb-1">Hak Akses Role</label>
                <select name="role" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800">
                    <option value="pekerja_kandang">Pekerja Kandang</option>
                    <option value="admin">Administrasi (Admin)</option>
                </select>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modalAddUser')" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-extrabold rounded-xl">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const el = document.getElementById(modalId);
        if(el) el.classList.toggle('hidden');
    }
</script>
@endsection
