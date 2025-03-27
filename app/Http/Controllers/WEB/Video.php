<?php

namespace App\Http\Controllers\WEB;

use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Video extends Controller
{
    public function getVideoPage(){

        return view('BackendPages.Video.addVideo');
    }

    public function VideoListPage(){
        return view('BackendPages.Video.videoList');
    }

    public function EditVideoPage($id)
    {
        return view('BackendPages.Video.editVideo', ['id' => $id]);
    }

    public function VideoContentPage($id) {
        return view('BackendPages.Video.Video-Content', ['id' => $id]);
    }

    public function createVideo(Request $request)
    {
        $tenantData = TenantHelper::getTenantData();
        $video_type = $request->video_type;
        $video_name = $request->video_name;
        $thumbnail_url = $request->video_thumbnail;
        $expiration_date = $request->expiration_date;

        // if ($request->hasFile('album_img')) {
        //     $file = $request->file('album_img');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = 'Gallery/' . time() . '.' . $extension;
        //     $file->storeAs('public', $filename);
        // }
        $albumData = array(
            "video_type" => $video_type,
            "video_name" => $video_name,
            "video_thumbnail" => $thumbnail_url,
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

        DB::table('videos')->insert($albumData);


        return response()->json([
            'message' => 'Video created successfully'
        ], 200);
    }

    public function videoList(){
        $tenantData = TenantHelper::getTenantData();
        $list = DB::table('videos')->where('tenant_id', $tenantData['tenant_id'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $list,
        ], 200);
    }

    public function videoInfo($id)
    {
        $video_info = DB::table('videos')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $video_info,
        ], 200);
    }

    public function updateVideo(Request $request, $id)
    {
        $video = DB::table('videos')->where('id', $id)->first();

        if (!$video) {
            return response()->json([
                "message" => "Video Not Found"
            ], 400);
        }

        // Get the current image path and name


        // Update other video details
        DB::table('videos')
            ->where('id', $id)
            ->update([
                'video_name' => $request->input('video_name'),
                'video_thumbnail' => $request->input('video_thumbnail'),
            ]);


        return response()->json([
            'Status' => 'Success',
            'message' => 'Video Details Updated successfully',
        ], 200);
    }

}
