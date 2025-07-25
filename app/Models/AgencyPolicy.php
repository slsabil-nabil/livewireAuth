<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'title',
        'content',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
