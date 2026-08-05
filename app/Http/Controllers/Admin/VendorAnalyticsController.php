<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VendorAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $validated=$request->validate(['period'=>['nullable','in:today,week,month,year,custom'],'from'=>['nullable','date'],'to'=>['nullable','date','after_or_equal:from']]);
        [$from,$to]=$this->dates($validated);
        $within=fn(Builder $query)=>$query->whereBetween('created_at',[$from,$to]);

        $dynamicVendors=DynamicVendor::query()->tap($within)->get();
        $users=User::query()->tap($within)->get();
        $feedback=Feedback::query()->with('user')->tap($within)->get();
        $categories=$dynamicVendors->pluck('category')->filter();

        $cards=[
            'active_vendors'=>$dynamicVendors->where('status','active')->count(),
            'inactive_vendors'=>$dynamicVendors->where('status','inactive')->count(),
            'total_categories'=>$categories->unique()->count(),
            'total_feedback'=>$feedback->count(),
            'total_users'=>$users->count(),
        ];
        $byCategory=$categories->countBy()->sortDesc();
        $monthlyLabels=collect(range(5,0))->map(fn($i)=>now()->subMonths($i)->format('M Y'));
        $monthCounts=fn($items)=>$monthlyLabels->map(fn($label)=>$items->filter(fn($item)=>$item->created_at->format('M Y')===$label)->count());
        $feedbackStats=$feedback->countBy('status');

        return view('admin.vendor-analytics.index',[
            'cards'=>$cards,'from'=>$from,'to'=>$to,'period'=>$validated['period']??'month',
            'charts'=>[
                'categories'=>['labels'=>$byCategory->keys(),'values'=>$byCategory->values()],
                'registrations'=>['labels'=>$monthlyLabels,'values'=>$monthCounts($dynamicVendors)],
                'selected_categories'=>['labels'=>$byCategory->keys()->take(6),'values'=>$byCategory->values()->take(6)],
                'feedback'=>['labels'=>$feedbackStats->keys(),'values'=>$feedbackStats->values()],
            ],
            'recentVendors'=>$dynamicVendors->sortByDesc('created_at')->take(5),'recentUsers'=>$users->sortByDesc('created_at')->take(5),'latestFeedback'=>$feedback->sortByDesc('created_at')->take(5),
        ]);
    }

    private function dates(array $filters): array
    {
        $now=now();$period=$filters['period']??'month';
        return match($period){
            'today'=>[$now->copy()->startOfDay(),$now->copy()->endOfDay()],
            'week'=>[$now->copy()->startOfWeek(),$now->copy()->endOfWeek()],
            'year'=>[$now->copy()->startOfYear(),$now->copy()->endOfYear()],
            'custom'=>[Carbon::parse($filters['from']??$now)->startOfDay(),Carbon::parse($filters['to']??$now)->endOfDay()],
            default=>[$now->copy()->startOfMonth(),$now->copy()->endOfMonth()],
        };
    }
}
