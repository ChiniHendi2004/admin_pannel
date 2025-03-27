<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Facility
{
    public function CreatePage()
    {
        return view('BackendPages.FacilityPages.CreateFacility');
    }

    public function facilityListPage()
    {
        return view('BackendPages.FacilityPages.FacilityList');
    }

    public function addMoreDetails($id)
    {
        return view('BackendPages.FacilityPages.FacilityDetailsAdd', ['id' => $id]);
    }

    public function facilityUpdatePage($id)
    {
        return view('BackendPages.FacilityPages.EditFacility', ['id' => $id]);
    }

    public function ViewfacilityPage($id)
    {
        return view('BackendPages.FacilityPages.ViewFacility', ['id' => $id]);
    }

    public function getViewSingleDetails($id, $paragraphid)
    {
        return view('BackendPages.FacilityPages.ViewSingleDetail', ['paragraphid' => $paragraphid, 'id' => $id,]);
    }

    public function createFacility(Request $request)
    {
        $tenantData = TenantHelper::getTenantData();
        
        $facility_group_id = $request->id;
        $facility_name = $request->facility_name;
        $facility_image = $request->facility_image;
        $short_description = $request->short_description;
        $paragraph_text = $request->paragraph_text;

        $facilityid = DB::table('facilities')->insertGetId([
            'facility_group_id' => $facility_group_id,
            'facility_name' => $facility_name,
            'sr_no' => "1",
            'facility_image' => $facility_image,
            'short_description' => $short_description,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
            'status' => '1',
        ]);

        DB::table('facility_details')->insert([
            'facility_id' => $facilityid,
            'paragraph_order' => "1",
            'paragraph_text' => $paragraph_text,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
            'status' => '1',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Facility Created Successfully '
        ], 200);
    }

    public function FacilityList()
    {
        $tenantData = TenantHelper::getTenantData();

        $data = DB::table('facilities')->where('tenant_id', $tenantData['tenant_id'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getTitleDetail($id)
    {
        $title = DB::table('facilities')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $title
        ], 200);
    }

    public function editFacilityTitle(Request $request, $id)
    {
        $department = DB::table('facilities')->where('id', $id)->first();

        if (!$department) {
            return response()->json([
                "message" => "Title Not Found"
            ], 400);
        }

        DB::table('facilities')
            ->where('id', $id)
            ->update([
                'facility_name' => $request->facility_name,
                'facility_image' => $request->facility_image,
                'short_description' => $request->short_description,
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);
        return response()->json([
            'Status' => 'Success',
            'message' => $request->input('facility_name') . ' Update Successfully'
        ], 200);
    }

    public function getFacilityDetails(Request $request, $facilityId)
    {
        $paragraphOrder = $request->input('paragraph_order');

        $facilityDetail = DB::table('facility_details')
            ->where('facility_id', $facilityId)
            ->where('paragraph_order', $paragraphOrder)
            ->first();

        if (!$facilityDetail) {
            return response()->json(['success' => false, 'message' => 'No details found for the specified paragraph order.']);
        }

        return response()->json(['success' => true, 'data' => $facilityDetail]);
    }

    public function addMultiDetails(Request $request)
    {
        // Validate inputs
        $tenantData = TenantHelper::getTenantData();
        $facility_id = $request->input('facility_id');
        $paragraph_order = $request->input('paragraph_order');
        $paragraph_text = $request->input('paragraph_text');

        // Check if a record with the given title_id and content_no exists
        $checkQuery = DB::table('facility_details')
            ->where([
                'facility_id' => $facility_id,
                'paragraph_order' => $paragraph_order,
            ])
            ->first();

        if ($checkQuery) {
            // Update the existing record
            DB::table('facility_details')
                ->where([
                    'facility_id' => $facility_id,
                    'paragraph_order' => $paragraph_order,
                ])
                ->update([
                    'paragraph_text' => $paragraph_text,
                    'updated_at' => Carbon::now('Asia/Kolkata')
                ]);
        } else {
            // Insert a new record
            DB::table('facility_details')->insert([
                'facility_id' => $facility_id,
                'paragraph_order' => $paragraph_order,
                'paragraph_text' => $paragraph_text,
                'tenant_id' => $tenantData['tenant_id'],
                'client_slug' => $tenantData['client_slug'],
                'website_url' => $tenantData['website_url'],
                'employee_id' => $tenantData['employee_id'],
                'academic_session' => $tenantData['academic_session'],
                'expiration_date' => $tenantData['expiration_date'],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Facility Details updated successfully'
        ], 200);
    }

    public function getAllDetails($id)
    {
        $contents = DB::table('facility_details')->where('facility_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $contents
        ], 200);
    }

    public function removeFacility($id)
    {
        // Check if the facility exists
        $facility = DB::table('facilities')->where('id', $id)->first();

        if (!$facility) {
            return response()->json(['message' => 'Facility not found'], 404);
        }

        try {
            DB::transaction(function () use ($id) {
                // Delete facility details first
                DB::table('facility_details')->where('facility_id', $id)->delete();

                // Then delete the facility
                DB::table('facilities')->where('id', $id)->delete();
            });

            return response()->json(['message' => 'Facility and related details deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete Facility', 'error' => $e->getMessage()], 500);
        }
    }
}
