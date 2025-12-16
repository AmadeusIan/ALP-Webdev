<?php

namespace App\Http\Controllers;

use App\Models\MeetingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    // USER PAGE
    public function create()
    {
        return view('meeting.meetRequest');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        // Simpan ke database
        MeetingRequest::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'status' => 'pending', // default status
        ]);

        return redirect()->route('meeting-request.create')
            ->with('success', 'Meeting request submitted successfully!');
    }

    // ADMIN PAGE
    public function adminIndex()
    {
        $requests = MeetingRequest::with('user')->latest()->get();
        return view('meeting.adminMeet', compact('requests'));
    }

    // ADMIN PAGE
    public function accept(MeetingRequest $meeting)
    {
        $meeting->status = 'approved';
        $meeting->save();

        return back();
    }

    public function reject(MeetingRequest $meeting)
    {
        $meeting->status = 'rejected';
        $meeting->save();

        return back();
    }
}
