<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\ApiResponse;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return ApiResponse::success([
            'notifications' => $notifications,
            'unread_count' => $request->user()
                ->unreadNotifications()
                ->count(),
        ]);
    }
}
