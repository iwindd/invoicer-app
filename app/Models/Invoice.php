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
        'note', 'start', 'end', 'status', 'user_id'
    ];
    
    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function evidence() : HasMany {
        return $this->hasMany(Report::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items() : HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
