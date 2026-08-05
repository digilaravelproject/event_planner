<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventQuestionRequest;
use App\Models\AdminModuleOption;
use App\Models\EventRequirementQuestion;
use Illuminate\Http\Request;

class EventRequirementQuestionController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate(['search'=>['nullable','string','max:100'],'status'=>['nullable','in:0,1'],'sort'=>['nullable','in:question,question_code,question_type,display_order,status,created_at'],'direction'=>['nullable','in:asc,desc']]);
        $sort=$validated['sort']??'display_order'; $direction=$validated['direction']??'asc';
        $questions=EventRequirementQuestion::query()->when($validated['search']??null,fn($q,$s)=>$q->where(fn($q)=>$q->where('question','like',"%$s%")->orWhere('question_code','like',"%$s%")))->when(isset($validated['status']),fn($q)=>$q->where('status',$validated['status']))->orderBy($sort,$direction)->paginate(15)->withQueryString();
        return view('admin.event-questions.index',compact('questions'));
    }
    public function create(){return view('admin.event-questions.form',['question'=>new EventRequirementQuestion,'types'=>AdminModuleOption::forGroup('question_type')->get()]);}
    public function store(StoreEventQuestionRequest $request){EventRequirementQuestion::create($request->validated());return to_route('admin.event-questions.index')->with('success','Question created.');}
    public function show(EventRequirementQuestion $eventQuestion){return redirect()->route('admin.event-questions.edit',$eventQuestion);}
    public function edit(EventRequirementQuestion $eventQuestion){return view('admin.event-questions.form',['question'=>$eventQuestion,'types'=>AdminModuleOption::forGroup('question_type')->get()]);}
    public function update(StoreEventQuestionRequest $request,EventRequirementQuestion $eventQuestion){$eventQuestion->update($request->validated());return to_route('admin.event-questions.index')->with('success','Question updated.');}
    public function destroy(EventRequirementQuestion $eventQuestion){$eventQuestion->delete();return to_route('admin.event-questions.index')->with('success','Question deleted.');}
}
