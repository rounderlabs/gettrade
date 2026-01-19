<?php

namespace App\Http\Controllers\Admin\Api;



use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{
    public function getRoles(Admin $admin)
    {
        return response()->json($admin->roles->pluck('name'));
    }

    public function assignRoles(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'roles' => 'required|array'
        ]);

        $admin->syncRoles($validated['roles']);
        return response()->json(['message' => 'Roles updated successfully']);
    }
}

