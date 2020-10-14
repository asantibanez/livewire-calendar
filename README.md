# Livewire Calendar

This package allows you to build a Livewire monthly calendar grid to show events for each day. Events can be loaded 
from within the component and will be presented on each day depending on the date of the event.

## Preview

![preview](https://github.com/asantibanez/livewire-calendar/raw/master/preview.gif)

## Installation

You can install the package via composer:

```bash
composer require asantibanez/livewire-calendar
```

## Requirements

This package uses `livewire/livewire` (https://laravel-livewire.com/) under the hood.

It also uses TailwindCSS (https://tailwindcss.com/) for base styling. 

Please make sure you include both of this dependencies before using this component. 

## Usage

In order to use this component, you must create a new Livewire component that extends from 
`LivewireCalendar`

You can use `make:livewire` to create a new component. For example.
``` bash
php artisan make:livewire AppointmentsCalendar
```

In the `AppointmentsCalendar` class, instead of extending from the base `Component` Livewire class, 
extend from `LivewireCalendar`. Also, remove the `render` method. 
You'll have a class similar to this snippet.
 
``` php
class AppointmentsCalendar extends LivewireCalendar
{
    //
}
```

In this class, you must override the following method

```php
public function events() : Collection
{
    // must return a Laravel collection
}
```

In the `events()` method, return a collection holding the events that will be displayed on 
the calendar. Events must be keyed arrays holding at least the following keys: 
`id`, `title`, `description`, `date` (`date` must be a `Carbon\Carbon` instance).

Example

```php
public function events() : Collection
{
    return collect([
        [
            'id' => 1,
            'title' => 'Breakfast',
            'description' => 'Pancakes! 🥞',
            'date' => Carbon::today(),
        ],
        [
            'id' => 2,
            'title' => 'Meeting with Pamela',
            'description' => 'Work stuff',
            'date' => Carbon::tomorrow(),
        ],
    ]);
}
```

The `date` value will be used to determine to what day the event belongs to. To
load values in the `events()` method, you can use the following component properties
to filter your events.
- `startsAt`: starting date of month   
- `endsAt`: ending date of month   
- `gridStartsAt`: starting date of calendar grid. Can be a date from the previous month.   
- `endingStartsAt`: ending date of calendar grid. Can be a date from the next month.   

Example

```php
public function events(): Collection
{
    return Model::query()
        ->whereDate('scheduled_at', '>=', $this->gridStartsAt)
        ->whereDate('scheduled_at', '<=', $this->gridEndsAt)
        ->get()
        ->map(function (Model $model) {
            return [
                'id' => $model->id,
                'title' => $model->title,
                'description' => $model->notes,
                'date' => $model->scheduled_at,
            ];
        });
}
```

Now, we can include our component in any view. 

Example

```blade
<livewire:appointments-calendar/>
``` 

This will render a calendar grid.

![example](https://github.com/asantibanez/livewire-calendar/raw/master/example.png)

By default, the component will render the current month. If you want to change the
starting month, you can set the `year` and `month` props.

 Example
 
 ```blade
<livewire:appointments-calendar
    year="2019"
    month="12"
/>
 ``` 

You should include scripts with `@livewireCalendarScripts` to enable drag and drop which is turned on by default.
You must include them after `@livewireScripts`

```blade
@livewireScripts
@livewireCalendarScripts
``` 

The component has 3 public methods that can help navigate forward and backward through months: 
- `goToPreviousMonth`
- `goToNextMonth` 
- `goToCurrentMonth`

You can use these methods on extra views using `before-calendar-view` or `after-calendar-view` explained below.  

### Advanced usage

### Ui customization

You can customize the behavior of the component with the following properties when rendering on a view:

- `week-starts-at` which can be a number from 0 to 6 according to Carbon days of week to indicate
with which day of week the calendar should render first. 
                          
- `event-view` which can be any `blade.php` view that will be used to render the event card. 
This view will be injected with a `$event` variable holding its data. 

- `before-calendar-view` and `after-calendar-view` can be any `blade.php` views that can be rendered before or after
the calendar itself. These can be used to add extra features to your component using Livewire.

- `drag-and-drop-classes` can be any css class used to render the hover effect when dragging an event over each day
in the calendar. By default, this value is `border border-blue-400 border-4` 

- `day-of-week-view` which can be any `blade.php` view that will be used to render the header of each calendar day.
This view will be injected the `day` property which is a Carbon instance of the day of the week.

```blade
<livewire:appointments-grid
    week-starts-at="0, 1, 2, 3, 4, 5 or 6. 0 stands for Sunday"
    event-view="path/to/view/starting/from/views/folder"
    day-of-week-view="path/to/view/starting/from/views/folder"
    before-calendar-view="path/to/view/starting/from/views/folder"
    after-calendar-view="path/to/view/starting/from/views/folder"
    drag-and-drop-classes="css classes"
/>
```

### Advanced ui customization

(This options should be used using blade views based on the component default views)

To use these options, it is recommended to publish the base blade views used by the component and extend their 
behavior and styling to your liking. To do this, run `php artisan vendor:publish` and export the `livewire-calendar` tag.

- `calendar-view` which can be any `blade.php` view that renders the whole component. It's advised to override this
view with an altered copy of the base `calendar-view` eg adding a view next to the component.

- `day-view` which can be any `blade.php` view that will be used to render each day of the month. This view will be 
injected with the following properties: `componentId` (id of the Livewire component)
, `day` (day of the month as a Carbon instance)
, `dayInMonth` (if the day is part of the month or not)
, `isToday` (if the day is today's date)
, `events` (events collection that correspond to this day)

Example

```blade
<livewire:appointments-grid
    calendar-view="path/to/view/starting/from/views/folder"
    day-view="path/to/view/starting/from/views/folder"
/>
```

### Interaction customization

You can override the following methods to add interactivity to your component

```php
public function onDayClick($year, $month, $day)
{
    // This event is triggered when a day is clicked
    // You will be given the $year, $month and $day for that day
}

public function onEventClick($eventId)
{
    // This event is triggered when an event card is clicked
    // You will be given the event id that was clicked 
}

public function onEventDropped($eventId, $year, $month, $day)
{
    // This event will fire when an event is dragged and dropped into another calendar day
    // You will get the event id, year, month and day where it was dragged to
}
```

### Automatic polling

You can also add automatic polling if needed using `pollMillis` parameters. You can combo with `pollAction` in
order to call a specific action in your component at the desired polling interval.

### Disabling interactions

By default click and drag/drop events are enabled. To disable them you can use the following parameters when
rendering the component
```blade
<livewire:appointments-grid
    :day-click-enabled="false"
    :event-click-enabled="false"
    :drag-and-drop-enabled="false"
/>
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email santibanez.andres@gmail.com instead of using the issue tracker.

## Credits

- [Andrés Santibáñez](https://github.com/asantibanez)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
