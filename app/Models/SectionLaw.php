<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionLaw extends Model
{
    use HasFactory;

    protected $primaryKey = 'sec_id';

    protected $table = 'sections_law';
}
