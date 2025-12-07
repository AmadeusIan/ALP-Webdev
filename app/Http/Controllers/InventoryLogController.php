<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use Illuminate\Http\Request;

class InventoryLogController extends Controller
{
    
    public function index()
    {
        $logs = InventoryLog::with(['fabric', 'user'])->latest()->paginate(20);

        return view('inventory.index', compact('logs'));
    }
}
