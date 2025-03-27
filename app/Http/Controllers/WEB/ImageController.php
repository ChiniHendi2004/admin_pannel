<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ImageController extends Controller
{
    public function showUploadPage()
    {
        $groups = DB::table('image_groups')->get(); // Fetch groups from image_groups table
        return view('BackendPages.ImageGroup.uploadImageGroup', compact('groups'));
    }

    public function fetchImages(Request $request, $group_id, $item_id)
    {
        $tenant_id = $request->header('tenant_id');

        $query = DB::table('images')
        ->where('images.image_group_id', $group_id)
            ->where('images.item_id', $item_id)
            ->where('images.tenant_id', $tenant_id); // Filtering by tenant_id

        $images = $query->get();

        return response()->json($images);
    }

    // Fetch Items based on selected group
    public function getItems($groupType)
    {
        $items = [];

        if ($groupType === '1') {
            $items = DB::table('facilities')->select('id', 'facility_name as name')->get();
        } elseif ($groupType === '2') {
            $items = DB::table('activities')->select('id', 'activity_name as name')->get();
        }

        return response()->json($items);
    }

    // Upload Image
    public function uploadImage(Request $request)
    {
        $request->validate([
            'group' => 'required',
            'item_id' => 'required',
            'title' => 'required',
            'image_url' => 'required',
            'short_description' => 'required'
        ]);

        // Save to Database
        DB::table('images')->insert([
            'image_group_id' => $request->group,
            'item_id' => $request->item_id,
            'title' => $request->title,
            'file_path' => $request->image_url,
            'short_description' => $request->short_description,
            'tenant_id' => 1,
            'client_slug' => 'client-part',
            'website_url' => 'www.col.com',
            'employee_id' => 1,
            'academic_session' => 2025,
            'expiration_date' => '2025-12-12',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Image uploaded successfully!']);
    }

    // Fetch all Images
    public function getImages()
    {
        $images = DB::table('images')
        ->join('image_groups', 'images.image_group_id', '=', 'image_groups.id')
        ->leftJoin('facilities', function ($join) {
            $join->on('images.item_id', '=', 'facilities.id')
            ->where('image_groups.group_name', '=', 'Facility');
        })
            ->leftJoin('activities', function ($join) {
                $join->on('images.item_id', '=', 'activities.id')
                ->where('image_groups.group_name', '=', 'Activity');
            })
            ->select(
                'images.id',
                'image_groups.group_name',
                DB::raw("COALESCE(facilities.facility_name, activities.activity_name) AS item_name"),
                'images.file_path',
                'images.status'
            )
            ->get();

        return response()->json(['data' => $images]);
    }

    // Delete Image
    public function deleteImage($id)
    {
        DB::table('images')->where('id', $id)->delete();
        return response()->json(['message' => 'Image deleted successfully!']);
    }

    // Update Image Status
    public function updateImageStatus(Request $request, $id)
    {
        DB::table('images')->where('id', $id)->update(['status' => $request->status]);
        return response()->json(['message' => 'Status updated successfully!']);
    }
}
