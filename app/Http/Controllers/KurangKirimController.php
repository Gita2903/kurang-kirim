<?php

namespace App\Http\Controllers;

use App\Exports\KurangKirimExport;
use App\Models\KurangKirim;
use App\Models\Toko;
use App\Models\UploadLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KurangKirimController extends Controller
{
    /**
     * Get the storage disk for lampiran files.
     */
    private function storageDisk(): string
    {
        return config('app.lampiran_disk', 'r2');
    }

    /**
     * Show the dashboard form.
     */
    public function dashboard()
    {
        $tokoList = Toko::active()->orderBy('kode_toko')->get();

        return view('dashboard', compact('tokoList'));
    }

    /**
     * Store a new kurang kirim record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'toko_id'            => 'required|exists:toko,id',
            'tgl_kirim'          => 'required|date',
            'nomor_surat_jalan'  => 'required|string|max:100',
            'lampiran'           => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240',
        ], [
            'toko_id.required'            => 'Kode Toko wajib dipilih.',
            'toko_id.exists'              => 'Kode Toko tidak valid.',
            'tgl_kirim.required'          => 'Tanggal Kirim wajib diisi.',
            'tgl_kirim.date'              => 'Format Tanggal Kirim tidak valid.',
            'nomor_surat_jalan.required'  => 'Nomor Surat Jalan wajib diisi.',
            'nomor_surat_jalan.max'       => 'Nomor Surat Jalan maksimal 100 karakter.',
            'lampiran.required'           => 'File Lampiran wajib diunggah.',
            'lampiran.file'               => 'Lampiran harus berupa file.',
            'lampiran.mimes'              => 'Format file harus: PDF, JPG, PNG, DOC, DOCX, XLS, atau XLSX.',
            'lampiran.max'                => 'Ukuran file maksimal 10MB.',
        ]);

        // Get toko data for kode_toko
        $toko = Toko::findOrFail($validated['toko_id']);

        // Store file
        $file = $request->file('lampiran');
        $safeFilename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $filePath = $file->storeAs('lampiran', $safeFilename, $this->storageDisk());

        // Log upload
        UploadLog::create([
            'original_filename' => $file->getClientOriginalName(),
            'safe_filename'     => $safeFilename,
            'file_size'         => $file->getSize(),
            'mime_type'         => $file->getMimeType(),
            'upload_path'       => $filePath,
            'ip_address'        => $request->ip(),
        ]);

        // Create record
        KurangKirim::create([
            'toko_id'            => $toko->id,
            'kode_toko'          => $toko->kode_toko,
            'tgl_kirim'          => $validated['tgl_kirim'],
            'nomor_surat_jalan'  => $validated['nomor_surat_jalan'],
            'lampiran'           => $filePath,
            'status'             => 1,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Surat Jalan berhasil disimpan!');
    }

    /**
     * Show the history page.
     */
    public function history(Request $request)
    {
        $query = KurangKirim::with('toko')->where('status', 1);

        // Search/filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat_jalan', 'like', "%{$search}%")
                  ->orWhere('kode_toko', 'like', "%{$search}%")
                  ->orWhereHas('toko', function ($q2) use ($search) {
                      $q2->where('nama_toko', 'like', "%{$search}%");
                  });
            });
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->where('tgl_kirim', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('tgl_kirim', '<=', $request->date_to);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['nomor_surat_jalan', 'tgl_kirim', 'created_at'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'created_at';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortField, $sortDirection);

        $dataList = $query->paginate(10)->withQueryString();

        return view('history', compact('dataList', 'sortField', 'sortDirection'));
    }

    /**
     * Show form to edit a record.
     */
    public function edit(KurangKirim $kurangKirim)
    {
        $tokoList = Toko::active()->orderBy('kode_toko')->get();

        return view('edit', compact('kurangKirim', 'tokoList'));
    }

    /**
     * Update a record.
     */
    public function update(Request $request, KurangKirim $kurangKirim)
    {
        $validated = $request->validate([
            'toko_id'            => 'required|exists:toko,id',
            'tgl_kirim'          => 'required|date',
            'nomor_surat_jalan'  => 'required|string|max:100',
            'lampiran'           => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240',
        ], [
            'toko_id.required'            => 'Kode Toko wajib dipilih.',
            'toko_id.exists'              => 'Kode Toko tidak valid.',
            'tgl_kirim.required'          => 'Tanggal Kirim wajib diisi.',
            'tgl_kirim.date'              => 'Format Tanggal Kirim tidak valid.',
            'nomor_surat_jalan.required'  => 'Nomor Surat Jalan wajib diisi.',
            'nomor_surat_jalan.max'       => 'Nomor Surat Jalan maksimal 100 karakter.',
            'lampiran.file'               => 'Lampiran harus berupa file.',
            'lampiran.mimes'              => 'Format file harus: PDF, JPG, PNG, DOC, DOCX, XLS, atau XLSX.',
            'lampiran.max'                => 'Ukuran file maksimal 10MB.',
        ]);

        $toko = Toko::findOrFail($validated['toko_id']);

        $data = [
            'toko_id'            => $toko->id,
            'kode_toko'          => $toko->kode_toko,
            'tgl_kirim'          => $validated['tgl_kirim'],
            'nomor_surat_jalan'  => $validated['nomor_surat_jalan'],
        ];

        // Replace file if new one uploaded
        if ($request->hasFile('lampiran')) {
            if ($kurangKirim->lampiran_path && Storage::disk($this->storageDisk())->exists($kurangKirim->lampiran_path)) {
                Storage::disk($this->storageDisk())->delete($kurangKirim->lampiran_path);
            }

            $file = $request->file('lampiran');
            $safeFilename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('lampiran', $safeFilename, $this->storageDisk());

            UploadLog::create([
                'original_filename' => $file->getClientOriginalName(),
                'safe_filename'     => $safeFilename,
                'file_size'         => $file->getSize(),
                'mime_type'         => $file->getMimeType(),
                'upload_path'       => $filePath,
                'ip_address'        => $request->ip(),
            ]);

            $data['lampiran'] = $filePath;
        }

        $kurangKirim->update($data);

        return redirect()->route('history')
            ->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Soft-delete (set status=0) a record.
     */
    public function destroy(KurangKirim $kurangKirim)
    {
        $kurangKirim->update(['status' => 0]);

        return redirect()->route('history')
            ->with('success', 'Data berhasil dihapus!');
    }

    /**
     * View the lampiran file inline (opens in browser).
     */
    public function view(KurangKirim $kurangKirim)
    {
        $path = $kurangKirim->lampiran_path;

        abort_unless($path && Storage::disk($this->storageDisk())->exists($path), 404);

        return Storage::disk($this->storageDisk())->response($path, basename($kurangKirim->lampiran));
    }

    /**
     * Download the lampiran file.
     */
    public function download(KurangKirim $kurangKirim)
    {
        $path = $kurangKirim->lampiran_path;

        abort_unless($path && Storage::disk($this->storageDisk())->exists($path), 404);

        return Storage::disk($this->storageDisk())->download($path, basename($kurangKirim->lampiran));
    }

    /**
     * Export history data to Excel.
     */
    public function export(Request $request)
    {
        $filename = 'kurang_kirim_' . now()->format('Y-m-d_His') . '.xlsx';

        return (new KurangKirimExport($request))->download($filename);
    }
}
