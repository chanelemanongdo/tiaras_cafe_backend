<?php

namespace App\Models;

class ReservationQueue
{
    private array $queue = [];

    public function enqueue(Reservation $reservation): void
    {
        $reservationId = $reservation->getReservationId();

        if ($reservationId !== null) {
            foreach ($this->queue as $queuedReservation) {
                if ($queuedReservation->getReservationId() === $reservationId) {
                    return;
                }
            }
        } else {
            foreach ($this->queue as $queuedReservation) {
                if ($queuedReservation === $reservation) {
                    return;
                }
            }
        }

        $this->queue[] = $reservation;
    }

    public function dequeue(): ?Reservation
    {
        if ($this->isEmpty()) {
            return null;
        }

        return array_shift($this->queue);
    }

    public function peek(): ?Reservation
    {
        return $this->queue[0] ?? null;
    }

    public function isEmpty(): bool
    {
        return empty($this->queue);
    }

    public function getAll(): array
    {
        return $this->queue;
    }

    public function removeReservation(int $reservationId): void
    {
        $this->queue = array_values(array_filter(
            $this->queue,
            fn($reservation) =>
                $reservation->getReservationId() !== $reservationId
        ));
    }

    public function clear(): void
    {
        $this->queue = [];
    }
}