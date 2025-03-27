<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlbumController extends Controller
{
    public function albumPage()
    {
        return view('Backendpages.Album.CreateAlbum');
    }

    public function updateAlbumPage()
    {
        return view('Backendpages.Album.UpdateAlbum');
    }

    // Fetch all albums and pass to the view
    public function index()
    {
        // Fetch all albums
        $albums = DB::table('albums')->get();

        // Pass albums to the view
        return view('albums.index', compact('albums'));
    }

    public function createAlbum(Request $request)
    {
        $thumbline_url = "https://img.freepik.com/premium-photo/large-brick-building-with-tower-that-says-university-america_551880-7776.jpg?w=1060";

        $data = [
            'name' => $request->input('name'),
            'tenant_id' => $request->input('tenant_id'),
            'thumbline_url' => $request->input('thumbline_url') ?? $thumbline_url,
            'client_slug' => $request->input('client_slug'),
            'website_url' => $request->input('website_url'),
            'employee_id' => $request->input('employee_id'),
            'academic_session' => $request->input('academic_session'),
        ];

        if (
            empty($data['name']) || empty($data['tenant_id']) || empty($data['client_slug']) || empty($data['website_url']) ||
            empty($data['employee_id']) || empty($data['academic_session'])
        ) {
            return response()->json([
                "status" => "Invalid attempts",
                "message" => "Missing required fields"
            ], 400);
        }

        $data['expiration_date'] = Carbon::now('Asia/Kolkata')->addDays(100)->toDateString();

        $albumId = DB::table('albums')->insertGetId($data);

        return response()->json([
            'status' => "200",
            'message' => "Album Created Successfully",
            'id' => $albumId
        ]);
    }

    // public function thumbnailList()
    // {
    //     // Fetch albums and join with album_images to get thumbnail_url
    //     $albums = DB::table('albums')
    //         ->leftJoin('album_images', 'albums.id', '=', 'album_images.album_id')
    //         ->select('albums.*', 'album_images.thumbnail_url')
    //         ->get();

    //     return response()->json([
    //         "status" => "success",
    //         "data" => $albums
    //     ], 200);
    // }

    public function thumbline_list()
    {
        try {
            $albums = DB::table('albums')
                ->select('id', 'name', 'thumbline_url', 'website_url', 'expiration_date')
                ->where('status', '1')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $albums,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch albums: ' . $e->getMessage(),
            ], 500);
        }
    }





    // Select an album by its ID
    public function selectAlbum($id)
    {
        $album = DB::table('albums')->find($id);

        if (!$album) {
            return response()->json(['status' => 'error', 'message' => 'Album not found'], 404);
        }

        return response()->json($album);
    }

    // Fetch album data by its ID
    public function fetchAlbumData($id)
    {
        $album = DB::table('albums')->where('id', $id)->first();

        if (!$album) {
            return response()->json(['status' => 'error', 'message' => 'Album not found'], 404);
        }

        return response()->json($album);
    }

    // Update an album by its ID
    public function update(Request $request, $id)
    {
        // Retrieve the existing album
        $album = DB::table('albums')->where('id', $id)->first();

        // Check if the album exists
        if (!$album) {
            return response()->json([
                'status' => 'error',
                'message' => 'Album ID not found'
            ], 404);
        }

        $thumbline_url = "https://img.freepik.com/premium-photo/large-brick-building-with-tower-that-says-university-america_551880-7776.jpg?w=1060";


        // Prepare the data to be updated
        $data = [
            'name' => $request->name ? $request->name : $album->name,
            'thumbline_url' => $request->input('thumbline_url') ?? $album->thumbline_url,

            'tenant_id' => $request->tenant_id ? $request->tenant_id : $album->tenant_id,
            'client_slug' => $request->client_slug ? $request->client_slug : $album->client_slug,
            'website_url' => $request->website_url ? $request->website_url : $album->website_url,
            'employee_id' => $request->employee_id ? $request->employee_id : $album->employee_id,
            'academic_session' => $request->academic_session ? $request->academic_session : $album->academic_session,
            'expiration_date' => $request->expiration_date ? $request->expiration_date : $album->expiration_date,
        ];

        // Update the album
        DB::table('albums')->where('id', $id)->update($data);

        // Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'Album updated successfully'
        ]);
    }

    // Delete an album by its ID
    public function destroy($id)
    {
        $deleted = DB::table('albums')->where('id', $id)->delete();

        if (!$deleted) {
            return response()->json(['status' => 'error', 'message' => 'Album not found'], 404);
        }

        return response()->json(['status' => 'success', 'message' => 'Album deleted successfully']);
    }

    // View an album by its ID
    // public function viewAlbum($id)
    // {
    //     $album = DB::table('albums')->where('id', $id)->first();

    //     if (!$album) {
    //         return response()->json(['status' => 'error', 'message' => 'Album not found'], 404);
    //     }

    //     return response()->json($album);
    // }

    public function storeImage(Request $request, $id)
    {
        // Validate the image input
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Check if the album exists in the database using a DB query
        $album = DB::table('albums')->where('id', $id)->first();

        if (!$album) {
            return redirect()->route('albums.index')->with('error', 'Album not found.');
        }

        // Store the image and get the path (using Laravel's file storage)
        $path = $request->file('image')->store('album_images', 'public');

        // Insert the image into the album_images table using DB query
        DB::table('album_images')->insert([
            'album_id' => $id,  // directly using the $id since it's passed in the URL
            'image_url' => $path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect back to the album page with a success message
        return redirect()->route('albums.index')->with('success', 'Image added successfully!');
    }





    public function viewAlbumImage($id)
    {
        // Fetch album details
        $album = DB::table('albums')
            ->select('name')
            ->where('id', $id)
            ->first();

        if (!$album) {
            return response()->json(['success' => false, 'message' => 'Album not found'], 404);
        }

        // Fetch the first image that has a thumbnail (if exists)
        $thumbnail = DB::table('album_images')
            ->select('thumbnail_url')
            ->where('album_id', $id)
            ->whereNotNull('thumbnail_url') // Ensure thumbnail_url is not null
            ->first();

        // Debugging log to check what is returned
        Log::info('Thumbnail data:', ['thumbnail' => $thumbnail]);

        // Fetch all images linked to the album
        $images = DB::table('album_images')
            ->select('id', 'image_url')
            ->where('album_id', $id)
            ->get();

        // Log the image data for debugging
        Log::info('Images data:', ['images' => $images]);

        return response()->json([
            'success' => true,
            'album' => [
                'name' => $album->name,
                'thumbnail_url' => $thumbnail ? $thumbnail->thumbnail_url : 'default_thumbnail_url.jpg', // Use a default thumbnail URL if none exists
            ],
            'images' => $images,
        ]);
    }
}
