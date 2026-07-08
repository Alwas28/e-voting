<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'title', 'description', 'file_path', 'file_name', 'file_size', 'is_published', 'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function getDownloadUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getIconAttribute(): string
    {
        $ext = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
        return match($ext) {
            'pdf'              => 'pdf',
            'doc', 'docx'     => 'word',
            'xls', 'xlsx'     => 'excel',
            'ppt', 'pptx'     => 'ppt',
            'zip', 'rar'      => 'zip',
            default           => 'file',
        };
    }
}
