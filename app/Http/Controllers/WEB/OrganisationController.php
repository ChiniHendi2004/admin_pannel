<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class OrganisationController
{
    public function create()
    {
        return view('BackendPages.Organisation.addOrganisation');
    }

    // Handle the form submission to store a new organization
    public function store(Request $request)
    {
        $rules = [
            'info_type'    => 'required|string|max:30',
            'name'         => 'required|string|max:100',
            'address_one'  => 'required|string',
            'address_two'  => 'nullable|string',
            'city'         => 'required|string|max:100',
            'pin'          => 'required|integer',
            'district'     => 'required|string|max:50',
            'state'        => 'required|string|max:50',
            'mobile_no'    => 'required|string|max:10',
            'phone_no'     => 'nullable|string|max:10',
            'fax_no'       => 'nullable|string|max:15',
            'email'        => 'required|email|max:50',
            'website_url'  => 'nullable|url|max:100',
            'org_map'      => 'nullable|string',
            'logo'         => 'nullable|string',
            'footer_logo'  => 'nullable|string',
            'facebook'     => 'nullable|url|max:50',
            'youtube'      => 'nullable|url|max:50',
            'instagram'    => 'nullable|url|max:50',
            'linkedin'     => 'nullable|url|max:50',
            'google'       => 'nullable|url|max:50',
            'whats_app'    => 'nullable|string',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle file uploads
        $logoPath = $request->file('logo') ? $request->file('logo')->store('logos', 'public') : null;
        $footerLogoPath = $request->file('footer_logo') ? $request->file('footer_logo')->store('footer_logos', 'public') : null;

        // Insert data into the database
        DB::table('college_info')->insert([
            'info_type'    => $request->input('info_type'),
            'name'         => $request->input('name'),
            'address_one'  => $request->input('address_one'),
            'address_two'  => $request->input('address_two'),
            'city'         => $request->input('city'),
            'pin'          => $request->input('pin'),
            'district'     => $request->input('district'),
            'state'        => $request->input('state'),
            'mobile_no'    => $request->input('mobile_no'),
            'phone_no'     => $request->input('phone_no'),
            'fax_no'       => $request->input('fax_no'),
            'email'        => $request->input('email'),
            'website_url'  => $request->input('website_url'),
            'org_map'      => $request->input('org_map'),
            'logo'         => $logoPath,
            'footer_logo'  => $footerLogoPath,
            'facebook'     => $request->input('facebook'),
            'youtube'      => $request->input('youtube'),
            'instagram'    => $request->input('instagram'),
            'linkedin'     => $request->input('linkedin'),
            'google'       => $request->input('google'),
            'whats_app'    => $request->input('whats_app'),
            'created_at'   => now(),
            'updated_at'   => now(),
            'tenant_id'    => 1, // Assuming tenant_id is related to the authenticated user
        ]);

        return response()->json(['success' => 'Organization added successfully.']);
    }



}
