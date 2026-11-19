<?php

namespace App\Repositories;

use App\Models\Reservation;
use PDO;

class ReservationRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllReservations(): array
    {
        $sql = "
            SELECT
                r.*,
                p.pakage_name AS package_name,
                s.service_name AS service_name
            FROM reservation r
            INNER JOIN package p
                ON r.package_id = p.pakage_id
            INNER JOIN service s
                ON r.service_id = s.service_id
            ORDER BY r.event_date, r.start_time
        ";

        $statement = $this->connection->query($sql);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $reservations = [];

        foreach ($rows as $row) {
            $reservations[] = new Reservation(
                $row['full_name'],
                $row['contact_number'],
                $row['booking_location'],
                (int) $row['package_id'],
                (int) $row['service_id'],
                $row['event_date'],
                $row['start_time'],
                $row['end_time'],
                $row['status'],
                (int) $row['reservation_id'],
                $row['created_at'],
                $row['cancelled_at'],
                $row['address'],
                $row['package_name'],
                $row['service_name']
            );
        }

        return $reservations;
    }

    public function addReservation(Reservation $reservation): bool
    {
        if ($reservation->getStatus() !== "Pending") {
            return false;
        }

        $sql = "
            INSERT INTO reservation (
                full_name,
                address,
                contact_number,
                booking_location,
                package_id,
                service_id,
                event_date,
                start_time,
                end_time,
                status
            )
            VALUES (
                :full_name,
                :address,
                :contact_number,
                :booking_location,
                :package_id,
                :service_id,
                :event_date,
                :start_time,
                :end_time,
                'Pending'
            )
        ";

        $statement = $this->connection->prepare($sql);

        $success = $statement->execute([
            ':full_name' => $reservation->getFullName(),
            ':address' => $reservation->getAddress(),
            ':contact_number' => $reservation->getContactNumber(),
            ':booking_location' => $reservation->getEventLocation(),
            ':package_id' => $reservation->getPackageId(),
            ':service_id' => $reservation->getServiceId(),
            ':event_date' => $reservation->getEventDate(),
            ':start_time' => $reservation->getStartTime(),
            ':end_time' => $reservation->getEndTime()
        ]);

        if ($success) {
            $reservation->setReservationId(
                (int) $this->connection->lastInsertId()
            );
        }

        return $success;
    }

    public function updateReservationStatus(
        int $reservationId,
        string $status
    ): bool {
        $allowedTransitions = [
            "Pending" => ["Confirmed", "Rejected", "Cancelled"],
            "Confirmed" => ["Cancelled"],
            "Rejected" => [],
            "Cancelled" => []
        ];

        $statement = $this->connection->prepare(
            "SELECT status FROM reservation WHERE reservation_id = :id"
        );

        $statement->execute([':id' => $reservationId]);

        $currentStatus = $statement->fetchColumn();

        if (
            $currentStatus === false ||
            !isset($allowedTransitions[$currentStatus]) ||
            !in_array($status, $allowedTransitions[$currentStatus], true)
        ) {
            return false;
        }

        $sql = "
            UPDATE reservation
            SET
                status = :new_status,
                cancelled_at = CASE
                    WHEN :cancelled_status = 'Cancelled' THEN NOW()
                    ELSE NULL
                END
            WHERE reservation_id = :id
            AND status = :current_status
        ";

        $update = $this->connection->prepare($sql);

        $update->execute([
            ':new_status' => $status,
            ':cancelled_status' => $status,
            ':id' => $reservationId,
            ':current_status' => $currentStatus
        ]);

        return $update->rowCount() === 1;
    }
}