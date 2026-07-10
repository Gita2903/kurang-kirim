@extends('layouts.app')
@section('title', 'Riwayat')
@section('content')
<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Riwayat Surat Jalan</h1>
            <p class="mt-1 text-sm text-slate-400">Daftar seluruh surat jalan yang telah diinput.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto mt-4 sm:mt-0">
            <a href="{{ route('history.export', request()->query()) }}" class="flex items-center justify-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-2.5 text-sm font-semibold text-emerald-400 hover:bg-emerald-500/20 transition-all duration-300">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Excel
            </a>
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-cyan-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all duration-300">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Baru
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl border border-white/5 bg-white/[0.02] backdrop-blur-xl p-5 mb-6">
        <form method="GET" action="{{ route('history') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode toko, nama toko, atau nomor SJ..." class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:border-violet-500/50 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all duration-300">
            </div>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-slate-100 focus:border-violet-500/50 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all duration-300 [color-scheme:dark]">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-slate-100 focus:border-violet-500/50 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all duration-300 [color-scheme:dark]">
            <button type="submit" class="rounded-xl bg-white/10 px-5 py-2.5 text-sm font-medium text-slate-200 hover:bg-white/15 transition-all duration-300">
                <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>Cari
            </button>
            @if(request()->hasAny(['search','date_from','date_to']))
                <a href="{{ route('history') }}" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-white/5 transition-all duration-300 text-center">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-white/5 bg-white/[0.02] backdrop-blur-xl shadow-2xl shadow-black/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/5 bg-white/[0.03]">
                        <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 w-12">No</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Kode Toko</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Nama Toko</th>
                        @php $dir = $sortDirection === 'asc' ? 'desc' : 'asc'; @endphp
                        <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                            <a href="{{ route('history', array_merge(request()->query(), ['sort'=>'tgl_kirim','direction'=>$sortField==='tgl_kirim'?$dir:'asc'])) }}" class="inline-flex items-center gap-1 hover:text-white transition-colors">
                                Tgl Kirim
                                @if($sortField==='tgl_kirim')<svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection==='asc'?'M5 15l7-7 7 7':'M19 9l-7 7-7-7' }}"/></svg>@endif
                            </a>
                        </th>
                        <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                            <a href="{{ route('history', array_merge(request()->query(), ['sort'=>'nomor_surat_jalan','direction'=>$sortField==='nomor_surat_jalan'?$dir:'asc'])) }}" class="inline-flex items-center gap-1 hover:text-white transition-colors">
                                No. Surat Jalan
                                @if($sortField==='nomor_surat_jalan')<svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection==='asc'?'M5 15l7-7 7 7':'M19 9l-7 7-7-7' }}"/></svg>@endif
                            </a>
                        </th>
                        <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Lampiran</th>
                        <th class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($dataList as $i => $item)
                        <tr class="hover:bg-white/[0.03] transition-colors duration-200">
                            <td class="px-5 py-4 text-slate-400">{{ $dataList->firstItem() + $i }}</td>
                            <td class="px-5 py-4"><span class="inline-flex items-center rounded-lg bg-violet-500/10 px-2.5 py-1 text-xs font-semibold text-violet-300">{{ $item->kode_toko }}</span></td>
                            <td class="px-5 py-4 text-slate-200">{{ $item->toko->nama_toko ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-300">{{ $item->tgl_kirim->format('d/m/Y') }}</td>
                            <td class="px-5 py-4 text-slate-200 font-mono text-xs">{{ $item->nomor_surat_jalan }}</td>
                            <td class="px-5 py-4">
                                @if($item->lampiran)
                                    <div class="flex items-center gap-2">
                                        <a href="{{ $item->lampiran_url }}" target="_blank" class="inline-flex items-center gap-1.5 text-cyan-400 hover:text-cyan-300 transition-colors text-xs font-medium" title="Lihat">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Lihat
                                        </a>
                                        <a href="{{ route('kurang-kirim.download', $item) }}" class="inline-flex items-center gap-1.5 text-violet-400 hover:text-violet-300 transition-colors text-xs font-medium" title="Download">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Unduh
                                        </a>
                                    </div>
                                @else
                                <span class="text-slate-600 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('kurang-kirim.edit', $item) }}" class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500/10 text-amber-400 hover:bg-amber-500/20 transition-all" title="Edit">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('kurang-kirim.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 transition-all" title="Hapus">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/5">
                                        <svg class="h-8 w-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <p class="text-slate-500 text-sm">Belum ada data surat jalan.</p>
                                    <a href="{{ route('dashboard') }}" class="text-violet-400 hover:text-violet-300 text-sm font-medium transition-colors">+ Tambah data pertama</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($dataList->hasPages())
            <div class="border-t border-white/5 px-5 py-4">{{ $dataList->links() }}</div>
        @endif
    </div>
</div>
@endsection
