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
 * @property Carbon $gridStartsAt
 * @property Carbon $gridEndsAt
 * @property int $weekStartsAt
 * @property int $weekEndsAt
 * @property string $calendarView
 * @property string $dayView
 * @property string $eventView
 * @property string $dayOfWeekView
 * @property string $beforeCalendarWeekView
 * @property string $afterCalendarWeekView
 * @property string $dragAndDropClasses
 * @property int $pollMillis
 * @property string $pollAction
 * @property boolean $dragAndDropEnabled
 * @property boolean $dayClickEnabled
 * @property boolean $eventClickEnabled
 */
class LivewireCalendar extends Component
{
    public $startsAt;
    public $endsAt;

    public $gridStartsAt;
    public $gridEndsAt;

    public $weekStartsAt;
    public $weekEndsAt;

    public $calendarView;
    public $dayView;
    public $eventView;
    public $dayOfWeekView;

    public $dragAndDropClasses;

    public $beforeCalendarView;
    public $afterCalendarView;

    public $pollMillis;
    public $pollAction;

    public $dragAndDropEnabled;
    public $dayClickEnabled;
    public $eventClickEnabled;

    protected $casts = [
        'startsAt' => 'date',
        'endsAt' => 'date',
        'gridStartsAt' => 'date',
        'gridEndsAt' => 'date',
    ];

    public function mount($initialYear = null,
                          $initialMonth = null,
                          $weekStartsAt = null,
                          $calendarView = null,
                          $dayView = null,
                          $eventView = null,
                          $dayOfWeekView = null,
                          $dragAndDropClasses = null,
                          $beforeCalendarView = null,
                          $afterCalendarView = null,
                          $pollMillis = null,
                          $pollAction = null,
                          $dragAndDropEnabled = true,
                          $dayClickEnabled = true,
                          $eventClickEnabled = true,
                          $extras = [])
    {
        $this->weekStartsAt = $weekStartsAt ?? Carbon::SUNDAY;
        $this->weekEndsAt = $this->weekStartsAt == Carbon::SUNDAY
            ? Carbon::SATURDAY
            : collect([0,1,2,3,4,5,6])->get($this->weekStartsAt + 6 - 7)
        ;

        $initialYear = $initialYear ?? Carbon::today()->year;
        $initialMonth = $initialMonth ?? Carbon::today()->month;

        $this->startsAt = Carbon::createFromDate($initialYear, $initialMonth, 1)->startOfDay();
        $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();

        $this->calculateGridStartsEnds();

        $this->setupViews($calendarView, $dayView, $eventView, $dayOfWeekView, $beforeCalendarView, $afterCalendarView);

        $this->setupPoll($pollMillis, $pollAction);

        $this->dragAndDropEnabled = $dragAndDropEnabled;
        $this->dragAndDropClasses = $dragAndDropClasses ?? 'border border-blue-400 border-4';

        $this->dayClickEnabled = $dayClickEnabled;
        $this->eventClickEnabled = $eventClickEnabled;

        $this->afterMount($extras);
    }

    public function afterMount($extras = [])
    {
        //
    }

    public function setupViews($calendarView = null,
                               $dayView = null,
                               $eventView = null,
                               $dayOfWeekView = null,
                               $beforeCalendarView = null,
                               $afterCalendarView = null)
    {
        $this->calendarView = $calendarView ?? 'livewire-calendar::calendar';
        $this->dayView = $dayView ?? 'livewire-calendar::day';
        $this->eventView = $eventView ?? 'livewire-calendar::event';
        $this->dayOfWeekView = $dayOfWeekView ?? 'livewire-calendar::day-of-week';

        $this->beforeCalendarView = $beforeCalendarView ?? null;
        $this->afterCalendarView = $afterCalendarView ?? null;
    }

    public function setupPoll($pollMillis, $pollAction)
    {
        $this->pollMillis = $pollMillis;
        $this->pollAction = $pollAction;
    }

    public function goToPreviousMonth()
    {
        $this->startsAt->subMonthNoOverflow();
        $this->endsAt->subMonthNoOverflow();

        $this->calculateGridStartsEnds();
    }

    public function goToNextMonth()
    {
        $this->startsAt->addMonthNoOverflow();
        $this->endsAt->addMonthNoOverflow();

        $this->calculateGridStartsEnds();
    }

    public function goToCurrentMonth()
    {
        $this->startsAt = Carbon::today()->startOfMonth()->startOfDay();
        $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();

        $this->calculateGridStartsEnds();
    }

    public function calculateGridStartsEnds()
    {
        $this->gridStartsAt = $this->startsAt->clone()->startOfWeek($this->weekStartsAt);
        $this->gridEndsAt = $this->endsAt->clone()->endOfWeek($this->weekEndsAt);
    }

    /**
     * @throws Exception
     */
    public function monthGrid()
    {
        $firstDayOfGrid = $this->gridStartsAt;
        $lastDayOfGrid = $this->gridEndsAt;

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

    public function onEventClick($eventId)
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

        return view($this->calendarView)
            ->with([
                'componentId' => $this->id,
                'monthGrid' => $this->monthGrid(),
                'events' => $events,
                'getEventsForDay' => function ($day) use ($events) {
                    return $this->getEventsForDay($day, $events);
                }
            ]);
    }
}
