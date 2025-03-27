<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon; 
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DepartmentGroup extends Controller
{
    public function getPage()
    {
        return view('BackendPages.DepartmentPages.departmentGroup');
    }

    public function groupList()
    {
        return view('BackendPages.DepartmentPages.GroupList');
    }

    public function addDepartmentGroups(Request $request)
    {
        $tenantData = TenantHelper::getTenantData();

        $department = array(
            "group_name" => $request->group_name,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
            'status' => '1',
        );

        DB::table('department_groups')->insert($department);

        return response()->json([
            "status" => "success",
            "message" => $request->group_name . " Created successfully"
        ], 200);
    }

    public function deparmentList()
    {
        $tenantData = TenantHelper::getTenantData();

        $list = DB::table('department_groups')->where('tenant_id', $tenantData['tenant_id'])->get();

        return response()->json([
            "status" => "success",
            "data" => $list
        ], 200);
    }

    public function deparmentListLatest()
    {
        $tenantData = TenantHelper::getTenantData();
        $list = DB::table('department_groups')->orderBy('created_at', 'desc')->where('tenant_id', $tenantData['tenant_id'])->get();

        return response()->json([
            "status" => "success",
            "data" => $list
        ], 200);
    }


    public function editDepartmentGroup(Request $request)
    {
        $id  = $request->input('id');

        $updates = DB::table('department_groups')->where('id', $id)->first();
        if (! $updates) {
            // Handle the case where the update doesn't exist
            return redirect()->back()->with('error', 'Updates not found');
        }

        DB::table('department_groups')->where('id', $id)
            ->update([
                'group_name' => $request->input('group_name'),
                'created_at' => $updates->created_at,
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);

        return  response()->json([
            'status' => 'success',
            'message' => $request->input('group_name') . ' updated Successfully'
        ], 200);
    }



    public function groupDetail($id)
    {
        $departments = DB::table('department_groups')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $departments
        ], 200);
    }

    public function statusEdit(Request $request, $id)
    {
        DB::table('department_groups')
            ->where('id', $id)
            ->update([
                'status' => $request->input('status'),
            ]);

        return response()->json([
            'status' => 'success',
            'message' => "Status Changed",
        ], 200);
    }


    public function activeGroupList()
    {
        $tenantData = TenantHelper::getTenantData();
        $data = DB::table('department_groups')
            ->where('status', '1')
            ->where('tenant_id', $tenantData['tenant_id'])
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }


    public function removeUpdates($id)
    {
        DB::table('department_groups')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Department Groups Deleted Successfully'
        ], 200);
    }
}
