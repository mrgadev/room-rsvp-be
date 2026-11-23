<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(string $user_id, array $data): Transaction {
        $checkin_date = Carbon::parse($data['checkin_date'])->startOfDay();
        $checkout_date = Carbon::parse($data['checkout_date'])->startOfDay();

        $nights = $checkin_date->diffInDays($checkout_date);
        if($nights <= 0) {
            throw ValidationException::withMessages([
                'checkout_date' => ['Checkout date must be after checkin date']
            ]);
        }

        return DB::transaction(function() use ($user_id, $data, $checkin_date, $checkout_date, $nights) {
            // Lock room while creating transaction to prevent race condition
            $room = Room::where('id', $data['room_id'])
            ->lockForUpdate()
            ->firstOrFail();

            // Calculate active transaction where the booking date is overlapping
            // Overlap happens when existing check in < new check out AND existing checkout > new checkin
            $bookedUnits = Transaction::where('room_id', $room->id)
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->where('checkin_date', '<', $checkout_date->toDateString())
            ->where('checkout_date', '>', $checkin_date->toDateString())
            ->count();

            $availableUnits = $room->total_units - $bookedUnits;

            if($availableUnits <= 0) {
                throw ValidationException::withMessages([
                    'room_id' => ['Room already full in desired date range']
                ]);
            }

            // Calculate total price using diff between checkin and checkout and price per night at room table
            $totalPrice = $nights * $room->price;

            // Save transaction
            return Transaction::create([
                'user_id' => $user_id,
                'room_id' => $room->id,
                'checkin_date' => $checkin_date->toDateString(),
                'checkout_date' => $checkout_date->toDateString(),
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);
        });

        // return DB
    }
}
