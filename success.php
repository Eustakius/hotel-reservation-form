<?php
session_start();

// Check if reservation data exists
if (!isset($_SESSION['reservation_id'])) {
    header("Location: reservasi_hotel.xhtml");
    exit();
}

$reservation_id = $_SESSION['reservation_id'];
$data = $_SESSION['reservation_data'];

// Clear session after retrieving data
unset($_SESSION['reservation_id']);
unset($_SESSION['reservation_data']);

// Set proper content type for XHTML
header('Content-Type: application/xhtml+xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Reservation Successful - Eustakius Satu Rajawali Ku (220711648)</title>
    <link rel="stylesheet" type="text/css" href="styles/style.css" />
    <style type="text/css">
        .success-icon {
            font-size: 80px;
            color: #28a745;
            text-align: center;
            margin: 20px 0;
        }
        .reservation-details {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 30px;
        }
        .reservation-details h3 {
            margin-top: 0;
            color: #333;
        }
        .detail-row {
            display: block;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #555;
            display: inline-block;
            width: 40%;
        }
        .detail-value {
            color: #333;
            display: inline-block;
            width: 58%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reservation Successful!</h1>
            <p class="subtitle">Your booking has been confirmed</p>
        </div>
        
        <div class="success-icon">✓</div>
        
        <div class="alert alert-success">
            <h2>Thank you for your reservation!</h2>
            <p>Your booking ID is: <strong>#<?php echo htmlspecialchars($reservation_id, ENT_QUOTES, 'UTF-8'); ?></strong></p>
            <p>A confirmation email will be sent to: <strong><?php echo $data['email']; ?></strong></p>
        </div>
        
        <div class="reservation-details">
            <h3>Reservation Details</h3>
            <div class="detail-row">
                <span class="detail-label">Guest Name:</span>
                <span class="detail-value"><?php echo $data['name']; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Room Type:</span>
                <span class="detail-value"><?php echo $data['room_type']; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Bed Type:</span>
                <span class="detail-value"><?php echo $data['bed_type']; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Breakfast:</span>
                <span class="detail-value"><?php echo $data['breakfast']; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Check-in Date:</span>
                <span class="detail-value"><?php echo date('d F Y', strtotime($data['checkin_date'])); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Check-out Date:</span>
                <span class="detail-value"><?php echo date('d F Y', strtotime($data['checkout_date'])); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span class="detail-value"><?php echo $data['payment_method']; ?></span>
            </div>
        </div>
        
        <div class="form-actions">
            <a href="reservasi_hotel.xhtml" class="btn-submit">Make Another Reservation</a>
        </div>
        
        <div class="footer">
            <p>Eustakius Satu Rajawali Ku - NPM: 220711648</p>
        </div>
    </div>
</body>
</html>
