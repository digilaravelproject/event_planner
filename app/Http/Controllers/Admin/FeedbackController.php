<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateFeedbackRequest;
use App\Models\AdminModuleOption;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $validated=$request->validate(['search'=>['nullable','string','max:100'],'status'=>['nullable','string','max:30'],'sort'=>['nullable','in:subject,rating,status,created_at'],'direction'=>['nullable','in:asc,desc']]);$sort=$validated['sort']??'created_at';$direction=$validated['direction']??'desc';
        $feedback=Feedback::with('user')->when($validated['search']??null,fn($q,$s)=>$q->where(fn($q)=>$q->where('subject','like',"%$s%")->orWhere('message','like',"%$s%")->orWhereHas('user',fn($q)=>$q->where('name','like',"%$s%")->orWhere('email','like',"%$s%"))))->when($validated['status']??null,fn($q,$s)=>$q->where('status',$s))->orderBy($sort,$direction)->paginate(15)->withQueryString();
        $stats=['average'=>round((float)Feedback::avg('rating'),1),'total'=>Feedback::count(),'pending'=>Feedback::where('status','pending')->count(),'resolved'=>Feedback::where('status','resolved')->count()];
        return view('admin.feedback.index',['feedbackItems'=>$feedback,'stats'=>$stats,'statuses'=>AdminModuleOption::forGroup('feedback_status')->get()]);
    }
    public function show(Feedback $feedback){return view('admin.feedback.show',['feedback'=>$feedback->load('user'),'statuses'=>AdminModuleOption::forGroup('feedback_status')->get()]);}
    public function update(UpdateFeedbackRequest $request,Feedback $feedback){$feedback->update($request->validated());return back()->with('success','Feedback updated.');}
    public function destroy(Feedback $feedback){$feedback->delete();return to_route('admin.feedback.index')->with('success','Feedback deleted.');}
}
