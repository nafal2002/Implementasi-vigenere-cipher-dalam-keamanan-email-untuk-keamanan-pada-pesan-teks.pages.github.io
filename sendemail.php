<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'phpmailer/Exception.php';
require_once 'phpmailer/PHPMailer.php';
require_once 'phpmailer/SMTP.php';

$mail = new PHPMailer(true);

$alert = '';

// Fungsi Vigenere Cipher untuk enkripsi
function vigenereCipher($text, $key) {
    $result = '';
    $text = strtoupper($text);
    $key = strtoupper($key);
    $keyLength = strlen($key);

    for ($i = 0, $j = 0; $i < strlen($text); $i++) {
        if (ctype_alpha($text[$i])) {
            $result .= chr((ord($text[$i]) + ord($key[$j])) % 26 + 65);
            $j = ($j + 1) % $keyLength;
        } else {
            $result .= $text[$i];
        }
    }

    return $result;
}

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Cek apakah kunci enkripsi disediakan
    $encryptionKey = isset($_POST['encryption_key']) ? $_POST['encryption_key'] : 'default_key';

    // Enkripsi pesan menggunakan Vigenere Cipher
    $encryptedMessage = vigenereCipher($message, $encryptionKey);

    try{
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pnrteam1@gmail.com';
        $mail->Password = 'vkbyeqlapvbbpemr';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = '587';

        $mail->setFrom('pnrteam1@gmail.com');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Message Received (Contact Page)';
        $mail->Body = "<h3>Name : $name <br>Email: $email <br>Message : $encryptedMessage</h3>";

        $mail->send();
        $alert = '<div class="alert-success">
                    <span>Message Sent! Thank you for contacting us.</span>
                  </div>';
    } catch (Exception $e){
        $alert = '<div class="alert-error">
                    <span>'.$e->getMessage().'</span>
                  </div>';
    }
}
?>
