<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class Department extends Controller
{
    public function getPage()
    {
        return view('BackendPages.DepartmentPages.CreateDepartment');
    }

    public function departmentListPage()
    {
        return view('BackendPages.DepartmentPages.DepartmentList');
    }

    public function addMoreDetails($id)
    {
        return view('BackendPages.DepartmentPages.DepartmentDetailsAdd', ['id' => $id]);
    }

    public function departmentUpdatePage($id)
    {
        return view('BackendPages.DepartmentPages.EditDepartment', ['id' => $id]);
    }

    public function ViewDepartmentPage($id)
    {
        return view('BackendPages.DepartmentPages.ViewDepartments', ['id' => $id]);
    }

    public function getViewSingleDetails($id, $paragraphid)
    {
        return view('BackendPages.DepartmentPages.ViewSingleDetail', ['paragraphid' => $paragraphid, 'id' => $id,]);
    }

    public function createDepartment(Request $request)
    {
        $tenantData = TenantHelper::getTenantData();
        
        $department_group_id = $request->dep_id;
        $department_name = $request->department_name;
        $short_description = $request->short_description;
        $paragraph_text = $request->paragraph_text;


        $departmentId = DB::table('departments')->insertGetId([
            'department_group_id' => $department_group_id,
            'department_name' => $department_name,
            'short_description' => $short_description,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
        ]);


        DB::table('department_details')->insert([
            'department_id' => $departmentId,
            'paragraph_order' => "1",
            'paragraph_text' => $paragraph_text,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Department Created Successfully '
        ], 200);
    }

    public function departmentList()
    {
        $tenantData = TenantHelper::getTenantData();
        $data = DB::table('departments')->where('tenant_id', $tenantData['tenant_id'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getTitleDetail($id)
    {
        $title = DB::table('departments')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $title
        ], 200);
    }

    public function editDepartmentTitle(Request $request, $id)
    {
        $department = DB::table('departments')->where('id', $id)->first();
        if (!$department) {
            return response()->json([
                "message" => "Title Not Found"
            ], 400);
        }

        DB::table('departments')
            ->where('id', $id)
            ->update([
                'department_name' => $request->department_name,
                'short_description' => $request->short_description,
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);
        return response()->json([
            'Status' => 'Success',
            'message' => $request->input('department_name') . 'Department Update Successfully'
        ], 200);
    }


    public function getDepartmentsDetails(Request $request, $departmentId)
    {
        $paragraphOrder = $request->input('paragraph_order');

        $DepartmentsDetails = DB::table('department_details')
            ->where('department_id', $departmentId)
            ->where('paragraph_order', $paragraphOrder)
            ->first();

        if (!$DepartmentsDetails) {
            return response()->json(['success' => false, 'message' => 'No details found for the specified paragraph order.']);
        }

        return response()->json(['success' => true, 'data' => $DepartmentsDetails]);
    }


    public function addMultiDetails(Request $request)
    {
        // Validate inputs
        $tenantData = TenantHelper::getTenantData();
        $department_id = $request->input('department_id');
        $paragraph_order = $request->input('paragraph_order');
        $paragraph_text = $request->input('paragraph_text');
        $expiration_date = Carbon::parse($request->input('created_at'))->addYear(1);

        // Check if a record with the given title_id and content_no exists
        $checkQuery = DB::table('department_details')
            ->where([
                'department_id' => $department_id,
                'paragraph_order' => $paragraph_order,
            ])
            ->first();

        if ($checkQuery) {
            // Update the existing record
            DB::table('department_details')
                ->where([
                    'department_id' => $department_id,
                    'paragraph_order' => $paragraph_order,
                ])
                ->update([
                    'paragraph_text' => $paragraph_text,
                    'updated_at' => Carbon::now('Asia/Kolkata')
                ]);
        } else {
            // Insert a new record
            DB::table('department_details')->insert([
                'department_id' => $department_id,
                'paragraph_order' => $paragraph_order,
                'paragraph_text' => $paragraph_text,
                'tenant_id' => $tenantData['tenant_id'],
                'client_slug' => $tenantData['client_slug'],
                'website_url' => $tenantData['website_url'],
                'employee_id' => $tenantData['employee_id'],
                'academic_session' => $tenantData['academic_session'],
                'expiration_date' => $tenantData['expiration_date'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Department details Added successfully'
        ], 200);
    }

    public function getAllDetails($id)
    {
        $contents = DB::table('department_details')->where('department_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $contents
        ], 200);
    }

    public function RemoveDepartment($id)
    {
        DB::table('departments')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Department Deleted Successfully'
        ], 200);
    }

}
