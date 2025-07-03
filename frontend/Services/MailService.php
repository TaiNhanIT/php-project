<?php

namespace Services;
require_once __DIR__ . '/../Vendor/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../Vendor/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../Vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    private $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/email.php';
    }

    public function sendResetPasswordEmail($email, $token)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $this->config['smtp']['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->config['smtp']['username'];
            $mail->Password = $this->config['smtp']['password'];
            $mail->SMTPSecure = $this->config['smtp']['secure'];
            $mail->Port = $this->config['smtp']['port'];

            $mail->setFrom($this->config['smtp']['from_email'], $this->config['smtp']['from_name']);
            $mail->addAddress($email);
            $mail->Subject = 'Reset Your Password';
            $mail->isHTML(true);
            $mail->Body = "<p>Click <a href='http://localhost/php-project/public/?url=auth/resetPassword&token=" . urlencode($token) . "'>here</a> to reset your password.</p>";

            $mail->send();
            return ['success' => true, 'message' => 'Liên kết reset đã được gửi.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi gửi email: ' . $mail->ErrorInfo];
        }
    }
}