<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Enums\{RoleEnum, UserStatusEnum};

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        return Inertia::render('admin/User', [
            'users' => (fn () => User::query()
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->with(['roles'])->paginate(5)->withQueryString()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8|confirmed',
            'role'       => ['required', Rule::enum(RoleEnum::class)],
            'status'     => ['required', Rule::enum(UserStatusEnum::class)],
        ]);

        $user = User::query()->create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'status'     => $validated['status'],
        ]);

        $user->assignRole(RoleEnum::tryFrom($validated['role']));

        return redirect()->back()->with([
            'message' => 'User created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => ['required', Rule::enum(RoleEnum::class)],
            'status'   => ['required', Rule::enum(UserStatusEnum::class)],
        ]);

        $user->update([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'status'     => $validated['status'],
        ]);
        if ($request->has('password') && $validated['password']) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }
        $user->syncRoles(RoleEnum::tryFrom($validated['role']));

        return redirect()->back()->with([
            'message' => 'User updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): void
    {
        //
    }
}
