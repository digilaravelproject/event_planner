<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserEventPlan;
use App\Services\PlanPdfService;
use App\Services\PlanPresentationService;

class UserPlanController extends Controller
{
    public function index(User $user)
    {
        $plans = $user->eventPlans()->whereNull('parent_plan_id')->withCount('suggestions')->latest()->paginate(15);

        return view('admin.users.plans.index', compact('user', 'plans'));
    }

    public function show(UserEventPlan $plan, PlanPresentationService $presenter)
    {
        $plan->load(['user', 'suggestions', 'parent']);
        $presentation = $presenter->present($plan);

        return view('admin.users.plans.show', compact('plan', 'presentation'));
    }

    public function download(UserEventPlan $plan, PlanPresentationService $presenter, PlanPdfService $pdf)
    {
        $plan->load('user');

        return response($pdf->render($plan, $presenter->present($plan)))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="admin-wedding-plan-'.$plan->id.'.pdf"');
    }
}
