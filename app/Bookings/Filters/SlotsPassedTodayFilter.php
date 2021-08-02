<?php

namespace App\Bookings\Filters;

use App\Bookings\IFilter;
use App\Bookings\TimeSlotGenerator;
use Carbon\CarbonPeriod;

class SlotsPassedTodayFilter implements IFilter
{
    public function apply(TimeSlotGenerator $generator, CarbonPeriod $interval) {
        $interval->addFilter(function($slot) use ($generator) {
            if ($generator->schedule->date->isToday()) {
                if ($slot->lt(now())) {
                    return false;
                }
            }

            return true;
        });
    }
}
