<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ManageMassage extends Controller
{
    public function CreateMassegePage()
    {
        return view('BackendPages.Massege.Manage-Message');
    }


    public function messageListPage()
    {
        return view('BackendPages.Massege.Message-List');
    }
    public function ViewMassegePage($id)
    {
        return view('BackendPages.Massege.View-Massege', ['id' => $id]);
    }

    public function updateMessagePage($id)
    {
        return view('BackendPages.Massege.Message-Edit', ['id' => $id]);
    }

    public function getProfileDesignationWise(Request  $request)
    {
        $list = DB::table('user_profile')->select('id', 'name')->where('designation', $request->designation)->get();
        return response()->json([
            'status' => 'success',
            'data' => $list
        ]);
    }

    public function selectMeassage($id)
    {
        $data = DB::table('manage_massage')
            ->select('user_profile.name', 'designation_master.designation_type', 'manage_massage.*')
            ->join('user_profile', 'manage_massage.designation_id', '=', 'user_profile.designation')
            ->join('designation_master', 'manage_massage.designation_id', '=', 'designation_master.designation_id')
            ->where('manage_massage.slno', $id)
            ->first();
        $imageUrl = asset('storage/' . $data->message_image);
        $data->img_url = $imageUrl;
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function createMessage(Request $request)
    {
        $designation_id = $request->designation;
        $profile_id = $request->profile_id;
        $message_title = $request->message_title;
        $messsage_content = $request->messsage_content;

        if ($request->hasFile('message_image')) {
            $file = $request->file('message_image');
            $extension = $file->getClientOriginalExtension();
            $filename = 'uploads/' . time() . '.' . $extension;
            $file->storeAs('public', $filename);
        }
        $messageData = array(
            "designation_id" => $designation_id,
            "profile_id" => $profile_id,
            "message_image" => $filename,
            "message_title" => $message_title,
            "messsage_content" => $messsage_content,

        );

        DB::table('manage_massage')->insert($messageData);


        return response()->json([
            'message' => 'Message Created successfully'
        ], 200);
    }


    public function getall()
    {
        $list = DB::table('manage_massage')
            ->leftJoin('designation_master', 'manage_massage.designation_id', '=', 'designation_master.designation_id')
            ->leftJoin('user_profile', 'manage_massage.profile_id', '=', 'user_profile.id')
            ->select('manage_massage.*', 'user_profile.name', 'designation_master.designation_type')
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $list
        ]);
    }

    public function getUserDesignation()
    {
        $list = DB::table('user_profile')
            ->select([
                'user_profile.designation',
                'designation_master.designation_type'
            ])
            ->leftJoin('designation_master', 'user_profile.designation', 'designation_master.id')
            ->distinct()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $list
        ], 200);
    }

    public function getUserDepartment()
    {
        $list = DB::table('user_profile')
            ->select([
                'user_profile.designation',
                'designation_master.designation_type'
            ])
            ->leftJoin('department_master', 'user_profile.department', 'department_master.department_id')
            ->distinct()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $list
        ], 200);
    }

    public function messageUpdate(Request $request, $id)
    {
        $data = DB::table('manage_massage')->where('slno', $id)->first();

        if (!$data) {
            return response()->json([
                "message" => "Data Not Found"
            ], 400);
        }

        // Get the current image path and name
        $currentImagePath = $data->message_image;
        $currentImageName = pathinfo($currentImagePath, PATHINFO_BASENAME);

        if ($request->hasFile('message_image')) {
            $file = $request->file('message_image');
            $extension = $file->getClientOriginalExtension();
            $newImageName = 'uploads/' . time() . '.' . $extension;

            // Store the new image with the updated path
            $file->storeAs('public', $newImageName);

            if ($currentImagePath) {

                Storage::delete('public/uploads/' . $currentImageName);
            }
        } else {
            $newImageName = $currentImagePath;
        }

        // Update other Message details
        DB::table('manage_massage')
            ->where('slno', $id)
            ->update([
                'message_image' => $newImageName,
                'message_title' => $request->input('message_title'),
                'messsage_content' => $request->input('messsage_content'),
                'updated_at' => Carbon::now('Asia/Kolkata')
            ]);


        return response()->json([
            'Status' => 'Success',
            'message' => 'Message Updated successfully',
        ], 200);
    }

    public function removeMessage($id)
    {
        DB::table('manage_massage')->where('slno', $id)->delete();
        return response()->json([
            'Status' => 'Success',
            'message' => 'Message Deleted successfully',
        ], 200);
    }
    public function latestMessageList()
    {
        $data = DB::table('manage_massage')
            ->select('manage_massage.*', 'user_profile.*', 'designation_master.*')
            ->leftJoin('user_profile', 'manage_massage.profile_id', '=', 'user_profile.id')
            ->leftJoin('designation_master', 'manage_massage.designation_id', '=', 'designation_master.designation_id')
            ->orderBy('manage_massage.created_at', 'desc')
            ->take(3)
            ->get();

        foreach ($data as $list) {
            $imageUrl = asset('storage/' . $list->message_image);
            $list->image_url = $imageUrl;
        }

        return response()->json([
            'Status' => 'Success',
            'data' => $data,
        ], 200);
    }
}
