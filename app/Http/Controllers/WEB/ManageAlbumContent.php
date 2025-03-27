<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ManageAlbumContent extends Controller
{
    public function getPage()
    {
        return view('Backendpages.Gallery.Album-Content');
    }

    public function addImgContent(Request $request, $id)
    {
        $tenantData = TenantHelper::getTenantData();
        // if ($request->hasFile('album_img')) {
        //     $file = $request->file('album_img');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = 'Gallery/' . time() . '.' . $extension;
        //     $file->storeAs('public', $filename);
        // }

        $contentDetail = array(
            "album_id" => $id,
            "thumbnail_url" => $request->thumbnail_url,
            "image_url" => $request->image_url,
            "description" => $request->description,
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

        DB::table('album_images')->insert($contentDetail);

        return response()->json([
            "status" => "success",
            "message" => "Album content uploaded successfully"
        ], 200);
    }

    public function imgContentList($id)
    {
        $list = DB::table('album_images')->where('album_id', $id)->get();
        
        return response()->json([
            "status" => "success",
            "data" => $list
        ], 200);
    }

    public function deleteimgContent($id)
    {
        $albumContent = DB::table('album_images')->where('id', $id)->first();

        if (!$albumContent) {
            return response()->json([
                "message" => "Album Not Found"
            ], 400);
        }
        // $currentImagePath = $albumContent->imgcontent_name;
        // $currentImageName = pathinfo($currentImagePath, PATHINFO_BASENAME);
        // if ($currentImagePath) {
        //     Storage::delete('public/Gallery/' . $currentImageName);
        // }
        DB::table('album_images')->where('id', $id)->delete();
        return response()->json([
            "status" => "success",
            "message" => "Image deleted successfully",
        ], 200);
    }

    public function getContentPage($id)
    {
        return view('Backendpages.Gallery.Edit-Album_Content', ['id' => $id]);
    }


    public function albumContentDetails($id)
    {
        $data = DB::table('album_images')->where('id', $id)->first();
        // $imageUrl = asset('storage/' . $data->album_img);
        // $data->img_url = $imageUrl ;
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function albumContentUpdate(Request $request, $id)
    {
        $album = DB::table('album_images')->where('id', $id)->first();

        if (!$album) {
            return response()->json([
                "message" => "Album Not Found"
            ], 400);
        }

        // Get the current image path and name
        // $currentImagePath = $album->imgcontent_name;
        // $currentImageName = pathinfo($currentImagePath, PATHINFO_BASENAME);

        // if ($request->hasFile('imgcontent_name')) {
        //     $file = $request->file('imgcontent_name');
        //     $extension = $file->getClientOriginalExtension();
        //     $newImageName = 'Gallery/' . time() . '.' . $extension;

        //     // Store the new image with the updated path
        //     $file->storeAs('public', $newImageName);

        //     if ($currentImagePath) {

        //         Storage::delete('public/Gallery/' . $currentImageName);
        //     }
        // } else {
        //     $newImageName = $currentImagePath;
        // }

        // Update other album details
        DB::table('album_images')
            ->where('id', $id)
            ->update([
                'description' => $request->input('description'),
                'image_url' => $request->input('image_url'),
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);


        return response()->json([
            'Status' => 'Success',
            'message' => 'Album Content Updated successfully',
        ], 200);
    }


    public function latestAlbumContent()
    {
        $data =  DB::table('album_content')
            ->orderBy('created_at', 'desc')
            ->limit(9)
            ->get();

        foreach ($data as $list) {
            $imageUrl = asset('storage/' . $list->imgcontent_name);
            $list->image_url = $imageUrl;
        }

        return response()->json([
            'Status' => 'Success',
            'data' => $data,
        ], 200);
    }
}
