<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Course extends Controller
{
    public function CreatePage()
    {
        return view('BackendPages.CoursePages.CreateCourse');
    }

    public function CourseListPage()
    {
        return view('BackendPages.CoursePages.CourseList');
    }

    public function addMoreDetails($id)
    {
        return view('BackendPages.CoursePages.CourseDetailsAdd', ['id' => $id]);
    }

    public function CourseUpdatePage($id)
    {
        return view('BackendPages.CoursePages.EditCourse', ['id' => $id]);
    }

    public function ViewCoursePage($id)
    {
        return view('BackendPages.CoursePages.ViewCourse', ['id' => $id]);
    }

    public function getViewSingleDetails($id, $paragraphid)
    {
        return view('BackendPages.CoursePages.ViewSingleDetail', ['paragraphid' => $paragraphid, 'id' => $id,]);
    }



    public function createCourse(Request $request)
    {
        $Course_group_id = $request->id;
        $title = $request->title;
        $short_description = $request->short_description;
        $paragraph_text = $request->paragraph_text;
        $expiration_date = Carbon::parse($request->input('created_at'))->addYear(1);


        $courseid = DB::table('course')->insertGetId([
            'course_group_id' => $Course_group_id,
            'title' => $title,
            'short_description' => $short_description,
            'tenant_id' => "1",
            'client_slug' => "User",
            'website_url' => "https://atreyawebs.com",
            'employee_id' => "1",
            'academic_session' => "2025-2026",
            'expiration_date' => $expiration_date,
        ]);


        DB::table('course_details')->insert([
            'course_id' => $courseid,
            'paragraph_order' => "1",
            'paragraph_text' => $paragraph_text,
            'tenant_id' => "1",
            'client_slug' => "User",
            'website_url' => "https://atreyawebs.com",
            'employee_id' => "1",
            'academic_session' => "2025-2026",
            'expiration_date' => $expiration_date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Courses Created Successfully '
        ], 200);
    }

    public function CourseList()
    {
        $data = DB::table('course')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getTitleDetail($id)
    {
        $title = DB::table('course')->where('id', $id)->first();


        return response()->json([
            'status' => 'success',
            'data' => $title
        ], 200);
    }

    public function editCourseHeading(Request $request, $id)
    {
        $department = DB::table('course')->where('id', $id)->first();

        if (!$department) {
            return response()->json([
                "message" => "Title Not Found"
            ], 400);
        }

        DB::table('course')
            ->where('id', $id)
            ->update([
                'title' => $request->title,
                'short_description' => $request->short_description,
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);
        return response()->json([
            'Status' => 'Success',
            'message' => $request->input('title') . ' Update Successfully'
        ], 200);
    }


    public function getCourseDetails(Request $request, $Courseid)
    {
        $paragraphOrder = $request->input('paragraph_order');

        $facilityDetail = DB::table('course_details')
            ->where('course_id', $Courseid)
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
        $Course_id = $request->input('course_id');
        $paragraph_order = $request->input('paragraph_order');
        $paragraph_text = $request->input('paragraph_text');
        $expiration_date = Carbon::parse($request->input('created_at'))->addYear(1);

        // Check if a record with the given title_id and content_no exists
        $checkQuery = DB::table('course_details')
            ->where([
                'course_id' => $Course_id,
                'paragraph_order' => $paragraph_order,
            ])
            ->first();

        if ($checkQuery) {
            // Update the existing record
            DB::table('course_details')
                ->where([
                    'course_id' => $Course_id,
                    'paragraph_order' => $paragraph_order,
                ])
                ->update([
                    'paragraph_text' => $paragraph_text,
                    'updated_at' => Carbon::now('Asia/Kolkata')
                ]);
        } else {
            // Insert a new record
            DB::table('course_details')->insert([
                'course_id' => $Course_id,
                'paragraph_order' => $paragraph_order,
                'paragraph_text' => $paragraph_text,
                'tenant_id' => "1",
                'client_slug' => "User",
                'website_url' => "https://atreyawebs.com",
                'employee_id' => "1",
                'academic_session' => "2025-2026",
                'expiration_date' => $expiration_date,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Course Details upload successfully'
        ], 200);
    }

    public function getAllDetails($id)
    {
        $contents = DB::table('course_details')->where('course_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $contents
        ], 200);
    }

    public function destroy($id)
    {
        // Check if the course exists
        $course = DB::table('course')->where('id', $id)->first();

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        // Delete related course details first
        DB::table('course_details')->where('course_id', $id)->delete();

        // Delete the course
        DB::table('course')->where('id', $id)->delete();

        return response()->json(['message' => 'Course and its details deleted successfully']);
    }


    public function deleteContent($id)
    {
        $content = DB::table('course_details')->where('paragraph_order', $id)->first();

        if (!$content) {
            return response()->json([
                "message" => "Content Not Found"
            ], 400);
        }

        DB::table('course_details')->where('paragraph_order', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Content deleted successfully'
        ], 200);
    }


    public function deleteDetails(Request $request)
    {
        // Validate inputs
        $course_id = $request->input('course_id');
        $paragraph_order = $request->input('paragraph_order');

        // Check if a record with the given course_name and paragraph_order exists
        $checkQuery = DB::table('course_details')
            ->where([
                'course_id' => $course_id,
                'paragraph_order' => $paragraph_order,
            ])
            ->first();

        if ($checkQuery) {
            // Update the existing record
            DB::table('course_details')
                ->where([
                    'course_id' => $course_id,
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
