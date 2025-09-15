<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Event\Exception\EventFullException;
use App\Domain\Event\Exception\EventNotPublishedException;
use App\Domain\Shared\AggregateRoot;
use App\Domain\Shared\DomainEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Event extends AggregateRoot
{
    private EventId $id;
    private EventTitle $title;
    private EventDescription $description;
    private EventDate $date;
    private EventLocation $location;
    private EventCapacity $capacity;
    private EventStatus $status;
    private EventCategory $category;
    private Collection $attendees;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    private function __construct(
        EventId $id,
        EventTitle $title,
        EventDescription $description,
        EventDate $date,
        EventLocation $location,
        EventCapacity $capacity,
        EventCategory $category
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->location = $location;
        $this->capacity = $capacity;
        $this->category = $category;
        $this->status = EventStatus::DRAFT;
        $this->attendees = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public static function create(
        EventTitle $title,
        EventDescription $description,
        EventDate $date,
        EventLocation $location,
        EventCapacity $capacity,
        EventCategory $category
    ): self {
        $event = new self(
            EventId::generate(),
            $title,
            $description,
            $date,
            $location,
            $capacity,
            $category
        );

        $event->recordDomainEvent(new EventCreated($event->id, $event->title));

        return $event;
    }

    public function publish(): void
    {
        if ($this->status->equals(EventStatus::PUBLISHED)) {
            return;
        }

        $this->status = EventStatus::PUBLISHED;
        $this->updatedAt = new \DateTimeImmutable();

        $this->recordDomainEvent(new EventPublished($this->id));
    }

    public function cancel(): void
    {
        if ($this->status->equals(EventStatus::CANCELLED)) {
            return;
        }

        $this->status = EventStatus::CANCELLED;
        $this->updatedAt = new \DateTimeImmutable();

        $this->recordDomainEvent(new EventCancelled($this->id));
    }

    public function registerAttendee(Attendee $attendee): void
    {
        if (!$this->status->equals(EventStatus::PUBLISHED)) {
            throw new EventNotPublishedException('Cannot register attendee to unpublished event');
        }

        if ($this->isFull()) {
            throw new EventFullException('Event is at full capacity');
        }

        if ($this->hasAttendee($attendee->getId())) {
            return; // Already registered
        }

        $this->attendees->add($attendee);
        $this->updatedAt = new \DateTimeImmutable();

        $this->recordDomainEvent(new AttendeeRegistered($this->id, $attendee->getId()));
    }

    public function cancelAttendee(AttendeeId $attendeeId): void
    {
        $attendee = $this->findAttendee($attendeeId);
        if (!$attendee) {
            return; // Not registered
        }

        $this->attendees->removeElement($attendee);
        $this->updatedAt = new \DateTimeImmutable();

        $this->recordDomainEvent(new AttendeeCancelled($this->id, $attendeeId));
    }

    public function isFull(): bool
    {
        return $this->attendees->count() >= $this->capacity->value();
    }

    public function hasAttendee(AttendeeId $attendeeId): bool
    {
        return $this->findAttendee($attendeeId) !== null;
    }

    private function findAttendee(AttendeeId $attendeeId): ?Attendee
    {
        foreach ($this->attendees as $attendee) {
            if ($attendee->getId()->equals($attendeeId)) {
                return $attendee;
            }
        }

        return null;
    }

    // Getters
    public function getId(): EventId
    {
        return $this->id;
    }

    public function getTitle(): EventTitle
    {
        return $this->title;
    }

    public function getDescription(): EventDescription
    {
        return $this->description;
    }

    public function getDate(): EventDate
    {
        return $this->date;
    }

    public function getLocation(): EventLocation
    {
        return $this->location;
    }

    public function getCapacity(): EventCapacity
    {
        return $this->capacity;
    }

    public function getStatus(): EventStatus
    {
        return $this->status;
    }

    public function getCategory(): EventCategory
    {
        return $this->category;
    }

    public function getAttendees(): Collection
    {
        return $this->attendees;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getAttendeesCount(): int
    {
        return $this->attendees->count();
    }

    public function getAvailableSpots(): int
    {
        return $this->capacity->value() - $this->attendees->count();
    }
} 