<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class AggregateRoot
{
    private ?Collection $domainEvents = null;

    private function events(): Collection
    {
        return $this->domainEvents ??= new ArrayCollection();
    }

    protected function recordDomainEvent(DomainEvent $domainEvent): void
    {
        $this->events()->add($domainEvent);
    }

    public function pullDomainEvents(): array
    {
        $events = $this->events()->toArray();
        $this->events()->clear();
        return $events;
    }

    public function getDomainEvents(): Collection
    {
        return $this->events();
    }
}