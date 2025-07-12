<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventRequest;
use App\Models\SavedEvent;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    function home()
    {

        $requests = EventRequest::where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('home', [
            'events' => $requests,
        ]);
    }

    function savedEvents()
    {
        $userId = Auth::id();

        $savedEventIds = SavedEvent::where('user_id', $userId)
            ->pluck('event_id');

        $events = EventRequest::whereIn('id', $savedEventIds)
            ->where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('saved_event', [
            'events' => $events,
        ]);
    }
}
