<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Helper\TenantHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ManageTestimonial extends Controller
{
    public function getTestimonialsPage()
    {
        return view('BackendPages.ManageTestimonial.Add-Testimonial');
    }

    public function TestimonialListPage()
    {
        return view('BackendPages.ManageTestimonial.Testimonial-List');
    }
    public function TestimonialUpdatePage($id)
    {
        return view('BackendPages.ManageTestimonial.Update-Testimonial', ['id' => $id]);
    }

    public function addTestimonials(Request $request)
    {
        $tenant = TenantHelper::getTenantData();
        $name = $request->name;
        $testimonial_text = $request->testimonial_text;
        $rating = $request->rating;
        $photo_url = $request->photo_url;

        $data = [
            'tenant_id' => $tenant['tenant_id'],
            "employee_id" => $tenant['employee_id'],
            "client_slug" => $tenant['client_slug'],
            'name' => $name,
            'testimonial_text' => $testimonial_text,
            "website_url" => $tenant['website_url'],
            'photo_url' => $photo_url,
            'rating' => $rating,
            "academic_session" => $tenant['academic_session'],
            "expiration_date" => $tenant['expiration_date'],
        ];

        DB::table('manage_testimonial')->insert($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Testimonial Successfully Created'
        ], 200);
    }

    public function TestimonialList()
    {
        $tenant = TenantHelper::getTenantData();
        $data = DB::table('manage_testimonial')
            ->where('tenant_id', $tenant['tenant_id'])
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function TestimonialListlatest()
    {
        $data = DB::table('manage_testimonial')
            ->where('testimonial_type', 'Testimonial')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function TestimonialDetails($id)
    {
        $data = DB::table('manage_testimonial')->where('id', $id)->first();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function deleteTestimonial($id)
    {
        DB::table('manage_testimonial')->where('id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Testimonial Deleted Successfully'
        ], 200);
    }

    public function updateTestimonial(Request $request, $id)
    {
        $data = DB::table('manage_testimonial')->where('id', $id)->first();

        if (!$data) {
            return response()->json([
                "message" => "Testimonial Not Found"
            ], 400);
        }

        // Update other album details
        DB::table('manage_testimonial')
            ->where('id', $id)
            ->update([
                'name' => $request->input('name'),
                'testimonial_text' => $request->input('testimonial_text'),
                'rating' => $request->input('rating'),
                'photo_url' => $request->input('photo_url'),
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);


        return response()->json([
            'Status' => 'Success',
            'message' => 'Testimonial Details Updated Successfully',
        ], 200);
    }
}
