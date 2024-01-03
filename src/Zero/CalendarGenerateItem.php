<?php

namespace Drupal\zero_calendar\Zero;

use Drupal\Core\Url;

class CalendarGenerateItem {

  /** @var string[] */
  private array $parameters = [];

  public static function create(): self {
    return new CalendarGenerateItem();
  }

  public function getParameters(): array {
    return $this->parameters;
  }

  public function getParameter(string $key) {
    return $this->parameters[$key] ?? NULL;
  }

  public function setParameter(string $key, $value): self {
    $this->parameters[$key] = $value;
    return $this;
  }

  public function removeParameter(string $key): self {
    unset($this->parameters[$key]);
    return $this;
  }

  public function title(string $title): self {
    return $this->setParameter('title', $title);
  }

  public function start(int $start): self {
    return $this->setParameter('start', $start);
  }

  public function repeat(string $repeat = NULL): self {
    return $this->setParameter('repeat', $repeat);
  }

  public function generateURL(): Url {
    return Url::fromRoute('zero_calendar.generate', [], [
      'query' => $this->parameters,
    ]);
  }

}
