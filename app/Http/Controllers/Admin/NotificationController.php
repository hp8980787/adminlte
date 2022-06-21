<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\User;
use App\Notifications\CreatePurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            $notifications = $user->unreadNotifications;
            return response()->json($notifications);
        } else {
//            $purchase = Purchase::query()->find(2);
//            $user->notify(new CreatePurchase($purchase));
//            $user2 = User::query()->find(2);
//            $user2->notify(new CreatePurchase($purchase));
//            dd(1);
                $categories = DB::table('notifications')
                    ->selectRaw('(select count(*)as nums from notifications as n where  read_at is null and n.type=notifications.type and n.notifiable_id=notifications.notifiable_id )as nums,
                    type,max(read_at)as read_at,notifiable_id')
                    ->groupBy('type','notifiable_id')->get();
                $notifications = $user->unReadNotifications;
            return view('admin.notifications.index', compact('notifications','categories'));
        }
    }
}
