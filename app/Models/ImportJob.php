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
        'user_id',
        'status',
        'csv_path',
    ];

    /**
     * Get the associate records for this bulk import job
     */
    public function records()
    {
        return $this->hasMany(ImportJobRecord::class);
    }

    /**
     * Get the associate owner for this bulk import job
     */
    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
