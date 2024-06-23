<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'note', 'start', 'end', 'status', 'createdby_id'
    ];
    
    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class, "owner_id");
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, "createdby_id");
    }

    public function items() : HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }
}
