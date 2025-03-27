<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FacilityGroup
{
    public function CreateGroupPage()
    {
        return view('BackendPages.FacilityPages.CreateFacilityGroup');
    }

    public function FacilitygroupList()
    {
        return view('BackendPages.FacilityPages.FacityGroupList');
    }

    public function addFacilityGroups(Request $request)
    {

        $expiration_date = Carbon::parse($request->input('created_at'))->addYear(1);

        $department = array(
            "group_name" => $request->group_name,
            "tenant_id" => "1",
            "client_slug" => "user",
            "website_url" => "https://atreyawebs.com",
            "employee_id" => "1",
            "academic_session" => "2025-2026",
            "expiration_date" => $expiration_date,
        );

        DB::table('facility_groups')->insert($department);

        return response()->json([
            "status" => "success",
            "message" => $request->group_name . " Created successfully"
        ], 200);
    }

    public function facilityList()
    {
        $list = DB::table('facility_groups')->get();

        return response()->json([
            "status" => "success",
            "data" => $list
        ], 200);
    }

    public function facilityListLatest()
    {
        $list = DB::table('facility_groups')->orderBy('created_at', 'desc')->get();

        return response()->json([
            "status" => "success",
            "data" => $list
        ], 200);
    }


    public function editFacilityGroup(Request $request)
    {
        $id  = $request->input('id');

        $updates = DB::table('facility_groups')->where('id', $id)->first();
        if (! $updates) {
            // Handle the case where the update doesn't exist
            return redirect()->back()->with('error', 'Facilities not found');
        }

        DB::table('facility_groups')->where('id', $id)
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
        $departments = DB::table('facility_groups')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $departments
        ], 200);
    }

    public function statusEdit(Request $request, $id)
    {
        DB::table('facility_groups')
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
        $data = DB::table('facility_groups')
            ->where('status', '1')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }


    public function removeFacilityGroups($id)
    {
        DB::table('facility_groups')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Facility Group Deleted Successfully'
        ], 200);
    }
}
