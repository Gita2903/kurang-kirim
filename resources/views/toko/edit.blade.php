@extends('layouts.app')
@section('title', 'Edit Toko')
@section('content')
<div class="max-w-xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('toko.index') }}" class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-slate-400 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all duration-300">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Edit Toko</h1>
            <p class="mt-0.5 text-sm text-slate-400">{{ $toko->kode_toko }} — {{ $toko->nama_toko }}</p>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="flex items-start gap-3 rounded-xl border border-rose-500/20 bg-rose-500/10 px-5 py-4 text-rose-300 backdrop-blur-sm mb-6">
        <svg class="h-5 w-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <ul class="text-sm space-y-0.5">
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
    @endif

    {{-- Form --}}
    <div class="rounded-2xl border border-white/5 bg-white/[0.02] backdrop-blur-xl shadow-2xl shadow-black/20">
        <div class="p-6 sm:p-8">
            <form action="{{ route('toko.update', $toko) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <div>
                    <label for="kode_toko" class="block text-sm font-medium text-slate-300 mb-2">Kode Toko <span class="text-rose-400">*</span></label>
                    <input type="text" id="kode_toko" name="kode_toko" value="{{ old('kode_toko', $toko->kode_toko) }}" required maxlength="50"
                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-mono text-slate-100 placeholder-slate-500 focus:border-violet-500/50 focus:bg-white/[0.07] focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all duration-300">
                </div>
                <div>
                    <label for="nama_toko" class="block text-sm font-medium text-slate-300 mb-2">Nama Toko <span class="text-rose-400">*</span></label>
                    <input type="text" id="nama_toko" name="nama_toko" value="{{ old('nama_toko', $toko->nama_toko) }}" required maxlength="255"
                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:border-violet-500/50 focus:bg-white/[0.07] focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all duration-300">
                </div>

                {{-- Info --}}
                <div class="pt-2 border-t border-white/5 text-xs text-slate-500 flex items-center gap-4">
                    <span>
                        <svg class="h-3.5 w-3.5 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Dibuat: {{ $toko->created_at->format('d M Y H:i') }}
                    </span>
                    <span>
                        Status:
                        @if($toko->status)
                            <span class="text-emerald-400">Aktif</span>
                        @else
                            <span class="text-slate-400">Nonaktif</span>
                        @endif
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-4">
                    <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-cyan-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 hover:from-violet-500 hover:to-cyan-500 active:scale-[0.98] transition-all duration-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('toko.index') }}"
                        class="w-full sm:w-auto text-center rounded-xl border border-white/10 bg-white/5 px-6 py-3 text-sm font-medium text-slate-300 hover:bg-white/10 hover:border-white/20 active:scale-[0.98] transition-all duration-300">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
