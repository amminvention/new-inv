<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoliceEmployee extends Model
{
    use HasFactory;

    protected $table = 'police_employee';

    public function rank()
    {
        return $this->belongsTo(Rank::class, 'pe_rank', 'rank_id')->withDefault();
    }
}
