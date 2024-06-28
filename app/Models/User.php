<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'lineToken'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * customer
     *
     * @return HasOne
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'application_id');
    }
    
    /**
     * customers
     *
     * @return HasMany
     */
    public function customers() : HasMany
    {
        return $this->hasMany(Customer::class);
    }
    
    /**
     * invoices
     *
     * @param  mixed $filterType
     * @return void
     */
    public function invoices(?string $filterType = '--')
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
    
    /**
     * payments
     *
     * @return HasMany
     */
    public function payments() : HasMany {
        return $this->hasMany(Payment::class);
    }
    
    /**
     * activities
     *
     * @return HasMany
     */
    public function activities() : HasMany {
        return $this->hasMany(Activity::class);
    }
}
