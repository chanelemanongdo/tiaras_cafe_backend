<?php
$pageTitle = "Reservations";
require_once __DIR__ . '/../includes/header.php';
?>

<section class="reservation-page">

    <div class="reservation-heading">
        <span>CELEBRATE WITH TIARA'S CAFÉ</span>
        <h1>Event Reservations</h1>
        <p>
            Make your special occasions memorable with Tiara's Café.
            We offer coffee bar services for different events.
        </p>
    </div>

    <div class="reservation-section">
        <h2>Our Services</h2>

        <div class="service-list">
            <div>Weddings</div>
            <div>Anniversaries</div>
            <div>Birthdays and Debuts</div>
            <div>School Events</div>
            <div>Office Events</div>
        </div>
    </div>

    <div class="reservation-section">
        <h2>Our Packages</h2>

        <div class="package-list">

            <div class="package">
                <span>PACKAGE 1</span>
                <h3>50 Cups</h3>
                <p class="package-price">₱6,500</p>
            </div>

            <div class="package">
                <span>PACKAGE 2</span>
                <h3>100 Cups</h3>
                <p class="package-price">₱10,500</p>
            </div>

            <div class="package">
                <span>PACKAGE 3</span>
                <h3>150 Cups</h3>
                <p class="package-price">₱14,500</p>
            </div>

        </div>
    </div>

    <div class="reservation-section">
        <h2>Package Inclusions</h2>

        <ul class="reservation-list">
            <li>Full coffee bar setup</li>
            <li>Service within 4 hours</li>
            <li>5 coffee drink options</li>
            <li>2 non-coffee drink options</li>
            <li>3 Italian soda options</li>
            <li>All drinks are iced and served in 16oz cups</li>
            <li>2 to 3 baristas</li>
        </ul>
    </div>

    <div class="reservation-section">
        <h2>Terms and Conditions</h2>

        <ul class="reservation-list">
            <li>50% down payment is required to reserve a date or slot.</li>
            <li>The down payment is non-refundable.</li>
            <li>Transportation charges may vary depending on the event location.</li>
        </ul>
    </div>

    <div class="reservation-form-section">
        <h2>Reservation Request</h2>
        <p>
            Fill out the form below to submit a reservation request.
            Your request will be subject to confirmation by the café.
        </p>

        <form method="POST" action="reservation.php" class="reservation-form">

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="tel" id="contact_number" name="contact_number" required>
            </div>

            <div class="form-group">
                <label for="service_id">Event Type</label>
                <select id="service_id" name="service_id" required>
                    <option value="">Select Event Type</option>
                    <option value="1">Wedding</option>
                    <option value="2">Anniversary</option>
                    <option value="3">Birthday/Debut</option>
                    <option value="4">School Event</option>
                    <option value="5">Office Event</option>
                </select>
            </div>

            <div class="form-group">
                <label for="package_id">Choose a Package</label>
                <select id="package_id" name="package_id" required>
                    <option value="">Select a Package</option>
                    <option value="1">Package 1 - 50 Cups - ₱6,500</option>
                    <option value="2">Package 2 - 100 Cups - ₱10,500</option>
                    <option value="3">Package 3 - 150 Cups - ₱14,500</option>
                </select>
            </div>

            <div class="form-group">
                <label for="event_location">Event Location</label>
                <input type="text" id="event_location" name="event_location" required>
            </div>

            <div class="form-group">
                <label for="event_date">Event Date</label>
                <input type="date" id="event_date" name="event_date" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input type="time" id="start_time" name="start_time" required>
                </div>

                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input type="time" id="end_time" name="end_time" required>
                </div>
            </div>

            <button type="submit" class="btn">Submit Reservation Request</button>

        </form>
    </div>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
