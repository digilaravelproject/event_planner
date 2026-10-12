<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use App\Services\TransactionExportService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = $this->query($request)->paginate(20)->withQueryString();

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(UserSubscription $transaction)
    {
        $transaction->load(['user', 'plan']);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function exportPdf(Request $request, TransactionExportService $export)
    {
        return response($export->pdf($this->query($request)->get()))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="transactions-'.now()->format('Y-m-d').'.pdf"');
    }

    public function exportExcel(Request $request, TransactionExportService $export)
    {
        return response($export->excel($this->query($request)->get()))
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="transactions-'.now()->format('Y-m-d').'.xlsx"');
    }

    private function query(Request $request): Builder
    {
        return UserSubscription::query()
            ->with(['user', 'plan'])
            ->when($request->filled('search'), function (Builder $query) use ($request): void {
                $search = trim((string) $request->input('search'));
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('razorpay_order_id', 'like', "%{$search}%")
                        ->orWhere('razorpay_payment_id', 'like', "%{$search}%")
                        ->orWhereHas('user', fn (Builder $user) => $user
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('mobile_number', 'like', "%{$search}%"))
                        ->orWhereHas('plan', fn (Builder $plan) => $plan->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('status'), fn (Builder $query) => $query->where('status', $request->input('status')))
            ->latest();
    }
}
