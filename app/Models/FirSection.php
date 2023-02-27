<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirSection extends Model
{
    use HasFactory;

    protected $primaryKey = 'fs_id';

    protected $table = 'fir_sections';

    public function sectionLaws()
    {
        return $this->belongsTo(SectionLaw::class, 'fs_section_id', 'sec_id')->withDefault();
    }
}
