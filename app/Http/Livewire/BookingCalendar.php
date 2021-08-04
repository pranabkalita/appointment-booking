<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Livewire\Component;

class BookingCalendar extends Component
{
    public $date;
    public $calendarStartDate;

    public $service;
    public $employee;

    public function mount()
    {
        $this->calendarStartDate = now();

        $this->setDate(now()->timestamp);
    }

    public function setDate($timestamp)
    {
        $this->date = $timestamp;
    }

    public function getAvailableTimeSlotsProperty()
    {
        if (!$this->employee || !$this->employeeSchedule) {
            return collect();
        }

        return $this->employee->availableTimeSlots($this->employeeSchedule, $this->service);
    }

    public function getEmployeeScheduleProperty()
    {
        return $this->employee->schedules()
            ->whereDate('date', $this->calendarSelectedDateObject)
            ->first();
    }

    public function getCalendarSelectedDateObjectProperty()
    {
        return Carbon::createFromTimestamp($this->date);
    }

    public function getCalendarWeekIntervalProperty()
    {
        return CarbonInterval::days(1)
            ->toPeriod(
                $this->calendarStartDate,
                $this->calendarStartDate->clone()->addWeek()
            );
    }

    public function getWeekIsGreaterThanCurrentProperty()
    {
        return $this->calendarStartDate->gt(now());
    }

    public function incrementCalendarWeek()
    {
        $this->calendarStartDate->addWeek()->addDay();
    }

    public function decrementCalendarWeek()
    {
        $this->calendarStartDate->subWeek()->subDay();
    }

    public function render()
    {
        return view('livewire.booking-calendar');
    }
}
