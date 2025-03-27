<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CourseGroups extends Controller
{
    public function CreateGroupPage()
    {
        return view('BackendPages.CoursePages.CreateCourseGroup');
    }

    public function CoursegroupList()
    {
        return view('BackendPages.CoursePages.CourseGroupList');
    }


    public function addCourseGroups(Request $request)
    {

        $expiration_date = Carbon::parse($request->input('created_at'))->addYear(1);

        $department = array(
            "group_name" => $request->group_name,
            "tenant_id" => "1",
            "client_slug" => "user",
            "website_url" => "https://atreyawebs.com",
            "employee_id" => "1",
            "academic_session" => "2025-2026",
            "expiration_date" => $expiration_date,
        );

        DB::table('Course_groups')->insert($department);

        return response()->json([
            "status" => "success",
            "message" => $request->group_name . " Created successfully"
        ], 200);
    }

    public function CourseList()
    {
        $list = DB::table('course_groups')->get();

        return response()->json([
            "status" => "success",
            "data" => $list
        ], 200);
    }

    public function CourseListLatest()
    {
        $list = DB::table('course_groups')->orderBy('created_at', 'desc')->get();

        return response()->json([
            "status" => "success",
            "data" => $list
        ], 200);
    }


    public function editCourseGroup(Request $request)
    {
        $id  = $request->input('id');

        $updates = DB::table('course_groups')->where('id', $id)->first();
        if (! $updates) {
            // Handle the case where the update doesn't exist
            return redirect()->back()->with('error', 'Data not found');
        }

        DB::table('course_groups')->where('id', $id)
            ->update([
                'group_name' => $request->input('group_name'),
                'created_at' => $updates->created_at,
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);

        return  response()->json([
            'status' => 'success',
            'message' => $request->input('group_name') . ' updated Successfully'
        ], 200);
    }



    public function groupDetail($id)
    {
        $departments = DB::table('course_groups')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' => $departments
        ], 200);
    }

    public function statusEdit(Request $request, $id)
    {
        DB::table('course_groups')
            ->where('id', $id)
            ->update([
                'status' => $request->input('status'),
            ]);

        return response()->json([
            'status' => 'success',
            'message' => "Status Changed",
        ], 200);
    }


    public function activeGroupList()
    {
        $data = DB::table('course_groups')
            ->where('status', '1')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }


    public function removeUpdates($id)
    {
        DB::table('course_groups')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Updates Deleted Successfully'
        ], 200);
    }
}
