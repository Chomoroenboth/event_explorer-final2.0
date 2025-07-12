<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EventRequest;
use App\Models\User;
use App\Models\SavedEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function getStats()
    {
        try {
            $stats = [
                'pending_requests' => EventRequest::where('approval_status', EventRequest::APPROVAL_STATUS_PENDING)->count(),
                'approved_events' => EventRequest::where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED)->count(),
                'total_users' => User::count(),
                'total_requests' => EventRequest::count(),
                'free_events' => EventRequest::where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED)
                    ->where('is_free', true)->count(),
                'paid_events' => EventRequest::where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED)
                    ->where('is_free', false)->count(),
            ];

            // Calculate approval rate
            $stats['approval_rate'] = $stats['total_requests'] > 0 
                ? round(($stats['approved_events'] / $stats['total_requests']) * 100) 
                : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending event requests
     */
    public function getPendingRequests(Request $request)
    {
        try {
            $query = EventRequest::where('approval_status', EventRequest::APPROVAL_STATUS_PENDING);

            // Pagination
            $perPage = min($request->get('per_page', 15), 50);
            $requests = $query->orderBy('created_at', 'asc')->paginate($perPage);

            // Transform requests data
            $requestsData = $requests->getCollection()->map(function ($request) {
                return [
                    'id' => $request->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'start_datetime' => $request->start_datetime,
                    'end_datetime' => $request->end_datetime,
                    'location' => $request->location,
                    'area' => $request->area,
                    'category' => $request->category,
                    'event_type' => $request->event_type,
                    'format' => $request->format,
                    'is_free' => (bool) $request->is_free,
                    'price' => $request->price ? (float) $request->price : null,
                    'image_url' => $request->image ? asset('storage/' . $request->image) : null,
                    'reference_link' => $request->reference_link,
                    'requester_email' => $request->requester_email,
                    'requester_phone' => $request->requester_phone,
                    'requested_by' => $request->requested_by,
                    'approval_status' => $request->approval_status,
                    'created_at' => $request->created_at,
                    'updated_at' => $request->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'event_requests' => $requestsData,
                    'pagination' => [
                        'current_page' => $requests->currentPage(),
                        'total_pages' => $requests->lastPage(),
                        'per_page' => $requests->perPage(),
                        'total' => $requests->total(),
                        'from' => $requests->firstItem(),
                        'to' => $requests->lastItem(),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pending requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific event request
     */
    public function getEventRequest($id)
    {
        try {
            $eventRequest = EventRequest::where('id', $id)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_PENDING)
                ->first();

            if (!$eventRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event request not found'
                ], 404);
            }

            $requestData = [
                'id' => $eventRequest->id,
                'title' => $eventRequest->title,
                'description' => $eventRequest->description,
                'start_datetime' => $eventRequest->start_datetime,
                'end_datetime' => $eventRequest->end_datetime,
                'location' => $eventRequest->location,
                'area' => $eventRequest->area,
                'category' => $eventRequest->category,
                'event_type' => $eventRequest->event_type,
                'format' => $eventRequest->format,
                'is_free' => (bool) $eventRequest->is_free,
                'price' => $eventRequest->price ? (float) $eventRequest->price : null,
                'image_url' => $eventRequest->image ? asset('storage/' . $eventRequest->image) : null,
                'reference_link' => $eventRequest->reference_link,
                'requester_email' => $eventRequest->requester_email,
                'requester_phone' => $eventRequest->requester_phone,
                'requested_by' => $eventRequest->requested_by,
                'approval_status' => $eventRequest->approval_status,
                'created_at' => $eventRequest->created_at,
                'updated_at' => $eventRequest->updated_at,
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'event_request' => $requestData
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch event request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update event request
     */
    public function updateEventRequest(Request $request, $id)
    {
        try {
            $eventRequest = EventRequest::where('id', $id)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_PENDING)
                ->first();

            if (!$eventRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event request not found'
                ], 404);
            }

            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'start_datetime' => 'sometimes|required|date|after_or_equal:now',
                'end_datetime' => 'sometimes|required|date|after:start_datetime',
                'location' => 'sometimes|required|string|max:255',
                'area' => ['sometimes', 'required', 'string', Rule::in(EventRequest::getAreas())],
                'category' => ['sometimes', 'required', 'string', Rule::in(EventRequest::getCategories())],
                'event_type' => ['sometimes', 'required', 'string', Rule::in(EventRequest::getEventTypes())],
                'format' => ['sometimes', 'required', 'string', Rule::in(EventRequest::getFormats())],
                'is_free' => 'sometimes|required|boolean',
                'price' => 'nullable|required_if:is_free,false|numeric|min:0',
                'requester_email' => 'sometimes|required|email|max:255',
                'requester_phone' => 'sometimes|nullable|string|max:25',
                'reference_link' => 'sometimes|nullable|url|max:255',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($eventRequest->image) {
                    Storage::disk('public')->delete($eventRequest->image);
                }
                
                $image = $request->file('image');
                $imagePath = $image->store('event_images', 'public');
                $validatedData['image'] = $imagePath;
            }

            // Handle free pricing
            if (array_key_exists('is_free', $validatedData) && $validatedData['is_free']) {
                $validatedData['price'] = 0;
            }

            $eventRequest->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Event request updated successfully',
                'data' => [
                    'event_request' => [
                        'id' => $eventRequest->id,
                        'title' => $eventRequest->title,
                        'approval_status' => $eventRequest->approval_status,
                        'updated_at' => $eventRequest->updated_at,
                    ]
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve event request
     */
    public function approveEventRequest($id)
    {
        try {
            $eventRequest = EventRequest::where('id', $id)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_PENDING)
                ->first();

            if (!$eventRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event request not found'
                ], 404);
            }

            $eventRequest->update([
                'approval_status' => EventRequest::APPROVAL_STATUS_APPROVED,
                'approved_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Event request approved successfully',
                'data' => [
                    'event_request' => [
                        'id' => $eventRequest->id,
                        'title' => $eventRequest->title,
                        'approval_status' => $eventRequest->approval_status,
                        'approved_by' => $eventRequest->approved_by,
                        'updated_at' => $eventRequest->updated_at,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve event request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject event request
     */
    public function rejectEventRequest($id)
    {
        try {
            $eventRequest = EventRequest::where('id', $id)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_PENDING)
                ->first();

            if (!$eventRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event request not found'
                ], 404);
            }

            // Delete image if exists
            if ($eventRequest->image) {
                Storage::disk('public')->delete($eventRequest->image);
            }

            $eventRequest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event request rejected and deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject event request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all published events (admin view)
     */
    public function getEvents(Request $request)
    {
        try {
            $query = EventRequest::where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED);

            // Apply filters
            if ($request->has('category') && $request->category) {
                $query->where('category', $request->category);
            }

            if ($request->has('area') && $request->area) {
                $query->where('area', $request->area);
            }

            if ($request->has('is_free') && $request->is_free !== '') {
                $query->where('is_free', (bool) $request->is_free);
            }

            // Pagination
            $perPage = min($request->get('per_page', 15), 50);
            $events = $query->orderBy('created_at', 'desc')->paginate($perPage);

            // Transform events data
            $eventsData = $events->getCollection()->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'start_datetime' => $event->start_datetime,
                    'end_datetime' => $event->end_datetime,
                    'location' => $event->location,
                    'area' => $event->area,
                    'category' => $event->category,
                    'event_type' => $event->event_type,
                    'format' => $event->format,
                    'is_free' => (bool) $event->is_free,
                    'price' => $event->price ? (float) $event->price : null,
                    'image_url' => $event->image ? asset('storage/' . $event->image) : null,
                    'reference_link' => $event->reference_link,
                    'requester_email' => $event->requester_email,
                    'requester_phone' => $event->requester_phone,
                    'requested_by' => $event->requested_by,
                    'approved_by' => $event->approved_by,
                    'approval_status' => $event->approval_status,
                    'saves_count' => SavedEvent::where('event_id', $event->id)->count(),
                    'created_at' => $event->created_at,
                    'updated_at' => $event->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'events' => $eventsData,
                    'pagination' => [
                        'current_page' => $events->currentPage(),
                        'total_pages' => $events->lastPage(),
                        'per_page' => $events->perPage(),
                        'total' => $events->total(),
                        'from' => $events->firstItem(),
                        'to' => $events->lastItem(),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch events',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new event (admin)
     */
    public function createEvent(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'start_datetime' => 'required|date|after_or_equal:now',
                'end_datetime' => 'required|date|after:start_datetime',
                'location' => 'required|string|max:255',
                'area' => ['required', 'string', Rule::in(EventRequest::getAreas())],
                'category' => ['required', 'string', Rule::in(EventRequest::getCategories())],
                'event_type' => ['required', 'string', Rule::in(EventRequest::getEventTypes())],
                'format' => ['required', 'string', Rule::in(EventRequest::getFormats())],
                'is_free' => 'required|boolean',
                'price' => 'nullable|required_if:is_free,false|numeric|min:0',
                'requester_email' => 'required|email|max:255',
                'requester_phone' => 'nullable|string|max:25',
                'reference_link' => 'nullable|url|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('event_images', 'public');
                $validatedData['image'] = $imagePath;
            }

            // Set admin-specific fields
            $validatedData['approval_status'] = EventRequest::APPROVAL_STATUS_APPROVED;
            $validatedData['approved_by'] = Auth::id();
            $validatedData['requested_by'] = null; // Admin posted, not user requested

            if ($validatedData['is_free']) {
                $validatedData['price'] = 0;
            }

            $event = EventRequest::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Event created and published successfully',
                'data' => [
                    'event' => [
                        'id' => $event->id,
                        'title' => $event->title,
                        'approval_status' => $event->approval_status,
                        'created_at' => $event->created_at,
                    ]
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific event (admin view)
     */
    public function getEvent($id)
    {
        try {
            $event = EventRequest::where('id', $id)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED)
                ->first();

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found'
                ], 404);
            }

            $eventData = [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'start_datetime' => $event->start_datetime,
                'end_datetime' => $event->end_datetime,
                'location' => $event->location,
                'area' => $event->area,
                'category' => $event->category,
                'event_type' => $event->event_type,
                'format' => $event->format,
                'is_free' => (bool) $event->is_free,
                'price' => $event->price ? (float) $event->price : null,
                'image_url' => $event->image ? asset('storage/' . $event->image) : null,
                'reference_link' => $event->reference_link,
                'requester_email' => $event->requester_email,
                'requester_phone' => $event->requester_phone,
                'requested_by' => $event->requested_by,
                'approved_by' => $event->approved_by,
                'approval_status' => $event->approval_status,
                'saves_count' => SavedEvent::where('event_id', $event->id)->count(),
                'created_at' => $event->created_at,
                'updated_at' => $event->updated_at,
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'event' => $eventData
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update existing event
     */
    public function updateEvent(Request $request, $id)
    {
        try {
            $event = EventRequest::where('id', $id)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED)
                ->first();

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found'
                ], 404);
            }

            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'start_datetime' => 'sometimes|required|date|after_or_equal:now',
                'end_datetime' => 'sometimes|required|date|after:start_datetime',
                'location' => 'sometimes|required|string|max:255',
                'area' => ['sometimes', 'required', 'string', Rule::in(EventRequest::getAreas())],
                'category' => ['sometimes', 'required', 'string', Rule::in(EventRequest::getCategories())],
                'event_type' => ['sometimes', 'required', 'string', Rule::in(EventRequest::getEventTypes())],
                'format' => ['sometimes', 'required', 'string', Rule::in(EventRequest::getFormats())],
                'is_free' => 'sometimes|required|boolean',
                'price' => 'nullable|required_if:is_free,false|numeric|min:0',
                'requester_email' => 'sometimes|required|email|max:255',
                'requester_phone' => 'sometimes|nullable|string|max:25',
                'reference_link' => 'sometimes|nullable|url|max:255',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($event->image) {
                    Storage::disk('public')->delete($event->image);
                }
                
                $image = $request->file('image');
                $imagePath = $image->store('event_images', 'public');
                $validatedData['image'] = $imagePath;
            }

            // Handle free pricing
            if (array_key_exists('is_free', $validatedData) && $validatedData['is_free']) {
                $validatedData['price'] = 0;
            }

            $event->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Event updated successfully',
                'data' => [
                    'event' => [
                        'id' => $event->id,
                        'title' => $event->title,
                        'updated_at' => $event->updated_at,
                    ]
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete event
     */
    public function deleteEvent($id)
    {
        try {
            $event = EventRequest::where('id', $id)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED)
                ->first();

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found'
                ], 404);
            }

            // Delete associated saved events
            SavedEvent::where('event_id', $event->id)->delete();

            // Delete image file if exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }

            $event->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete event',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}