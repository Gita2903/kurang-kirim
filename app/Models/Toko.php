<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Toko extends Model
{
    protected $table = 'toko';

    const UPDATED_AT = null;

    protected $fillable = [
        'kode_toko',
        'nama_toko',
        'alamat',
        'status',
    ];

    /**
     * Get the kurang kirim records for this toko.
     */
    public function kurangKirim(): HasMany
    {
        return $this->hasMany(KurangKirim::class);
    }

    /**
     * Scope: only active toko.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
