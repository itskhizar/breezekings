<?php

$vendor_autoload = dirname(dirname(__DIR__)) . '/vendor/autoload.php';

if (file_exists($vendor_autoload)) {
    require_once $vendor_autoload;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    /**
     * Send an email via PHPMailer (SMTP) with fallback to PHP mail().
     *
     * @param string $to_email   Recipient email
     * @param string $to_name    Recipient display name
     * @param string $subject    Email subject
     * @param string $body       HTML body
     * @param string $reply_to   Reply-to email address (e.g. sender of contact form)
     * @return bool
     */
    public function send($to_email, $to_name, $subject, $body, $reply_to = '')
    {
        // --- PHPMailer path (preferred) ---
        if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = defined('SMTP_HOST') ? SMTP_HOST : 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = defined('SMTP_USER') ? SMTP_USER : 'azamwaseem44@gmail.com';
                $mail->Password   = defined('SMTP_PASS') ? SMTP_PASS : '';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = defined('SMTP_PORT') ? (int)SMTP_PORT : 587;
                $mail->CharSet    = 'UTF-8';

                // Sender
                $from_name  = defined('SITE_NAME') ? SITE_NAME : 'Breezekings';
                $from_email = defined('SMTP_FROM') ? SMTP_FROM : 'azamwaseem44@gmail.com';
                $mail->setFrom($from_email, $from_name);

                // Reply-To (who sent the contact form)
                if (!empty($reply_to)) {
                    $mail->addReplyTo($reply_to);
                }

                // Recipients
                $mail->addAddress($to_email, $to_name);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $body;
                $mail->AltBody = strip_tags($body);

                $mail->send();
                return true;
            } catch (Exception $e) {
                // Log error silently, fall through to PHP mail()
                error_log('PHPMailer error: ' . $mail->ErrorInfo);
            }
        }

        // --- Fallback: PHP mail() ---
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Breezekings <azamwaseem44@gmail.com>\r\n";
        if (!empty($reply_to)) {
            $headers .= "Reply-To: $reply_to\r\n";
        }
        return @mail($to_email, $subject, $body, $headers);
    }

    /**
     * Send a contact form submission to the admin.
     */
    public function sendContactForm($name, $sender_email, $subject, $message)
    {
        $admin_email = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'azamwaseem44@gmail.com';
        $safe_name    = htmlspecialchars($name);
        $safe_email   = htmlspecialchars($sender_email);
        $safe_subject = htmlspecialchars($subject ?: 'General Inquiry');
        $safe_message = nl2br(htmlspecialchars($message));

        $body = "
        <div style='font-family:Inter,Arial,sans-serif;max-width:600px;margin:0 auto;background:#f8fafc;padding:0'>
            <div style='background:#0B1F3A;padding:28px 32px'>
                <h2 style='color:#fff;margin:0;font-size:20px'>New Contact Message — Breezekings</h2>
            </div>
            <div style='background:#fff;padding:32px;border:1px solid #e2e8f0;border-top:0'>
                <table style='width:100%;border-collapse:collapse;font-size:14px'>
                    <tr>
                        <td style='padding:10px 0;color:#64748b;width:100px;font-weight:600'>From</td>
                        <td style='padding:10px 0;color:#1e293b'>{$safe_name}</td>
                    </tr>
                    <tr style='background:#f8fafc'>
                        <td style='padding:10px;color:#64748b;font-weight:600'>Email</td>
                        <td style='padding:10px;color:#1e293b'><a href='mailto:{$safe_email}' style='color:#C8102E'>{$safe_email}</a></td>
                    </tr>
                    <tr>
                        <td style='padding:10px 0;color:#64748b;font-weight:600'>Subject</td>
                        <td style='padding:10px 0;color:#1e293b'>{$safe_subject}</td>
                    </tr>
                </table>
                <div style='margin-top:24px;padding:20px;background:#f1f5f9;border-radius:8px;border-left:4px solid #C8102E'>
                    <p style='margin:0 0 8px;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:1px'>Message</p>
                    <p style='margin:0;color:#334155;font-size:14px;line-height:1.7'>{$safe_message}</p>
                </div>
                <p style='margin-top:28px;font-size:12px;color:#94a3b8'>This message was sent via the contact form at <a href='https://breezekings.com/contact' style='color:#C8102E'>breezekings.com/contact</a></p>
            </div>
        </div>";

        return $this->send(
            $admin_email,
            'Breezekings Admin',
            'Breezekings Contact: ' . ($subject ?: 'General Inquiry'),
            $body,
            $sender_email
        );
    }
}

/* Initialize mailer object */
$mailer = new Mailer;
