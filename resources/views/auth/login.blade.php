@extends('layouts.app')

@section('title', 'Masuk - Lapor-Pak')

@section('content')

<div class="max-w-6xl mx-auto space-y-8">

    {{-- HERO LOGIN --}}
    <section class="grid lg:grid-cols-2 gap-6 items-stretch">

        {{-- BAGIAN KIRI --}}
        <div class="relative overflow-hidden rounded-3xl bg-green-800 text-white min-h-[570px]">

            {{-- Foto sekolah --}}
            <div class="absolute inset-0">
                <img
                    src="{{ asset('images/sekolah.jpg') }}"
                    alt="SMK Ma'arif NU 1 Ajibarang"
                    class="w-full h-full object-cover opacity-35"
                    onerror="this.style.display='none'"
                >
                <div class="absolute inset-0 bg-gradient-to-br from-green-950/95 via-green-900/85 to-green-700/70"></div>
            </div>

            <div class="relative z-10 p-7 md:p-10 h-full flex flex-col justify-between">

                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/15 border border-white/20 text-sm">
                        <span class="w-2 h-2 bg-green-300 rounded-full"></span>
                        Layanan Aduan Fasilitas Sekolah
                    </div>

                    <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mt-8">
                        Fasilitas Rusak?
                        <span class="text-green-300">Lapor-Pak!</span>
                    </h1>

                    <p class="text-green-50 leading-relaxed mt-5 max-w-md">
                        Mari bersama menjaga fasilitas sekolah.
                        Laporkan kerusakan yang kamu temukan agar
                        dapat segera ditindaklanjuti dan diperbaiki.
                    </p>

                    <div class="mt-7">
                        <a href="#form-login"
                           class="inline-flex items-center gap-2 bg-white text-green-800 px-6 py-3 rounded-xl font-bold hover:bg-green-50 hover:-translate-y-1 transition">
                            Mulai Melapor
                            <span>→</span>
                        </a>
                    </div>
                </div>

                {{-- MASKOT TEKNISI --}}
                <div class="flex justify-center items-end mt-8">

                    <svg viewBox="0 0 360 300"
                         class="w-full max-w-sm drop-shadow-2xl"
                         xmlns="http://www.w3.org/2000/svg"
                         role="img"
                         aria-label="Maskot teknisi sekolah siap memperbaiki fasilitas">

                        {{-- Lingkaran latar --}}
                        <circle cx="180" cy="155" r="125" fill="#bbf7d0" opacity=".22"/>

                        {{-- Toolbox --}}
                        <rect x="24" y="210" width="90" height="58" rx="10" fill="#facc15"/>
                        <path d="M45 210 V197 Q45 187 55 187 H83 Q93 187 93 197 V210"
                              stroke="#facc15" stroke-width="8" fill="none"/>
                        <rect x="58" y="230" width="22" height="12" rx="3" fill="#854d0e"/>

                        {{-- Kaki --}}
                        <path d="M135 215 L130 270 L165 270 L175 220Z" fill="#1e3a8a"/>
                        <path d="M190 220 L200 270 L235 270 L225 215Z" fill="#1e3a8a"/>
                        <path d="M125 265 H168 V280 H118 Q110 280 115 272Z" fill="#334155"/>
                        <path d="M197 265 H240 L250 275 Q255 282 245 282 H200Z" fill="#334155"/>

                        {{-- Badan seragam --}}
                        <path d="M125 135 Q180 115 235 135 L245 220 Q180 240 115 220Z" fill="#facc15"/>
                        <path d="M180 133 V224" stroke="#ca8a04" stroke-width="4"/>
                        <rect x="140" y="163" width="27" height="22" rx="4" fill="#15803d"/>
                        <rect x="193" y="163" width="27" height="22" rx="4" fill="#15803d"/>

                        {{-- Leher --}}
                        <rect x="160" y="110" width="40" height="36" rx="14" fill="#d99a70"/>

                        {{-- Kepala --}}
                        <ellipse cx="180" cy="90" rx="48" ry="52" fill="#eab48e"/>

                        {{-- Rambut --}}
                        <path d="M133 85 Q128 34 180 33 Q233 34 227 86 Q205 60 180 65 Q151 59 133 85Z"
                              fill="#334155"/>

                        {{-- Helm keselamatan --}}
                        <path d="M127 73 Q132 27 180 27 Q228 27 233 73Z" fill="#facc15"/>
                        <rect x="120" y="69" width="120" height="12" rx="6" fill="#eab308"/>
                        <path d="M180 34 V66" stroke="#fff7ae" stroke-width="6"/>

                        {{-- Wajah --}}
                        <circle cx="163" cy="91" r="4" fill="#334155"/>
                        <circle cx="197" cy="91" r="4" fill="#334155"/>
                        <path d="M166 111 Q180 122 194 111"
                              stroke="#9a4d38" stroke-width="4" fill="none" stroke-linecap="round"/>

                        {{-- Lengan kiri --}}
                        <path d="M124 145 Q95 160 92 198"
                              stroke="#eab48e" stroke-width="20" fill="none" stroke-linecap="round"/>
                        <circle cx="92" cy="199" r="12" fill="#eab48e"/>

                        {{-- Lengan kanan --}}
                        <path d="M235 145 Q263 130 276 105"
                              stroke="#eab48e" stroke-width="20" fill="none" stroke-linecap="round"/>
                        <circle cx="278" cy="102" r="12" fill="#eab48e"/>

                        {{-- Kunci inggris --}}
                        <path d="M278 91 L295 73 Q305 68 313 78 L300 91 L310 101 L322 88 Q329 110 307 119 Q291 123 282 109Z"
                              fill="#cbd5e1" stroke="#475569" stroke-width="3"/>

                        {{-- Detail bintang --}}
                        <path d="M70 95 L75 108 L88 113 L75 118 L70 131 L65 118 L52 113 L65 108Z"
                              fill="#fde68a"/>
                        <path d="M280 190 L284 200 L294 204 L284 208 L280 218 L276 208 L266 204 L276 200Z"
                              fill="#fde68a"/>

                    </svg>
                </div>

                <div class="text-center text-sm text-green-100 mt-3">
                    <p class="font-bold text-white">Siap membantu memperbaiki!</p>
                    <p>Laporkan kerusakan, wujudkan sekolah yang nyaman.</p>
                </div>

            </div>
        </div>

        {{-- BAGIAN KANAN: FORM LOGIN --}}
        <div id="form-login"
             class="bg-white rounded-3xl border border-green-100 shadow-sm p-7 md:p-10 flex flex-col justify-center">

            <div class="mb-8">

                <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-2xl mb-5">
                    🛠️
                </div>

                <span class="text-sm font-bold text-green-700">
                    SELAMAT DATANG
                </span>

                <h2 class="text-3xl font-extrabold text-slate-800 mt-2">
                    Masuk ke Lapor-Pak
                </h2>

                <p class="text-slate-500 mt-3 leading-relaxed">
                    Silakan masuk menggunakan akun yang telah
                    diberikan oleh Admin untuk membuat dan memantau laporan.
                </p>

            </div>

            <form method="POST"
                  action="{{ route('login.post') }}"
                  class="space-y-5">

                @csrf

                <div>
                    <label for="username"
                           class="block text-sm font-semibold text-slate-700 mb-2">
                        Username
                    </label>

                    <input
                        id="username"
                        name="username"
                        type="text"
                        value="{{ old('username') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Masukkan username"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3.5 bg-slate-50 focus:bg-white focus:border-green-400 focus:ring-2 focus:ring-green-100 transition"
                    >
                </div>

                <div>
                    <label for="password"
                           class="block text-sm font-semibold text-slate-700 mb-2">
                        Password
                    </label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3.5 bg-slate-50 focus:bg-white focus:border-green-400 focus:ring-2 focus:ring-green-100 transition"
                    >
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox"
                           name="remember"
                           value="1"
                           class="w-4 h-4 accent-green-600 rounded">
                    Ingat saya
                </label>

                <button type="submit"
                        class="w-full bg-green-600 text-white rounded-xl py-3.5 font-bold shadow-sm hover:bg-green-700 hover:-translate-y-0.5 transition">
                    Masuk Sekarang →
                </button>

            </form>

            <div class="mt-7 pt-6 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-500">
                    Belum memiliki akun?
                </p>
                <p class="text-sm font-semibold text-green-700 mt-1">
                    Hubungi Admin sekolah untuk mendapatkan akun.
                </p>
            </div>

        </div>

    </section>

    {{-- PREVIEW LAPORAN --}}
    <section class="space-y-5">

        <div class="flex flex-wrap justify-between items-end gap-3">
            <div>
                <span class="text-sm font-bold text-green-700">
                    CONTOH LAPORAN
                </span>

                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800 mt-2">
                    Kerusakan yang Perlu Diperhatikan
                </h2>

                <p class="text-slate-500 mt-2">
                    Segera laporkan jika menemukan fasilitas sekolah yang rusak.
                </p>
            </div>

            <span class="text-xs bg-green-100 text-green-800 px-3 py-2 rounded-full font-semibold">
                Preview ilustrasi
            </span>
        </div>

        <div class="grid md:grid-cols-3 gap-5">

            {{-- LAPORAN 1 --}}
            <div class="bg-white rounded-2xl border border-green-100 shadow-sm overflow-hidden hover:-translate-y-1 hover:shadow-md transition">

                <div class="h-40 bg-amber-50 flex items-center justify-center">
                    <svg viewBox="0 0 200 130" class="w-44 h-32">
                        <rect x="25" y="22" width="150" height="88" rx="8" fill="#fde68a"/>
                        <path d="M50 50 H150 M50 70 H150 M50 90 H115" stroke="#b45309" stroke-width="6" stroke-linecap="round"/>
                        <path d="M90 20 L105 43 L94 56 L111 72 L95 100" stroke="#dc2626" stroke-width="5" fill="none"/>
                    </svg>
                </div>

                <div class="p-5">
                    <span class="text-xs font-bold text-amber-700 bg-amber-100 px-3 py-1 rounded-full">
                        Fasilitas Kelas
                    </span>

                    <h3 class="font-bold text-slate-800 mt-3">
                        Meja atau Kursi Rusak
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Laporkan meja atau kursi yang patah agar segera diperbaiki.
                    </p>
                </div>

            </div>

            {{-- LAPORAN 2 --}}
            <div class="bg-white rounded-2xl border border-green-100 shadow-sm overflow-hidden hover:-translate-y-1 hover:shadow-md transition">

                <div class="h-40 bg-blue-50 flex items-center justify-center">
                    <svg viewBox="0 0 200 130" class="w-44 h-32">
                        <path d="M100 17 V58" stroke="#475569" stroke-width="7" stroke-linecap="round"/>
                        <path d="M100 58 L53 94 M100 58 L147 94" stroke="#64748b" stroke-width="7" stroke-linecap="round"/>
                        <circle cx="100" cy="58" r="12" fill="#94a3b8"/>
                        <path d="M100 58 L70 30 M100 58 L130 30" stroke="#2563eb" stroke-width="9" stroke-linecap="round"/>
                        <path d="M65 103 H135" stroke="#64748b" stroke-width="6" stroke-linecap="round"/>
                        <path d="M100 4 V15 M92 10 H108" stroke="#eab308" stroke-width="5" stroke-linecap="round"/>
                    </svg>
                </div>

                <div class="p-5">
                    <span class="text-xs font-bold text-blue-700 bg-blue-100 px-3 py-1 rounded-full">
                        Kelistrikan
                    </span>

                    <h3 class="font-bold text-slate-800 mt-3">
                        Kipas Angin Tidak Berfungsi
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Laporkan kipas yang mati atau tidak berputar dengan normal.
                    </p>
                </div>

            </div>

            {{-- LAPORAN 3 --}}
            <div class="bg-white rounded-2xl border border-green-100 shadow-sm overflow-hidden hover:-translate-y-1 hover:shadow-md transition">

                <div class="h-40 bg-cyan-50 flex items-center justify-center">
                    <svg viewBox="0 0 200 130" class="w-44 h-32">
                        <path d="M35 37 H165 V108 H35Z" fill="#bae6fd"/>
                        <path d="M35 37 L100 65 L165 37" fill="#7dd3fc" stroke="#0284c7" stroke-width="5"/>
                        <path d="M100 65 V108" stroke="#0284c7" stroke-width="4"/>
                        <path d="M78 16 Q90 28 78 38 M112 12 Q124 24 112 34" stroke="#0284c7" stroke-width="5" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>

                <div class="p-5">
                    <span class="text-xs font-bold text-cyan-700 bg-cyan-100 px-3 py-1 rounded-full">
                        Fasilitas Umum
                    </span>

                    <h3 class="font-bold text-slate-800 mt-3">
                        Atap atau Plafon Bocor
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Laporkan kebocoran agar tidak mengganggu kegiatan belajar.
                    </p>
                </div>

            </div>

        </div>

    </section>

    {{-- AJAKAN --}}
    <section class="rounded-3xl bg-gradient-to-r from-green-700 to-green-600 p-8 md:p-10 text-white text-center">

        <div class="text-4xl mb-4">🏫</div>

        <h2 class="text-2xl md:text-3xl font-extrabold">
            Sekolah Nyaman, Belajar Lebih Aman!
        </h2>

        <p class="text-green-50 max-w-2xl mx-auto mt-3 leading-relaxed">
            Jangan biarkan kerusakan fasilitas sekolah semakin parah.
            Sampaikan laporan dengan jelas agar petugas dapat segera
            mengetahui dan melakukan perbaikan.
        </p>

        <a href="#form-login"
           class="inline-block bg-white text-green-800 px-7 py-3 rounded-xl font-bold mt-6 hover:bg-green-50 transition">
            Laporkan Kerusakan Sekarang
        </a>

    </section>

</div>

@endsection
```