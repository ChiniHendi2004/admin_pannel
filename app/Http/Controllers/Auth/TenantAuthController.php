<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ResetPasswordMail;

class TenantAuthController extends Controller
{
    // Show Login Form
    public function showLoginForm()
    {
        return view('auth.login');
    }
  public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Show Forgot Password Form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Show Reset Password Form
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Handle Login
    public function login(Request $request)
    {
        $request->validate([
            'client_slug' => 'required',
            'password' => 'required',
        ]);

        $tenant = Tenant::where('client_slug', $request->client_slug)->first();

        if ($tenant && Hash::check($request->password, $tenant->password)) {
            Auth::login($tenant);
            session(['tenant_id' => $tenant->id]); // Store tenant ID in session
            return redirect()->route('home')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors(['Invalid credentials']);
    }

    // Show Dashboard
    public function home()
    {
        $tenantId = session('tenant_id');
        if (!$tenantId) {
            return redirect()->route('login')->withErrors(['Please log in first.']);
        }

        $tenant = Tenant::find($tenantId);

        return view('BackendPages.Dashboard.dashboard', ['tenant' => $tenant]);
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        session()->forget('tenant_id');
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }

    // Register Tenant
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'required|string|email|max:255|unique:tenants,email',
            'client_slug' => 'required|string|max:255|unique:tenants,client_slug',
            'website_url' => 'required|url|max:255',
            'employee_id' => 'required|integer',
            'academic_session' => 'required|date',
            'expiration_date' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Tenant::create([
            'name' => $request->name,
            'description' => $request->description,
            'email' => $request->email,
            'client_slug' => $request->client_slug,
            'website_url' => $request->website_url,
            'employee_id' => $request->employee_id,
            'academic_session' => $request->academic_session,
            'expiration_date' => $request->expiration_date,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Tenant registered successfully!');
    }

    // Send Reset Password Email
    public function sendResetPasswordEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:tenants,email']);

        $tenant = Tenant::where('email', $request->email)->first();

        // Generate reset token and save it in the database
        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $tenant->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Send the reset password email
        Mail::to($tenant->email)->send(new ResetPasswordMail($token));

        return back()->with('status', 'Password reset link sent to your email.');
    }

    // Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:password_resets,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $resetRecord = DB::table('password_resets')->where([
            'token' => $request->token,
            'email' => $request->email,
        ])->first();

        if (!$resetRecord) {
            return back()->withErrors(['error' => 'Invalid token or email.']);
        }

        $tenant = Tenant::where('email', $request->email)->first();
        $tenant->update(['password' => Hash::make($request->password)]);

        // Delete the reset record after successful password reset
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Password has been reset successfully.');
    }
}
