<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FAQController extends Controller
{
    public function FAQPage()
    {
        return view('BackendPages.MasterPage.FAQ-Master');
    }

    public function FAQListPage()
    {
        return view('BackendPages.MasterPage.FAQ-Master-List');
    }

    public function addFAQ(Request $request)
    {
        $tenantData = TenantHelper::getTenantData();

        $Detail = array(
            "department_id" => $request->department_id,
            "course_id" => $request->course_id,
            "question" => $request->question,
            "answer" => $request->answer,
            'tenant_id' => $tenantData['tenant_id'],
            'client_slug' => $tenantData['client_slug'],
            'website_url' => $tenantData['website_url'],
            'employee_id' => $tenantData['employee_id'],
            'academic_session' => $tenantData['academic_session'],
            'expiration_date' => $tenantData['expiration_date'],
        );

        DB::table('faq_master')->insert($Detail);

        return response()->json([
            "status" => "success",
            "message" => $request->question . " Created successfully"
        ], 200);
    }

    public function faqList()
    {
        $tenantData = TenantHelper::getTenantData();
        $list = DB::table('faq_master')->where('tenant_id',$tenantData['tenant_id'])->get();

        return response()->json([
            "status" => "success",
            "data" => $list
        ], 200);
    }


    public function editFAQ(Request $request)
    {
        $faq_id  = $request->input('faq_id');

        // Check if the Updates exists
        // dd($request->input('update_id'));
        $updates = DB::table('faq_master')->where('id', $faq_id)->first();
        if (! $updates) {
            // Handle the case where the update doesn't exist
            return redirect()->back()->with('error', 'Updates not found');
        }
        DB::table('faq_master')->where('id', $faq_id)
            ->update([
                'question' => $request->input('question'),
                'answer' => $request->input('answer'),
                'created_at' => $updates->created_at,
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);

        return  response()->json([
            'status' => 'success',
            'message' => $request->input('faq_title') . ' updated Successfully'
        ], 200);
    }



    public function faqDetail($id)
    {
        $updateDetails = DB::table('faq_master')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $updateDetails
        ], 200);
    }

    public function statusEdit(Request $request, $faq_id)
    {
        DB::table('faq_master')
            ->where('id', $faq_id)
            ->update([
                'status' => $request->input('status'),
            ]);

        return response()->json([
            'status' => 'success',
            'message' => "Status Changed",
        ], 200);
    }

    public function removeFAQ($id)
    {
        DB::table('faq_master')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'FAQ Deleted Successfully'
        ], 200);
    }

    public function getFAQstatusWise()
    {
        $tenantData = TenantHelper::getTenantData();
        $data = DB::table('faq_master')
            ->where('status', '1')
            ->where('tenant_id', $tenantData['tenant_id'])
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
