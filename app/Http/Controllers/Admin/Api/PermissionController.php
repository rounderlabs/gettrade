<?php
namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return response()->json(Permission::where('guard_name', 'admin')->get());
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name']);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => 'admin'
        ]);

        return response()->json($permission, 201);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json(['message' => 'Permission deleted']);
    }
}

