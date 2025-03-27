<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UpdatesController extends Controller
{
    public function getUpdatesPage()
    {
        return view('BackendPages.UpdatesPage.Create-Updates');
    }
    public function editUpdatesPage($id)
    {
        return view('BackendPages.UpdatesPage.Edit-Updates',  ['id' => $id]);
    }
    public function getUpdatesListPage()
    {
        return view('BackendPages.UpdatesPage.Updates-List');
    }
    public function addContentPage($id)
    {
        return view('BackendPages.UpdatesPage.Updates-Content', ['id' => $id]);
    }
    public function getSingleContent($update_id, $paragraph_no)
    {
        return view('BackendPages.Updatespage.Updates-Content-Single', ['paragraph_order' => $paragraph_no, 'update_id' => $update_id,]);
    }
    public function ViewUpdatespage($id)
    {
        return view('BackendPages.Updatespage.View-Updates', ['id' => $id]);
    }

    public function index(Request $request)
    {
        $query = DB::table('updates');

        // Sorting
        if ($request->has('sort_by')) {
            $direction = $request->get('order', 'asc');
            $query->orderBy($request->sort_by, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 20);
        $updates = $query->paginate($perPage);

        return response()->json($updates);
    }

    public function create(Request $request)
    {
        $list = DB::table('department_groups')
            ->where('id', $request->updates_id)
            ->select('group_name')
            ->first();

        $update_type = $list->group_name;
        $event_date = $request->event_date;
        $title = $request->title;  // Make sure it's coming from the correct field
        $display_image = $request->display_image;
        $paragraph_text = $request->updates_content;
        
        $tenantData = TenantHelper::getTenantData();
        // Make sure title is not null
        if (!$title) {
            return response()->json(['message' => 'Title is required'], 400);
        }

        $updatId = DB::table('updates')->insertGetId([
            'update_type' => $update_type,
            'event_date' => $event_date,
            'title' => $title,
            'display_image' => $display_image,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
            'status' => 1,
        ]);

        // Add the update details
        DB::table('update_details')->insert([
            'update_id' => $updatId,
            'paragraph_no' => 1,
            'detail_type' => "text",
            'paragraph_text' => $paragraph_text,
            'detail_image' => $display_image,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
            'status' => 1,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Updates Created Successfully '
        ], 200);
    }



    public function updatesList()
    {
        $tenantData = TenantHelper::getTenantData();

        $data =  DB::table('updates')->where('tenant_id', $tenantData['tenant_id'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function selectUpdatesDetails($id, $no)
    {
        // Fetch updates and join with album_images to get thumbnail_url
        $updates = DB::table('update_details')->where('update_id', "=", $id)->where('paragraph_no', $no)
            ->leftJoin('updates', 'updates.id', '=', 'update_details.update_id')
            ->select('updates.*', 'update_details.*')
            ->get();

        return response()->json([
            "status" => "success",
            "data" => $updates
        ], 200);
    }

    public function selectIdWise($id)
    {
        $updates = DB::table('updates')
            ->where('id', $id)
            ->first();

        if (!$updates) {
            return response()->json(['message' => 'updates not found'], 404);
        }

        return response()->json(['updates' => $updates]);
    }

    public function editUpdates(Request $request, $id)
    {
        // Retrieve the existing update
        $update = DB::table('updates')->where('id', $id)->get();

        // Check if the update exists
        if (!$update) {
            return response()->json([
                'status' => 'error',
                'message' => 'Update ID not found'
            ], 404);
        }

        // Prepare the data to be updated
        $data = [
            'title' => $request->title,
            'event_date' => $request->updates_date,
            'display_image' => $request->display_image 
        ];

        // Update the update record in the database
        DB::table('updates')->where('id', $id)->update($data);

        // Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'Update updated successfully'
        ]);
    }


    public function destroyUpdates($id)
    {
        // Find the update by ID
        $update = DB::table('updates')->where('id', $id)->first();

        if (!$update) {
            return response()->json(['message' => 'Update not found'], 404);
        }

        // Delete the update
        DB::table('updates')->where('id', $id)->delete();

        return response()->json(['message' => 'Update deleted successfully']);
    }


    public function getUpdatesDetails(Request $request, $id)
    {
        $paragraphOrder = $request->input('paragraph_order');

        $updateDetails = DB::table('update_details')
            ->where('update_id', $id)
            ->where('paragraph_no', $paragraphOrder)
            ->first();

        if (!$updateDetails) {
            return response()->json(['success' => false, 'message' => 'No details found for the specified paragraph order.']);
        }

        return response()->json(['success' => true, 'data' => $updateDetails]);
    }



    public function addMultiDetails(Request $request)
    {
        // Validate inputs
        $tenantData = TenantHelper::getTenantData();

        $update_id = $request->input('update_id');
        $paragraph_no = $request->input('paragraph_order');
        $paragraph_text = $request->input('paragraph_text');
        $expiration_date = Carbon::parse($request->input('created_at'))->addYear(1);

        $checkQuery = DB::table('update_details')
            ->where([
                'update_id' => $update_id,
                'paragraph_no' => $paragraph_no,
            ])
            ->first();

        if ($checkQuery) {
            // Update the existing record
            DB::table('update_details')
                ->where([
                    'update_id' => $update_id,
                    'paragraph_no' => $paragraph_no,
                ])
                ->update([
                    'paragraph_text' => $paragraph_text,
                    'updated_at' => Carbon::now('Asia/Kolkata')
                ]);
        } else {
            // Insert a new record
            DB::table('update_details')->insert([
                'update_id' => $update_id,
                'paragraph_no' => $paragraph_no,
                'paragraph_text' => $paragraph_text,
                'tenant_id' => $tenantData['tenant_id'],
                'client_slug' => $tenantData['client_slug'],
                'website_url' => $tenantData['website_url'],
                'employee_id' => $tenantData['employee_id'],
                'academic_session' => $tenantData['academic_session'],
                'expiration_date' => $tenantData['expiration_date'],
                'status' => 1,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Update Details Added successfully'
        ], 200);
    }



    public function getAllDetails($id)
    {
        $details = DB::table('update_details')->where('update_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $details
        ], 200);
    }



    public function deleteDetails($id)
    {
        $details = DB::table('update_details')->where('paragraph_no', $id)->first();

        if (!$details) {
            return response()->json([
                "message" => "Content Not Found"
            ], 400);
        }

        DB::table('update_details')->where('paragraph_no', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Content deleted successfully'
        ], 200);
    }
}
