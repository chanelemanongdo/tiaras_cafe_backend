<?php

namespace App\Models;

class ReservationHashTable
{
    private array $buckets = [];
    private int $capacity;

    public function __construct(int $capacity = 10)
    {
        $this->capacity = $capacity;

        for ($i = 0; $i < $capacity; $i++) {
            $this->buckets[$i] = [];
        }
    }

    private function hashFunction(int $key): int
    {
        return $key % $this->capacity;
    }

    public function insert(Reservation $reservation): void
    {
        $key = $reservation->getReservationId();

        if ($key === null) {
            return;
        }

        $index = $this->hashFunction($key);

        foreach ($this->buckets[$index] as $position => $entry) {
            if ($entry['key'] === $key) {
                $this->buckets[$index][$position]['value'] = $reservation;
                return;
            }
        }

        $this->buckets[$index][] = [
            'key' => $key,
            'value' => $reservation
        ];
    }

    public function search(int $key): ?Reservation
    {
        $index = $this->hashFunction($key);

        foreach ($this->buckets[$index] as $entry) {
            if ($entry['key'] === $key) {
                return $entry['value'];
            }
        }

        return null;
    }
}
