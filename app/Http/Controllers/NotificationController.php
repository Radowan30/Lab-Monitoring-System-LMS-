<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::query()->latest();
        $page = $request->get('page', 1);
        $offset = ($page - 1) * 20;

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $total = $query->count();
        $notifications = $query->skip($offset)->take(20)->get();

        return response()->json([
            'notifications' => $notifications,
            'hasMore' => $total > ($offset + 20)
        ]);
    }

    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        return response()->json([
            'message' => $notification->message,
            'timestamp' => $notification->created_at->format('d/m/Y h:i:s A')
        ]);
    }

    public function markAsSeen($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['seen' => true]);
        return response()->json(['success' => true]);
    }

    public function unseenCount()
    {
        $count = Notification::where('seen', 0)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();
        return response()->json(['count' => $count]);
    }
}
