<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Notifications\BookingReminder1H;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::query()
            ->with(['user'])
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        return view('bookings.create');
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $start = $data['start'];
        $data['start'] = fromUserDateTimeToUTC($start);
        $data['end'] = fromUserDateTimeToUTC($data['end']);
        $data['timestamp'] = fromUserDateTimeToUTC($start);

        $booking = $request->user()->bookings()->create($data);
        $startTime = CarbonImmutable::parse($request->start);

        // Schedule 1H reminder
        $oneHourTime = fromUserDateTimeToUTC($startTime->subHour());
        if (now('UTC')->lessThan($oneHourTime)) {
            $booking->user->scheduledNotifications()->create([
                'notification_class' => BookingReminder1H::class,
                'notifiable_id' => $booking->id,
                'notifiable_type' => Booking::class,
                'sent' => false,
                'processing' => false,
                'scheduled_at' => $oneHourTime,
                'sent_at' => null,
                'tries' => 0,
            ]);
        }

        return redirect()->route('bookings.index');
    }

    public function edit(Booking $booking)
    {
        return view('bookings.edit', compact('booking'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        $data = $request->validated();
        if (isset($data['start'])) {
            $data['start'] = fromUserDateTimeToUTC($data['start']);
        }

        if (isset($data['end'])) {
            $data['end'] = fromUserDateTimeToUTC($data['end']);
        }
        $originStart = $booking->start;
        $booking->update($data);

        $startTime = CarbonImmutable::parse($request->start);

        // First we need to check if there are any already scheduled notifications
        if (!$originStart->eq($booking->start)) {
            // Since we are clearing the scheduled notifications, we need to create them again for the new date
            // Schedule 1H reminder
            $oneHourTime = fromUserDateTimeToUTC($startTime->subHour(), $booking->user->timezone);
            if (now('UTC')->lessThan($oneHourTime)) {
                $booking->scheduledNotifications()
                    ->update([
                        'scheduled_at' => $oneHourTime,
                    ]);
            }
        }

        return redirect()->route('bookings.index');
    }

    public function destroy(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 404);
        $booking->scheduledNotifications()->delete();
        $booking->delete();

        $booking->scheduledNotifications()
            ->delete();

        return redirect()->route('bookings.index');
    }
}
