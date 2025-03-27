<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ManageAchievers extends Controller
{
    public function getAchiversPage()
    {
        return view('BackendPages.ManageAchivers.Add-Achivers');
    }

    public function AchiversListPage()
    {
        return view('BackendPages.ManageAchivers.Achivers-List');
    }
    public function AchiversUpdatePage($id)
    {
        return view('BackendPages.ManageAchivers.Update-Achivers', ['id' => $id]);
    }

    public function addAchivers(Request $request)
    {
        $tenant = TenantHelper::getTenantData();

        $tenant_id = $tenant['tenant_id'];
        $employee_id = $tenant['employee_id'] ;
        $client_slug = $tenant['client_slug'];
        $type = $request->type;
        $name = $request->name;
        $company_name = $request->company_name;
        $achievement_details = $request->achievement_details;
        $rank = $request->rank;
        $academic_session = $tenant['academic_session'];
        $expiration_date = $tenant['expiration_date'];
        $website_url = $tenant['website_url'];

        $data = [
            'tenant_id' => $tenant_id,
            'employee_id' => $employee_id,
            'client_slug' => $client_slug,
            'name' => $name,
            'company_name' => $company_name,
            'achievement_details' => $achievement_details,
            // 'person_img' => $filename,
            'rank' => $rank,
            'website_url' => $website_url,
            'academic_session' => $academic_session,
            'expiration_date' => $expiration_date,
        ];

        DB::table('achievers')->insert($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Achivers Successfully Created'
        ], 200);
    }

    public function achiversList()
    {
        $data = DB::table('achievers')->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function achiversListlatest()
    {
        $data = DB::table('achievers')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function achiversDetails($id)
    {
        $data = DB::table('achievers')->where('id', $id)->first();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function deleteAchivers($id)
    {
        DB::table('achievers')->where('id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Achivers Deleted Successfully'
        ], 200);
    }

    public function updateAchivers(Request $request, $id)
    {
        $data = DB::table('achievers')->where('id', $id)->first();

        if (!$data) {
            return response()->json([
                "message" => "Achivers Not Found"
            ], 400);
        }

        DB::table('achievers')
            ->where('id', $id)
            ->update([
                'name' => $request->input('name'),
                'company_name' => $request->input('company_name'),
                'achievement_details' => $request->input('achievement_details'),
                'rank' => $request->input('rank'),
                // 'person_img' => $filename,
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);

        return response()->json([
            'Status' => 'Success',
            'message' => 'Achivers Details Updated Successfully',
        ], 200);
    }
}
