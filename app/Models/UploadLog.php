<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadLog extends Model
{
    protected $table = 'upload_logs';

    const UPDATED_AT = null;

    protected $fillable = [
        'original_filename',
        'safe_filename',
        'file_size',
        'mime_type',
        'upload_path',
        'ip_address',
    ];
}
