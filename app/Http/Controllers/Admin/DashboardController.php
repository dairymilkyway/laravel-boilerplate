<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $totalUsers = User::withTrashed()->count();
        $totalRoles = Role::count();

        return view('admin.dashboard', compact('totalUsers', 'totalRoles'));
    }
}
