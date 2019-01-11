<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'note_type_code',
        'description',
        'affected_document_type_code',
        'affected_document_series',
        'affected_document_number',
        'total_global_discount',
        'total_prepayment',
    ];

    public function document()
    {
        return $this->hasOne(Document::class);
    }
}