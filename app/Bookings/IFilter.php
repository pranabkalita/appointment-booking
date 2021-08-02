<?php

namespace App\Bookings;

use App\Bookings\TimeSlotGenerator;
use Carbon\CarbonPeriod;

interface IFilter
{
    public function apply(TimeSlotGenerator $generator, CarbonPeriod $interval);
}
