<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class AggregateRoot
{
    /** @var Collection<int, DomainEvent> */
    private Collection $domainEvents;

    protected function __construct()
    {
        /** @var Collection<int, DomainEvent> $events */
        $events = new ArrayCollection();
        $this->domainEvents = $events;
    }

    protected function recordDomainEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents->add($domainEvent);
    }

    /**
     * @return Collection<int, DomainEvent>
    */
    public function events(): Collection
    {
        return $this->domainEvents;
    }

    /**
     * @return list<DomainEvent>
    */
    public function pullDomainEvents(): array
    {
        /** @var list<DomainEvent> $export */
        $export = array_values($this->domainEvents->toArray());
        $this->domainEvents->clear();

        return $export;
    }

    /**
     * @return Collection<int, DomainEvent>
     */
    public function getDomainEvents(): Collection
    {
        return $this->domainEvents;
    }
}
