<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\UserVerification;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_properties' => Property::count(),
            'pending_verifications' => User::where('is_verified', false)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
