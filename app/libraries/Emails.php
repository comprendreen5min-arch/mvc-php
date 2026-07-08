<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Emails
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    private function getSmtpSettings()
    {
        $this->db->query("SELECT * FROM app_smtp WHERE id = 1");
        return $this->db->single();
    }

    public function send($to, $subject, $body)
    {
        $settings = $this->getSmtpSettings();

        if (!$settings) {
            return false;
        }

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = $settings->smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $settings->smtp_username;
            $mail->Password   = Helper::decrypt($settings->smtp_password);

            // Encryption
            if ($settings->smtp_encryption === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } elseif ($settings->smtp_encryption === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = false;
                $mail->SMTPAutoTLS = false;
            }

            $mail->Port       = $settings->smtp_port;

            //Recipients
            $mail->setFrom($settings->smtp_username, SITENAME); // Using SITENAME constant
            $mail->addAddress($to);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log error or handle it
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
