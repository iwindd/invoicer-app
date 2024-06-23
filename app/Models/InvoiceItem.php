<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'name', 'amount', 'value'
    ];

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Invoice::class, "invoice_id");
    }
}
