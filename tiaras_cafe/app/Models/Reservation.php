<?php

namespace App\Models;

class Reservation
{
    private ?int $reservationId;
    private string $fullName;
    private string $address;
    private string $contactNumber;
    private string $eventLocation;
    private int $packageId;
    private int $serviceId;
    private string $packageName;
    private string $serviceName;
    private string $eventDate;
    private string $startTime;
    private string $endTime;
    private string $status;
    private ?string $createdAt;
    private ?string $cancelledAt;

    public function __construct(
        string $fullName,
        string $contactNumber,
        string $eventLocation,
        int $packageId,
        int $serviceId,
        string $eventDate,
        string $startTime,
        string $endTime,
        string $status = "Pending",
        ?int $reservationId = null,
        ?string $createdAt = null,
        ?string $cancelledAt = null,
        string $address = "",
        string $packageName = "",
        string $serviceName = ""
    ) {
        $this->reservationId = $reservationId;
        $this->fullName = $fullName;
        $this->address = $address;
        $this->contactNumber = $contactNumber;
        $this->eventLocation = $eventLocation;
        $this->packageId = $packageId;
        $this->serviceId = $serviceId;
        $this->packageName = $packageName;
        $this->serviceName = $serviceName;
        $this->eventDate = $eventDate;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->cancelledAt = $cancelledAt;
    }

    public function getReservationId(): ?int { return $this->reservationId; }
    public function getFullName(): string { return $this->fullName; }
    public function getAddress(): string { return $this->address; }
    public function getContactNumber(): string { return $this->contactNumber; }
    public function getEventLocation(): string { return $this->eventLocation; }
    public function getPackageId(): int { return $this->packageId; }
    public function getServiceId(): int { return $this->serviceId; }
    public function getPackageName(): string { return $this->packageName; }
    public function getServiceName(): string { return $this->serviceName; }
    public function getEventDate(): string { return $this->eventDate; }
    public function getStartTime(): string { return $this->startTime; }
    public function getEndTime(): string { return $this->endTime; }
    public function getStatus(): string { return $this->status; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function getCancelledAt(): ?string { return $this->cancelledAt; }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setReservationId(int $reservationId): void
    {
        $this->reservationId = $reservationId;
    }

    public function setCancelledAt(?string $cancelledAt): void
    {
        $this->cancelledAt = $cancelledAt;
    }
}
