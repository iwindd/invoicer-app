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
        'id', 'firstname', 'lastname', 'email', 'joined_at', 'application_id'
    ];

    public function application() : BelongsTo {
        return $this->belongsTo(User::class);
    }
    
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'application_id', 'customer_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoices(?string $filterType = '--') : HasMany
    {
        $query = $this->hasMany(Invoice::class);

        if ($filterType !== '--') {
            switch ($filterType) {
                case '4':
                    $query->where([
                        ['status', 0],
                        ['end', '<', now()]
                    ]);
                    break;
                case '3':
                    $query->where([
                        ['status', 0],
                        ['start', '<', now()],
                        ['end', '>', now()]
                    ]);
                    break;
                default:
                    $query->where('status', $filterType);
                    break;
            }
        }

        return $query;
    }
}
