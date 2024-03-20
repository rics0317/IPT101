<?php
// Establish database connection
require "database.php";  
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function generateVerificationCode() {
    // Generate a random verification code
    return mt_rand(100000, 999999);
}

function sendMail($email, $v_code) {
    require ("PHPMailer/PHPMailer.php");
    require ("PHPMailer/SMTP.php");
    require ("PHPMailer/Exception.php");

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP(); //Send using SMTP
        $mail->Host       = 'smtp.gmail.com'; //Set the SMTP server to send through
        $mail->SMTPAuth   = true; //Enable SMTP authentication
        $mail->Username   = 'ricoalcala902@gmail.com'; //Your Gmail username
        $mail->Password   = 'ggqk lith serc auor'; //Your Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption
        $mail->Port       = 587; //TCP port to connect to

        $mail->setFrom('ricoalcala902@gmail.com', 'Rico Alcala');
        $mail->addAddress($email);

        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = 'Email Verification from Rico';
        $mail->Body    = "Thanks for registration! Click the link below to verify your email address:
            <a href='http://localhost/ipt101/verify.php?email=$email&v_code=$v_code'>Verify</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Get form data
$username = $_POST['username'];
$password = $_POST['password'];
$lastname = $_POST['lastname'];
$firstname = $_POST['firstname'];
$middlename = $_POST['middlename'];
$email = $_POST['email'];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format");
}

// Generate verification code
$verification_code = generateVerificationCode();

$password=password_hash($_POST['password'],PASSWORD_BCRYPT);

// Insert user into database with verification code
$sql = "INSERT INTO user (username, password, Lastname, First_name, Middle_name, Email, Status, verification_code, Active) 
        VALUES ('$username', '$password', '$lastname', '$firstname', '$middlename', '$email', 'inactive', '$verification_code', '0')";

if ($conn->query($sql) === TRUE) {
    // Send verification email
    if (sendMail($email, $verification_code)) {
        echo "<div Location: login.php ;'>Registration successful. Verification email sent. Please check your email.</div>";
    } else {
        echo "Error: Unable to send verification email.";
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
