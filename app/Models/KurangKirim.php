<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class KurangKirim extends Model
{
    protected $table = 'kurang_kirim';

    const UPDATED_AT = null;

    protected $fillable = [
        'toko_id',
        'kode_toko',
        'tgl_kirim',
        'nomor_surat_jalan',
        'lampiran',
        'created_by',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tgl_kirim' => 'date',
        ];
    }

    /**
     * Get the toko that owns this record.
     */
    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    /**
     * Normalize lampiran path — handles legacy vs new format.
     *
     * Legacy : lampiran_1777367750_69f07ac6b02af.jpg  → lampiran/lampiran_1777367750_69f07ac6b02af.jpg
     * Legacy : 17488533106818645078733283990468.jpg   → lampiran/17488533106818645078733283990468.jpg
     * Laravel: lampiran/1777447084_filename.pdf       → no change (already has folder)
     */
    private function normalizeLampiranPath(string $path): string
    {
        // Sudah ada directory separator → pakai apa adanya
        if (str_contains($path, '/')) {
            return $path;
        }

        // Format lama: filename doang tanpa folder → prepend lampiran/
        return 'lampiran/' . $path;
    }

    /**
     * Get the configured storage disk for lampiran.
     */
    private static function lampiranDisk(): string
    {
        return config('app.lampiran_disk', 'r2');
    }

    /**
     * Get the URL for the lampiran file (via download route).
     */
    public function getLampiranUrlAttribute(): ?string
    {
        if (!$this->lampiran) {
            return null;
        }

        // Serve inline via view route (works for both local & R2)
        return route('kurang-kirim.view', $this);
    }

    /**
     * Get the normalized storage path for the lampiran file.
     */
    public function getLampiranPathAttribute(): ?string
    {
        if (!$this->lampiran) {
            return null;
        }

        return $this->normalizeLampiranPath($this->lampiran);
    }

    /**
     * Check if the lampiran file exists in storage.
     */
    public function lampiranExists(): bool
    {
        if (!$this->lampiran) {
            return false;
        }

        try {
            return Storage::disk(self::lampiranDisk())->exists($this->normalizeLampiranPath($this->lampiran));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('S3 exists error: ' . $e->getMessage());
            return false;
        }
    }
}
