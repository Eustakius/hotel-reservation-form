<?php
// Start session
session_start();

// Define DB access constant
define('DB_ACCESS', true);

// Include configuration
require_once 'config.php';

// Initialize errors array
$errors = array();

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize and validate inputs
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $telp = isset($_POST['telp']) ? trim($_POST['telp']) : '';
    $reservator_name = isset($_POST['reservator_name']) ? trim($_POST['reservator_name']) : '';
    $booking_date = isset($_POST['booking_date']) ? trim($_POST['booking_date']) : '';
    $room_type = isset($_POST['room_type']) ? trim($_POST['room_type']) : '';
    $bed_type = isset($_POST['bed_type']) ? trim($_POST['bed_type']) : '';
    $breakfast = isset($_POST['breakfast']) ? trim($_POST['breakfast']) : '';
    $checkin_date = isset($_POST['checkin_date']) ? trim($_POST['checkin_date']) : '';
    $checkout_date = isset($_POST['checkout_date']) ? trim($_POST['checkout_date']) : '';
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';
    
    // Validation rules
    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (strlen($name) < 3) {
        $errors[] = "Name must be at least 3 characters";
    } elseif (strlen($name) > 100) {
        $errors[] = "Name must not exceed 100 characters";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Name must contain only letters and spaces";
    }
    
    if (empty($gender) || !in_array($gender, array('Male', 'Female'))) {
        $errors[] = "Please select a valid gender";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } elseif (strlen($email) > 100) {
        $errors[] = "Email must not exceed 100 characters";
    }
    
    if (empty($telp)) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match("/^[0-9+\-\s()]+$/", $telp)) {
        $errors[] = "Invalid phone number format. Use only numbers, +, -, (), and spaces";
    } elseif (strlen($telp) < 8) {
        $errors[] = "Phone number is too short";
    } elseif (strlen($telp) > 20) {
        $errors[] = "Phone number is too long";
    }
    
    if (empty($reservator_name)) {
        $errors[] = "Reservator name is required";
    } elseif (strlen($reservator_name) < 3) {
        $errors[] = "Reservator name must be at least 3 characters";
    } elseif (strlen($reservator_name) > 100) {
        $errors[] = "Reservator name must not exceed 100 characters";
    }
    
    if (empty($booking_date)) {
        $errors[] = "Booking date is required";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $booking_date)) {
        $errors[] = "Booking date must be in format YYYY-MM-DD";
    } else {
        $date_parts = explode('-', $booking_date);
        if (!checkdate($date_parts[1], $date_parts[2], $date_parts[0])) {
            $errors[] = "Booking date is not a valid date";
        }
    }
    
    if (empty($room_type) || !in_array($room_type, array('Deluxe', 'Suite', 'Standard'))) {
        $errors[] = "Please select a valid room type";
    }
    
    if (empty($bed_type) || !in_array($bed_type, array('Single', 'Double'))) {
        $errors[] = "Please select a valid bed type";
    }
    
    if (empty($breakfast) || !in_array($breakfast, array('With', 'Without'))) {
        $errors[] = "Please select breakfast option";
    }
    
    if (empty($checkin_date)) {
        $errors[] = "Check-in date is required";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $checkin_date)) {
        $errors[] = "Check-in date must be in format YYYY-MM-DD";
    } else {
        $date_parts = explode('-', $checkin_date);
        if (!checkdate($date_parts[1], $date_parts[2], $date_parts[0])) {
            $errors[] = "Check-in date is not a valid date";
        }
    }
    
    if (empty($checkout_date)) {
        $errors[] = "Check-out date is required";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $checkout_date)) {
        $errors[] = "Check-out date must be in format YYYY-MM-DD";
    } else {
        $date_parts = explode('-', $checkout_date);
        if (!checkdate($date_parts[1], $date_parts[2], $date_parts[0])) {
            $errors[] = "Check-out date is not a valid date";
        }
    }
    
    // Validate date logic
    if (empty($errors)) {
        $checkin = strtotime($checkin_date);
        $checkout = strtotime($checkout_date);
        $today = strtotime(date('Y-m-d'));
        
        if ($checkin < $today) {
            $errors[] = "Check-in date cannot be in the past";
        }
        
        if ($checkout <= $checkin) {
            $errors[] = "Check-out date must be after check-in date";
        }
        
        $days_diff = ($checkout - $checkin) / (60 * 60 * 24);
        if ($days_diff > 30) {
            $errors[] = "Maximum stay duration is 30 days";
        }
    }
    
    if (empty($payment_method) || !in_array($payment_method, array('Cash', 'Credit Card', 'Transfer'))) {
        $errors[] = "Please select a valid payment method";
    }
    
    // If there are errors, display error page
    if (!empty($errors)) {
        displayErrorPage($errors);
        exit();
    }
    
    // Prepare SQL statement to prevent SQL injection
    $sql = "INSERT INTO reservations (name, gender, email, telp, reservator_name, booking_date, room_type, bed_type, breakfast, checkin_date, checkout_date, payment_method) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement
        mysqli_stmt_bind_param($stmt, "ssssssssssss", 
            $name, $gender, $email, $telp, $reservator_name, 
            $booking_date, $room_type, $bed_type, $breakfast, 
            $checkin_date, $checkout_date, $payment_method);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $reservation_id = mysqli_insert_id($conn);
            
            // Store data for success page
            $_SESSION['reservation_id'] = $reservation_id;
            $_SESSION['reservation_data'] = array(
                'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
                'room_type' => htmlspecialchars($room_type, ENT_QUOTES, 'UTF-8'),
                'bed_type' => htmlspecialchars($bed_type, ENT_QUOTES, 'UTF-8'),
                'breakfast' => htmlspecialchars($breakfast, ENT_QUOTES, 'UTF-8'),
                'checkin_date' => $checkin_date,
                'checkout_date' => $checkout_date,
                'payment_method' => htmlspecialchars($payment_method, ENT_QUOTES, 'UTF-8')
            );
            
            // Close statement
            mysqli_stmt_close($stmt);
            
            // Redirect to success page
            header("Location: success.php");
            exit();
        } else {
            mysqli_stmt_close($stmt);
            displayErrorPage(array("Database error: Could not complete reservation. Please try again later."));
            exit();
        }
    } else {
        displayErrorPage(array("Database error: Could not prepare statement. Please contact administrator."));
        exit();
    }
} else {
    // If accessed directly without POST, redirect to form
    header("Location: reservasi_hotel.xhtml");
    exit();
}

// Function to display error page
function displayErrorPage($errors) {
    header('Content-Type: application/xhtml+xml; charset=UTF-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Reservation Error - Eustakius Satu Rajawali Ku (220711648)</title>
    <link rel="stylesheet" type="text/css" href="styles/style.css" />
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reservation Error</h1>
        </div>
        <div class="alert alert-error">
            <h2>Please correct the following errors:</h2>
            <ul>
                <?php foreach($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="form-actions">
            <a href="reservasi_hotel.xhtml" class="btn-submit">Back to Form</a>
        </div>
        <div class="footer">
            <p>Eustakius Satu Rajawali Ku - NPM: 220711648</p>
        </div>
    </div>
</body>
</html>
<?php
}
?>
