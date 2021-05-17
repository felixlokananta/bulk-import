<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'order_date',
        'order_status',
        'shipped_date'
    ];

    /**
     * Get the associate items within the order
     */
    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }


    /**
     * Get the associate user
     */
    public function user()
    {
        return $this->belongsTo(Customer::class);
    }


}
