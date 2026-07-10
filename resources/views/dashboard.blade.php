@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Input Surat Jalan</h1>
        <p class="mt-1 text-sm text-slate-400">Isi form di bawah untuk menambahkan data surat jalan baru.</p>
    </div>
    <div class="rounded-2xl border border-white/5 bg-white/[0.02] backdrop-blur-xl shadow-2xl shadow-black/20">
        <div class="p-6 sm:p-8">
            <form id="mainForm" action="{{ route('kurang-kirim.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                {{-- Kode Toko --}}
                <div>
                    <label for="toko_id" class="block text-sm font-medium text-slate-300 mb-2">Kode Toko <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <select id="toko_id" name="toko_id" class="w-full appearance-none rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 focus:border-violet-500/50 focus:bg-white/[0.07] focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all duration-300 cursor-pointer">
                            <option value="" class="bg-slate-900">— Pilih Toko —</option>
                            @foreach($tokoList as $toko)
                                <option value="{{ $toko->id }}" class="bg-slate-900" {{ old('toko_id') == $toko->id ? 'selected' : '' }}>{{ $toko->kode_toko }} — {{ $toko->nama_toko }}</option>
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
                    <input type="date" id="tgl_kirim" name="tgl_kirim" value="{{ old('tgl_kirim', date('Y-m-d')) }}" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 focus:border-violet-500/50 focus:bg-white/[0.07] focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all duration-300 [color-scheme:dark]">
                    @error('tgl_kirim')<p class="mt-2 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>
                {{-- Nomor Surat Jalan --}}
                <div>
                    <label for="nomor_surat_jalan" class="block text-sm font-medium text-slate-300 mb-2">Nomor Surat Jalan <span class="text-rose-400">*</span></label>
                    <input type="text" id="nomor_surat_jalan" name="nomor_surat_jalan" value="{{ old('nomor_surat_jalan') }}" placeholder="Contoh: SJ-2026-001" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:border-violet-500/50 focus:bg-white/[0.07] focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition-all duration-300">
                    @error('nomor_surat_jalan')<p class="mt-2 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>
                {{-- File Upload --}}
                <div>
                    <label for="lampiran" class="block text-sm font-medium text-slate-300 mb-2">Lampiran File <span class="text-rose-400">*</span></label>
                    <div id="dropZone" class="relative rounded-xl border-2 border-dashed border-white/10 bg-white/[0.02] p-8 hover:border-violet-500/30 hover:bg-white/[0.04] transition-all duration-300 cursor-pointer group">
                        <input type="file" id="lampiran" name="lampiran" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div id="uploadPlaceholder" class="flex flex-col items-center gap-3 text-center">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-500/10 text-violet-400 group-hover:bg-violet-500/20 transition-all duration-300">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-300"><span class="text-violet-400">Klik untuk upload</span> atau drag & drop</p>
                                <p class="mt-1 text-xs text-slate-500">PDF, JPG, PNG, DOC, DOCX, XLS, XLSX (maks. 10MB)</p>
                            </div>
                        </div>
                        <div id="filePreview" class="hidden flex items-center gap-4">
                            <div id="imgPreviewWrap" class="hidden shrink-0"><img id="imgPreview" class="h-20 w-20 rounded-lg object-cover border border-white/10" alt="Preview"></div>
                            <div id="fileIconWrap" class="hidden shrink-0"><div class="flex h-14 w-14 items-center justify-center rounded-xl bg-violet-500/10"><svg class="h-7 w-7 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div></div>
                            <div class="min-w-0 flex-1"><p id="fileName" class="text-sm font-medium text-slate-200 truncate"></p><p id="fileSize" class="text-xs text-slate-500 mt-0.5"></p></div>
                            <button type="button" id="removeFile" class="shrink-0 flex h-8 w-8 items-center justify-center rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 transition-all z-20"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                    </div>
                    @error('lampiran')<p class="mt-2 text-xs text-rose-400">{{ $message }}</p>@enderror
                </div>
                {{-- Buttons --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-4">
                    <button type="submit" class="flex-1 flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-cyan-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 hover:from-violet-500 hover:to-cyan-500 active:scale-[0.98] transition-all duration-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Submit
                    </button>
                    <button type="reset" id="resetBtn" class="w-full sm:w-auto rounded-xl border border-white/10 bg-white/5 px-6 py-3 text-sm font-medium text-slate-300 hover:bg-white/10 hover:border-white/20 active:scale-[0.98] transition-all duration-300">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',()=>{
    // Initialize TomSelect for searchable dropdown
    new TomSelect('#toko_id', {
        create: false,
        placeholder: '— Cari & Pilih Toko —',
        maxOptions: 50,
    });

    const fi=document.getElementById('lampiran'),dz=document.getElementById('dropZone'),ph=document.getElementById('uploadPlaceholder'),pv=document.getElementById('filePreview'),iw=document.getElementById('imgPreviewWrap'),ip=document.getElementById('imgPreview'),fw=document.getElementById('fileIconWrap'),fn=document.getElementById('fileName'),fs=document.getElementById('fileSize'),rb=document.getElementById('removeFile');function fmt(b){if(b<1024)return b+' B';if(b<1048576)return(b/1024).toFixed(1)+' KB';return(b/1048576).toFixed(1)+' MB'}function show(f){fn.textContent=f.name;fs.textContent=fmt(f.size);if(f.type.startsWith('image/')){const r=new FileReader();r.onload=e=>{ip.src=e.target.result;iw.classList.remove('hidden');fw.classList.add('hidden')};r.readAsDataURL(f)}else{iw.classList.add('hidden');fw.classList.remove('hidden')}ph.classList.add('hidden');pv.classList.remove('hidden');dz.classList.add('border-violet-500/30')}function clear(){fi.value='';ph.classList.remove('hidden');pv.classList.add('hidden');iw.classList.add('hidden');fw.classList.add('hidden');dz.classList.remove('border-violet-500/30')}fi.addEventListener('change',e=>{if(e.target.files.length>0)show(e.target.files[0])});rb.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();clear()});['dragenter','dragover'].forEach(ev=>dz.addEventListener(ev,e=>{e.preventDefault();dz.classList.add('border-violet-500/50','bg-violet-500/5')}));['dragleave','drop'].forEach(ev=>dz.addEventListener(ev,e=>{e.preventDefault();dz.classList.remove('border-violet-500/50','bg-violet-500/5')}));document.getElementById('resetBtn').addEventListener('click',clear)
});
</script>
@endpush
