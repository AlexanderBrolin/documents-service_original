<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'documents';

    public const PATH = 'public/documents/';

    protected $fillable = [
        'individual_id',
        'type'
    ];

    protected $with = [
        'fields',
        'documentImage'
    ];

    public function individual()
    {
        return $this->belongsTo(Individual::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function documentImage()
    {
        return $this->hasMany(DocumentImage::class);
    }

    public function lastDocumentImage()
    {
        return $this->documentImage()->get()->last();
    }
}
