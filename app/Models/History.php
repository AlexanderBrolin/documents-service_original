<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'history';

    protected $fillable = [
        'type',
        'author_id',
        'field_id',
        'document_id',
        'individual_id',
        'before',
        'after'
    ];

    protected $with = [
        'author',
        'field'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function individual()
    {
        return $this->belongsTo(Individual::class, 'individual_id', 'id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }
}
