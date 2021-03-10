<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    use HasFactory;

    protected $table = 'individuals';

    protected $casts = [
        'create_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $with = [
        'documents',
        'history'
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function history()
    {
        return $this->hasMany(History::class)->orderBy('created_at', 'desc');
    }
}
