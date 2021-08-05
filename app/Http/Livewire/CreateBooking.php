<?php

namespace App\Http\Livewire;

use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Livewire\Component;

class CreateBooking extends Component
{
    public $employees;

    public $state = [
        'service' => '',
        'employee' => '',
        'time' => ''
    ];

    protected $listeners = [
        'updated-booking-time' => 'setTime'
    ];

    public function mount()
    {
        $this->employees = collect();
    }

    public function setTime($time)
    {
        $this->state['time'] = $time;
    }

    public function updatedStateService($serviceId)
    {
        $this->state['employee'] = '';
        $this->clearTime();

        if (!$serviceId) {
            $this->employees = collect();
            return;
        }

        $this->employees = $this->selectedService->employees;
    }

    public function updatedStateEmployee()
    {
        $this->clearTime();
    }

    public function clearTime()
    {
        $this->state['time'] = '';
    }

    public function getTimeObjectProperty()
    {
        return Carbon::createFromTimestamp($this->state['time']);
    }

    public function getHasDetailsToBookProperty()
    {
        return $this->state['service'] && $this->state['employee'] && $this->state['time'];
    }

    public function getSelectedServiceProperty()
    {
        if (!$this->state['service']) {
            return null;
        }

        return Service::find($this->state['service']);
    }

    public function getSelectedEmployeeProperty()
    {
        if (!$this->state['employee']) {
            return null;
        }

        return Employee::find($this->state['employee']);
    }

    public function render()
    {
        $services = Service::get();

        return view('livewire.create-booking', compact('services'))
            ->layout('layouts.guest');
    }
}
