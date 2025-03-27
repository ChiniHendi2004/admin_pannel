<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ManageVideoContent extends Controller
{
    public function getContentPage($id){
        return view('BackendPages.Video.editVideoContent', ['id' => $id]);
    }
    public function addVideoContent(Request $request, $id)
    {
        $tenantData = TenantHelper::getTenantData();

        $contentDetail = array(
            "video_id" => $id,
            "video_link" => $request->video_link,
            "video_desc" => $request->video_desc,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
            'status' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        );

        DB::table('video_contents')->insert($contentDetail);

        return response()->json([
            "status" => "success",
            "message" => "Album content uploaded successfully"
        ], 200);
    }

    public function videoContentList($id){
        $videolist = DB::table('video_contents')->where('video_id', $id)->get();

        return response()->json([
            "status" => "success",
            "data" => $videolist
        ], 200);
    }

    public function videoContentDetails($id){
        $videoContent = DB::table('video_contents')->where('id', $id)->first();

        return response()->json([
            "status" => "success",
            "data" => $videoContent
        ], 200);
    }

    public function videoContentUpdate(Request $request, $id)
    {
        $video = DB::table('video_contents')->where('id', $id)->first();

        if (!$video) {
            return response()->json([
                "message" => "Video Not Found"
            ], 400);
        }

        // Update other video details
        DB::table('video_contents')
            ->where('id', $id)
            ->update([
                'video_desc' => $request->input('video_desc'),
                'video_link' => $request->input('video_link'),
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);


        return response()->json([
            'Status' => 'Success',
            'message' => 'Video Content Updated successfully',
        ], 200);
    }

    public function deleteVideoContent($id){
        $videoContent = DB::table('video_contents')->where('id', $id)->first();

        if (!$videoContent) {
            return response()->json([
                "message" => "Video Not Found"
            ], 400);
        }
        DB::table('video_contents')->where('id', $id)->delete();
        return response()->json([
            "status" => "success",
            "message" => "Video deleted successfully",
        ], 200);
    }
}
