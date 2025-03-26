<?php

// function.php

function startSecureSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function digitsCode($length = 6)
{
    return str_pad(rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

function sendMail($to, $subject, $message)
{
    $headers = "From: mediatek@localhost\r\n";
    $headers .= "Reply-To: no-reply@localhost\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    mail($to, $subject, $message, $headers);
}

function fakeMailSend($to, $subject, $message)
{
    if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
        sendMail($to, $subject, $message);
    } else {
        echo "<div style='background:#111;padding:1em;margin:1em 0;border:1px solid #ccc'>";
        echo "<h4>$subject</h4>";
        echo "<p><strong>Destinataire :</strong> $to</p>";
        echo "<pre>$message</pre>";
        echo "</div>";
    }
}
