<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\EventRequest;
use App\Models\SavedEvent;
use App\Models\Event;

class EventRequestController extends Controller
{
    function proposeEvent()
    {
        return view('propose_event');
    }

    function storeProposedEvent(Request $request)
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
            'image'           => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $image = $request->file('image');
            $imagePath = $image->store('event_images', 'public');
            $validatedData['image'] = $imagePath;

            $dataToSave = $validatedData;

            $dataToSave['approval_status'] = EventRequest::APPROVAL_STATUS_PENDING;

            if (Auth::guard('web')->check()) {
                $dataToSave['requested_by'] = Auth::id();
            }

            if ($dataToSave['is_free']) {
                $dataToSave['price'] = 0;
            }

            EventRequest::create($dataToSave);

            return redirect()->back()->with('success', 'Your event proposal has been submitted successfully! We will review it shortly.');
        } catch (\Exception $e) {
            Log::error('Failed to save event proposal: ' . $e->getMessage());

            // return redirect()->back()
            //     ->withInput()
            //     ->with('error', 'Sorry, there was an issue submitting your proposal. Please try again later.');

            // redirect with the error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Sorry, there was an issue submitting your proposal. Please try again later. Error: ' . $e->getMessage());
        }
    }

    public function approve(Request $request)
    {
        try {
            $eventRequestId = $request->route('id');

            $eventRequest = EventRequest::where('id', $eventRequestId)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_PENDING)
                ->firstOrFail();

            $eventRequest->approval_status = EventRequest::APPROVAL_STATUS_APPROVED;
            $eventRequest->approved_by = Auth::id();
            $eventRequest->save();

            return redirect()->back()->with('success', 'Event request has been approved.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to approve event request: ' . $e->getMessage());
        }
    }

    public function reject(Request $request)
    {
        try {
            $eventRequestId = $request->route('id');

            $eventRequest = EventRequest::where('id', $eventRequestId)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_PENDING)
                ->firstOrFail();

            if ($eventRequest->image) {
                $imagePath = public_path('event_images/' . $eventRequest->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $eventRequest->delete();

            return redirect()->back()->with('success', 'Event request has been rejected.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reject event request: ' . $e->getMessage());
        }
    }

    public function saveEvent(Request $request)
    {
        try {
            $eventRequestId = $request->route('id');

            $eventRequest = EventRequest::where('id', $eventRequestId)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED)
                ->firstOrFail();

            if (is_null($eventRequest->id)) {
                return redirect()->back()->with('error', 'Failed to save event: Associated event not found or not created yet.');
            }

            $savedEvent = SavedEvent::where('user_id', Auth::id())
                ->where('event_id', $eventRequest->id)
                ->first();

            if ($savedEvent) {
                SavedEvent::where('user_id', Auth::id())
                    ->where('event_id', $eventRequest->id)
                    ->delete();
                return redirect()->back()->with('success', 'Event has been unsaved successfully.');
            } else {
                SavedEvent::create([
                    "user_id" => Auth::id(),
                    "event_id" => $eventRequest->id,
                ]);
            }

            return redirect()->back()->with('success', 'Event has been saved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to save event: ' . $e->getMessage());
        }
    }

    public function updateProposedEvent(Request $request)
    {
        $eventRequestId = $request->route('id');

        $eventRequest = EventRequest::where('id', $eventRequestId)
            ->where('approval_status', EventRequest::APPROVAL_STATUS_PENDING)
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
                if ($eventRequest->image) {
                    $imagePath = public_path('event_images/' . $eventRequest->image);
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

            $eventRequest->update($validatedData);

            return redirect()->back()->with('success', 'Event proposal updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update event proposal: ' . $e->getMessage());
        }
    }
}
