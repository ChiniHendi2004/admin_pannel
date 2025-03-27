<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helper\TenantHelper;

class Content 
{
    public function CreatePage()
    {
        return view('BackendPages.ContentPages.CreateContent');
    }

    public function contentListPage()
    {
        return view('BackendPages.ContentPages.ContentList');
    }

    public function addMoreDetails($id)
    {
        return view('BackendPages.ContentPages.ContentDetailsAdd', ['id' => $id]);
    }

    public function contentUpdatePage($id)
    {
        return view('BackendPages.ContentPages.EditContent', ['id' => $id]);
    }

    public function ViewContentPage($id)
    {
        return view('BackendPages.ContentPages.ViewContent', ['id' => $id]);
    }

    public function getViewSingleDetails($id, $paragraphid)
    {
        return view('BackendPages.ContentPages.ViewSingleDetail', ['paragraphid' => $paragraphid, 'id' => $id,]);
    }

    public function createContent(Request $request)
    {
        $content_group_id = $request->id;
        $title = $request->title;
        $short_description = $request->short_description;
        $paragraph_text = $request->paragraph_text;

        $tenantData = TenantHelper::getTenantData();

        $contentid = DB::table('content')->insertGetId([
            'content_group_id' => $content_group_id,
            'title' => $title,
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

        DB::table('content_details')->insert([
            'content_id' => $contentid,
            'paragraph_order' => '1',
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
            'message' => 'Content Created Successfully '
        ], 200);
    }

    public function contentList()
    {
        $tenantData = TenantHelper::getTenantData();
        $data = DB::table('content')->where('tenant_id', $tenantData['tenant_id'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getTitleDetail($id)
    {
        $title = DB::table('content')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $title
        ], 200);
    }

    public function updateContent(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
        ]);

        $updated = DB::table('content')
            ->where('id', $id)
            ->update([
                'title' => $request->title,
                'short_description' => $request->short_description,
                'updated_at' => now()
            ]);

        if (!$updated) {
            return response()->json(['message' => 'Failed to update content'], 500);
        }

        return response()->json(['message' => 'Content updated successfully'], 200);
    }

    public function getContentDetails(Request $request, $contentid)
    {
        $paragraphOrder = $request->input('paragraph_order');

        $facilityDetail = DB::table('content_details')
            ->where('content_id', $contentid)
            ->where('paragraph_order', $paragraphOrder)
            ->first();

        if (!$facilityDetail) {
            return response()->json(['success' => false, 'message' => 'No details found for the specified paragraph order.']);
        }

        return response()->json(['success' => true, 'data' => $facilityDetail]);
    }

    public function addMultiDetails(Request $request)
    {
        $content_id = $request->input('content_id');
        $paragraph_order = $request->input('paragraph_order');
        $paragraph_text = $request->input('paragraph_text');
        $expiration_date = Carbon::parse($request->input('created_at'))->addYear(1);

        $tenantData = TenantHelper::getTenantData();

        $checkQuery = DB::table('content_details')
            ->where([
                'content_id' => $content_id,
                'paragraph_order' => $paragraph_order,
            ])
            ->first();

        if ($checkQuery) {
            DB::table('content_details')
                ->where([
                    'content_id' => $content_id,
                    'paragraph_order' => $paragraph_order,
                ])
                ->update([
                    'paragraph_text' => $paragraph_text,
                    'updated_at' => Carbon::now('Asia/Kolkata')
                ]);
        } else {
            DB::table('content_details')->insert([
                'content_id' => $content_id,
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
            'message' => 'Content Details updated successfully'
        ], 200);
    }

    public function getAllDetails($id)
    {
        $contents = DB::table('content_details')->where('content_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $contents
        ], 200);
    }
    public function deleteContent($id)
    {
        DB::table('content_details')->where('content_id', $id)->delete();
        DB::table('content')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Content and its details deleted successfully'
        ], 200);
    }

    public function removeContent(Request $request)
    {
        $paragraphOrder = $request->input('paragraph_order');
        $contentId = $request->input('content_id');

        $deleted = DB::table('content_details')
            ->where('paragraph_order', $paragraphOrder)
            ->where('content_id', $contentId)
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Paragraph deleted successfully']);
        }

        return response()->json(['message' => 'Failed to delete paragraph'], 400);
    }
}
