<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $data['breadcrumbs'] = [
            ['link' => "/", 'name' => "Dashboard"], ['name' => "Users"]
        ];
        if ($request->ajax()) {

            $data = User::where('id', '!=', Auth::user()->id);

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
                ->addColumn('action', function ($data) {
                    $btn = '<span class="action-edit"><a href="' . route('admin.user.edit', $data->id) . '"><i data-feather="edit"></i></a></span>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.user.index', $data);
    }

    public function create()
    {
        $data['breadcrumbs'] = [
            ['link' => "/", 'name' => "Dashboard"], ['link' => "/user-management", 'name' => "Users"], ['name' => "Add User"]
        ];
        $data['range'] = District::where('dis_reg_id', 0)->pluck('dist_name_eng', 'dis_id');
        return view('admin.user.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required:max:255',
            'email' => 'required|email|unique:inv_login_users',
            'password' => 'required|min:8',
            'mobile' => 'required',
            'cnic' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = bcrypt($request->password);
        $data->mobile = $request->mobile;
        $data->cnic = $request->cnic;
        $data->range_id = !empty($request->range) ? $request->range : 0;
        $data->district_id = !empty($request->district) ? $request->district : 0;
        $data->police_station_id = !empty($request->police_station) ? $request->police_station : 0;
        $data->save();

        return redirect()->back()->with('success', 'User Created Successfully');
    }

    public function edit($id)
    {
        $data['user'] = User::findOrFail($id);
        $data['breadcrumbs'] = [
            ['link' => "/", 'name' => "Dashboard"], ['link' => "/user-management", 'name' => "Users"], ['name' => "Add User"]
        ];
        $data['range'] = District::where('dis_reg_id', 0)->pluck('dist_name_eng', 'dis_id');
        return view('admin.user.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $data = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required:max:255',
            'email' => 'required|email|unique:inv_login_users,id',
            'password' => 'nullable|min:8',
            'mobile' => 'required',
            'cnic' => 'required',
        ]);

        if ($validator->fails()) {
//            dd($validator);
            return redirect()->back()->withErrors($validator);
        }
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = bcrypt($request->password);
        $data->mobile = $request->mobile;
        $data->cnic = $request->cnic;
        $data->range_id = !empty($request->range_id) ? $request->range_id : 0;
        $data->district_id = !empty($request->district_id) ? $request->district_id : 0;
        $data->police_station_id = !empty($request->police_station_id) ? $request->police_station_id : 0;
        $data->save();

        return redirect()->back()->with('success', 'User Updated Successfully');
    }
}
