<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    // -------------------- VIEW --------------------

    public function myCalendar()
    {
        return view('calendar.my', [
            'mode' => 'user',
            'userId' => Auth::id(),
        ]);
    }

    public function adminAll()
    {
        return view('calendar.my', [
            'mode' => 'admin'
        ]);
    }

    public function adminUser(User $user)
    {
        return view('calendar.admin_user', compact('user'));
    }

    // -------------------- API --------------------

    // ADMIN: Semua event approved
    public function apiAllEvents()
    {
        return Order::where('status', 'approved')
            ->with('user')
            ->get()
            ->map(function ($o) {
                $start = Carbon::parse($o->start_date)->toDateString();
                $end   = Carbon::parse($o->end_date)->toDateString();
                return [
                    'id' => $o->id,
                    'title' => ($o->user->name ?? 'User') . ' — #' . $o->order_number,
                    'start' => $start,
                    'end'   => $end,
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#10b981',
                    'textColor' => '#ffffff',
                ];
            });
    }

    // ADMIN: Semua event approved milik user tertentu
    public function apiUserEvents($user_id)
    {
        return Order::where('status', 'approved')
            ->where('user_id', $user_id)
            ->get()
            ->map(function ($o) {
                return [
                    'id' => $o->id,
                    'title' => '#' . $o->order_number,
                    'start' => Carbon::parse($o->start_date)->toDateString(),
                    'end'   => Carbon::parse($o->end_date)->toDateString(),
                    'backgroundColor' => '#0ea5e9',
                    'borderColor' => '#0ea5e9',
                    'textColor' => '#ffffff',
                ];
            });
    }

    // USER: Semua event user (approved/pending/rejected)
    public function apiEventsByUser($userId)
    {
        $orders = Order::where('user_id', $userId)->get();

        return $orders->map(function ($o) {
            $color = match ($o->status) {
                'approved' => '#10b981',
                'pending'  => '#f59e0b',
                'rejected' => '#ef4444',
                default    => '#6b7280',
            };

            return [
                'id' => $o->id,
                'title' => '#' . $o->order_number,
                'start' => Carbon::parse($o->start_date)->toDateString(),
                'end'   => Carbon::parse($o->end_date)->toDateString(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
            ];
        });
    }
}
