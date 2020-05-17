<?php

namespace Asantibanez\LivewireCalendar;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class LivewireCalendar
 * @package Asantibanez\LivewireCalendar
 * @property Carbon $startsAt
 * @property Carbon $endsAt
 * @property Carbon $weekStartsAt
 * @property Carbon $weekEndsAt
 * @property string $dayView
 * @property string $eventView
 * @property string $dayOfWeek
 */
class LivewireCalendar extends Component
{
    public $startsAt;

    public $endsAt;

    public $weekStartsAt;

    public $weekEndsAt;

    public $dayView;

    public $eventView;

    public $dayOfWeekView;

    protected $casts = [
        'startsAt' => 'date',
        'endsAt' => 'date',
    ];

    public function mount($initialYear = null,
                          $initialMonth = null,
                          $weekStartsAt = null,
                          $dayView = null,
                          $eventView = null,
                          $dayOfWeekView = null)
    {
        $initialYear = $initialYear ?? Carbon::today()->year;
        $initialMonth = $initialMonth ?? Carbon::today()->month;

        $this->startsAt = Carbon::createFromDate($initialYear, $initialMonth, 1)->startOfDay();

        $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();

        $this->weekStartsAt = $weekStartsAt ?? Carbon::SUNDAY;

        $this->weekEndsAt = $this->weekStartsAt == Carbon::SUNDAY
            ? Carbon::SATURDAY
            : collect([0,1,2,3,4,5,6])->get($this->weekStartsAt + 6 - 7)
        ;

        $this->dayView = $dayView ?? 'livewire-calendar::day';

        $this->eventView = $eventView ?? 'livewire-calendar::event';

        $this->dayOfWeekView = $dayOfWeekView ?? 'livewire-calendar::day-of-week';
    }

    /**
     * @throws Exception
     */
    public function monthGrid()
    {
        $firstDayOfGrid = $this->startsAt->clone()->startOfWeek($this->weekStartsAt);

        $lastDayOfGrid = $this->endsAt->clone()->endOfWeek($this->weekEndsAt);

        $numbersOfWeeks = $lastDayOfGrid->diffInWeeks($firstDayOfGrid) + 1;

        $days = $lastDayOfGrid->diffInDays($firstDayOfGrid) + 1;

        if ($days % 7 != 0) {
            throw new Exception("Livewire Calendar not correctly configured. Check initial inputs.");
        }

        $monthGrid = collect();
        $currentDay = $firstDayOfGrid->clone();

        while(!$currentDay->greaterThan($lastDayOfGrid)) {
            $monthGrid->push($currentDay->clone());
            $currentDay->addDay();
        }

        $monthGrid = $monthGrid->chunk(7);
        if ($numbersOfWeeks != $monthGrid->count()) {
            throw new Exception("Livewire Calendar calculated wrong number of weeks. Sorry :(");
        }

        return $monthGrid;
    }

    public function events() : Collection
    {
        return collect();
    }

    public function getEventsForDay($day, Collection $events) : Collection
    {
        return $events
            ->filter(function ($event) use ($day) {
                return Carbon::parse($event['date'])->isSameDay($day);
            });
    }

    public function onDayClick($year, $month, $day)
    {
        //
    }

    public function onEventDropped($eventId, $year, $month, $day)
    {
        //
    }

    /**
     * @return Factory|View
     * @throws Exception
     */
    public function render()
    {
        $events = $this->events();

        return view('livewire-calendar::calendar')
            ->with([
                'monthGrid' => $this->monthGrid(),
                'events' => $events,
                'getEventsForDay' => function ($day) use ($events) {
                    return $this->getEventsForDay($day, $events);
                }
            ]);
    }
}
