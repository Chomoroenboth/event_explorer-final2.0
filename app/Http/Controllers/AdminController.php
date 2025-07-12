<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $requests = EventRequest::where('approval_status', EventRequest::APPROVAL_STATUS_PENDING)
            ->orderBy('created_at', 'asc')
            ->get();
        return view('admin.dashboard', [
            'eventRequests' => $requests,
        ]);
    }

    // Display all approved/published events
    public function events()
    {
        $approvedEvents = EventRequest::where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.events', [
            'events' => $approvedEvents,
        ]);
    }

    public function editProposeEvent(Request $request)
    {
        try {
            $eventRequestId = $request->route('id');
            $eventRequest = EventRequest::where('id', $eventRequestId)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_PENDING)->first();

            return view('admin.edit_propose_event', [
                'eventRequest' => $eventRequest,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Event request not found or an error occurred.']);
        }
    }

    // NEW METHODS FOR POSTING EVENTS

    /**
     * Show the form for creating a new event
     */
    public function createEvent()
    {
        return view('admin.create_event');
    }

    /**
     * Store a new event directly created by admin
     */
    public function storeEvent(Request $request)
    {
        $validatedData = $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'required|string',
            'start_datetime'    => 'required|date|after_or_equal:now',
            'end_datetime'      => 'required|date|after:start_datetime',
            'location'          => 'required|string|max:255',
            'area'              => ['required', 'string', Rule::in(EventRequest::getAreas())],
            'category'          => ['required', 'string', Rule::in(EventRequest::getCategories())],
            'event_type'        => ['required', 'string', Rule::in(EventRequest::getEventTypes())],
            'format'            => ['required', 'string', Rule::in(EventRequest::getFormats())],
            'is_free'           => 'required|boolean',
            'price'             => 'nullable|required_if:is_free,0|numeric|min:0',
            'requester_email'   => 'required|email|max:255',
            'requester_phone'   => 'nullable|string|max:25',
            'reference_link'    => 'nullable|url|max:255',
            'image'             => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Handle image upload
            $image = $request->file('image');
            $imagePath = $image->store('event_images', 'public');
            $validatedData['image'] = $imagePath;

            // Set admin-specific fields
            $validatedData['approval_status'] = EventRequest::APPROVAL_STATUS_APPROVED;
            $validatedData['approved_by'] = Auth::id();
            $validatedData['requested_by'] = null; // Admin posted, not user requested

            if ($validatedData['is_free']) {
                $validatedData['price'] = 0;
            }

            EventRequest::create($validatedData);

            return redirect()->route('admin.events')
                ->with('success', 'Event has been created and published successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to create admin event: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Sorry, there was an issue creating the event. Please try again later. Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing an existing event
     */
    public function editEvent($id)
    {
        try {
            $event = EventRequest::where('id', $id)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED)
                ->firstOrFail();

            return view('admin.edit_event', [
                'event' => $event,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Event not found or an error occurred.']);
        }
    }

    /**
     * Update an existing event
     */
    public function updateEvent(Request $request, $id)
    {
        $event = EventRequest::where('id', $id)
            ->where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED)
            ->firstOrFail();

        $validatedData = $request->validate([
            'title'             => 'sometimes|required|string|max:255',
            'description'       => 'sometimes|required|string',
            'start_datetime'    => 'sometimes|required|date|after_or_equal:now',
            'end_datetime'      => 'sometimes|required|date|after:start_datetime',
            'location'          => 'sometimes|required|string|max:255',
            'area'              => ['sometimes', 'required', 'string', Rule::in(EventRequest::getAreas())],
            'category'          => ['sometimes', 'required', 'string', Rule::in(EventRequest::getCategories())],
            'event_type'        => ['sometimes', 'required', 'string', Rule::in(EventRequest::getEventTypes())],
            'format'            => ['sometimes', 'required', 'string', Rule::in(EventRequest::getFormats())],
            'is_free'           => 'sometimes|required|boolean',
            'price'             => 'nullable|required_if:is_free,0|numeric|min:0',
            'requester_email'   => 'sometimes|required|email|max:255',
            'requester_phone'   => 'sometimes|nullable|string|max:25',
            'reference_link'    => 'sometimes|nullable|url|max:255',
            'image'             => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($event->image) {
                    $imagePath = storage_path('app/public/' . $event->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
                $image = $request->file('image');
                $imagePath = $image->store('event_images', 'public');
                $validatedData['image'] = $imagePath;
            }

            // If is_free is set and true, set price to 0
            if (array_key_exists('is_free', $validatedData) && $validatedData['is_free']) {
                $validatedData['price'] = 0;
            }

            $event->update($validatedData);

            return redirect()->route('admin.events')
                ->with('success', 'Event updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update event: ' . $e->getMessage());
        }
    }

    /**
     * Delete an event
     */
    public function deleteEvent($id)
    {
        try {
            $event = EventRequest::where('id', $id)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED)
                ->firstOrFail();

            // Delete image file if exists
            if ($event->image) {
                $imagePath = storage_path('app/public/' . $event->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $event->delete();

            return redirect()->route('admin.events')
                ->with('success', 'Event deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete event: ' . $e->getMessage());
        }
    }
}