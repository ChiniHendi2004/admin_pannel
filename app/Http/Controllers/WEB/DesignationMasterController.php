<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DesignationMasterController extends Controller
{
    public function getPage()
    {
        return view('Backendpages.MasterPage.Designation-Master');
    }

    public function addDesignation(Request $request)
    {
        $tenantData = TenantHelper::getTenantData();

        $designationDetail = array(
            "designation_type" => $request->designation_type,
            "designation_desc" => $request->designation_desc,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
            'created_at' => now(),
            'updated_at' => now(),
        );

        DB::table('designation_master')->insert($designationDetail);

        return response()->json([
            "status" => "success",
            "message" => $request->designation_type . " Created successfully"
        ], 200);
    }

    public function designationsList()
    {
        $tenantData = TenantHelper::getTenantData();
        $list = DB::table('designation_master')->where('tenant_id',$tenantData['tenant_id'])->get();
        return response()->json([
            "status" => "success",
            "data" => $list
        ], 200);
    }


    public function editDesignation(Request $request)
    {
        $designation_id  = $request->input('designation_id');

        // Check if the designations exists
        // dd($request->input('designation_id'));
        $designations = DB::table('designation_master')->where('id', $designation_id)->first();
        if (! $designations) {
            // Handle the case where the designation doesn't exist
            return redirect()->back()->with('error', 'designations not found');
        }

        DB::table('designation_master')->where('id', $designation_id)
            ->update([
                'designation_type' => $request->input('designation_type'),
                'designation_desc' => $request->input('designation_desc'),
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);

        return  response()->json([
            'status' => 'success',
            'message' => $request->input('designation_type') . ' Updated Successfully'
        ], 200);
    }



    public function designationsDetail($id)
    {
        $designationDetails = DB::table('designation_master')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $designationDetails
        ], 200);
    }

    public function statusEdit(Request $request, $id)
    {
        DB::table('designation_master')
            ->where('id', $id)
            ->update([
                'designation_status' => $request->input('designation_status'), // Corrected field name to 'status'
            ]);

        return response()->json([
            'status' => 'success',
            'message' => "Status Changed",
        ], 200);
    }

    public function removeDesignations($id)
    {
        DB::table('designation_master')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => ' designations Deleted Successfully'
        ], 200);
    }
    public function selectstatusData()
    {
        $data = DB::table('designation_master')->where('status', '1')->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
