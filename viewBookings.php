<?php
include_once("config.php");

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$selectedBookingId = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : null;
$selectedDate = isset($_GET['date']) ? $_GET['date'] : null;

$currentMonth = isset($_GET['month']) ? intval($_GET['month']) : date("m");
$currentYear = isset($_GET['year']) ? intval($_GET['year']) : date("Y");

if ($currentMonth < 1 || $currentMonth > 12) {
    $currentMonth = date("m");
}
if ($currentYear < 1900 || $currentYear > 2100) {
    $currentYear = date("Y");
}

$firstDayOfMonth = strtotime("$currentYear-$currentMonth-01");
$daysInMonth = date("t", $firstDayOfMonth);
$startDay = date("w", $firstDayOfMonth);

$searchResults = [];
if (!empty($searchTerm)) {
    $sqlSearch = "SELECT id, firstlastname, email FROM bookings WHERE firstlastname LIKE :search ORDER BY firstlastname";
    $stmtSearch = $conn->prepare($sqlSearch);
    $stmtSearch->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
    $stmtSearch->execute();
    $searchResults = $stmtSearch->fetchAll(PDO::FETCH_ASSOC);
}

$sqlAllBookings = "SELECT id, checkin, checkout FROM bookings";
$stmtAllBookings = $conn->prepare($sqlAllBookings);
$stmtAllBookings->execute();
$allBookingsData = $stmtAllBookings->fetchAll(PDO::FETCH_ASSOC);

$bookingsByDay = [];
foreach ($allBookingsData as $booking) {
    $checkinTimestamp = strtotime($booking['checkin']);
    $checkoutTimestamp = strtotime($booking['checkout']);

    if ($checkinTimestamp !== false && $checkoutTimestamp !== false && $checkoutTimestamp >= $checkinTimestamp) {
        for ($currentTimestamp = $checkinTimestamp; $currentTimestamp <= $checkoutTimestamp; $currentTimestamp = strtotime("+1 day", $currentTimestamp)) {
            $dayKey = date('Y-m-d', $currentTimestamp);
            if (!isset($bookingsByDay[$dayKey])) {
                $bookingsByDay[$dayKey] = [];
            }
            $bookingsByDay[$dayKey][] = $booking['id'];
        }
    }
}

$selectedBooking = null;
if ($selectedBookingId) {
    $sqlBookingDetail = "SELECT * FROM bookings WHERE id = :id";
    $stmtBookingDetail = $conn->prepare($sqlBookingDetail);
    $stmtBookingDetail->bindParam(':id', $selectedBookingId, PDO::PARAM_INT);
    $stmtBookingDetail->execute();
    $selectedBooking = $stmtBookingDetail->fetch(PDO::FETCH_ASSOC);
}


$prevMonth = ($currentMonth == 1) ? 12 : $currentMonth - 1;
$prevYear = ($currentMonth == 1) ? $currentYear - 1 : $currentYear;
$nextMonth = ($currentMonth == 12) ? 1 : $currentMonth + 1;
$nextYear = ($currentMonth == 12) ? $currentYear + 1 : $currentYear;

$todayMonth = date("m");
$todayYear = date("Y");

function buildNavParams($excludeKeys = []) {
    $params = $_GET;
    foreach ($excludeKeys as $key) {
        unset($params[$key]);
    }
    if (in_array('clear_selection', $excludeKeys)) {
       unset($params['booking_id']);
       unset($params['date']);
    }

    if (!empty($params)) {
        return '&' . http_build_query($params);
    }
    return '';
}

$navParamsBase = buildNavParams(['month', 'year']);
$navParamsToday = buildNavParams(['month', 'year', 'clear_selection']);

$navParamsDayClickBase = buildNavParams(['date', 'booking_id']);
$navParamsSearchResult = buildNavParams(['booking_id']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Styles remain the same as previous version using Bootstrap grid */
        .calendar-navigation { margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .calendar-navigation h2 { margin: 0; font-size: 1.8rem; order: 2; flex-basis: 100%; text-align: center; }
        @media (min-width: 768px) { .calendar-navigation h2 { order: 0; flex-basis: auto; } }
        .calendar-navigation-left, .calendar-actions { display: flex; align-items: center; gap: 10px; }
        .calendar { display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; margin-top: 15px; }
        .calendar .day-header { padding: 8px; text-align: center; font-weight: bold; background-color: #f8f9fa; border: 1px solid #dee2e6; }
        .calendar .day { padding: 8px; text-align: right; border: 1px solid #dee2e6; cursor: pointer; position: relative; min-height: 80px; display: flex; flex-direction: column; justify-content: flex-start; font-size: 0.9rem; background-color: #fff; transition: background-color 0.2s ease-in-out; }
        .calendar .day:hover { background-color: #e9ecef; }
        .calendar .day.other-month { color: #adb5bd; background-color: #f8f9fa; cursor: default; }
        .calendar .day.other-month:hover { background-color: #f8f9fa; }
        .calendar .day strong { display: block; margin-bottom: 5px; font-size: 1rem; align-self: flex-end; }
        .has-bookings { background-color: #cfe2ff; font-weight: bold; }
        .calendar .day.selected-date { border: 2px solid #0d6efd; background-color: #e6f0ff; }
        .day-bookings { margin-top: 20px; padding: 15px; border: 1px solid #dee2e6; border-radius: 0.25rem; background-color: #f8f9fa; }
        .day-bookings h4 { margin-top: 0; margin-bottom: 12px; font-size: 1.4rem; }
        .day-bookings ul { list-style: none; padding: 0; margin: 0; }
        .day-bookings ul li { margin-bottom: 8px; font-size: 1rem; }
        .day-bookings ul li a { color: #0d6efd; text-decoration: none; display: block; padding: 5px 0; }
        .day-bookings ul li a:hover { text-decoration: underline; background-color: #e9ecef; }
        .btn { padding: 0.5rem 1rem; font-size: 1rem; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
        .today-button { background-color: #198754; color: white !important; }
        .today-button:hover { background-color: #157347; }
        .search-container { margin-bottom: 20px; }
        .search-input-group { display: flex; border: 1px solid #ced4da; border-radius: 0.25rem; overflow: hidden; }
        .search-input { flex-grow: 1; padding: 0.5rem 0.75rem; border: none; outline: none; font-size: 1rem; }
        .search-button { background-color: #0d6efd; color: white; border: none; padding: 0.5rem 1rem; cursor: pointer; font-size: 1rem; }
        .search-button:hover { background-color: #0b5ed7; }
        .search-results { list-style: none; padding: 0; margin-top: 10px; border: 1px solid #dee2e6; border-radius: 0.25rem; background-color: #fff; max-height: 200px; overflow-y: auto; }
        .search-results li { padding: 0.75rem 1rem; border-bottom: 1px solid #eee; cursor: pointer; font-size: 1rem; }
        .search-results li:last-child { border-bottom: none; }
        .search-results li:hover { background-color: #e9ecef; }
        .search-results li a { text-decoration: none; color: #212529; display: block; }
        .booking-detail { margin-top: 20px; padding: 20px; border: 1px solid #ccc; border-radius: 0.3rem; background-color: #f8f9fa; }
        .booking-detail h4 { font-size: 1.6rem; margin-top: 0; margin-bottom: 15px; color: #343a40; }
        .booking-detail p { font-size: 1.1rem; margin-bottom: 8px; line-height: 1.5; }
        .booking-detail strong { font-weight: 600; color: #495057; }
        .booking-detail .actions { margin-top: 15px; }
        .booking-detail .actions .btn { margin-right: 10px; }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                </ul>
        </div>
        <a href="logout.php" class="btn btn-danger d-flex align-items-center">
            Logout <i class="fa-solid fa-right-from-bracket ms-2"></i>
        </a>
    </div>
</nav>

    <div class="container-fluid">
        <div class="row">

            <?php include_once("sidebar.php"); ?>

            <div class="col-10">
                 <div class="container mt-3">

                    <div class="search-container">
                        <form method="GET" action="">
                            <input type="hidden" name="month" value="<?php echo $currentMonth; ?>">
                            <input type="hidden" name="year" value="<?php echo $currentYear; ?>">
                            <div class="search-input-group">
                                <input type="text" class="search-input" placeholder="Search by Name..."
                                       name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
                                <button class="search-button btn-sm" type="submit"><i class="fas fa-search"></i> Search</button>
                            </div>
                        </form>

                        <?php if (!empty($searchTerm) && !empty($searchResults)): ?>
                            <ul class="search-results mt-2">
                                 <p class="px-3 py-2 mb-0 bg-light border-bottom">Search Results:</p>
                                <?php foreach ($searchResults as $result): ?>
                                    <?php
                                        $searchResultUrl = "?month=" . $currentMonth
                                                        . "&year=" . $currentYear
                                                        . "&search=" . urlencode($searchTerm)
                                                        . "&booking_id=" . $result['id'];
                                    ?>
                                    <li>
                                        <a href="<?php echo htmlspecialchars($searchResultUrl); ?>">
                                            <?php echo htmlspecialchars($result['firstlastname']); ?>
                                            (<?php echo htmlspecialchars($result['email']); ?>)
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php elseif (!empty($searchTerm) && empty($searchResults)): ?>
                            <p class="alert alert-warning mt-2">No bookings found matching "<?php echo htmlspecialchars($searchTerm); ?>".</p>
                        <?php endif; ?>
                    </div>

                    <?php if ($selectedBookingId && $selectedBooking): ?>
                        <div class="booking-detail mb-4">
                            <h4><i class="fas fa-calendar-check"></i> Booking Details</h4>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($selectedBooking['firstlastname']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($selectedBooking['email']); ?></p>
                            <p><strong>Check-in:</strong> <?php echo date('l, F j, Y \a\t g:i A', strtotime($selectedBooking['checkin'])); ?></p>
                            <p><strong>Check-out:</strong> <?php echo date('l, F j, Y \a\t g:i A', strtotime($selectedBooking['checkout'])); ?></p>
                            <p><strong>Adults:</strong> <?php echo htmlspecialchars($selectedBooking['adults']); ?></p>
                            <p><strong>Kids:</strong> <?php echo htmlspecialchars($selectedBooking['kids']); ?></p>
                            <p><strong>Rooms:</strong> <?php echo htmlspecialchars($selectedBooking['rooms']); ?></p>
                            <p><strong>Special Requests:</strong> <?php echo nl2br(htmlspecialchars($selectedBooking['specialrequests'] ?: 'None')); ?></p>
                            <div class="actions">
                                <a href='editBookings.php?id=<?php echo $selectedBooking['id']; ?>' class='btn btn-success'><i class="fas fa-edit"></i> Edit</a>
                                <a href='deleteBooking.php?delete_id=<?php echo $selectedBooking['id']; ?>' class='btn btn-danger' onclick="return confirm;"><i class="fas fa-trash-alt"></i> Delete</a>
                            </div>
                        </div>
                     <?php elseif ($selectedBookingId && !$selectedBooking): ?>
                         <div class="alert alert-danger">Error: Booking details for ID <?php echo $selectedBookingId; ?> could not be found. It might have been deleted.</div>
                    <?php endif; ?>


                    <div class="calendar-navigation">
                        <div class="calendar-navigation-left">
                        <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?><?php echo $navParamsBase; ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-chevron-left"></i> Prev
                            </a>
                             <a href="addBookings.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Booking</a>
                        </div>
                        <h2><?php echo date("F Y", $firstDayOfMonth); ?></h2>
                        <div class="calendar-actions">
                            <a href="?month=<?php echo $todayMonth; ?>&year=<?php echo $todayYear; ?><?php echo $navParamsToday; ?>" class="btn today-button">Today</a>
                            <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?><?php echo $navParamsBase; ?>" class="btn btn-outline-secondary">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="calendar">
                        <?php
                        $daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                        foreach ($daysOfWeek as $dayName) {
                            echo "<div class='day-header'>$dayName</div>";
                        }

                        for ($i = 0; $i < $startDay; $i++) {
                            echo "<div class='day other-month'></div>";
                        }

                        for ($day = 1; $day <= $daysInMonth; $day++) {
                            $currentDate = date("Y-m-d", strtotime("$currentYear-$currentMonth-$day"));
                            $hasBookings = isset($bookingsByDay[$currentDate]) && !empty($bookingsByDay[$currentDate]);
                            $dayClasses = ['day'];
                            if ($hasBookings) {
                                $dayClasses[] = 'has-bookings';
                            }

                             $isCurrentlySelected = ($currentDate == $selectedDate);

                             if ($isCurrentlySelected) {
                                 $dayClasses[] = 'selected-date';
                             }

                             $baseDayLinkUrl = "?month=" . $currentMonth . "&year=" . $currentYear;
                             $baseDayLinkParams = $navParamsDayClickBase;

                             if ($isCurrentlySelected) {

                                 $dayClickUrl = $baseDayLinkUrl . $baseDayLinkParams;
                             } else {

                                 $dayClickUrl = $baseDayLinkUrl . "&date=" . $currentDate . $baseDayLinkParams;
                             }



                            echo "<div class='" . implode(' ', $dayClasses) . "' onclick=\"window.location.href='" . htmlspecialchars($dayClickUrl) . "'\">";
                            echo "<strong>$day</strong>";
                            if ($hasBookings) {
                                 echo "<span class='badge bg-info position-absolute bottom-0 start-0 m-1'>" . count($bookingsByDay[$currentDate]) . "</span>";
                            }
                            echo "</div>";
                        }
 

                        $endDay = ($startDay + $daysInMonth) % 7;
                        if ($endDay != 0) {
                           for ($i = $endDay; $i < 7; $i++) {
                               echo "<div class='day other-month'></div>";
                           }
                        }
                        ?>
                    </div>

                    <div id="daily-bookings" class="mt-4">
                        <?php

                        if ($selectedDate && !$selectedBookingId) {
                            echo "<div class='day-bookings'>";
                            echo "<h4><i class='fas fa-list-ul'></i> Bookings for " . date("F j, Y", strtotime($selectedDate)) . "</h4>";

                            if (isset($bookingsByDay[$selectedDate]) && !empty($bookingsByDay[$selectedDate])) {
                                echo "<ul>";
                                foreach ($bookingsByDay[$selectedDate] as $bookingId) {
                                    $sqlBookingListItem = "SELECT id, firstlastname, email FROM bookings WHERE id = :id";
                                    $stmtBookingListItem = $conn->prepare($sqlBookingListItem);
                                    $stmtBookingListItem->bindParam(':id', $bookingId, PDO::PARAM_INT);
                                    $stmtBookingListItem->execute();
                                    $bookingListItem = $stmtBookingListItem->fetch(PDO::FETCH_ASSOC);

                                    if ($bookingListItem) {

                                        $viewBookingUrl = "?month=" . $currentMonth
                                                        . "&year=" . $currentYear
                                                        . "&date=" . $selectedDate
                                                        . "&booking_id=" . $bookingListItem['id'];

                                        echo "<li>";
                                        echo "<a href='" . htmlspecialchars($viewBookingUrl) . "'>";
                                        echo "<i class='fas fa-user'></i> " . htmlspecialchars($bookingListItem['firstlastname']);
                                        echo " <span class='text-muted'>(" . htmlspecialchars($bookingListItem['email']) . ")</span>";
                                        echo "</a>";
                                        echo "</li>";
                                    }
                                }
                                echo "</ul>";
                            } else {
                                echo "<p><i class='fas fa-info-circle'></i> No bookings found for this day.</p>";
                            }
                            echo "</div>";
                        }
                        ?>
                    </div>

                 </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const currentPage = window.location.pathname.split("/").pop();
            const sidebarLinks = document.querySelectorAll('#sidebarMenu .nav-link');
            sidebarLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>