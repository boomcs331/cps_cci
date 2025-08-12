<?php
// QR Code Generator using phpqrcode library (ISO 18004 compliant)
require_once 'lib/phpqrcode_real/qrlib.php';

// Get QR code data from URL parameter
$qr_data = isset($_GET['data']) ? $_GET['data'] : '';

// Get optional parameters
$size = isset($_GET['size']) ? intval($_GET['size']) : 4;
$margin = isset($_GET['margin']) ? intval($_GET['margin']) : 4;
$level = isset($_GET['level']) ? $_GET['level'] : 'L';

// Validate parameters
if (empty($qr_data)) {
    $qr_data = 'No Data';
}

// Validate size (1-10 for lib phpqrcode)
if ($size < 1 || $size > 10) {
    $size = 4;
}

// Validate margin (0-10 for lib phpqrcode)
if ($margin < 0 || $margin > 10) {
    $margin = 4;
}

// Validate error correction level
if (!in_array($level, ['L', 'M', 'Q', 'H'])) {
    $level = 'L';
}

// Set content type to image
header('Content-Type: image/png');
header('Cache-Control: public, max-age=86400'); // Cache for 24 hours

// Generate QR code directly to output using ISO 18004 compliant library
try {
    // Convert level string to constant
    $level_constant = constant('QR_ECLEVEL_' . $level);
    
    // Generate QR code using the real phpqrcode library
    QRcode::png($qr_data, false, $level_constant, $size, $margin);
    
} catch (Exception $e) {
    // If error occurs, create a simple error image
    $width = $size * 25 + ($margin * 2);
    $height = $size * 25 + ($margin * 2);
    
    $image = imagecreate($width, $height);
    $white = imagecolorallocate($image, 255, 255, 255);
    $red = imagecolorallocate($image, 255, 0, 0);
    
    imagefill($image, 0, 0, $white);
    imagestring($image, 2, 10, $height/2 - 10, 'QR Error', $red);
    
    imagepng($image);
    imagedestroy($image);
}
exit;
?>
 