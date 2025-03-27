<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helper\TenantHelper;


class ManageInquiry extends Controller
{
    public function GetContactpage()
    {
        return view('BackendPages.ManageInquiry.ManageContact');
    }

    public function CreateContactPage()
    {
        return view('BackendPages.ManageInquiry.CreateContact');
    }

    public function ContactUpdatePage($id)
    {
        return view('BackendPages.ManageInquiry.ContactUpdate', ['id' => $id]);
    }

    public function CreateContact(Request $request)
    {
        $tenant = TenantHelper::getTenantData();
        {
            $visitor_name = $request->visitor_name;
            $visitor_email = $request->visitor_email;
            $visitor_phone = $request->visitor_phone;
            $department_id = '1';
            $complaint_status = $request->complaint_status;
            $message = $request->message;
            $tenant_id = $tenant['tenant_id'];
            $client_slug = $tenant['client_slug'];
            $website_url = $tenant['website_url'];
            $employee_id = $tenant['employee_id'];
            $academic_session = $tenant['academic_session'];
            $expiration_date = $tenant['expiration_date'];

            $Data = array(
                'visitor_name' => $visitor_name,
                'visitor_email' => $visitor_email,
                'visitor_phone' => $visitor_phone,
                'department_id' => $department_id,
                'complaint_status' => $complaint_status,
                'message' => $message,
                'tenant_id' => $tenant_id,
                'client_slug' => $client_slug,
                'website_url' => $website_url,
                'employee_id' => $employee_id,
                'academic_session' => $academic_session,
                'expiration_date' => $expiration_date,
            );


            DB::table('contact_us')->insert($Data);


            return response()->json([
                'message' => 'Contact Created Successfully'
            ], 200);
        }
    }


    public function contactList(Request $request)
    {

        $data = DB::table('contact_us')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $data
        ], 200);
    }

    public function contactInfo($id)
    {
        $data = DB::table('contact_us')
            ->where('id', $id)
            ->first();
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function updateContact(Request $request, $id)
    {
        $data = DB::table('contact_us')->where('id', $id)->first();

        if (!$data) {
            return response()->json([
                "message" => "contact Not Found"
            ], 400);
        }

        DB::table('contact_us')
            ->where('id', $id)
            ->update([
                'visitor_name' => $request->input('visitor_name'),
                'visitor_email' => $request->input('visitor_email'),
                'complaint_status' => $request->input('complaint_status'),
                'visitor_phone' => $request->input('visitor_phone'),
                'message' => $request->input('message'),
            ]);


        return response()->json([
            'Status' => 'Success',
            'message' => ' Details Updated Successfully',
        ], 200);
    }

    public function deleteContact($id)
    {
        DB::table('contact_us')->where('id', $id)->delete();
        return response()->json([
            'message' => 'inquiry Deleted Successfully'
        ], 200);
    }

    public function statusEdit(Request $request, $id)
    {
        DB::table('contact_us')
            ->where('id', $id)
            ->update([
                'status' => $request->input('status')
            ]);

        return response()->json([
            'status' => 'success',
            'message' => "Status Changed",
        ], 200);
    }
}
