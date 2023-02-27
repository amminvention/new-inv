<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirInvestigationOfficer extends Model
{
    use HasFactory;

    protected $table = 'fir_investigations_officer';

    public function employee()
    {
        return $this->belongsTo(PoliceEmployee::class, 'io_pe_id', 'pe_id')->withDefault();
    }
}
