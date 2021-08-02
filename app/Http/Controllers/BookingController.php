<?php

namespace App\Http\Controllers;

use App\Bookings\Filters\SlotsPassedTodayFilter;
use App\Bookings\TimeSlotGenerator;
use App\Models\Schedule;
use App\Models\Service;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __invoke()
    {
        $schedule = Schedule::find(4);
        $service = Service::find(2);

        $slots = (new TimeSlotGenerator($schedule, $service))
            ->applyFilters([
                new SlotsPassedTodayFilter()
            ])
            ->get();


        return view('bookings.create', compact('slots'));
    }
}
