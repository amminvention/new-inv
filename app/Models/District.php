<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';

    public function range()
    {
        return $this->belongsTo(District::class, 'dis_reg_id', 'dis_id')->withDefault();
    }
}
