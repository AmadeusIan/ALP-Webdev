<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MeetingRequest;
use Illuminate\Http\Request;

class CalendarApiController extends Controller
{
    public function all()
    {
        $events = MeetingRequest::where('status','approved')
            ->with('user:id,name')
            ->get()
            ->map(fn($r) => [
                'id' => $r->id,
                'title' => ($r->user->name ?? 'User') . ' — ' . $r->title,
                'start' => $r->start->toIsoString(),
                'end' => $r->end?->toIsoString(),
            ]);
        return response()->json($events);
    }

    public function byUser($id)
    {
        $events = MeetingRequest::where('user_id',$id)
            ->where('status','approved')
            ->get()
            ->map(fn($r) => [
                'id' => $r->id,
                'title' => $r->title,
                'start' => $r->start->toIsoString(),
                'end' => $r->end?->toIsoString(),
            ]);
        return response()->json($events);
    }
}
