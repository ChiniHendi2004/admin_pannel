<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;



class ClientImageGroupController extends Controller
{
    public function create()
    {
        return view('BackendPages.ImageGroup.createImageGroup');
    }

    public function index()
    {
        $groups = DB::table('image_groups')->get();
        return response()->json(['data' => $groups]);
    }
    public function store(Request $request)
    {
        DB::table('image_groups')->insert([
            'group_name' => $request->group_name,
            'tenant_id' => 1,
            'client_slug' => 'client-part',
            'website_url' => 'www.col.com',
            'employee_id' => 1,
            'academic_session' => 2025,
            'expiration_date' => '2025-12-12',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Image group created successfully.']);
    }
    public function update(Request $request, $id)
    {
        DB::table('image_groups')->where('id', $id)->update([
            'group_name' => $request->group_name,
        ]);

        return response()->json(['message' => 'Image group updated successfully.']);
    }
    public function destroy($id)
    {
        DB::table('image_groups')->where('id', $id)->delete();
        return response()->json(['message' => 'Image group deleted successfully.']);
    }

    public function updateStatus(Request $request, $id)
    {
        DB::table('image_groups')->where('id', $id)->update([
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Status updated successfully.']);
    }
}
