<?php
namespace App\Http\Controllers;

use App\Models\MeetingRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        return MeetingRequest::where('status', 'approved')
            ->with('user')
            ->get()
            ->map(fn($e) => [
                'title' => "{$e->user->name} - {$e->title}",
                'start' => $e->start,   // ✔ FIXED
                'end'   => $e->end,     // ✔ FIXED
                'backgroundColor' => '#10b981',
                'borderColor' => '#10b981',
                'textColor' => '#ffffff',
            ]);
    }

    // ADMIN: Semua event approved milik user tertentu
    public function apiUserEvents($user_id)
    {
        return MeetingRequest::where('status', 'approved')
            ->where('user_id', $user_id)
            ->get()
            ->map(fn($e) => [
                'title' => $e->title,
                'start' => $e->start,   
                'end'   => $e->end,     
            ]);
    }

    // USER: Semua event user (approved/pending/rejected)
    public function apiEventsByUser($userId)
    {
        $meetings = MeetingRequest::where('user_id', $userId)->get();

        return $meetings->map(function ($m) {

            $color = match ($m->status) {
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
                'textColor' => '#ffffff',
            ];
        });
    }
}
