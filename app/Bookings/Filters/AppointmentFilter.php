<?php

namespace App\Bookings\Filters;

use App\Bookings\IFilter;
use App\Bookings\TimeSlotGenerator;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class AppointmentFilter implements IFilter
{
    protected $appointments;

    public function __construct(Collection $appointments)
    {
        $this->appointments = $appointments;
    }

    public function apply(TimeSlotGenerator $generator, CarbonPeriod $interval)
    {
        $interval->addFilter(function($slot) use ($generator) {
            foreach($this->appointments as $appointment) {
                $appointmentStartTime = $appointment->date->setTimeFrom(
                    $appointment->start_time->subMinutes($generator->service->duration)
                );
                $appointmentEndTime = $appointment->date->setTimeFrom(
                    $appointment->end_time
                );

                if ($slot->between($appointmentStartTime, $appointmentEndTime)) {
                    return false;
                }
            }

            return true;
        });
    }
}
