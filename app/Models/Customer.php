<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'application', 'isApplication', 'firstname', 'lastname', 'email', 'joinedAt', 'createdBy_id'
    ];

    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, "createdBy_id");
    }

    public function invoices() : HasMany
    {
        return $this->hasMany(Invoice::class, 'owner_id');
    }
}
