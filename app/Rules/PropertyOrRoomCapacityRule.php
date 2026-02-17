<?php

namespace App\Rules;

use App\Models\Property;
use App\Models\Room;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PropertyOrRoomCapacityRule implements ValidationRule
{
    private int $room_id;
    private int $property_id;
    private Room $room;
    private Property $property;

    public function __construct(?int $property_id = null, ?int $room_id = null)
    {

        $this->property_id = $property_id;
        if ($this->property_id) {
            $this->property = Property::findOrFail($this->property_id);
        }

        // Get the room if room_id is provided.
        $this->room_id = $room_id;
        if ($this->room_id) {
            $this->room = Room::findOrFail($this->room_id);
        }
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value)) {
            return;
        }
        $guestCount = count($value);

        // Check room capacity (if room is specified)
        if ($this->room_id && $this->room) {
            $roomCapacity = $this->room->capacity;
            if ($roomCapacity > 0 && $guestCount > $roomCapacity) {
                $fail($attribute, "The number of guests ({$guestCount}) exceeds the room capacity ({$roomCapacity}).")
                    ->translate();
                return;
            }
        }

        // If there's no room specified, then check property capacity
        if (!$this->room_id && $this->property) {
            $propertyCapacity = $this->property->max_guests;
            if ($propertyCapacity > 0 && $guestCount > $propertyCapacity) {
                $fail($attribute, "The number of guests ({$guestCount}) exceeds the property capacity ({$propertyCapacity}).")
                    ->translate();
                return;
            }
        }
    }
}
