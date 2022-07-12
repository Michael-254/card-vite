<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->with('roles')
            ->when(request()->input('search'), function ($query, $search) {
                $query->Where('name', 'like', "%{$search}%");
                $query->orWhere('site', 'like', "%{$search}%");
                $query->orWhere('department', 'like', "%{$search}%");
                $query->orWhereHas('roles', function ($query) use ($search) {
                    $query->where('role', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'department' => $user->department,
                'site' => $user->site,
                'roles' => $user->roles,
            ]);

        $AllRoles = Role::select('id', 'role')->get();

        return Inertia::render('Roles/Table', [
            'Users' => $users,
            'CreatedRoles' => $AllRoles,
            'filters' => request()->all('search'),
        ]);
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $currentRoles = DB::table('role_user')
            ->where(['user_id' => $id])
            ->pluck('role_id')
            ->toArray();
        $roles = Role::all();

        return Inertia::render('Roles/Edit', [
            'User' => $user,
            'CurrentRoles' => $currentRoles,
            'Roles' => $roles
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->roles()->detach();
        $user->roles()->attach($request->checkedRoles);
        return redirect()->route('Manage roles')->with('success', 'Roles Updated Successfully');
    }

    public function createRole(Request $request)
    {
        $request->validate([
            'role' => 'required|unique:roles',
        ]);

        Role::create(['role' => strtoupper($request->role)]);
        return redirect()->route('Manage roles')->with('success', 'Role created Successfully');
    }

    public function destroyRole($id)
    {
        Role::findOrFail($id)->delete();
        return redirect()->route('Manage roles')->with('success', 'Role destroyed Successfully');
    }
}
