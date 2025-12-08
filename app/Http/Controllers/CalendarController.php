<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    
    public function myCalendar()
    {
        return view('calendar.my'); 
    }

    public function allCalendar()
    {
        return view('calendar.all');
    }

    
}
