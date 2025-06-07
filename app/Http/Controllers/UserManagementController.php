<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Check if the requested role is available
     *
     * @param string $role The role to check
     * @param int|null $exceptUserId User ID to exclude from the check (for updates)
     * @return bool True if the role is available, false otherwise
     */
    private function isRoleAvailable($role, $exceptUserId = null)
    {
        // Only apply restrictions to chairman and secretary roles
        if (!in_array($role, ['chairman', 'secretary'])) {
            return true;
        }

        // Query to check if the role already exists
        $query = User::where('role', $role);

        // Exclude the current user when updating
        if ($exceptUserId) {
            $query->where('id', '!=', $exceptUserId);
        }

        // Return true if no users have this role yet
        return $query->count() === 0;
    }

    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:chairman,secretary,staff',
            'phone' => 'nullable|string|max:11',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        // Check if the requested role is available
        if (!$this->isRoleAvailable($request->role)) {
            // Role is already taken, return with error
            $roleTitle = ucfirst($request->role);
            return back()->withInput()
                ->withErrors(['role' => "A $roleTitle already exists. Only one $roleTitle is allowed in the system."]);
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:chairman,secretary,staff',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        // Prevent secretary from deactivating their own account
    if (Auth::user()->role === 'secretary' && Auth::user()->id === $user->id && $request->status === 'inactive') {
        return back()->withErrors(['status' => 'You cannot deactivate your own account.']);
    }

        // Check if the role is being changed
        if ($user->role !== $request->role) {
            // Check if the requested role is available
            if (!$this->isRoleAvailable($request->role, $user->id)) {
                // Role is already taken, return with error
                $roleTitle = ucfirst($request->role);
                return back()->withInput()
                    ->withErrors(['role' => "A $roleTitle already exists. Only one $roleTitle is allowed in the system."]);
            }
        }

        // Only update password if it's provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent self-deletion
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
