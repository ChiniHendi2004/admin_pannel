<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Support\Facades\Log;

class ContentGroup
{
    public function CreateGroupPage()
    {
        return view('BackendPages.ContentPages.CreateContentGroup');
    }

    public function ContentgroupList()
    {
        return view('BackendPages.ContentPages.ContentGroupList');
    }

    public function addContentGroups(Request $request)
    {
        $expiration_date = Carbon::parse($request->input('created_at'))->addYear(1);

        $tenantData = TenantHelper::getTenantData();
        Log::info('Department data being inserted:', $tenantData);

        $department = array(
            "group_name" => $request->group_name,
            "tenant_id" => $tenantData['tenant_id'],
            "client_slug" => $tenantData['client_slug'],
            "website_url" => $tenantData['website_url'],
            "employee_id" => $tenantData['employee_id'],
            "academic_session" => $tenantData['academic_session'],
            "expiration_date" => $expiration_date,
        );

        DB::table('content_groups')->insert($department);

        return response()->json([
            "status" => "success",
            "message" => $request->group_name . " Created successfully"
        ], 200);
    }

    public function contentList()
    {
        $tenantData = TenantHelper::getTenantData();
        $list = DB::table('content_groups')->where('tenant_id', $tenantData['tenant_id'])->get();

        return response()->json([
            "status" => "success",
            "data" => $list
        ], 200);
    }

    public function contentListLatest()
    {
        $tenantData = TenantHelper::getTenantData();
        $list = DB::table('content_groups')->where('tenant_id', $tenantData['tenant_id'])->orderBy('created_at', 'desc')->get();

        return response()->json([
            "status" => "success",
            "data" => $list
        ], 200);
    }

    public function editContentGroup(Request $request)
    {
        $id  = $request->input('id');

        $updates = DB::table('content_groups')->where('id', $id)->first();
        if (! $updates) {
            return redirect()->back()->with('error', 'Data not found');
        }

        DB::table('content_groups')->where('id', $id)
            ->update([
                'group_name' => $request->input('group_name'),
                'created_at' => $updates->created_at,
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);

        return response()->json([
            'status' => 'success',
            'message' => $request->input('group_name') . ' updated Successfully'
        ], 200);
    }

    public function groupDetail($id)
    {
        $departments = DB::table('content_groups')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $departments
        ], 200);
    }

    public function statusEdit(Request $request, $id)
    {
        DB::table('content_groups')
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

        $data = DB::table('content_groups')
        ->where('tenant_id', $tenantData['tenant_id'])
            ->where('status', '1')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function removeUpdates($id)
    {
        DB::table('content_groups')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Updates Deleted Successfully'
        ], 200);
    }
}
