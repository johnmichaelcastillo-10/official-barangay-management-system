<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Debug: Check kung ano ang actual status value
        \Log::info('User status: ' . $user->status);

        // Check if user status is active (case-insensitive)
        if (strtolower($user->status) !== 'active') {
            Auth::logout();
            return back()->withErrors([
                'username' => 'Your account has been deactivated. Please contact the administrator.',
            ])->onlyInput('username');
        }

        $request->session()->regenerate();

        // Redirect based on user role
        switch ($user->role) {
            case 'chairman':
                return redirect()->route('dashboard.chairman');
            case 'secretary':
                return redirect()->route('dashboard.secretary');
            case 'staff':
                return redirect()->route('dashboard.staff');
            default:
                return redirect()->route('dashboard');
        }
    }

    return back()->withErrors([
        'username' => 'The provided credentials do not match our records.',
    ])->onlyInput('username');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
