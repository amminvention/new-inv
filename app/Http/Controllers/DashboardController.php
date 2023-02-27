<?php

namespace App\Http\Controllers;

use App\Models\Fir;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  // Dashboard - Analytics
  public function dashboardAnalytics()
  {
    $pageConfigs = ['pageHeader' => false];

    return view('/content/dashboard/dashboard-analytics', ['pageConfigs' => $pageConfigs]);
  }

  // Dashboard - Ecommerce
  public function dashboardEcommerce()
  {
    $pageConfigs = ['pageHeader' => false];
    $data['total_cases'] = Fir::whereHas('sections', function ($query) {
            $query->whereIn('fs_section_id', [277, 401, 397, 403, 399])->where('is_primary', 1);
        })->count();
      $data['fir'] = Fir::with('investigationOfficer.employee.rank:rank_id,rank_name_en', 'detail:fd_id,fd_fir_id,fir_short_detail', 'district.range:dis_id,dist_name_eng', 'district:dis_id,dis_reg_id,dist_name_eng', 'policeStation:ps_id,ps_name_eng', 'sections.sectionLaws:sec_id,section_name', 'investigationOfficer.employee:pe_id,pe_name,pe_rank')
          ->whereHas('sections', function ($query) {
              $query->whereIn('fs_section_id', [277, 401, 397, 403, 399])->where('is_primary', 1);
          })->limit(10)->get();
    return view('/content/dashboard/dashboard-ecommerce', ['pageConfigs' => $pageConfigs, 'data' => $data]);
  }
}
