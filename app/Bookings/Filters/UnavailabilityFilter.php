<?php

namespace App\Bookings\Filters;

use App\Bookings\IFilter;
use App\Bookings\TimeSlotGenerator;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class UnavailabilityFilter implements IFilter
{
    protected $unavailabilities;

    public function __construct(Collection $unavailabilities)
    {
        $this->unavailabilities = $unavailabilities;
    }

    public function apply(TimeSlotGenerator $generator, CarbonPeriod $interval) {
        $interval->addFilter(function($slot) use ($generator) {
            foreach($this->unavailabilities as $unavailability) {
                $unavailabilityStart = $unavailability->schedule->date->setTimeFrom(
                    $unavailability->start_time->subMinutes(
                        $generator->service->duration - $generator::INCREMENT
                    )
                );
                $unavailabilityEnd = $unavailability->schedule->date->setTimeFrom(
                    $unavailability->end_time->subMinutes($generator::INCREMENT)
                );

                if ( $slot->between($unavailabilityStart, $unavailabilityEnd) ) {
                    return false;
                }
            }

            return true;
        });
    }
}
