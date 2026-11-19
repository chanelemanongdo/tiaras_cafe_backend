<?php

require_once dirname(__DIR__, 2) . '/includes/admin_auth.php';

use App\Database\Database;
use App\Controllers\ReservationController;
use App\Repositories\ReservationRepository;
use App\Models\ReservationHashTable;

$pageTitle = "Reservation Management";

if (empty($_SESSION['reservation_csrf_token'])) {
    $_SESSION['reservation_csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['reservation_csrf_token'];

$database = new Database();
$connection = $database->connect();

if ($connection === null) {
    die("Unable to connect to the database.");
}

$repository = new ReservationRepository($connection);
$controller = new ReservationController();

$controller->loadReservationsFromDatabase($repository);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (
        !is_string($submittedToken) ||
        !hash_equals($csrfToken, $submittedToken)
    ) {
        http_response_code(403);
        exit('Invalid form submission.');
    }
}

$reservations = $controller->getAllReservations();

$hashTable = new ReservationHashTable();

foreach ($reservations as $reservation) {
    $hashTable->insert($reservation);
}

$message = "";
$searchResult = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_reservation'])) {

    $searchId = filter_var(
        $_POST['search_id'] ?? null,
        FILTER_VALIDATE_INT
    );

    if ($searchId !== false && $searchId !== null && $searchId > 0) {
        $searchResult = $hashTable->search($searchId);

        if ($searchResult === null) {
            $message = "No reservation found with that ID.";
        }
    } else {
        $message = "Please enter a valid reservation ID.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {

    $reservationId = filter_var(
        $_POST['reservation_id'] ?? null,
        FILTER_VALIDATE_INT
    );

    $newStatus = $_POST['new_status'] ?? '';

    $allowedStatuses = ["Confirmed", "Rejected", "Cancelled"];

    if (
        $reservationId !== false &&
        $reservationId !== null &&
        $reservationId > 0 &&
        in_array($newStatus, $allowedStatuses, true)
    ) {
        if (
            $controller->updateReservationStatusInDatabase(
                $reservationId,
                $newStatus,
                $repository
            )
        ) {
            $message = "Reservation status updated successfully.";
        } else {
            $message = "Unable to update reservation status.";
        }
    } else {
        $message = "Invalid reservation information.";
    }

    $controller->loadReservationsFromDatabase($repository);

    $reservations = $controller->getAllReservations();

    $hashTable = new ReservationHashTable();

    foreach ($reservations as $reservation) {
        $hashTable->insert($reservation);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle) ?> | Tiara's Café</title>

    <link rel="stylesheet" href="/tiaras_cafe/public/CSS/style.css">
</head>

<body>

    <header>
        <h1>Tiara's Café</h1>
        <h2>Reservation Management</h2>
    </header>

    <main class="reservation-management">

        <a href="dashboard.php">Back to Dashboard</a>

        <h2>Customer Reservation Requests</h2>

        <p>Review and manage submitted event reservation requests.</p>

        <h3>Search Reservation</h3>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <label for="search_id">Reservation ID:</label>
            <input
                type="number"
                id="search_id"
                name="search_id"
                min="1"
                required
            >
            <button type="submit" name="search_reservation">
                Search
            </button>
        </form>

        <?php if ($message !== ""): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if ($searchResult !== null): ?>

            <h3>Reservation Search Result</h3>

            <table>
                <tr>
                    <th>Reservation ID</th>
                    <td><?= (int) $searchResult->getReservationId() ?></td>
                </tr>
                <tr>
                    <th>Customer Name</th>
                    <td><?= htmlspecialchars($searchResult->getFullName()) ?></td>
                </tr>
                <tr>
                    <th>Contact Number</th>
                    <td><?= htmlspecialchars($searchResult->getContactNumber()) ?></td>
                </tr>
                <tr>
                    <th>Service</th>
                    <td><?= htmlspecialchars($searchResult->getServiceName()) ?></td>
                </tr>
                <tr>
                    <th>Package</th>
                    <td><?= htmlspecialchars($searchResult->getPackageName()) ?></td>
                </tr>
                <tr>
                    <th>Event Location</th>
                    <td><?= htmlspecialchars($searchResult->getEventLocation()) ?></td>
                </tr>
                <tr>
                    <th>Event Date</th>
                    <td><?= htmlspecialchars($searchResult->getEventDate()) ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?= htmlspecialchars($searchResult->getStatus()) ?></td>
                </tr>
            </table>

        <?php endif; ?>

        <h3>All Reservation Requests</h3>

        <table>

            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Customer Name</th>
                    <th>Contact Number</th>
                    <th>Service</th>
                    <th>Package</th>
                    <th>Event Location</th>
                    <th>Event Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php if (empty($reservations)): ?>

                    <tr>
                        <td colspan="11">
                            No reservation requests to display yet.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($reservations as $reservation): ?>

                        <tr>

                            <td><?= (int) $reservation->getReservationId() ?></td>

                            <td><?= htmlspecialchars($reservation->getFullName()) ?></td>

                            <td><?= htmlspecialchars($reservation->getContactNumber()) ?></td>

                            <td><?= htmlspecialchars($reservation->getServiceName()) ?></td>

                            <td><?= htmlspecialchars($reservation->getPackageName()) ?></td>

                            <td><?= htmlspecialchars($reservation->getEventLocation()) ?></td>

                            <td>
                                <?= htmlspecialchars(
                                    date('M d, Y', strtotime($reservation->getEventDate()))
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    date('h:i A', strtotime($reservation->getStartTime()))
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    date('h:i A', strtotime($reservation->getEndTime()))
                                ) ?>
                            </td>

                            <td><?= htmlspecialchars($reservation->getStatus()) ?></td>

                            <td>

                                <?php if ($reservation->getStatus() === "Pending"): ?>

                                    <form method="POST">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="reservation_id"
                                               value="<?= (int) $reservation->getReservationId() ?>">
                                        <input type="hidden" name="new_status" value="Confirmed">
                                        <button type="submit" name="update_status">Confirm</button>
                                    </form>

                                    <form method="POST">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="reservation_id"
                                               value="<?= (int) $reservation->getReservationId() ?>">
                                        <input type="hidden" name="new_status" value="Rejected">
                                        <button type="submit" name="update_status">Reject</button>
                                    </form>

                                    <form method="POST">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="reservation_id"
                                               value="<?= (int) $reservation->getReservationId() ?>">
                                        <input type="hidden" name="new_status" value="Cancelled">
                                        <button type="submit" name="update_status">Cancel</button>
                                    </form>

                                <?php elseif ($reservation->getStatus() === "Confirmed"): ?>

                                    <form method="POST"
                                          onsubmit="return confirm('Cancel this confirmed reservation?');">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="reservation_id"
                                               value="<?= (int) $reservation->getReservationId() ?>">
                                        <input type="hidden" name="new_status" value="Cancelled">
                                        <button type="submit" name="update_status">Cancel</button>
                                    </form>

                                <?php else: ?>

                                    No actions available.

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </main>

</body>

</html>
