<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportJob extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'csv_path'
    ];

    /**
     * Get the associate orders for a customer
     */
    public function records()
    {
        return $this->hasMany(ImportJobRecord::class);
    }
}
