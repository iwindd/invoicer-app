<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'application', 'isApplication', 'firstname', 'lastname', 'email', 'joinedAt', 'createdBy_id'
    ];

    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class, "createdBy_id");
    }
}
