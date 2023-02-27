<div class="card">
    <div class="card-body">
        <h4 class="fw-bolder border-bottom pb-50 mb-1 text-center">Scruitny of Facts</h4>
        <div class="info-container">
            <ul class="list-unstyled">
                <li class="mb-75">
                    <span class="fw-bolder me-25">Name of PS:</span>
                    <span>{{ $fir->policeStation->ps_name_eng }}</span>
                </li>
                <li class="mb-75">
                    <span class="fw-bolder me-25">FIR:</span>
                    <span>{{ $fir->fir_no ." / ". $fir->fir_year }}</span>
                </li>
                <li class="mb-75">
                    <span class="fw-bolder me-25">US:</span>
                    <span class="badge bg-light-info">{{ implode(" | ", $fir->sections->pluck('sectionLaws')->pluck('section_name')->toArray()) }}</span>
                </li>
                <li class="mb-75">
                    <span class="fw-bolder me-25">Place of Offence:</span>
                    <span dir="rtl">{!! preg_replace('/[\x{0600}-\x{06FF}]+/u', '<span class="urdu">$0</span>', $fir->place_of_crime) !!}</span>
                </li>
                <li class="mb-75">
                    <span class="fw-bolder me-25">Date & Time of Offence:</span>
                    <span>{{ \Carbon\Carbon::parse($fir->datetime_of_event)->format('d-m-Y H:i') }}</span>
                </li>
                <li class="mb-75">
                    <span class="fw-bolder me-25">Name of Complainant:</span>
                    <span>{!! preg_replace('/[\x{0600}-\x{06FF}]+/u', '<span class="urdu">$0</span>', $fir->comp_name) !!}</span>
                </li>
                <li class="mb-75">
                    <span class="fw-bolder me-25">Name of IO:</span>
                    <span class="">
                        @if($fir->fir_current_io_id < 0)
                            {!! preg_replace('/[\x{0600}-\x{06FF}]+/u', '<span class="urdu">$0</span>', $fir->investigationOfficer->other_officer) !!}
                        @elseif($fir->fir_current_io_id > 0)
                            {!! preg_replace('/[\x{0600}-\x{06FF}]+/u', '<span class="urdu">$0</span>', $fir->investigationOfficer->employee->pe_name) !!} <span>{{ $fir->investigationOfficer->employee->rank->rank_name_en }}</span>
                        @endif
                    </span>
                </li>
                <li class="mb-75">
                    <span class="fw-bolder me-25">No. of Accused:</span>
                    <span>{{ $fir->unknown_accused }}</span>
                </li>
                <li class="mb-75">
                    <span class="fw-bolder me-25">Accused Nominated:</span>
                    <span>{{ $total_suspects }}</span>
                </li>
                <li class="mb-75">
                    <span class="fw-bolder me-25">Accused Arrested:</span>
                    <span>{{ $total_arrested }}</span>
                </li>
                <li class="mb-75">
                    <span class="fw-bolder me-25">No. if Accused Absconder:</span>
                    <span>{{ $total_absconder }}</span>
                </li>
                <li class="mb-75">
                    <span class="fw-bolder me-25">Motive:</span>
                    <span></span>
                </li>
                <li class="mb-75">
                    <span class="fw-bolder me-25">Brief Facts:</span>
                    <span>{!! preg_replace('/[\x{0600}-\x{06FF}]+/u', '<span class="urdu">$0</span>', $fir->detail->fir_short_detail) !!}</span>
                </li>

            </ul>
        </div>
    </div>
</div>
