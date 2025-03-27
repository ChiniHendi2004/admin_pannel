<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class Committee extends Controller
{
    public function committeePage()
    {
        return view('Backendpages.Committee.CreateCommittee');
    }

    public function committeeListPage()
    {
        return view('Backendpages.Committee.CommitteeList');
    }

    public function editCommitteePage($id)
    {
        return view('Backendpages.Committee.EditCommittee', ['id' => $id]);
    }

    public function addMoreDetailsPage($id)
    {
        return view('Backendpages.Committee.CommitteeDetailsAdd', ['id' => $id]);
    }


    public function viewcommitteePage($id)
    {
        return view('Backendpages.Committee.ViewCommittee', ['id' => $id]);
    }

    public function getViewSingleDetails($id, $paragraphid)
    {
        return view('Backendpages.Committee.ViewSingleDetails', ['paragraphid' => $paragraphid, 'id' => $id,]);
    }



    public function createCommittee(Request $request)
    {
        $tenantData = TenantHelper::getTenantData();
        
        $committee_group_id = $request->committee_group_id;
        $committee_name = $request->committee_name;
        $short_description = $request->short_description;
        $paragraph_text = $request->paragraph_text;
        $expiration_date = Carbon::parse($request->input('created_at'))->addYear(1);


        $committee_id = DB::table('committees')->insertGetId([
            'committee_group_id' => $committee_group_id,
            'committee_name' => $committee_name,
            'short_description' => $short_description,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
            'status' => 1,
        ]);


        DB::table('committee_details')->insert([
            'committee_id' => $committee_id,
            'paragraph_order' => "1",
            'paragraph_text' => $paragraph_text,
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
            'message' => 'Committee Created Successfully '
        ], 200);
    }

    public function CommitteeList()
    {
        $tenantData = TenantHelper::getTenantData();

        $data = DB::table('committees')->where('tenant_id', $tenantData['tenant_id'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function selectIdWise($id)
    {
        $committee_name = DB::table('committees')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $committee_name
        ], 200);
    }

    // list page edit button for committee 3 button option 

    public function editCommitteeTitle(Request $request, $id)
    {
        $department = DB::table('committees')->where('id', $id)->first();

        if (!$department) {
            return response()->json([
                "message" => "Title Not Found"
            ], 400);
        }

        DB::table('committees')
            ->where('id', $id)
            ->update([
                'committee_name' => $request->committee_name,
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
        // Check if the committee exists
        $committee = DB::table('committees')->where('id', $id)->first();

        if (!$committee) {
            return response()->json(['message' => 'Committees not found'], 404);
        }

        // Delete the committee
        DB::table('committees')->where('id', $id)->delete();

        return response()->json(['message' => 'Committees deleted successfully']);
    }



    // --------------------------singel edit page for get desc call
    public function getCommitteeDetails(Request $request, $committeeId)
    {

        $paragraphOrder = $request->input('paragraph_order');

        $committeeDetails = DB::table('committee_details')
            ->where('committee_id', $committeeId)
            ->where('paragraph_order', $paragraphOrder)
            ->first();

        if (!$committeeDetails) {
            return response()->json(['success' => false, 'message' => 'No details found for the specified paragraph order.']);
        }

        return response()->json(['success' => true, 'data' => $committeeDetails]);
    }


    // namaskar

    public function addMultiDetails(Request $request)
    {
        // Validate inputs
        $tenantData = TenantHelper::getTenantData();

        $committee_id = $request->input('committee_id');
        $paragraph_order = $request->input('paragraph_order');
        $paragraph_text = $request->input('paragraph_text');
        $expiration_date = Carbon::parse($request->input('created_at'))->addYear(1);

        // Check if a record with the given committee_name and paragraph_order exists
        $checkQuery = DB::table('committee_details')
            ->where([
                'committee_id' => $committee_id,
                'paragraph_order' => $paragraph_order,
            ])
            ->first();

        if ($checkQuery) {
            // Update the existing record
            DB::table('committee_details')
                ->where([
                    'committee_id' => $committee_id,
                    'paragraph_order' => $paragraph_order,
                ])
                ->update([
                    'paragraph_text' => $paragraph_text,
                    'updated_at' => Carbon::now('Asia/Kolkata')
                ]);
        } else {
            // Insert a new record
            DB::table('committee_details')->insert([
                'committee_id' => $committee_id,
                'paragraph_order' => $paragraph_order,
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
            'message' => 'Details updated successfully'
        ], 200);
    }

    public function getAllDetails($id)
    {
        $contents = DB::table('committee_details')->where('committee_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $contents
        ], 200);
    }
    // view page inside delete hover

    public function deleteContent($id)
    {
        $content = DB::table('committee_details')->where('paragraph_order', $id)->first();

        if (!$content) {
            return response()->json([
                "message" => "Content Not Found"
            ], 400);
        }

        DB::table('committee_details')->where('paragraph_order', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Content deleted successfully'
        ], 200);
    }


    public function deleteDetails(Request $request)
    {
        // Validate inputs
        $committee_id = $request->input('committee_id');
        $paragraph_order = $request->input('paragraph_order');

        // Check if a record with the given committee_name and paragraph_order exists
        $checkQuery = DB::table('committee_details')
            ->where([
                'committee_id' => $committee_id,
                'paragraph_order' => $paragraph_order,
            ])
            ->first();

        if ($checkQuery) {
            // Update the existing record
            DB::table('committee_details')
                ->where([
                    'committee_id' => $committee_id,
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
