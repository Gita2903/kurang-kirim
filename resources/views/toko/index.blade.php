@extends('layouts.app')
@section('title', 'Master Toko')
@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Master Toko</h1>
            <p class="mt-1 text-sm text-slate-400">Kelola data toko yang terdaftar di sistem.</p>
        </div>
        <a href="{{ route('toko.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-cyan-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 hover:from-violet-500 hover:to-cyan-500 active:scale-[0.98] transition-all duration-300">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Toko
        </a>
    </div>

    {{-- Error Flash --}}
    @if(session('error'))
        <div class="flex items-center gap-3 rounded-xl border border-rose-500/20 bg-rose-500/10 px-5 py-4 text-rose-300 backdrop-blur-sm mb-6">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Search --}}
    <form method="GET" action="{{ route('toko.index') }}" class="mb-6">
        <div class="flex items-center gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama toko..."
                    class="w-full rounded-xl border border-white/10 bg-white/5 pl-10 pr-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:border-violet-500/50 focus:bg-white/[0.07] focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all duration-300">
            </div>
            <button type="submit" class="rounded-xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-medium text-slate-300 hover:bg-white/10 hover:border-white/20 transition-all duration-300">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('toko.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-400 hover:bg-white/10 hover:border-white/20 transition-all duration-300">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </div>
    </form>

    {{-- Stats --}}
    <div class="flex items-center gap-4 text-sm text-slate-400 mb-4">
        <span class="inline-flex items-center gap-1.5">
            <svg class="h-4 w-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <strong class="text-white">{{ $tokoList->total() }}</strong> toko
        </span>
        @if(request('search'))
            <span class="text-slate-600">|</span>
            <span>Hasil: <strong class="text-white">"{{ request('search') }}"</strong></span>
        @endif
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-white/5 bg-white/[0.02] backdrop-blur-xl shadow-2xl shadow-black/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="border-b border-white/5 bg-white/[0.03]">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold text-slate-400 w-12">#</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-400">Kode Toko</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-400">Nama Toko</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-400 text-center">Status</th>
                        <th class="px-5 py-3.5 font-semibold text-slate-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($tokoList as $toko)
                    <tr class="hover:bg-white/[0.03] transition-colors duration-200">
                        <td class="px-5 py-4 text-slate-500 text-xs">
                            {{ $loop->iteration + ($tokoList->currentPage() - 1) * $tokoList->perPage() }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-mono text-xs bg-white/5 text-violet-300 px-2.5 py-1 rounded-lg border border-white/5">{{ $toko->kode_toko }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-medium text-slate-200">{{ $toko->nama_toko }}</p>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($toko->status)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 text-xs font-medium text-emerald-400">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-500/10 border border-slate-500/20 px-2.5 py-1 text-xs font-medium text-slate-400">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex justify-end items-center gap-2">
                                <form action="{{ route('toko.toggle-status', $toko) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" title="{{ $toko->status ? 'Nonaktifkan' : 'Aktifkan' }}"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg border transition-all duration-300
                                        {{ $toko->status
                                            ? 'border-amber-500/20 text-amber-400 hover:bg-amber-500/10'
                                            : 'border-emerald-500/20 text-emerald-400 hover:bg-emerald-500/10' }}">
                                        @if($toko->status)
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        @else
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </button>
                                </form>
                                <a href="{{ route('toko.edit', $toko) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-slate-300 hover:bg-white/10 hover:border-white/20 transition-all duration-300">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <form action="{{ route('toko.destroy', $toko) }}" method="POST"
                                    onsubmit="return confirm('Hapus toko {{ $toko->nama_toko }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg border border-rose-500/20 text-rose-400 hover:bg-rose-500/10 transition-all duration-300">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/5">
                                    <svg class="h-7 w-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <p class="text-slate-500">Belum ada data toko.</p>
                                <a href="{{ route('toko.create') }}" class="text-violet-400 hover:text-violet-300 text-sm font-medium transition-colors">
                                    + Tambah toko pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($tokoList->hasPages())
        <div class="border-t border-white/5 px-5 py-4">
            {{ $tokoList->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
