<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ManageAlbum extends Controller
{
    public function CreateGalleryPage()
    {
        return view('Backendpages.Gallery.Create-Album');
    }
    public function GalleryListPage()
    {
        return view('Backendpages.Gallery.Album-List');
    }

    public function EditAlbumPage($id)
    {
        return view('Backendpages.Gallery.Edit-Album', ['id' => $id]);
    }
    public function GalleryContentPage($id)
    {
        return view('Backendpages.Gallery.Album-Content', ['id' => $id]);
    }

    public function createAlbum(Request $request)
    {
        $tenantData = TenantHelper::getTenantData();
        $album_name = $request->album_name;
        $thumbnail_url = $request->thumbnail_url;

        // if ($request->hasFile('album_img')) {
        //     $file = $request->file('album_img');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = 'Gallery/' . time() . '.' . $extension;
        //     $file->storeAs('public', $filename);
        // }
        $albumData = array(
            "name" => $album_name,
            "thumbnail_url" => $thumbnail_url,
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

        DB::table('albums')->insert($albumData);


        return response()->json([
            'message' => 'Album created successfully'
        ], 200);
    }


    public function albumOrgList($id)
    {
        $list = DB::table('manage_album')->where('org_id', $id)->get();
        foreach ($list as $album_info) {
            $imageUrl = asset('storage/' . $album_info->album_img);
            $album_info->image_url = $imageUrl; // Create a new property 'image_url' with the asset URL
        }

        return response()->json([
            'status' => 'success',
            'data' => $list,
        ], 200);
    }

    public function albumList()
    {
        $tenantData = TenantHelper::getTenantData();
        $list = DB::table('albums')->where('tenant_id',$tenantData['tenant_id'])->get();
       
        return response()->json([
            'status' => 'success',
            'data' => $list,
        ], 200);
    }

    public function albumInfo($id)
    {
        $album_info = DB::table('albums')->where('id', $id)->first();
        return response()->json([
            "status" => "success",
            "data" =>  $album_info,
            // "pathinfo" => $imageUrl
        ], 200);
    }

    public function updateAlbum(Request $request, $id)
    {
        $album = DB::table('albums')->where('id', $id)->first();

        if (!$album) {
            return response()->json([
                "message" => "Album Not Found"
            ], 400);
        }

        // Get the current image path and name
        

        // Update other album details
        DB::table('albums')
            ->where('id', $id)
            ->update([
                'name' => $request->input('album_name'),
                'thumbnail_url' => $request->input('thumbnail_url'),
            ]);


        return response()->json([
            'Status' => 'Success',
            'message' => 'Album Details Updated successfully',
        ], 200);
    }

    public function removeAlbum($id)
    {
        $album = DB::table('albums')->where('id', $id)->first();

        if (!$album) {
            return response()->json([
                "message" => "Album Not Found"
            ], 400);
        }

        // Get the current image path and name
        $currentImagePath = $album->album_img;
        $currentImageName = pathinfo($currentImagePath, PATHINFO_BASENAME);

        if ($currentImagePath) {
            Storage::delete('public/Gallery/' . $currentImageName);
        }

        $albumContents = DB::table('album_content')->where('albumname_id', $id)->get();

        if ($albumContents->isNotEmpty()) {
            foreach ($albumContents as $albumContent) {
                $currentImageContent = $albumContent->imgcontent_name;
                $currentImageName = pathinfo($currentImageContent, PATHINFO_BASENAME);

                if ($currentImageContent) {
                    Storage::delete('public/Gallery/' . $currentImageName);
                }
            }
        }

        DB::table('album_images')->where('album_id', $id)->delete();

        DB::table('albums')->where('id', $id)->delete();

        return response()->json([
            'Status' => 'Success',
            'message' => 'Album Deleted Successfully',
        ], 200);
    }
}
