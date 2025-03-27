<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ActivityGroup extends Controller
{
    public function createGroupPage()
    {
        return view('BackendPages.Activity.CreateActivityGroup');
    }

    public function GroupListPAge()
    {
        return view('Backendpages.Activity.ActivityGroupList');
    }

    public function createActivityGroups(Request $request)
    {
        $tenantData = TenantHelper::getTenantData();

        $activity = array(
            "group_name" => $request->group_name,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
            'status' => '1',
        );

        DB::table('activity_groups')->insert($activity);

        return response()->json([
            "status" => "success",
            "message" => "Group Created successfully"
        ], 200);
    }

    public function activityGroupList()
    {
        $tenantData = TenantHelper::getTenantData();

        $list = DB::table('activity_groups')->where('tenant_id', $tenantData['tenant_id'])->get();
        return response()->json([
            "status" => "success",
            "data" => $list
        ], 200);
    }

    public function editActivityGroup(Request $request, $id)
    {
        $id  = $request->input('id');

        $activitygroups = DB::table('activity_groups')->where('id', $id)->first();
        if (! $activitygroups) {
            // Handle the case where the update doesn't exist
            return redirect()->back()->with('error', 'Updates not found');
        }

        DB::table('activity_groups')->where('id', $id)
            ->update([
                'group_name' => $request->input('group_name'),
                'created_at' => $activitygroups->created_at,
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);

        return  response()->json([
            'status' => 'success',
            'message' => 'Updated Successfully'
        ], 200);
    }

    public function selectIdWise($id)
    {
        $activity = DB::table('activity_groups')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $activity
        ], 200);
    }

    public function statusEdit(Request $request, $id)
    {
        DB::table('activity_groups')
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
        $data = DB::table('activity_groups')
            ->where('status', '1')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function removeUpdates($id)
    {
        DB::table('activity_groups')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Activity Deleted Successfully'
        ], 200);
    }
}
