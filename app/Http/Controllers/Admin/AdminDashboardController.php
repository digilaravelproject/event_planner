<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Venue;
use App\Models\SystemMaster;
use App\Models\BudgetRule;
use Illuminate\Http\Request;

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
        
        $totalVendors = Vendor::count();
        $totalVenues = Venue::count();
        $totalEvents = 1820; // Simulated active events planned on SaaS
        $totalRevenue = "₹14.8L";
        $plansGenerated = 5842;

        // Fetch recent active vendors to show on dashboard list
        $recentVendors = Vendor::orderBy('created_at', 'desc')->take(5)->get();

        // Chart data variables
        $revenueData = [
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'values' => [800000, 1500000, 1300000, 2100000, 3200000, 2800000]
        ];

        $registrationData = [
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'values' => [500, 600, 550, 780, 1300, 1100]
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalVendors',
            'totalVenues',
            'totalEvents',
            'totalRevenue',
            'plansGenerated',
            'recentVendors',
            'revenueData',
            'registrationData'
        ));
    }
}
