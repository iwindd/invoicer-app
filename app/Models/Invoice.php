<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'note', 'start', 'end', 'status', 'user_id'
    ];
        
    /**
     * customer
     *
     * @return BelongsTo
     */
    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    
    /**
     * evidences
     *
     * @return HasMany
     */
    public function evidences() : HasMany {
        return $this->hasMany(Report::class);
    }
    
    /**
     * evidence
     *
     * @return HasOne
     */
    public function evidence() : HasOne {
        return $this->hasOne(Report::class)->latest();
    }
    
    /**
     * user
     *
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * items
     *
     * @return HasMany
     */
    public function items() : HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
