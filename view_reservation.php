<?php
define('DB_ACCESS', true);
require_once 'config.php';

$sql = "SELECT * FROM reservations ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

header('Content-Type: application/xhtml+xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>View Reservations - Eustakius Satu Rajawali Ku (220711648)</title>
    <link rel="stylesheet" type="text/css" href="styles/style.css" />
    <style type="text/css">
        table { 
            width: 95%; 
            border-collapse: collapse; 
            margin: 20px auto;
            background: white;
        }
        th, td { 
            padding: 12px; 
            text-align: left; 
            border: 1px solid #ddd; 
        }
        th { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) { 
            background: #f9f9f9; 
        }
        tr:hover { 
            background: #f0f0f0; 
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>All Reservations</h1>
            <p class="subtitle">View all hotel bookings</p>
        </div>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Room Type</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['room_type'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['checkin_date'])); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['checkout_date'])); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="no-data">
            <p><strong>No reservations found</strong></p>
            <p>There are currently no bookings in the system.</p>
        </div>
        <?php endif; ?>
        
        <div class="form-actions">
            <a href="reservasi_hotel.xhtml" class="btn-submit">Back to Form</a>
        </div>
        
        <div class="footer">
            <p>Eustakius Satu Rajawali Ku - NPM: 220711648</p>
        </div>
    </div>
</body>
</html>
