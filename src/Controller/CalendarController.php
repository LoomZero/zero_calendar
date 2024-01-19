<?php

namespace Drupal\zero_calendar\Controller;

use DateTime;
use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Enums\RecurrenceFrequency;
use Spatie\IcalendarGenerator\ValueObjects\RRule;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CalendarController extends ControllerBase {

  public function generate() {
    $title = Drupal::request()->get('title');
    $start = Drupal::request()->get('start');
    $repeat = Drupal::request()->get('repeat');

    if (empty($title) || empty($start)) {
      throw new BadRequestHttpException('The parameter "title" and "start" are required.');
    }

    $event = Event::create($title);
    $event->startsAt(new DateTime(date('D, d M Y H:i:s', $start)));
    if ($repeat) {
      $event->rrule(RRule::frequency(RecurrenceFrequency::from($repeat)));
    }

    $http_options = [
      'Content-Type' => 'text/calendar; charset=utf-8',
    ];
    if ($filename) {
      $http_options['Content-Disposition'] = 'attachment; filename="' . $filename . '.ics"';
    }

    return new Response(Calendar::create($title)->event($event)->get(), 200, $http_options);
  }

}
