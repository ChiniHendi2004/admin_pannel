<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class Activity extends Controller
{
    public function activityPage()
    {
        return view('Backendpages.Activity.CreateActivity');
    }

    public function activityListPage()
    {
        return view('Backendpages.Activity.ActivityList');
    }

    public function editActivityPage($id)
    {
        return view('Backendpages.Activity.EditActivity', ['id' => $id]);
    }

    public function addMoreDetailsPage($id)
    {
        return view('Backendpages.Activity.ActivityDetailsAdd', ['id' => $id]);
    }

    public function viewActivityPage($id)
    {
        return view('Backendpages.Activity.ViewActivity', ['id' => $id]);
    }

    public function ViewSingleDetails($id, $paragraph_order)
    {
        return view('Backendpages.Activity.ViewSingleDetails', ['paragraph_order' => $paragraph_order, 'id' => $id,]);
    }

    public function createActivity(Request $request)
    {
        $tenantData = TenantHelper::getTenantData();
        
        $activity_group_id = $request->activity_group_id;
        $activity_name = $request->activity_name;
        $short_description = $request->short_description;
        $paragraph_text = $request->paragraph_text;


        $activity_id = DB::table('activities')->insertGetId([
            'activity_group_id' => $activity_group_id,
            'activity_name' => $activity_name,
            'event_date' =>  $request->event_date,
            'short_description' => $short_description,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
            'status' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        DB::table('activity_details')->insert([
            'activity_id' => $activity_id,
            'paragraph_order' => "1",
            'paragraph_text' => $paragraph_text,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
            'status' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Activity Created Successfully '
        ], 200);
    }

    public function activityList()
    {
        $tenantData = TenantHelper::getTenantData();
        $data = DB::table('activities')->where('tenant_id', $tenantData['tenant_id'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function selectIdWise($id)
    {
        $activity_name = DB::table('activities')->where('id', $id)->first();
        return response()->json([
            'status' => 'success',
            'data' => $activity_name
        ], 200);
    }

    // list page edit button for Activity 3 button option 

    public function editActivity(Request $request, $id)
    {
        $department = DB::table('activities')->where('id', $id)->first();

        if (!$department) {
            return response()->json([
                "message" => "Title Not Found"
            ], 400);
        }

        DB::table('activities')
            ->where('id', $id)
            ->update([
                'activity_name' => $request->activity_name,
                'event_date' => $request->event_date,
                'short_description' => $request->short_description,
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);
        return response()->json([
            'Status' => 'Success',
            'message' => 'Update Successfully'
        ], 200);
    }

    public function destroy($id)
    {
        // Check if the activity exists
        $activity = DB::table('activities')->where('id', $id)->first();

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        try {
            DB::transaction(function () use ($id) {
                // Delete activity details first
                DB::table('activity_details')->where('activity_id', $id)->delete();

                // Then delete the activity
                DB::table('activities')->where('id', $id)->delete();
            });

            return response()->json(['message' => 'Activity and related details deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete activity', 'error' => $e->getMessage()], 500);
        }
    }




    // --------------------------singel edit page for get desc call
    public function getActivityDetails(Request $request, $ActivityId)
    {

        $paragraphOrder = $request->input('paragraph_order');

        $activityDetails = DB::table('activity_details')
            ->where('activity_id', $ActivityId)
            ->where('paragraph_order', $paragraphOrder)
            ->first();

        if (!$activityDetails) {
            return response()->json(['success' => false, 'message' => 'No details found for the specified paragraph order.']);
        }

        return response()->json(['success' => true, 'data' => $activityDetails]);
    }


    public function addMultiDetails(Request $request)
    {
        $tenantData = TenantHelper::getTenantData();
        // Validate inputs
        $activity_id = $request->input('activity_id');
        $paragraph_order = $request->input('paragraph_order');
        $paragraph_text = $request->input('paragraph_text');
        $expiration_date = Carbon::parse($request->input('created_at'))->addYear(1);

        // Check if a record with the given activity_name and paragraph_order exists
        $checkQuery = DB::table('activity_details')
            ->where([
                'activity_id' => $activity_id,
                'paragraph_order' => $paragraph_order,
            ])
            ->first();

        if ($checkQuery) {
            // Update the existing record
            DB::table('activity_details')
                ->where([
                    'activity_id' => $activity_id,
                    'paragraph_order' => $paragraph_order,
                ])
                ->update([
                    'paragraph_text' => $paragraph_text,
                    'updated_at' => Carbon::now('Asia/Kolkata')
                ]);
        } else {
            // Insert a new record
            DB::table('activity_details')->insert([
                'activity_id' => $activity_id,
                'paragraph_order' => $paragraph_order,
                'paragraph_text' => $paragraph_text,
                'tenant_id' => $tenantData['tenant_id'],
                'client_slug' => $tenantData['client_slug'],
                'website_url' => $tenantData['website_url'],
                'employee_id' => $tenantData['employee_id'],
                'academic_session' => $tenantData['academic_session'],
                'expiration_date' => $tenantData['expiration_date'],
                'status' => '1',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Details updated successfully'
        ], 200);
    }

    public function getAllDetails($id)
    {
        $contents = DB::table('activity_details')->where('activity_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $contents
        ], 200);
    }

    // view page inside delete hover

    public function deleteContent($id)
    {
        $content = DB::table('activity_details')->where('paragraph_order', $id)->first();

        if (!$content) {
            return response()->json([
                "message" => "Content Not Found"
            ], 400);
        }

        DB::table('activity_details')->where('paragraph_order', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Content deleted successfully'
        ], 200);
    }

    public function deleteDetails(Request $request)
    {
        // Validate inputs
        $activity_id = $request->input('activity_id');
        $paragraph_order = $request->input('paragraph_order');

        // Check if a record with the given activity_name and paragraph_order exists
        $checkQuery = DB::table('activity_details')
            ->where([
                'activity_id' => $activity_id,
                'paragraph_order' => $paragraph_order,
            ])
            ->first();

        if ($checkQuery) {
            // Update the existing record
            DB::table('activity_details')
                ->where([
                    'activity_id' => $activity_id,
                    'paragraph_order' => $paragraph_order,
                ])
                ->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Delete  successfully'
        ], 200);
    }
    
}
