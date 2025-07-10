<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CalendarController extends Controller
{
    /**
     * Constructor to apply authorization middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function index()
    {
        // Check permission before proceeding
        if (Gate::denies('view-calendar')) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('calendar.index');
    }
 
}
