<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'image', 'status'
    ];
    
    /**
     * invoice
     *
     * @return BelongsTo
     */
    public function invoice() : BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

}
