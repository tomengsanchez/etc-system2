<?php
// app/helpers/mail_helper.php

// Manually require the PHPMailer files to bypass autoloader conflicts.
require_once dirname(APPROOT) . '/vendor/phpmailer/phpmailer/src/Exception.php';
require_once dirname(APPROOT) . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once dirname(APPROOT) . '/vendor/phpmailer/phpmailer/src/SMTP.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/**
 * Sends a verification email using PHPMailer.
 *
 * @param string $email The recipient's email address.
 * @param string $token The verification token.
 * @return void
 */
function sendVerificationEmail($email, $token) {
    $mail = new PHPMailer(true);

    try {
        //Server settings from config.php
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port       = SMTP_PORT;

        //Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Ecosys Training Center - Email Verification';
        
        // Generate the verification link
        $verification_link = URLROOT . '/user/verify/' . $token;
        $mail->Body    = "
            <h1>Email Verification</h1>
            <p>Thank you for registering. Please click the link below to verify your email address:</p>
            <p><a href='{$verification_link}'>{$verification_link}</a></p>
            <p>If you did not register, please ignore this email.</p>
        ";
        $mail->AltBody = 'Please copy and paste this link into your browser to verify your email: ' . $verification_link;

        $mail->send();
        
        // Set a success message for the user
        flash('register_success', 'Registration successful. Please check your email to verify your account.');

    } catch (Exception $e) {
        // If sending fails, show a generic error and log the detailed error
        // For debugging, you can uncomment the SMTPDebug line.
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $detailed_error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        error_log($detailed_error); // Log the error instead of showing it to the user in production.
        
        flash('register_success', "Registration successful, but the verification email could not be sent. Please contact support.", 'alert alert-danger');
    }
}
