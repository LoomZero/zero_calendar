<?php

namespace Drupal\zero_calendar\Controller;

use DateTime;
use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\ValueObjects\RRule;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CalendarController extends ControllerBase {

  public function generate() {
    $title = Drupal::request()->get('title');
    $start = Drupal::request()->get('start');
    $interval = Drupal::request()->get('interval');

    if (empty($title) || empty($start)) {
      throw new BadRequestHttpException('The parameter "title" and "start" are required.');
    }

    $event = Event::create($title);
    $event->startsAt(new DateTime(date('D, d M Y H:i:s', $start)));
    if ($interval) {
      $event->rrule(RRule::frequency($interval));
    }

    return new Response(Calendar::create($title)->event($event)->get(), 200, [
      'Content-Type' => 'text/calendar; charset=utf-8',
    ]);
  }

}
