<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = $this->userService->getAllPaginated(15, []);

        return view('dashboard.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $roles = Role::all(); // Get all available roles

        return view('dashboard.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        try {
            $userData = $request->validated();
            $roleId = $userData['role'] ?? null;

            // Remove role from user data as it's not a user field
            unset($userData['role']);

            // Create the user
            $user = $this->userService->create($userData);

            // Assign role to user if role is selected
            if ($roleId) {
                $role = Role::find($roleId);
                if ($role) {
                    $user->assignRole($role->name);
                }
            }

            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating User: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        return view('dashboard.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $roles = Role::all(); // Get all available roles

        return view('dashboard.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        try {
            $userData = $request->validated();
            $roleId = $userData['role'] ?? null;

            // Remove role from user data as it's not a user field
            unset($userData['role']);

            // Update the user
            $this->userService->update($user, $userData);

            // Assign role to user if role is selected
            if ($roleId) {
                $role = Role::find($roleId);
                if ($role) {
                    $user->syncRoles([$role->name]);
                }
            }

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating User: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            $this->userService->delete($user);

            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting User: '.$e->getMessage());
        }
    }
}
