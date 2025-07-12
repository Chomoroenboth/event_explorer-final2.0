<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'location' => $this->location,
            'area' => $this->area,
            'category' => $this->category,
            'event_type' => $this->event_type,
            'format' => $this->format,
            'is_free' => (bool) $this->is_free,
            'price' => $this->price ? (float) $this->price : null,
            'image_url' => $this->image ? asset('storage/' . $this->image) : null,
            'reference_link' => $this->reference_link,
            'requester_email' => $this->requester_email,
            'requester_phone' => $this->requester_phone,
            'approval_status' => $this->approval_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Conditional fields
            'requested_by' => $this->when($this->requested_by, $this->requested_by),
            'approved_by' => $this->when($this->approved_by, $this->approved_by),
            'is_saved' => $this->when(auth()->check(), function () {
                return \App\Models\SavedEvent::where('user_id', auth()->id())
                    ->where('event_id', $this->id)
                    ->exists();
            }),
            'saves_count' => $this->whenLoaded('savedEvents', function () {
                return $this->savedEvents->count();
            }),
        ];
    }
}