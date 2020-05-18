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

### Advanced usage

// Coming soon 😉

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

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
