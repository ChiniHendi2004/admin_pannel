<?php

namespace App\Http\Controllers\WEB;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helper\TenantHelper;

class ManageNews
{
    public function ScrollingNewsPage()
    {
        return view('BackendPages.ScrollingNews.Manage-Scrolling-News');
    }

    public function NewsListPage()
    {
        return view('BackendPages.ScrollingNews.NewsList');
    }

    public function NewsUpdatePage($id)
    {
        return view('BackendPages.ScrollingNews.NewsUpdate', ['id' => $id]);
    }

    public function NewsDetailPage($id)
    {
        return view('BackendPages.ScrollingNews.ViewNews', ['id' => $id]);
    }

    public function createScrollingNews(Request $request)
    {
        $tenant = TenantHelper::getTenantData();

        $heading = $request->input('heading');
        if (!$heading) {
            return response()->json([
                'status' => 'error',
                'message' => 'The heading field is required.',
            ], 422);
        }
        if (strlen($heading) > 70) {
            return response()->json([
                'status' => 'error',
                'message' => 'The heading must not exceed 70 characters.',
            ], 422);
        }
        $publication_date = $request->publication_date;
        $body = $request->body;
        $heading_url = $request->heading_url;

        $scrolling_iddata = array(
            "tenant_id" => $tenant['tenant_id'],
            "author_id" => '1',
            "employee_id" => $tenant['employee_id'] ,
            "client_slug" => $tenant['client_slug'],
            "heading" => $heading,
            "publication_date" => $publication_date,
            "body" => $body,
            "heading_url" => $heading_url,
            "website_url" => $tenant['website_url'],
            "academic_session" => $tenant['academic_session'],
            "expiration_date" => $tenant['expiration_date'],
        );
        DB::table('scrolling_news')->insert($scrolling_iddata);
        return response()->json([
            'status' => "Success",
            'message' => "News Created Successfully"
        ], 200);
    }

    public function newslist()
    {
        $tenant = TenantHelper::getTenantData();
        $list = DB::table('scrolling_news')->where('status', '1')->where('tenant_id', $tenant['tenant_id'])->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => "Success",
            'data' => $list

        ], 200);
    }

    public function newsStatuslist()
    {
        $list = DB::table('scrolling_news')
            ->where('status', '1')
            ->get();
        return response()->json([
            'status' => "Success",
            'data' => $list

        ], 200);
    }
    public function newsDetails($id)
    {
        $data = DB::table('scrolling_news')->where('id', $id)->first();
        return response()->json([
            'status' => "Success ",
            'data' => $data,
        ], 200);
    }

    public function updateNewsDetails(Request $request, $id)
    {
        $data = DB::table('scrolling_news')->where('id', $id)->first();
        if (! $data) {
            return redirect()->back()->with('error', 'News not found');
        }

        DB::table('scrolling_news')->where('id', $id)
            ->update([
                'heading' => $request->input('heading'),
                'publication_date' => $request->input('publication_date'),
                'heading_url' => $request->input('heading_url'),
                'body' => $request->input('body'),
            ]);

        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function newsDelete($id)
    {
        DB::table('scrolling_news')->where('id', $id)->delete();
        return response()->json([
            'status' => "Success ",
            'message' => "News Deleted Successfully",
        ], 200);
    }

    public function statusEdit(Request $request, $id)
    {
        DB::table('scrolling_news')
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
