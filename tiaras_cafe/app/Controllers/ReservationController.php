<?php

namespace App\Controllers;

use DateTime;
use App\Models\Reservation;
use App\Models\ReservationQueue;
use App\Repositories\ReservationRepository;

class ReservationController
{
    private array $reservations = [];
    private ReservationQueue $pendingQueue;

    public function __construct()
    {
        $this->pendingQueue = new ReservationQueue();
    }

    public function addReservation(Reservation $reservation): bool
    {
        if (
            !$this->isValidEventDate($reservation->getEventDate()) ||
            !$this->isValidEventTime(
                $reservation->getStartTime(),
                $reservation->getEndTime()
            )
        ) {
            return false;
        }

        foreach ($this->reservations as $existingReservation) {
            if (
                $reservation->getReservationId() !== null &&
                $existingReservation->getReservationId() ===
                    $reservation->getReservationId()
            ) {
                return false;
            }
        }

        $this->reservations[] = $reservation;

        if ($reservation->getStatus() === "Pending") {
            $this->pendingQueue->enqueue($reservation);
        }

        return true;
    }

    public function loadReservationsFromDatabase(
        ReservationRepository $repository
    ): void {
        $this->reservations = $repository->getAllReservations();
        $this->pendingQueue->clear();

        foreach ($this->reservations as $reservation) {
            if ($reservation->getStatus() === "Pending") {
                $this->pendingQueue->enqueue($reservation);
            }
        }
    }

    public function getAllReservations(): array
    {
        return $this->reservations;
    }

    public function getPendingReservations(): array
    {
        return $this->pendingQueue->getAll();
    }

    public function processNextPendingReservation(): ?Reservation
    {
        return $this->pendingQueue->dequeue();
    }

    public function isValidEventDate(string $eventDate): bool
    {
        $date = DateTime::createFromFormat('!Y-m-d', $eventDate);
        $errors = DateTime::getLastErrors();

        if ($date === false) {
            return false;
        }

        if (
            $errors !== false &&
            ($errors['warning_count'] > 0 || $errors['error_count'] > 0)
        ) {
            return false;
        }

        return $date->format('Y-m-d') === $eventDate &&
            $eventDate > date('Y-m-d');
    }

    public function isValidEventTime(
        string $startTime,
        string $endTime
    ): bool {
        $timePattern = '/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/';

        if (
            !preg_match($timePattern, $startTime) ||
            !preg_match($timePattern, $endTime)
        ) {
            return false;
        }

        return strtotime($endTime) > strtotime($startTime);
    }

    private function syncReservationQueue(Reservation $reservation): void
    {
        $reservationId = $reservation->getReservationId();

        if ($reservationId !== null) {
            $this->pendingQueue->removeReservation($reservationId);
        }

        if ($reservation->getStatus() === "Pending") {
            $this->pendingQueue->enqueue($reservation);
        }
    }

    public function updateReservationStatus(
        int $index,
        string $status
    ): void {
        $allowedTransitions = [
            "Pending" => ["Confirmed", "Rejected", "Cancelled"],
            "Confirmed" => ["Cancelled"],
            "Rejected" => [],
            "Cancelled" => []
        ];

        if (!isset($this->reservations[$index])) {
            return;
        }

        $reservation = $this->reservations[$index];
        $currentStatus = $reservation->getStatus();

        if (
            !isset($allowedTransitions[$currentStatus]) ||
            !in_array($status, $allowedTransitions[$currentStatus], true)
        ) {
            return;
        }

        $reservation->setStatus($status);

        $reservation->setCancelledAt(
            $status === "Cancelled" ? date('Y-m-d H:i:s') : null
        );

        $this->syncReservationQueue($reservation);
    }

    public function saveReservation(
        Reservation $reservation,
        ReservationRepository $repository
    ): bool {
        if (
            $reservation->getStatus() !== "Pending" ||
            !$this->isValidEventDate($reservation->getEventDate()) ||
            !$this->isValidEventTime(
                $reservation->getStartTime(),
                $reservation->getEndTime()
            )
        ) {
            return false;
        }

        if (!$repository->addReservation($reservation)) {
            return false;
        }

        $this->reservations[] = $reservation;
        $this->pendingQueue->enqueue($reservation);

        return true;
    }

    public function updateReservationStatusInDatabase(
        int $reservationId,
        string $status,
        ReservationRepository $repository
    ): bool {
        $reservationToUpdate = null;

        foreach ($this->reservations as $reservation) {
            if ($reservation->getReservationId() === $reservationId) {
                $reservationToUpdate = $reservation;
                break;
            }
        }

        if ($reservationToUpdate === null) {
            return false;
        }

        if (
            !$repository->updateReservationStatus(
                $reservationId,
                $status
            )
        ) {
            return false;
        }

        $reservationToUpdate->setStatus($status);

        $reservationToUpdate->setCancelledAt(
            $status === "Cancelled" ? date('Y-m-d H:i:s') : null
        );

        $this->syncReservationQueue($reservationToUpdate);

        return true;
    }
}