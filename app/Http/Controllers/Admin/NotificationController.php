<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNotificationRequest;
use App\Models\AdminModuleOption;
use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $validated=$request->validate(['search'=>['nullable','string','max:100'],'status'=>['nullable','in:draft,scheduled,sent'],'sort'=>['nullable','in:title,notification_type,status,schedule_at,created_at'],'direction'=>['nullable','in:asc,desc']]);
        $sort=$validated['sort']??'created_at';$direction=$validated['direction']??'desc';
        $notifications=AdminNotification::withCount('users')->when($validated['search']??null,fn($q,$s)=>$q->where(fn($q)=>$q->where('title','like',"%$s%")->orWhere('message','like',"%$s%")))->when($validated['status']??null,fn($q,$s)=>$q->where('status',$s))->orderBy($sort,$direction)->paginate(15)->withQueryString();
        return view('admin.notifications.index',compact('notifications'));
    }
    public function create(){return $this->form(new AdminNotification);}
    public function store(StoreNotificationRequest $request){$notification=$this->persist($request,new AdminNotification);return to_route('admin.notifications.show',$notification)->with('success','Notification created.');}
    public function show(AdminNotification $notification){$notification->load(['creator','users'])->loadCount('users');return view('admin.notifications.show',compact('notification'));}
    public function edit(AdminNotification $notification){return $this->form($notification);}
    public function update(StoreNotificationRequest $request,AdminNotification $notification){$this->persist($request,$notification);return to_route('admin.notifications.show',$notification)->with('success','Notification updated.');}
    public function destroy(AdminNotification $notification){$notification->delete();return to_route('admin.notifications.index')->with('success','Notification deleted.');}
    public function send(AdminNotification $notification){$notification->update(['status'=>'sent','sent_at'=>now()]);return back()->with('success','Notification marked as sent to '.$notification->users()->count().' recipients.');}
    private function form(AdminNotification $notification){return view('admin.notifications.form',['notification'=>$notification,'users'=>User::orderBy('name')->get(['id','name','email']),'types'=>AdminModuleOption::forGroup('notification_type')->get()]);}
    private function persist(StoreNotificationRequest $request,AdminNotification $notification):AdminNotification
    {
        return DB::transaction(function()use($request,$notification){$data=$request->validated();$scope=$data['recipient_scope'];$userIds=$scope==='all'?User::pluck('id')->all():($data['users']??[]);unset($data['recipient_scope'],$data['users']);$data['created_by']=$notification->exists?$notification->created_by:auth('admin')->id();if($data['status']==='sent')$data['sent_at']=now();$notification->fill($data)->save();$notification->users()->sync($userIds);return $notification;});
    }
}
