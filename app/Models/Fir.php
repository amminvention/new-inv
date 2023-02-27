<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fir extends Model
{
    use HasFactory;

    protected $table = 'tbl_fir';

    public function sections()
    {
        return $this->hasMany(FirSection::class, 'fs_fir_id', 'fir_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'fir_dis_id', 'dis_id')->withDefault();
    }

    public function policeStation()
    {
        return $this->belongsTo(PoliceStation::class, 'ps_id', 'ps_id')->withDefault();
    }

    public function investigationOfficer()
    {
        return $this->hasOne(FirInvestigationOfficer::class, 'io_fir_id', 'fir_id')->withDefault();
    }

    public function detail()
    {
        return $this->hasOne(FirDetail::class, 'fd_fir_id', 'fir_id')->withDefault();
    }
}
