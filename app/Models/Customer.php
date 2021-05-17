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
}
