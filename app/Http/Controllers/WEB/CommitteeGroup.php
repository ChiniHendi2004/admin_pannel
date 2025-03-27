<?php

namespace App\Http\Controllers\WEB;

use App\Helper\TenantHelper;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommitteeGroup extends Controller
{
    public function createGroupPage()
    {
        return view('Backendpages.Committee.CreateCommitteeGroup');
    }

    public function GroupListPAge()
    {
        return view('Backendpages.Committee.CommitteeGroupList');
    }


    public function createCommitteeGroups(Request $request)
    {
        $tenantData = TenantHelper::getTenantData();
        
        $committee = array(
            "group_name" => $request->group_name,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
            'status' => 1
        );

        DB::table('committee_groups')->insert($committee);

        return response()->json([
            "status" => "success",
            "message" => "Group Created successfully"
        ], 200);
    }

    public function committeeList()
    {
        $tenantData = TenantHelper::getTenantData();

        $list = DB::table('committee_groups')->where('tenant_id', $tenantData['tenant_id'])->get();

        return response()->json([
            "status" => "success",
            "data" => $list
        ], 200);
    }

    public function editCommitteeGroup(Request $request, $id)
    {
        $id  = $request->input('id');

        $committeegroups = DB::table('committee_groups')->where('id', $id)->first();
        if (! $committeegroups) {
            // Handle the case where the update doesn't exist
            return redirect()->back()->with('error', 'Committees not found');
        }

        DB::table('committee_groups')->where('id', $id)
            ->update([
                'group_name' => $request->input('group_name'),
                'created_at' => $committeegroups->created_at,
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);

        return  response()->json([
            'status' => 'success',
            'message' => 'Updated Successfully'
        ], 200);
    }



    public function selectIdWise($id)
    {
        $committee = DB::table('committee_groups')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $committee
        ], 200);
    }

    public function statusEdit(Request $request, $id)
    {
        DB::table('committee_groups')
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

        $data = DB::table('committee_groups')
            ->where('status', '1')
            ->where('tenant_id', $tenantData['tenant_id'])
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }


    public function removeCommittees($id)
    {
        DB::table('committee_groups')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Committee Deleted Successfully'
        ], 200);
    }
}
