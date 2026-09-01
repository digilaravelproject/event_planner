<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StaffWelcomeMail;
use App\Models\Admin;
use App\Support\AdminMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Admin::where('role', 'staff')->latest()->paginate(15);

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff.form', ['staff' => null, 'menuItems' => AdminMenu::items()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $plainPassword = $this->generatedPassword($validated['first_name'], $validated['last_name']);
        $staff = Admin::create([
            ...$validated,
            'name' => trim($validated['first_name'].' '.$validated['last_name']),
            'password' => $plainPassword,
            'role' => 'staff',
            'is_active' => true,
        ]);

        try {
            Mail::to($staff->email)->send(new StaffWelcomeMail($staff, $plainPassword));
        } catch (\Throwable $exception) {
            report($exception);
            $staff->delete();

            return back()->withInput()->withErrors(['email' => 'The credentials email could not be sent, so the staff account was not created. Please check the mail configuration and try again.']);
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff created and login credentials emailed successfully.');
    }

    public function edit(Admin $staff)
    {
        $this->ensureStaff($staff);

        return view('admin.staff.form', ['staff' => $staff, 'menuItems' => AdminMenu::items()]);
    }

    public function update(Request $request, Admin $staff)
    {
        $this->ensureStaff($staff);
        $validated = $this->validated($request, $staff);
        $staff->update([...$validated, 'name' => trim($validated['first_name'].' '.$validated['last_name'])]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff details and permissions updated successfully.');
    }

    public function destroy(Admin $staff)
    {
        $this->ensureStaff($staff);
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff account deleted successfully.');
    }

    public function toggle(Admin $staff)
    {
        $this->ensureStaff($staff);
        $staff->update(['is_active' => ! $staff->is_active]);

        return back()->with('success', 'Staff account '.($staff->is_active ? 'activated' : 'deactivated').' successfully.');
    }

    private function validated(Request $request, ?Admin $staff = null): array
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins')->ignore($staff)],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+().\-\s]{6,30}$/'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(AdminMenu::keys())],
        ]);
        $validated['permissions'] = array_values(array_unique($validated['permissions'] ?? []));

        return $validated;
    }

    private function generatedPassword(string $firstName, string $lastName): string
    {
        $base = Str::lower(preg_replace('/[^a-zA-Z0-9]/', '', $firstName.$lastName) ?: 'staff');

        return $base.'@123';
    }

    private function ensureStaff(Admin $staff): void
    {
        abort_unless($staff->role === 'staff', 404);
    }
}
