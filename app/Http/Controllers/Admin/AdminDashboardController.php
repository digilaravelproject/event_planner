<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminDashboardController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index()
    {
        // Dynamic counts with realistic base offsets for SaaS appearance
        $realUsersCount = User::count();
        $totalUsers = $realUsersCount > 0 ? 2450 + $realUsersCount : 2450;
        
        $totalRevenue = "₹14.8L";

        // Chart data variables
        $revenueData = [
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'values' => [800000, 1500000, 1300000, 2100000, 3200000, 2800000]
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRevenue',
            'revenueData'
        ));
    }
}
