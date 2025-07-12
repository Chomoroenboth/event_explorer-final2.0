<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EventRequest;
use App\Models\SavedEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /**
     * Get all published events
     */
    public function index(Request $request)
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

            if ($request->has('event_type') && $request->event_type) {
                $query->where('event_type', $request->event_type);
            }

            if ($request->has('format') && $request->format) {
                $query->where('format', $request->format);
            }

            // Date range filter
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('start_datetime', '>=', $request->start_date);
            }

            if ($request->has('end_date') && $request->end_date) {
                $query->whereDate('end_datetime', '<=', $request->end_date);
            }

            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
                });
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'start_datetime');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = min($request->get('per_page', 15), 50); // Max 50 items per page
            $events = $query->paginate($perPage);

            // Transform events data
            $eventsData = $events->getCollection()->map(function ($event) use ($request) {
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
                    'created_at' => $event->created_at,
                    'updated_at' => $event->updated_at,
                ];

                // Add saved status if user is authenticated
                if (Auth::check()) {
                    $eventData['is_saved'] = SavedEvent::where('user_id', Auth::id())
                        ->where('event_id', $event->id)
                        ->exists();
                }

                return $eventData;
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
     * Get a specific event
     */
    public function show($id)
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
                'created_at' => $event->created_at,
                'updated_at' => $event->updated_at,
            ];

            // Add saved status if user is authenticated
            if (Auth::check()) {
                $eventData['is_saved'] = SavedEvent::where('user_id', Auth::id())
                    ->where('event_id', $event->id)
                    ->exists();
            }

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
     * Propose a new event
     */
    public function propose(Request $request)
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

            // Set default values
            $validatedData['approval_status'] = EventRequest::APPROVAL_STATUS_PENDING;
            
            if (Auth::check()) {
                $validatedData['requested_by'] = Auth::id();
            }

            if ($validatedData['is_free']) {
                $validatedData['price'] = 0;
            }

            $eventRequest = EventRequest::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Event proposal submitted successfully',
                'data' => [
                    'event_request' => [
                        'id' => $eventRequest->id,
                        'title' => $eventRequest->title,
                        'approval_status' => $eventRequest->approval_status,
                        'created_at' => $eventRequest->created_at,
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
                'message' => 'Failed to submit event proposal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save an event
     */
    public function saveEvent($id)
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

            $existingSavedEvent = SavedEvent::where('user_id', Auth::id())
                ->where('event_id', $event->id)
                ->first();

            if ($existingSavedEvent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event already saved'
                ], 409);
            }

            SavedEvent::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Event saved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unsave an event
     */
    public function unsaveEvent($id)
    {
        try {
            $savedEvent = SavedEvent::where('user_id', Auth::id())
                ->where('event_id', $id)
                ->first();

            if (!$savedEvent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found in saved events'
                ], 404);
            }

            $savedEvent->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event unsaved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unsave event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's saved events
     */
    public function savedEvents(Request $request)
    {
        try {
            $savedEventIds = SavedEvent::where('user_id', Auth::id())
                ->pluck('event_id');

            $query = EventRequest::whereIn('id', $savedEventIds)
                ->where('approval_status', EventRequest::APPROVAL_STATUS_APPROVED);

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
                    'is_saved' => true,
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
                'message' => 'Failed to fetch saved events',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}