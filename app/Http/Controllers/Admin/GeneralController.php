<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Fir;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class GeneralController extends Controller
{
    // Login
    public function login(){
        $pageConfigs = [
            'bodyClass' => "bg-full-screen-image",
            'blankPage' => true
        ];

        return view('auth.login', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    public function verify(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if(!empty($user)){
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                return redirect('/')
                    ->withSuccess('You have Successfully loggedin');
            }
        }else{
            return redirect()->route('auths.login');
        }
    }

    public function index(Request $request)
    {
        $data['breadcrumbs'] = [
            ['link' => "/", 'name' => "Dashboard"], ['link' => "/fir-list", 'name' => "Fir List"]
        ];
//        $data['fir'] = DB::table('tbl_fir')
//            ->select('tbl_fir.*', 'districts.dist_name_eng as district', 'police_stations.ps_name_eng as police_station', 'range.dist_name_eng as range')
//            ->addSelect(DB::raw("GROUP_CONCAT(CONCAT(sections_law.section_name) SEPARATOR '|') as sections"))
//            ->addSelect('ranks.rank_name_en', 'pe.pe_name', 'fio.other_officer')
//            ->whereIn('fir_sections.fs_section_id', [277, 401, 397, 403, 399]) // 277 - murder
//            ->join('fir_sections', 'tbl_fir.fir_id', '=', 'fir_sections.fs_fir_id')
//            ->join('sections_law', 'fir_sections.fs_section_id', '=', 'sections_law.sec_id')
//            ->join('districts', 'tbl_fir.fir_dis_id', '=', 'districts.dis_id')
//            ->join('police_stations', 'tbl_fir.ps_id', '=', 'police_stations.ps_id')
//            ->join('districts as range', 'districts.dis_reg_id', '=', 'range.dis_id')
//            ->leftJoin('fir_investigations_officer as fio', 'tbl_fir.fir_id', '=', 'fio.io_fir_id')
//            ->leftJoin('police_employee as pe', 'fio.io_pe_id', '=', 'pe.pe_id')
//            ->leftJoin('ranks', 'pe.pe_rank', '=', 'ranks.rank_id')
//            ->groupBy('tbl_fir.fir_id')
//            ->limit(100)
//            ->get();
        $data['range'] = District::where('dis_reg_id', 0)->pluck('dist_name_eng', 'dis_id');
        if ($request->ajax()) {

            $data = Fir::with('investigationOfficer.employee.rank:rank_id,rank_name_en', 'detail:fd_id,fd_fir_id,fir_short_detail', 'district.range:dis_id,dist_name_eng', 'district:dis_id,dis_reg_id,dist_name_eng', 'policeStation:ps_id,ps_name_eng', 'sections.sectionLaws:sec_id,section_name', 'investigationOfficer.employee:pe_id,pe_name,pe_rank')
                ->whereHas('sections', function ($query) {
                    $query->whereIn('fs_section_id', [277, 401, 397, 403, 399])->where('is_primary', 1);
                });

            $data = $data->orderBy('fir_id', 'desc');
            return DataTables::of($data)
                ->addColumn('range', function ($data) {
                    return $data->district->range->dist_name_eng;
                })
                ->addColumn('district', function ($data) {
                    return $data->district->dist_name_eng;
                })
                ->addColumn('police_station', function ($data) {
                    return $data->policeStation->ps_name_eng;
                })
                ->addColumn('officer', function ($data) {
                    if ($data->fir_current_io_id < 0) {
                        return preg_replace('/[\x{0600}-\x{06FF}]+/u', '<span class="urdu">$0</span>', $data->other_officer);
                    } elseif ($data->fir_current_io_id > 0) {
                        return preg_replace('/[\x{0600}-\x{06FF}]+/u', '<span class="urdu">$0</span>', $data->investigationOfficer->employee->pe_name) . " " . $data->investigationOfficer->employee->rank->rank_name_en;
                    }
                })
                ->addColumn('sections', function ($data) {
                    return '<span class="badge bg-light-info">' . implode(" | ", $data->sections->pluck('sectionLaws')->pluck('section_name')->toArray()) . '</span>';
                })
                ->addColumn('action', function ($data) {
                    $btn = '<span class="action-add"><a href="' . route('admin.fir.show-form-detail', ['id' => $data->fir_id, 'type' => 1]) . '"><i data-feather="plus"></i></a></span>';
                    $btn .= '<span class="action-edit"><a href="' . route('admin.fir.form-detail-b', $data->fir_id) . '"><i data-feather="edit"></i></a></span>';
                    return $btn;
                })
                ->rawColumns(['action', 'sections', 'officer'])
                ->addIndexColumn()
                ->filter(function ($query) {
                    if (request()->has('fir_no') && !empty(request('fir_no'))) {
                        $query->where('fir_no', request('fir_no'));
                    }

                    if (request()->has('fir_year') && request('fir_year')) {
                        $query->where('fir_year', request('fir_year'));
                    }

                    if (request()->has('sections') && request('sections')) {
                        $query->where('sections', 'like', "%{request('sections')}%");
                    }
                })
                ->make(true);
        }

        return view('admin.index', $data);
    }

    public function formDetailB($id)
    {
        $data['breadcrumbs'] = [
            ['link' => "/", 'name' => "Dashboard"], ['link' => "/fir-list", 'name' => "Fir List"], ['name' => "Quality of Investigation"]
        ];
        $data['fir'] = Fir::with('investigationOfficer.employee.rank:rank_id,rank_name_en', 'detail:fd_id,fd_fir_id,fir_short_detail', 'district.range:dis_id,dist_name_eng', 'district:dis_id,dis_reg_id,dist_name_eng', 'policeStation:ps_id,ps_name_eng', 'sections.sectionLaws:sec_id,section_name', 'investigationOfficer.employee:pe_id,pe_name,pe_rank')->where('fir_id', $id)->first();
        $data['feedback'] = DB::table('facts_quality')->where('fir_id', $id)->first();
        $data['form_b'] = array(
            [
                'head' => 'Computerized FIR',
                'field_name' => 'fir',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Immediate Response',
                'field_name' => 'response',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Delay if FSL/Chemical/DNA/Request',
                'field_name' => 'Delay_lab',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Obtain ML Report',
                'field_name' => 'Obtain_ML_Report',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Obtain PM Report',
                'field_name' => 'Obtain_PM_Report',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Delay to Crime Scene Visit',
                'field_name' => 'Delay_to_Crime_Scene_Visit',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Sketch of Crime Scene',
                'field_name' => 'Sketch_of_Crime_Scene',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Finger print lifted from crime scene',
                'field_name' => 'Finger_print_lifted_from_crime_scene',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Obtained finger print of accused',
                'field_name' => 'Obtained_finger_print_of_accused',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Obtained Chemical Examination Report',
                'field_name' => 'Obtained_Chemical_Examination_Report',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Obtained FSL Report',
                'field_name' => 'Obtained_FSL_Report',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'IP Parade Held',
                'field_name' => 'IP_Parade_Held',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'CDR Obtained',
                'field_name' => 'CDR_Obtained',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'DNA Test Done',
                'field_name' => 'DNA_Test_Done',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Digital Evidence Collected',
                'field_name' => 'Digital_Evidence_Collected',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Photograph of Crime Scene',
                'field_name' => 'Photograph_of_Crime_Scene',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Sketch of Accused',
                'field_name' => 'Sketch_of_Accused',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Obtained Criminal record of accused',
                'field_name' => 'Obtained_Criminal_record_of_accused',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Photograph of accused',
                'field_name' => 'Photograph_of_accused',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Copy of CNIC of accused',
                'field_name' => 'Copy_of_CNIC_of_accused',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Copy of CNIC of complainant attached',
                'field_name' => 'Copy_of_CNIC_of_complainant_attached',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'CRO Entry',
                'field_name' => 'CRO_Entry',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Interrogation',
                'field_name' => 'Interrogation',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Circumstancial Evidence',
                'field_name' => 'Circumstancial_Evidence',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Hue & Cry Notice',
                'field_name' => 'Hue_Cry_Notice',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Foot Mold',
                'field_name' => 'Foot_Mold',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'No. of statement u/s 161',
                'field_name' => 'No_of_statement_u_s_161',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'No. of suspect interrogated',
                'field_name' => 'No_of_suspect_interrogated',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
        );


        $data['stat_b'] = $this->stat_b($id);
        $data['stat_c'] = $this->stat_c($id);
        $data['stat_d'] = $this->stat_d($id);
        $data['stat_e'] = $this->stat_e($id);
        $data['total_suspects'] = DB::table('fir_witnesses')->where('wit_id', $data['fir']->fir_id)->count();
        $data['total_arrested'] = DB::table('fir_witnesses')->where('wit_id', $data['fir']->fir_id)->where('wit_arrest_date', '!=', '0000-00-00')->whereNotNull('wit_arrest_date')->count();
        $data['total_absconder'] = DB::table('register4')->where('fir_id', $data['fir']->fir_id)->where('status_person', 7)->count();
        return view('admin.form-detail-b', $data);
    }

    public function formDetailC($id)
    {
        $data['breadcrumbs'] = [
            ['link' => "/", 'name' => "Dashboard"], ['link' => "/fir-list", 'name' => "Fir List"], ['name' => "Quality of Evidence"]
        ];
        $data['form_c'] = array(
            [
                'head' => 'Recovery',
                'field_name' => 'recovery',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Statement U/S 164 CrPC',
                'field_name' => 'statement_confession',
                'options' => [
                    'average',
                    'good'
                ]
            ],
            [
                'head' => 'Quality of crime scene sketch',
                'field_name' => 'sketch_quality',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Finger print result',
                'field_name' => 'fingerprint_result',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Accused finger prints',
                'field_name' => 'accused_fingerprints',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Chemical Examination result',
                'field_name' => 'chem_exam_result',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'FSL Result',
                'field_name' => 'fsl_result',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Accused ID Parade',
                'field_name' => 'accused_id_parade',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Evidence developed through',
                'field_name' => 'evidence_developed',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Evidence developed through DNA',
                'field_name' => 'evidence_dna',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Result of digital evidence',
                'field_name' => 'digital_evidence_result',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Result of accused sketch',
                'field_name' => 'accused_sketch_result',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Circumstancial',
                'field_name' => 'circumstantial_evidence',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Corroborated evidence',
                'field_name' => 'corroborated_evidence',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Verification of accused',
                'field_name' => 'accused_verification',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Eye Witness available',
                'field_name' => 'eyewitness_available',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Outcome of suspects interrogation',
                'field_name' => 'suspect_interrogation_result',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Empties recovered from crime scene with weapon',
                'field_name' => 'weapon_empties_recovered',
                'options' => [
                    'no',
                    'yes'
                ]
            ]
        );
        $data['feedback'] = DB::table('evidence_quality')->where('fir_id', $id)->first();
        $data['fir'] = Fir::with('investigationOfficer.employee.rank:rank_id,rank_name_en', 'detail:fd_id,fd_fir_id,fir_short_detail', 'district.range:dis_id,dist_name_eng', 'district:dis_id,dis_reg_id,dist_name_eng', 'policeStation:ps_id,ps_name_eng', 'sections.sectionLaws:sec_id,section_name', 'investigationOfficer.employee:pe_id,pe_name,pe_rank')->where('fir_id', $id)->first();
        $data['total_suspects'] = DB::table('fir_witnesses')->where('wit_id', $data['fir']->fir_id)->count();
        $data['total_arrested'] = DB::table('fir_witnesses')->where('wit_id', $data['fir']->fir_id)->where('wit_arrest_date', '!=', '0000-00-00')->whereNotNull('wit_arrest_date')->count();
        $data['total_absconder'] = DB::table('register4')->where('fir_id', $data['fir']->fir_id)->where('status_person', 7)->count();

        $data['stat_b'] = $this->stat_b($id);
        $data['stat_c'] = $this->stat_c($id);
        $data['stat_d'] = $this->stat_d($id);
        $data['stat_e'] = $this->stat_e($id);
        return view('admin.form-detail-c', $data);
    }

    public function formDetailD($id)
    {
        $data['breadcrumbs'] = [
            ['link' => "/", 'name' => "Dashboard"], ['link' => "/fir-list", 'name' => "Fir List"], ['name' => "Monitoring of Supervision"]
        ];
        $data['form_d'] = array(
            [
                'head' => 'Issued Supervisory Diary',
                'field_name_1' => 'issued_supervisory_diary_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'issued_supervisory_diary_2',
                'options_2' => [
                    'no',
                    'yes'
                ],

            ],
            [
                'head' => 'Visited place of',
                'field_name_1' => 'visited_place_of_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'visited_place_of_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Issued Initial',
                'field_name_1' => 'issued_initial_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'issued_initial_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Issued Instruction diary',
                'field_name_1' => 'issued_instruction_diary_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'issued_instruction_diary_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Visited & Meet heirs of victim',
                'field_name_1' => 'visited_meet_heirs_of_victim_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'visited_meet_heirs_of_victim_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Whether proper Section of law applied',
                'field_name_1' => 'proper_section_of_law_applied_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'proper_section_of_law_applied_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Physical inspected the accused',
                'field_name_1' => 'quality_of_diaries_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'quality_of_diaries_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Quality of Diaries',
                'field_name_1' => 'daily_diary_entry_arrival_departure_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'daily_diary_entry_arrival_departure_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Daily diary entry / arrival departure',
                'field_name_1' => 'efforts_for_arrest_of_accused_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'efforts_for_arrest_of_accused_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Efforts for the arrest of accused',
                'field_name_1' => 'efforts_for_collection_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'efforts_for_collection_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Efforts for the collection',
                'field_name_1' => 'quality_of_evidence_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'quality_of_evidence_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Quality of Evidence',
                'field_name_1' => 'quality_of_investigation_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'quality_of_investigation_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Quality of investigation',
                'field_name_1' => 'copies_of_cnic_of_pws_attached_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'copies_of_cnic_of_pws_attached_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Copies of CNIC of PWs attached',
                'field_name_1' => 'issue_crs_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'issue_crs_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Issue CRS',
                'field_name_1' => 'monitored_by_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'monitored_by_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ]
        );
        $data['feedback'] = DB::table('monitoring_supervision')->where('fir_id', $id)->first();
        $data['fir'] = Fir::with('investigationOfficer.employee.rank:rank_id,rank_name_en', 'detail:fd_id,fd_fir_id,fir_short_detail', 'district.range:dis_id,dist_name_eng', 'district:dis_id,dis_reg_id,dist_name_eng', 'policeStation:ps_id,ps_name_eng', 'sections.sectionLaws:sec_id,section_name', 'investigationOfficer.employee:pe_id,pe_name,pe_rank')->where('fir_id', $id)->first();
        $data['total_suspects'] = DB::table('fir_witnesses')->where('wit_id', $data['fir']->fir_id)->count();
        $data['total_arrested'] = DB::table('fir_witnesses')->where('wit_id', $data['fir']->fir_id)->where('wit_arrest_date', '!=', '0000-00-00')->whereNotNull('wit_arrest_date')->count();
        $data['total_absconder'] = DB::table('register4')->where('fir_id', $data['fir']->fir_id)->where('status_person', 7)->count();
        $data['stat_b'] = $this->stat_b($id);
        $data['stat_c'] = $this->stat_c($id);
        $data['stat_d'] = $this->stat_d($id);
        $data['stat_e'] = $this->stat_e($id);
        return view('admin.form-detail-d', $data);
    }

    public function formDetailE($id)
    {
        $data['breadcrumbs'] = [
            ['link' => "/", 'name' => "Dashboard"], ['link' => "/fir-list", 'name' => "Fir List"], ['name' => "Scruitny of Legal Branch"]
        ];
        $data['form_e'] = array(
            [
                'head' => 'Quality of Investigation',
                'field_name' => 'quality_inv',
                'options' => [
                    'poor',
                    'average',
                    'good'
                ]
            ],
            [
                'head' => 'Quality of Evidence',
                'field_name' => 'quality_evd',
                'options' => [
                    'poor',
                    'average',
                    'good'
                ]
            ],
            [
                'head' => 'Quality of Supervision',
                'field_name' => 'quality_sup',
                'options' => [
                    'poor',
                    'average',
                    'good'
                ]
            ]
        );
        $data['fir'] = Fir::with('investigationOfficer.employee.rank:rank_id,rank_name_en', 'detail:fd_id,fd_fir_id,fir_short_detail', 'district.range:dis_id,dist_name_eng', 'district:dis_id,dis_reg_id,dist_name_eng', 'policeStation:ps_id,ps_name_eng', 'sections.sectionLaws:sec_id,section_name', 'investigationOfficer.employee:pe_id,pe_name,pe_rank')->where('fir_id', $id)->first();
        $data['feedback'] = DB::table('facts_scrutiny_legal_branch')->where('fir_id', $id)->first();
        $data['total_suspects'] = DB::table('fir_witnesses')->where('wit_id', $data['fir']->fir_id)->count();
        $data['total_arrested'] = DB::table('fir_witnesses')->where('wit_id', $data['fir']->fir_id)->where('wit_arrest_date', '!=', '0000-00-00')->whereNotNull('wit_arrest_date')->count();
        $data['total_absconder'] = DB::table('register4')->where('fir_id', $data['fir']->fir_id)->where('status_person', 7)->count();
        $data['stat_b'] = $this->stat_b($id);
        $data['stat_c'] = $this->stat_c($id);
        $data['stat_d'] = $this->stat_d($id);
        $data['stat_e'] = $this->stat_e($id);
        return view('admin.form-detail-e', $data);
    }

    public function formDetailSave(Request $request, $id)
    {
        $data['fir'] = Fir::where('fir_id', $id)->firstOrFail();

        try {
            $table = $request->get('table');
            $column = $request->get('column');
            $value = $request->get('value');

            $data = DB::table($table)->where('fir_id', $id)->first();
            if (empty($data)) {
                DB::table($table)->insert([
                    $column => $value,
                    'fir_id' => $id
                ]);
                $message = 'Feedback Saved Successfully';
            } else {
                DB::table($table)->where('fir_id', $id)->update([
                    $column => $value
                ]);
                $message = 'Feedback Updated Successfully';
            }
            return response()->json(['status' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    private function stat_b($id)
    {
        $data['form_b'] = array(
            [
                'head' => 'Computerized FIR',
                'field_name' => 'fir',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Immediate Response',
                'field_name' => 'response',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Delay if FSL/Chemical/DNA/Request',
                'field_name' => 'Delay_lab',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Obtain ML Report',
                'field_name' => 'Obtain_ML_Report',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Obtain PM Report',
                'field_name' => 'Obtain_PM_Report',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Delay to Crime Scene Visit',
                'field_name' => 'Delay_to_Crime_Scene_Visit',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Sketch of Crime Scene',
                'field_name' => 'Sketch_of_Crime_Scene',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Finger print lifted from crime scene',
                'field_name' => 'Finger_print_lifted_from_crime_scene',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Obtained finger print of accused',
                'field_name' => 'Obtained_finger_print_of_accused',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Obtained Chemical Examination Report',
                'field_name' => 'Obtained_Chemical_Examination_Report',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Obtained FSL Report',
                'field_name' => 'Obtained_FSL_Report',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'IP Parade Held',
                'field_name' => 'IP_Parade_Held',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'CDR Obtained',
                'field_name' => 'CDR_Obtained',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'DNA Test Done',
                'field_name' => 'DNA_Test_Done',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Digital Evidence Collected',
                'field_name' => 'Digital_Evidence_Collected',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Photograph of Crime Scene',
                'field_name' => 'Photograph_of_Crime_Scene',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Sketch of Accused',
                'field_name' => 'Sketch_of_Accused',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Obtained Criminal record of accused',
                'field_name' => 'Obtained_Criminal_record_of_accused',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Photograph of accused',
                'field_name' => 'Photograph_of_accused',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Copy of CNIC of accused',
                'field_name' => 'Copy_of_CNIC_of_accused',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Copy of CNIC of complainant attached',
                'field_name' => 'Copy_of_CNIC_of_complainant_attached',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'CRO Entry',
                'field_name' => 'CRO_Entry',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Interrogation',
                'field_name' => 'Interrogation',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Circumstancial Evidence',
                'field_name' => 'Circumstancial_Evidence',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Hue & Cry Notice',
                'field_name' => 'Hue_Cry_Notice',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Foot Mold',
                'field_name' => 'Foot_Mold',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'No. of statement u/s 161',
                'field_name' => 'No_of_statement_u_s_161',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'No. of suspect interrogated',
                'field_name' => 'No_of_suspect_interrogated',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
        );
        $counts_null_b = 0;
        $count_not_null_b = 0;
        $positive_b = 0;
        $negative_b = 0;
        $total_fields_b = 0;
        foreach ($data['form_b'] as $key => $value) {
            $counts_null_b += DB::table('facts_quality')->where('fir_id', $id)->whereNull($value['field_name'])->count();
            $count_not_null_b += DB::table('facts_quality')->where('fir_id', $id)->whereNotNull($value['field_name'])->count();
            $negative_b += DB::table('facts_quality')->where('fir_id', $id)->where($value['field_name'], 0)->count();
            $positive_b += DB::table('facts_quality')->where('fir_id', $id)->where($value['field_name'], 1)->count();
            $total_fields_b++;
        }
        $data['pending_b'] = $counts_null_b;
        $data['completed_b'] = $count_not_null_b;
        $data['positive_b'] = $positive_b;
        $data['negative_b'] = $negative_b;
        $total_b = $counts_null_b + $count_not_null_b;
        $data['total_b'] = $total_fields_b;
        $data['avg_b'] = $total_b > 0 ? (intval(($positive_b / ($counts_null_b + $count_not_null_b)) * 100)) : 0;

        return $data;
    }

    private function stat_c($id)
    {
        $data['form_c'] = array(
            [
                'head' => 'Recovery',
                'field_name' => 'recovery',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Statement U/S 164 CrPC',
                'field_name' => 'statement_confession',
                'options' => [
                    'average',
                    'good'
                ]
            ],
            [
                'head' => 'Quality of crime scene sketch',
                'field_name' => 'sketch_quality',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Finger print result',
                'field_name' => 'fingerprint_result',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Accused finger prints',
                'field_name' => 'accused_fingerprints',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Chemical Examination result',
                'field_name' => 'chem_exam_result',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'FSL Result',
                'field_name' => 'fsl_result',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Accused ID Parade',
                'field_name' => 'accused_id_parade',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Evidence developed through',
                'field_name' => 'evidence_developed',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Evidence developed through DNA',
                'field_name' => 'evidence_dna',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Result of digital evidence',
                'field_name' => 'digital_evidence_result',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Result of accused sketch',
                'field_name' => 'accused_sketch_result',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Circumstancial',
                'field_name' => 'circumstantial_evidence',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Corroborated evidence',
                'field_name' => 'corroborated_evidence',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Verification of accused',
                'field_name' => 'accused_verification',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Eye Witness available',
                'field_name' => 'eyewitness_available',
                'options' => [
                    'no',
                    'yes'
                ]
            ],
            [
                'head' => 'Outcome of suspects interrogation',
                'field_name' => 'suspect_interrogation_result',
                'options' => [
                    'negative',
                    'positive'
                ]
            ],
            [
                'head' => 'Empties recovered from crime scene with weapon',
                'field_name' => 'weapon_empties_recovered',
                'options' => [
                    'no',
                    'yes'
                ]
            ]
        );
        $counts_null_c = 0;
        $count_not_null_c = 0;
        $positive_c = 0;
        $negative_c = 0;
        $total_fields_c = 0;
        foreach ($data['form_c'] as $key => $value) {
            $counts_null_c += DB::table('evidence_quality')->where('fir_id', $id)->whereNull($value['field_name'])->count();
            $count_not_null_c += DB::table('evidence_quality')->where('fir_id', $id)->whereNotNull($value['field_name'])->count();
            $negative_c += DB::table('evidence_quality')->where('fir_id', $id)->where($value['field_name'], 0)->count();
            $positive_c += DB::table('evidence_quality')->where('fir_id', $id)->where($value['field_name'], 1)->count();
            $total_fields_c++;
        }
        $data['pending_c'] = $counts_null_c;
        $data['completed_c'] = $count_not_null_c;
        $data['positive_c'] = $positive_c;
        $data['negative_c'] = $negative_c;
        $total_c = $counts_null_c + $count_not_null_c;
        $data['total_c'] = $total_fields_c;
        $data['avg_c'] = $total_c > 0 ? (intval(($positive_c / ($counts_null_c + $count_not_null_c)) * 100)) : 0;

        return $data;
    }

    private function stat_d($id)
    {
        $data['form_d'] = array(
            [
                'head' => 'Issued Supervisory Diary',
                'field_name_1' => 'issued_supervisory_diary_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'issued_supervisory_diary_2',
                'options_2' => [
                    'no',
                    'yes'
                ],

            ],
            [
                'head' => 'Visited place of',
                'field_name_1' => 'visited_place_of_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'visited_place_of_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Issued Initial',
                'field_name_1' => 'issued_initial_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'issued_initial_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Issued Instruction diary',
                'field_name_1' => 'issued_instruction_diary_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'issued_instruction_diary_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Visited & Meet heirs of victim',
                'field_name_1' => 'visited_meet_heirs_of_victim_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'visited_meet_heirs_of_victim_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Whether proper Section of law applied',
                'field_name_1' => 'proper_section_of_law_applied_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'proper_section_of_law_applied_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Physical inspected the accused',
                'field_name_1' => 'quality_of_diaries_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'quality_of_diaries_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Quality of Diaries',
                'field_name_1' => 'daily_diary_entry_arrival_departure_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'daily_diary_entry_arrival_departure_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Daily diary entry / arrival departure',
                'field_name_1' => 'efforts_for_arrest_of_accused_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'efforts_for_arrest_of_accused_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Efforts for the arrest of accused',
                'field_name_1' => 'efforts_for_collection_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'efforts_for_collection_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Efforts for the collection',
                'field_name_1' => 'quality_of_evidence_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'quality_of_evidence_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Quality of Evidence',
                'field_name_1' => 'quality_of_investigation_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'quality_of_investigation_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Quality of investigation',
                'field_name_1' => 'copies_of_cnic_of_pws_attached_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'copies_of_cnic_of_pws_attached_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Copies of CNIC of PWs attached',
                'field_name_1' => 'issue_crs_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'issue_crs_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ],
            [
                'head' => 'Issue CRS',
                'field_name_1' => 'monitored_by_1',
                'options_1' => [
                    'no',
                    'yes'
                ],
                'field_name_2' => 'monitored_by_2',
                'options_2' => [
                    'no',
                    'yes'
                ],
            ]
        );
        $counts_null_d = 0;
        $count_not_null_d = 0;
        $positive_d = 0;
        $negative_d = 0;
        $total_fields_d = 0;
        foreach ($data['form_d'] as $key => $value) {
            $counts_null_d += DB::table('monitoring_supervision')->where('fir_id', $id)->whereNull($value['field_name_1'])->count();
            $count_not_null_d += DB::table('monitoring_supervision')->where('fir_id', $id)->whereNotNull($value['field_name_1'])->count();
            $negative_d += DB::table('monitoring_supervision')->where('fir_id', $id)->where($value['field_name_1'], 0)->count();
            $positive_d += DB::table('monitoring_supervision')->where('fir_id', $id)->where($value['field_name_1'], 1)->count();
            $total_fields_d++;
        }
        foreach ($data['form_d'] as $key => $value) {
            $counts_null_d += DB::table('monitoring_supervision')->where('fir_id', $id)->whereNull($value['field_name_2'])->count();
            $count_not_null_d += DB::table('monitoring_supervision')->where('fir_id', $id)->whereNotNull($value['field_name_2'])->count();
            $negative_d += DB::table('monitoring_supervision')->where('fir_id', $id)->where($value['field_name_2'], 0)->count();
            $positive_d += DB::table('monitoring_supervision')->where('fir_id', $id)->where($value['field_name_2'], 1)->count();
            $total_fields_d++;
        }
        $data['pending_d'] = $counts_null_d;
        $data['completed_d'] = $count_not_null_d;
        $data['positive_d'] = $positive_d;
        $data['negative_d'] = $negative_d;
        $total_d = $counts_null_d + $count_not_null_d;
        $data['total_d'] = $total_fields_d;
        $data['avg_d'] = $total_d > 0 ? (intval(($positive_d / ($counts_null_d + $count_not_null_d)) * 100)) : 0;

        return $data;
    }

    private function stat_e($id)
    {
        $data['form_e'] = array(
            [
                'head' => 'Quality of Investigation',
                'field_name' => 'quality_inv',
                'options' => [
                    'poor',
                    'average',
                    'good'
                ]
            ],
            [
                'head' => 'Quality of Evidence',
                'field_name' => 'quality_evd',
                'options' => [
                    'poor',
                    'average',
                    'good'
                ]
            ],
            [
                'head' => 'Quality of Supervision',
                'field_name' => 'quality_sup',
                'options' => [
                    'poor',
                    'average',
                    'good'
                ]
            ]
        );
        $counts_null_e = 0;
        $count_not_null_e = 0;
        $positive_e = 0;
        $negative_e = 0;
        $total_fields_e = 0;
        foreach ($data['form_e'] as $key => $value) {
            $counts_null_e += DB::table('facts_scrutiny_legal_branch')->where('fir_id', $id)->whereNull($value['field_name'])->count();
            $count_not_null_e += DB::table('facts_scrutiny_legal_branch')->where('fir_id', $id)->whereNotNull($value['field_name'])->count();
            $negative_e += DB::table('facts_scrutiny_legal_branch')->where('fir_id', $id)->where($value['field_name'], 0)->count();
            $positive_e += DB::table('facts_scrutiny_legal_branch')->where('fir_id', $id)->where($value['field_name'], 1)->count();
            $total_fields_e++;
        }
        $data['pending_e'] = $counts_null_e;
        $data['completed_e'] = $count_not_null_e;
        $data['positive_e'] = $positive_e;
        $data['negative_e'] = $negative_e;
        $total_e = $counts_null_e + $count_not_null_e;
        $data['total_e'] = $total_fields_e;
        $data['avg_e'] = $total_e > 0 ? (intval(($positive_e / ($counts_null_e + $count_not_null_e)) * 100)) : 0;

        return $data;
    }

    public function showformDetail($id, $type)
    {
        $data['type'] = $type;
        $data['fir'] = Fir::with('investigationOfficer.employee.rank:rank_id,rank_name_en', 'detail:fd_id,fd_fir_id,fir_short_detail', 'district.range:dis_id,dist_name_eng', 'district:dis_id,dis_reg_id,dist_name_eng', 'policeStation:ps_id,ps_name_eng', 'sections.sectionLaws:sec_id,section_name', 'investigationOfficer.employee:pe_id,pe_name,pe_rank')->where('fir_id', $id)->first();
        if ($type == 1) {
            $data['breadcrumbs'] = [
                ['link' => "/", 'name' => "Dashboard"], ['link' => "/fir-list", 'name' => "Fir List"], ['name' => "Quality of Investigation"]
            ];
            $data['form_b'] = array(
                [
                    'head' => 'Computerized FIR',
                    'field_name' => 'fir',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Immediate Response',
                    'field_name' => 'response',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Delay if FSL/Chemical/DNA/Request',
                    'field_name' => 'Delay_lab',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Obtain ML Report',
                    'field_name' => 'Obtain_ML_Report',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Obtain PM Report',
                    'field_name' => 'Obtain_PM_Report',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Delay to Crime Scene Visit',
                    'field_name' => 'Delay_to_Crime_Scene_Visit',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Sketch of Crime Scene',
                    'field_name' => 'Sketch_of_Crime_Scene',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Finger print lifted from crime scene',
                    'field_name' => 'Finger_print_lifted_from_crime_scene',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Obtained finger print of accused',
                    'field_name' => 'Obtained_finger_print_of_accused',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Obtained Chemical Examination Report',
                    'field_name' => 'Obtained_Chemical_Examination_Report',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Obtained FSL Report',
                    'field_name' => 'Obtained_FSL_Report',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'IP Parade Held',
                    'field_name' => 'IP_Parade_Held',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'CDR Obtained',
                    'field_name' => 'CDR_Obtained',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'DNA Test Done',
                    'field_name' => 'DNA_Test_Done',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Digital Evidence Collected',
                    'field_name' => 'Digital_Evidence_Collected',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Photograph of Crime Scene',
                    'field_name' => 'Photograph_of_Crime_Scene',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Sketch of Accused',
                    'field_name' => 'Sketch_of_Accused',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Obtained Criminal record of accused',
                    'field_name' => 'Obtained_Criminal_record_of_accused',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Photograph of accused',
                    'field_name' => 'Photograph_of_accused',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Copy of CNIC of accused',
                    'field_name' => 'Copy_of_CNIC_of_accused',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Copy of CNIC of complainant attached',
                    'field_name' => 'Copy_of_CNIC_of_complainant_attached',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'CRO Entry',
                    'field_name' => 'CRO_Entry',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Interrogation',
                    'field_name' => 'Interrogation',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Circumstancial Evidence',
                    'field_name' => 'Circumstancial_Evidence',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Hue & Cry Notice',
                    'field_name' => 'Hue_Cry_Notice',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Foot Mold',
                    'field_name' => 'Foot_Mold',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'No. of statement u/s 161',
                    'field_name' => 'No_of_statement_u_s_161',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'No. of suspect interrogated',
                    'field_name' => 'No_of_suspect_interrogated',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
            );
        } elseif ($type == 2) {

            $data['breadcrumbs'] = [
                ['link' => "/", 'name' => "Dashboard"], ['link' => "/fir-list", 'name' => "Fir List"], ['name' => "Quality of Evidence"]
            ];
            $data['form_c'] = array(
                [
                    'head' => 'Recovery',
                    'field_name' => 'recovery',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Statement U/S 164 CrPC',
                    'field_name' => 'statement_confession',
                    'options' => [
                        'average',
                        'good'
                    ]
                ],
                [
                    'head' => 'Quality of crime scene sketch',
                    'field_name' => 'sketch_quality',
                    'options' => [
                        'negative',
                        'positive'
                    ]
                ],
                [
                    'head' => 'Finger print result',
                    'field_name' => 'fingerprint_result',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Accused finger prints',
                    'field_name' => 'accused_fingerprints',
                    'options' => [
                        'negative',
                        'positive'
                    ]
                ],
                [
                    'head' => 'Chemical Examination result',
                    'field_name' => 'chem_exam_result',
                    'options' => [
                        'negative',
                        'positive'
                    ]
                ],
                [
                    'head' => 'FSL Result',
                    'field_name' => 'fsl_result',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Accused ID Parade',
                    'field_name' => 'accused_id_parade',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Evidence developed through',
                    'field_name' => 'evidence_developed',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Evidence developed through DNA',
                    'field_name' => 'evidence_dna',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Result of digital evidence',
                    'field_name' => 'digital_evidence_result',
                    'options' => [
                        'negative',
                        'positive'
                    ]
                ],
                [
                    'head' => 'Result of accused sketch',
                    'field_name' => 'accused_sketch_result',
                    'options' => [
                        'negative',
                        'positive'
                    ]
                ],
                [
                    'head' => 'Circumstancial',
                    'field_name' => 'circumstantial_evidence',
                    'options' => [
                        'negative',
                        'positive'
                    ]
                ],
                [
                    'head' => 'Corroborated evidence',
                    'field_name' => 'corroborated_evidence',
                    'options' => [
                        'negative',
                        'positive'
                    ]
                ],
                [
                    'head' => 'Verification of accused',
                    'field_name' => 'accused_verification',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Eye Witness available',
                    'field_name' => 'eyewitness_available',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ],
                [
                    'head' => 'Outcome of suspects interrogation',
                    'field_name' => 'suspect_interrogation_result',
                    'options' => [
                        'negative',
                        'positive'
                    ]
                ],
                [
                    'head' => 'Empties recovered from crime scene with weapon',
                    'field_name' => 'weapon_empties_recovered',
                    'options' => [
                        'no',
                        'yes'
                    ]
                ]
            );

        } elseif ($type == 3) {
            $data['breadcrumbs'] = [
                ['link' => "/", 'name' => "Dashboard"], ['link' => "/fir-list", 'name' => "Fir List"], ['name' => "Monitoring of Supervision"]
            ];
            $data['form_d'] = array(
                [
                    'head' => 'Issued Supervisory Diary',
                    'field_name_1' => 'issued_supervisory_diary_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'issued_supervisory_diary_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],

                ],
                [
                    'head' => 'Visited place of',
                    'field_name_1' => 'visited_place_of_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'visited_place_of_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ],
                [
                    'head' => 'Issued Initial',
                    'field_name_1' => 'issued_initial_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'issued_initial_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ],
                [
                    'head' => 'Issued Instruction diary',
                    'field_name_1' => 'issued_instruction_diary_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'issued_instruction_diary_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ],
                [
                    'head' => 'Visited & Meet heirs of victim',
                    'field_name_1' => 'visited_meet_heirs_of_victim_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'visited_meet_heirs_of_victim_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ],
                [
                    'head' => 'Whether proper Section of law applied',
                    'field_name_1' => 'proper_section_of_law_applied_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'proper_section_of_law_applied_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ],
                [
                    'head' => 'Physical inspected the accused',
                    'field_name_1' => 'quality_of_diaries_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'quality_of_diaries_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ],
                [
                    'head' => 'Quality of Diaries',
                    'field_name_1' => 'daily_diary_entry_arrival_departure_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'daily_diary_entry_arrival_departure_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ],
                [
                    'head' => 'Daily diary entry / arrival departure',
                    'field_name_1' => 'efforts_for_arrest_of_accused_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'efforts_for_arrest_of_accused_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ],
                [
                    'head' => 'Efforts for the arrest of accused',
                    'field_name_1' => 'efforts_for_collection_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'efforts_for_collection_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ],
                [
                    'head' => 'Efforts for the collection',
                    'field_name_1' => 'quality_of_evidence_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'quality_of_evidence_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ],
                [
                    'head' => 'Quality of Evidence',
                    'field_name_1' => 'quality_of_investigation_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'quality_of_investigation_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ],
                [
                    'head' => 'Quality of investigation',
                    'field_name_1' => 'copies_of_cnic_of_pws_attached_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'copies_of_cnic_of_pws_attached_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ],
                [
                    'head' => 'Copies of CNIC of PWs attached',
                    'field_name_1' => 'issue_crs_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'issue_crs_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ],
                [
                    'head' => 'Issue CRS',
                    'field_name_1' => 'monitored_by_1',
                    'options_1' => [
                        'no',
                        'yes'
                    ],
                    'field_name_2' => 'monitored_by_2',
                    'options_2' => [
                        'no',
                        'yes'
                    ],
                ]
            );
        } else {
            $data['breadcrumbs'] = [
                ['link' => "/", 'name' => "Dashboard"], ['link' => "/fir-list", 'name' => "Fir List"], ['name' => "Scruitny of Legal Branch"]
            ];
            $data['form_e'] = array(
                [
                    'head' => 'Quality of Investigation',
                    'field_name' => 'quality_inv',
                    'options' => [
                        'poor',
                        'average',
                        'good'
                    ]
                ],
                [
                    'head' => 'Quality of Evidence',
                    'field_name' => 'quality_evd',
                    'options' => [
                        'poor',
                        'average',
                        'good'
                    ]
                ],
                [
                    'head' => 'Quality of Supervision',
                    'field_name' => 'quality_sup',
                    'options' => [
                        'poor',
                        'average',
                        'good'
                    ]
                ]
            );
        }
        return view('admin.form-detail', $data);
    }
}
