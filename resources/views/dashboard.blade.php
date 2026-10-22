<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span>{{ __('Dashboard') }}</span>
                @if (auth()->user()->role === 'admin')
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700 font-semibold uppercase tracking-wider">Admin</span>
                @else
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 font-semibold uppercase tracking-wider">Member</span>
                @endif
            </h2>
            <span class="text-sm text-gray-500 hidden sm:inline">NihonLink J-Map Platform</span>
        </div>
    </x-slot>

    @php
        $totalPlaces = $stats['total_places'] ?? \App\Models\Place::count();
        $pendingPlaces = $stats['pending_places'] ?? \App\Models\Place::where('status', 'pending')->count();
        $totalUsers = $stats['total_users'] ?? \App\Models\User::count();
        $isAdmin = auth()->user()->role === 'admin';
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Notification Banners --}}
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200/90 text-emerald-900 flex items-start gap-3.5 shadow-sm transition">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-emerald-900 text-sm">Pengajuan Berhasil Dikirim</h4>
                        <p class="text-sm text-emerald-800 mt-0.5 leading-relaxed font-medium">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3.5 shadow-sm transition">
                    <div class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-rose-900 text-sm">Peringatan Akses</h4>
                        <p class="text-sm text-rose-800 mt-0.5 leading-relaxed font-medium">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 flex items-start gap-3.5 shadow-sm transition">
                    <div class="w-9 h-9 rounded-xl bg-blue-500 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-blue-800 leading-relaxed font-medium">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            @endif

            @if ($isAdmin)
                {{-- ================= ADMIN VIEW ================= --}}
                {{-- Admin Welcome Header --}}
                <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-rose-950 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute -right-12 -top-12 w-64 h-64 rounded-full bg-rose-500/10 blur-3xl pointer-events-none"></div>
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-600/30 border border-rose-500/40 text-rose-300 text-xs font-semibold uppercase tracking-wider mb-3">
                                <span>管理者ダッシュボード</span> &bull; <span>Panel Administrator</span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight mb-2">
                                Selamat Datang, {{ auth()->user()->name }}!
                            </h1>
                            <p class="text-slate-300 text-sm max-w-2xl leading-relaxed">
                                Pantau dan kelola seluruh data tempat, verifikasi pengajuan komunitas jejepangan, dan amati pertumbuhan ekosistem NihonLink J-Map.
                            </p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <a href="{{ route('places.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-sm font-semibold transition shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Tempat
                            </a>
                            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-sm font-semibold border border-white/20 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                Peta Publik
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Quick Stats Cards --}}
                <div>
                    <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Statistik Cepat Sistem
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Card 1: Jumlah Tempat --}}
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Jumlah Tempat</span>
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                            </div>
                            <div class="text-3xl font-extrabold text-gray-900 mb-2">{{ $totalPlaces }}</div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Total tempat terdaftar</span>
                                <a href="{{ route('places.index') }}" class="text-rose-600 hover:text-rose-700 font-medium inline-flex items-center gap-1">
                                    Kelola &rarr;
                                </a>
                            </div>
                        </div>

                        {{-- Card 2: Tempat Butuh Verifikasi --}}
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition relative">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Tempat Butuh Verifikasi</span>
                                <div class="w-10 h-10 rounded-xl {{ $pendingPlaces > 0 ? 'bg-amber-100 text-amber-600' : 'bg-green-50 text-green-600' }} flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <div class="text-3xl font-extrabold {{ $pendingPlaces > 0 ? 'text-amber-600' : 'text-gray-900' }} mb-2">
                                {{ $pendingPlaces }}
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                @if ($pendingPlaces > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Perlu Ditinjau
                                    </span>
                                @else
                                    <span class="text-green-600 text-xs font-semibold">Semua telah ditinjau</span>
                                @endif
                                <a href="{{ route('admin.places.pending') }}" class="text-rose-600 hover:text-rose-700 font-medium inline-flex items-center gap-1">
                                    Verifikasi &rarr;
                                </a>
                            </div>
                        </div>

                        {{-- Card 3: Jumlah Pengguna --}}
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Jumlah Pengguna</span>
                                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                            </div>
                            <div class="text-3xl font-extrabold text-gray-900 mb-2">{{ $totalUsers }}</div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Total member aktif</span>
                                <span class="text-purple-600 font-medium text-xs">Komunitas NihonLink</span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Action shortcuts --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-base font-bold text-gray-800 mb-4">Menu Cepat Admin</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('admin.places.pending') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-100 bg-slate-50 hover:bg-rose-50 hover:border-rose-200 transition group">
                            <div class="w-10 h-10 rounded-lg bg-amber-500 text-white flex items-center justify-center font-bold">
                                {{ $pendingPlaces }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-rose-600 text-sm">Verifikasi Tempat</h4>
                                <p class="text-xs text-gray-500">Review pengajuan pending</p>
                            </div>
                        </a>
                        <a href="{{ route('places.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-100 bg-slate-50 hover:bg-rose-50 hover:border-rose-200 transition group">
                            <div class="w-10 h-10 rounded-lg bg-blue-600 text-white flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-rose-600 text-sm">Daftar Tempat</h4>
                                <p class="text-xs text-gray-500">Kelola dan edit data tempat</p>
                            </div>
                        </a>
                        <a href="{{ route('places.create') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-100 bg-slate-50 hover:bg-rose-50 hover:border-rose-200 transition group">
                            <div class="w-10 h-10 rounded-lg bg-green-600 text-white flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-rose-600 text-sm">Tambah Tempat</h4>
                                <p class="text-xs text-gray-500">Input tempat baru langsung</p>
                            </div>
                        </a>
                        <a href="{{ url('/') }}" class="flex items-center gap-3 p-4 rounded-xl border border-gray-100 bg-slate-50 hover:bg-rose-50 hover:border-rose-200 transition group">
                            <div class="w-10 h-10 rounded-lg bg-rose-600 text-white flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 group-hover:text-rose-600 text-sm">Lihat Peta Utama</h4>
                                <p class="text-xs text-gray-500">Tampilan peta publik</p>
                            </div>
                        </a>
                    </div>
                </div>

            @else
                {{-- ================= MEMBER VIEW ================= --}}
                {{-- Banner Welcome Bertema Jepang --}}
                <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-gradient-to-br from-rose-700 via-rose-600 to-amber-600 text-white p-8 sm:p-12">
                    {{-- Decorative pattern overlay --}}
                    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
                    <div class="absolute -right-16 -bottom-16 w-80 h-80 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>

                    <div class="relative z-10 max-w-3xl">
                        {{-- Kanji & Title Badge --}}
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-black/25 backdrop-blur-md border border-white/20 text-xs font-semibold uppercase tracking-wider mb-4">
                            <span>ようこそ</span> &bull; <span>NihonLink J-Map Community</span>
                        </div>

                        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight mb-4">
                            Konnichiwa, {{ auth()->user()->name }}!
                        </h1>

                        <p class="text-base sm:text-lg text-rose-100 font-normal leading-relaxed mb-8 max-w-2xl">
                            Selamat datang di peta komunitas subkultur Jepang. Punya rekomendasi restoran ramen autentik, kafe bertema maid/anime, spot foto cosplay, atau toko merchandise favorit? Bagikan ke sesama penggemar jejepangan di seluruh Indonesia!
                        </p>

                        {{-- Action Buttons --}}
                        <div class="flex flex-wrap items-center gap-4">
                            {{-- Tombol Besar Ajukan Tempat Baru --}}
                            <a href="{{ route('places.create') }}"
                               class="inline-flex items-center gap-3 px-8 py-4 bg-white text-rose-600 hover:bg-rose-50 active:bg-rose-100 font-bold text-base rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-0.5 group">
                                <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <span>Ajukan Tempat Baru</span>
                                <svg class="w-5 h-5 text-rose-500 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>

                            {{-- Tombol Lihat Peta --}}
                            <a href="{{ url('/') }}"
                               class="inline-flex items-center gap-2 px-6 py-4 rounded-2xl font-semibold text-sm bg-black/20 hover:bg-black/30 border border-white/30 backdrop-blur-sm transition">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                Lihat Peta Utama
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Petunjuk Singkat Alur Pengajuan Tempat --}}
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 pb-4 border-b border-gray-100 gap-2">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
                                <span class="w-2.5 h-6 rounded-full bg-rose-600 inline-block"></span>
                                Petunjuk Singkat Alur Pengajuan Tempat
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">
                                Ikuti 4 langkah mudah berikut agar tempat rekomendasi Anda terbit di peta interaktif publik NihonLink J-Map.
                            </p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-600 self-start sm:self-auto">
                            Panduan Member
                        </span>
                    </div>

                    {{-- Step Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                        {{-- Step 1 --}}
                        <div class="relative bg-slate-50 hover:bg-rose-50/50 rounded-2xl p-6 border border-slate-100 hover:border-rose-200 transition group">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-rose-600 text-white flex items-center justify-center font-bold text-base shadow-sm group-hover:scale-105 transition">
                                    01
                                </div>
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-rose-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-900 text-base mb-2 group-hover:text-rose-600 transition">
                                Pilih Titik di Peta
                            </h3>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Buka form pengajuan, lalu klik pada peta interaktif Leaflet untuk menentukan koordinat lokasi secara tepat.
                            </p>
                        </div>

                        {{-- Step 2 --}}
                        <div class="relative bg-slate-50 hover:bg-rose-50/50 rounded-2xl p-6 border border-slate-100 hover:border-rose-200 transition group">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-rose-600 text-white flex items-center justify-center font-bold text-base shadow-sm group-hover:scale-105 transition">
                                    02
                                </div>
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-rose-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-900 text-base mb-2 group-hover:text-rose-600 transition">
                                Isi Detail Tempat
                            </h3>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Tuliskan nama tempat, pilih kategori (Restoran, Toko Anime, Cosplay, atau Event), deskripsi menarik, dan unggah foto tempat.
                            </p>
                        </div>

                        {{-- Step 3 --}}
                        <div class="relative bg-slate-50 hover:bg-rose-50/50 rounded-2xl p-6 border border-slate-100 hover:border-rose-200 transition group">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-rose-600 text-white flex items-center justify-center font-bold text-base shadow-sm group-hover:scale-105 transition">
                                    03
                                </div>
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-rose-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-900 text-base mb-2 group-hover:text-rose-600 transition">
                                Verifikasi Admin
                            </h3>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Data yang Anda ajukan akan berstatus pending untuk diverifikasi oleh tim admin NihonLink demi memastikan keaslian lokasi.
                            </p>
                        </div>

                        {{-- Step 4 --}}
                        <div class="relative bg-slate-50 hover:bg-rose-50/50 rounded-2xl p-6 border border-slate-100 hover:border-rose-200 transition group">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-green-600 text-white flex items-center justify-center font-bold text-base shadow-sm group-hover:scale-105 transition">
                                    04
                                </div>
                                <svg class="w-6 h-6 text-green-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-900 text-base mb-2 group-hover:text-green-600 transition">
                                Muncul di Peta Publik!
                            </h3>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Setelah disetujui, pin lokasi tempat Anda akan langsung aktif di peta utama dan dapat dijelajahi oleh semua komunitas.
                            </p>
                        </div>

                    </div>

                    {{-- Bottom Callout --}}
                    <div class="mt-8 p-4 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs sm:text-sm text-amber-800 leading-relaxed">
                            <strong>Tips Kontributor:</strong> Pastikan Anda mengunggah foto lokasi yang jelas dan memilih kategori yang paling sesuai agar pengajuan Anda dapat diverifikasi dengan cepat oleh Admin!
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
