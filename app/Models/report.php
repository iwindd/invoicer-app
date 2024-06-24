<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class report extends Model
{
    use HasFactory;

    protected $fillable = [
        'image', 'status'
    ];

    public function invoice() : BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

}
