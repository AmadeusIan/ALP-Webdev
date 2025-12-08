<?php
namespace App\Http\Controllers;
use App\Models\MeetingRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    // ----- VIEW -----

    public function myCalendar()
    {
        return view('calendar.my');
    }

    public function adminAll()
    {
        return view('calendar.admin_all');
    }

    public function adminUser(User $user)
    {
        return view('calendar.admin_user', compact('user'));
    }

    // ----- API -----

    public function apiAllEvents()
    {
        $events = MeetingRequest::where('status', 'approved')
            ->get()
            ->map(fn($e) => [
                'title' => $e->title,
                'start' => $e->date,
            ]);

        return response()->json($events);
    }

    public function apiUserEvents($user_id)
    {
        $events = MeetingRequest::where('status', 'approved')
            ->where('user_id', $user_id)
            ->get()
            ->map(fn($e) => [
                'title' => $e->title,
                'start' => $e->date,
            ]);

        return response()->json($events);
    }

    public function apiEventsByUser($userId)
    {
        $meetings = MeetingRequest::where('user_id', $userId)->get();

        return $meetings->map(function($m){
            $color = match($m->status) {
                'approved' => '#10b981',
                'pending'  => '#f59e0b',
                'rejected' => '#ef4444',
                default    => '#6b7280',
            };

            return [
                'id' => $m->id,
                'title' => $m->title,
                'start' => $m->start,
                'end' => $m->end,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff'
            ];
        });
    }
}
