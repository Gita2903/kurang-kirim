@extends('layouts.app')
@section('title', 'Edit Surat Jalan')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('history') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-white transition-colors mb-4">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Riwayat
        </a>
        <h1 class="text-2xl font-bold text-white">Edit Surat Jalan</h1>
        <p class="mt-1 text-sm text-slate-400">Perbarui data <span class="text-violet-400 font-medium">{{ $kurangKirim->nomor_surat_jalan }}</span></p>
    </div>
    <div class="rounded-2xl border border-white/5 bg-white/[0.02] backdrop-blur-xl shadow-2xl shadow-black/20">
        <div class="p-6 sm:p-8">
            <form action="{{ route('kurang-kirim.update', $kurangKirim) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PUT')
                {{-- Kode Toko --}}
                <div>
                    <label for="toko_id" class="block text-sm font-medium text-slate-300 mb-2">Kode Toko <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <select id="toko_id" name="toko_id" class="w-full appearance-none rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 focus:border-violet-500/50 focus:bg-white/[0.07] focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all duration-300 cursor-pointer">
                            <option value="" class="bg-slate-900">— Pilih Toko —</option>
                            @foreach($tokoList as $toko)
                                <option value="{{ $toko->id }}" class="bg-slate-900" {{ old('toko_id', $kurangKirim->toko_id) == $toko->id ? 'selected' : '' }}>{{ $toko->kode_toko }} — {{ $toko->nama_toko }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    @error('toko_id')<p class="mt-2 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>
                {{-- Tanggal Kirim --}}
                <div>
                    <label for="tgl_kirim" class="block text-sm font-medium text-slate-300 mb-2">Tanggal Kirim <span class="text-rose-400">*</span></label>
                    <input type="date" id="tgl_kirim" name="tgl_kirim" value="{{ old('tgl_kirim', $kurangKirim->tgl_kirim->format('Y-m-d')) }}" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 focus:border-violet-500/50 focus:bg-white/[0.07] focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all duration-300 [color-scheme:dark]">
                    @error('tgl_kirim')<p class="mt-2 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>
                {{-- Nomor Surat Jalan --}}
                <div>
                    <label for="nomor_surat_jalan" class="block text-sm font-medium text-slate-300 mb-2">Nomor Surat Jalan <span class="text-rose-400">*</span></label>
                    <input type="text" id="nomor_surat_jalan" name="nomor_surat_jalan" value="{{ old('nomor_surat_jalan', $kurangKirim->nomor_surat_jalan) }}" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:border-violet-500/50 focus:bg-white/[0.07] focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all duration-300">
                    @error('nomor_surat_jalan')<p class="mt-2 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>
                {{-- Lampiran --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Lampiran File <span class="text-slate-500 text-xs">(kosongkan jika tidak ingin mengganti)</span></label>
                    @if($kurangKirim->lampiran)
                    <div class="rounded-xl border border-white/10 bg-white/5 p-4 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-500/10">
                                <svg class="h-5 w-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm text-slate-300 truncate">{{ basename($kurangKirim->lampiran) }}</p>
                                <p class="text-xs text-slate-500">File saat ini</p>
                            </div>
                            <a href="{{ $kurangKirim->lampiran_url }}" target="_blank" class="text-xs text-cyan-400 hover:text-cyan-300 transition-colors font-medium">Lihat</a>
                        </div>
                    </div>
                    @endif
                    <input type="file" name="lampiran" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-slate-100 file:mr-4 file:rounded-lg file:border-0 file:bg-violet-500/10 file:px-4 file:py-2 file:text-sm file:font-medium file:text-violet-300 hover:file:bg-violet-500/20 transition-all duration-300">
                    @error('lampiran')<p class="mt-2 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>
                {{-- Buttons --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-4">
                    <button type="submit" class="flex-1 flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-cyan-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 hover:from-violet-500 hover:to-cyan-500 active:scale-[0.98] transition-all duration-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Perbarui
                    </button>
                    <a href="{{ route('history') }}" class="w-full sm:w-auto rounded-xl border border-white/10 bg-white/5 px-6 py-3 text-sm font-medium text-slate-300 hover:bg-white/10 hover:border-white/20 transition-all duration-300 text-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    new TomSelect('#toko_id', {
        create: false,
        placeholder: '— Cari & Pilih Toko —',
        maxOptions: 50,
    });
});
</script>
@endpush
