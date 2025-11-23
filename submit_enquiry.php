<?php
// submit_enquiry.php
// Handles enquiries from form.html and sends an email

// ------------- CONFIGURE THIS ------------- //
$toEmail = 'info@boomliftrentalschennai.com';   // where you want to receive enquiries
$fromEmail = 'no-reply@boomliftrentalschennai.com'; // MUST be a valid email on your domain
$subjectPrefix = 'Website Enquiry - Boomlift Rentals Chennai';
// ------------------------------------------ //

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

// Helper: trim + strip tags
function clean_input($value)
{
    return trim(filter_var($value, FILTER_SANITIZE_STRING));
}

$full_name = isset($_POST['full_name']) ? clean_input($_POST['full_name']) : '';
$email     = isset($_POST['email'])     ? trim($_POST['email']) : '';
$phone     = isset($_POST['phone'])     ? clean_input($_POST['phone']) : '';
$location  = isset($_POST['location'])  ? clean_input($_POST['location']) : '';
$equipment = isset($_POST['equipment']) ? clean_input($_POST['equipment']) : '';
// message field is commented out in your HTML, but we still support it if you enable later
$message   = isset($_POST['message'])   ? clean_input($_POST['message']) : '';

$errors = [];

// Basic validation
if ($full_name === '') {
    $errors[] = 'Full Name is required.';
}

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid Email Address is required.';
}

if ($phone === '') {
    $errors[] = 'Contact Number is required.';
}

if ($location === '') {
    $errors[] = 'Area / Location is required.';
}

if ($equipment === '') {
    $errors[] = 'Please select equipment.';
}

// Simple header injection protection
$emailSafe = str_replace(["\r", "\n"], '', $email);
$nameSafe  = str_replace(["\r", "\n"], '', $full_name);

if (!empty($errors)) {
    // Show a simple error page
?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Enquiry Error – Boomlift Rentals Chennai</title>
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <style>
            body {
                font-family: "Poppins", Arial, sans-serif;
                background: #0B192C;
                color: #ffffff;
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }

            .box {
                max-width: 460px;
                width: 100%;
                background: #111f33;
                border-radius: 12px;
                padding: 24px 20px;
                box-shadow: 0 18px 40px rgba(0, 0, 0, 0.7);
                text-align: center;
            }

            h1 {
                font-size: 22px;
                margin-bottom: 10px;
                color: #FF6500;
            }

            ul {
                margin: 10px 0 16px;
                padding-left: 18px;
                text-align: left;
                font-size: 14px;
            }

            li {
                margin-bottom: 4px;
            }

            a.btn {
                display: inline-block;
                padding: 10px 18px;
                border-radius: 999px;
                background: #FF6500;
                color: #fff;
                text-decoration: none;
                font-weight: 600;
                font-size: 14px;
                border: 2px solid #FF6500;
            }

            a.btn:hover {
                background: #ffffff;
                color: #FF6500;
            }
        </style>
    </head>

    <body>
        <div class="box">
            <h1>Check your enquiry details</h1>
            <p>Some required fields were missing or invalid:</p>
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
            <p>Please go back and correct the highlighted details.</p>
            <p>
                <a href="javascript:history.back()" class="btn">Go back to the form</a>
            </p>
        </div>
    </body>

    </html>
<?php
    exit;
}

// Build email
$subject = $subjectPrefix . ' from ' . $nameSafe;

$bodyLines = [
    "New enquiry from Boomlift Rentals Chennai website:",
    "",
    "Name:      " . $full_name,
    "Email:     " . $emailSafe,
    "Phone:     " . $phone,
    "Location:  " . $location,
    "Equipment: " . $equipment,
];

if ($message !== '') {
    $bodyLines[] = "";
    $bodyLines[] = "Additional details:";
    $bodyLines[] = $message;
}

$body = implode("\n", $bodyLines);

// Email headers
$headers  = 'From: ' . $nameSafe . ' <' . $fromEmail . ">\r\n";
$headers .= 'Reply-To: ' . $emailSafe . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send email
$mailSent = @mail($toEmail, $subject, $body, $headers);

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Enquiry Received – Boomlift Rentals Chennai</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <style>
        body {
            font-family: "Poppins", Arial, sans-serif;
            background: #0B192C;
            color: #ffffff;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .box {
            max-width: 460px;
            width: 100%;
            background: #111f33;
            border-radius: 12px;
            padding: 24px 20px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.7);
            text-align: center;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 10px;
            color: #FF6500;
        }

        p {
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        a.btn {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 999px;
            background: #FF6500;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            border: 2px solid #FF6500;
            margin-top: 8px;
        }

        a.btn:hover {
            background: #ffffff;
            color: #FF6500;
        }
    </style>
</head>

<body>
    <div class="box">
        <?php if ($mailSent): ?>
            <h1>Thank you for your enquiry</h1>
            <p>We’ve received your details for Chennai boom lift / scissor lift / man lift rentals.</p>
            <p>Our team will get back to you shortly with availability and pricing.</p>
        <?php else: ?>
            <h1>Enquiry saved, but email failed</h1>
            <p>Your enquiry was submitted, but we could not send the email automatically.</p>
            <p>Please try calling us directly: <strong>+91 99999 99999</strong></p>
        <?php endif; ?>
        <p>
            <a href="index.html" class="btn">Back to Home</a>
        </p>
    </div>
</body>

</html>