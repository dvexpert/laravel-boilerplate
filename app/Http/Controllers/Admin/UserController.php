<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Enums\{PermissionEnum, RoleEnum, UserStatusEnum};
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth('web')->user()->cannot(PermissionEnum::USER_READ)) {
            return redirect()->to(route('dashboard'))
                ->with([
                    'success' => false,
                    'message' => 'You do not have permission to access this page.',
                ]);
        }

        $search = $request->input('search');

        return Inertia::render('admin/User', [
            'users' => (fn () => User::query()
                ->when($search, function ($query) use ($search): void {
                    $query->where(function (EloquentBuilder $eloquentBuilder) use ($search): void {
                        $eloquentBuilder->whereLike('first_name', "%{$search}%")
                            ->orWhereLike('last_name', "%{$search}%")
                            ->orWhereLike('email', "%{$search}%");
                    });
                })
                ->with(['roles'])
                ->withTrashed()
                ->paginate($request->input('per_page', 5))
                ->withQueryString()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth('web')->user()->cannot(PermissionEnum::USER_CREATE)) {
            return redirect()->to(route('dashboard'))
                ->with([
                    'success' => false,
                    'message' => 'You do not have permission to access this page.',
                ]);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8|confirmed',
            'role'       => ['required', 'array'],
            'role.*'     => [Rule::enum(RoleEnum::class)],
            'status'     => ['required', Rule::enum(UserStatusEnum::class)],
        ]);

        $user = User::query()->create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'status'     => $validated['status'],
        ]);

        $user->syncRoles($validated['role']);

        return back()->with([
            'success' => true,
            'message' => 'User created successfully',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (auth('web')->user()->cannot(PermissionEnum::USER_UPDATE)) {
            return redirect()->to(route('dashboard'))
                ->with([
                    'success' => false,
                    'message' => 'You do not have permission to access this page.',
                ]);
        }

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
            'role'     => ['required', 'array'],
            'role.*'   => [Rule::enum(RoleEnum::class)],
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

        $user->syncRoles($validated['role']);

        return back()->with([
            'success' => true,
            'message' => 'User updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth('web')->user()->cannot(PermissionEnum::USER_DELETE)) {
            return redirect()->to(route('dashboard'))
                ->with([
                    'success' => false,
                    'message' => 'You do not have permission to access this page.',
                ]);
        }

        if ($user->trashed()) {
            // Restore
            $user->restore();
        } else {
            // Soft delete
            $user->delete();
        }

        return redirect()->to(route('admin.user.index'))->with([
            'success' => true,
            'message' => 'User ' . ($user->trashed() ? 'restored' : 'deleted') . ' successfully',
        ]);
    }

    public function audits(Request $request, User $user)
    {
        if (auth('web')->user()->cannot(PermissionEnum::USER_READ)) {
            return redirect()->to(route('dashboard'))
                ->with([
                    'success' => false,
                    'message' => 'You do not have permission to access this page.',
                ]);
        }

        $sortBy  = $request->input('sort', null);
        $sortDir = $request->input('order', 'asc');
        $search  = str($request->input('search', ''))->lower()->trim();

        return response()->json([
            'audits' => $user
                ->audits()
                ->with('user:id,first_name,last_name,email')
                ->when($sortBy, function ($query) use ($sortBy, $sortDir): void {
                    $query->orderBy($sortBy, $sortDir);
                })
                ->when($search->isNotEmpty(), function ($query) use ($search): void {
                    $search = $search->toString();
                    $query->where(function (EloquentBuilder $eloquentBuilder) use ($search): void {
                        if (str_starts_with($search, '#')) {
                            $id = (int) ltrim($search, '#');
                            $eloquentBuilder->where('id', $id);
                        } else {
                            $eloquentBuilder
                                ->where('event', 'like', "%{$search}%")
                                ->orWhere('auditable_type', 'like', "%{$search}%")
                                ->orWhereHas('user', function (EloquentBuilder $userQuery) use ($search): void {
                                    $userQuery->where('first_name', 'like', "%{$search}%")
                                        ->orWhere('last_name', 'like', "%{$search}%");
                                });
                        }
                    });
                })
                ->paginate($request->input('per_page', 5), pageName: 'user_audits'),
        ]);
    }
}
