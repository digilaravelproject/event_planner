<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Services\AdminUserExportService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::with(['subscription', 'subscriptionHistory' => fn ($query) => $query->with('plan')->latest()])->withCount(['eventPlans as plans_count' => fn ($query) => $query->whereNull('parent_plan_id')])->orderBy('created_at', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    public function exportPdf(AdminUserExportService $export)
    {
        $users = $this->exportUsers();

        return response($export->pdf($users))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="users-'.now()->format('Y-m-d').'.pdf"');
    }

    public function exportExcel(AdminUserExportService $export)
    {
        $users = $this->exportUsers();

        return response($export->excel($users))
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="users-'.now()->format('Y-m-d').'.xlsx"');
    }

    /**
     * Show the form for editing the user's details.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $subscriptions = Subscription::all();

        return view('admin.users.edit', compact('user', 'subscriptions'));
    }

    /**
     * Update the user details.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'mobile_number' => ['required', 'string', 'max:20'],
            'subscription_id' => ['nullable', 'exists:subscriptions,id'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Toggle the user active/deactive status.
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = ! $user->status;
        $user->save();

        $statusText = $user->status ? 'activated' : 'deactivated';

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' {$statusText} successfully!");
    }

    /**
     * Remove the user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    private function exportUsers()
    {
        return User::with('subscription')
            ->withCount(['eventPlans as plans_count' => fn ($query) => $query->whereNull('parent_plan_id')])
            ->withSum(['eventPlans as plans_total' => fn ($query) => $query->whereNull('parent_plan_id')], 'total_cost')
            ->orderBy('created_at')
            ->get();
    }
}
