<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'user_id',
        'first_name',
        'last_name'
    ];

    /**
     * Get the associate orders for a customer
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the user owner for a customer
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
