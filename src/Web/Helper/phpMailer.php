<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require __DIR__ . '/../../../vendor/autoload.php';


$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../../');
$dotenv->load();

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = getenv('HOST_MAIL');                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = getenv('USER_MAIL');                     //SMTP username
    $mail->Password   = getenv('PASS_MAIL');                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port       = getenv(
        'PORT_MAIL'
    );
    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom(getenv('FROM_EMAIL_MAIL'), getenv('FROM_NAME_MAIL'));
    $mail->addAddress('ronypc@outlook.com');     //Add a recipient

    //Attachments
//    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name


    //Content
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';//Set email format to HTML
    $mail->Subject = getenv('FROM_NAME_MAIL');
    $mail->AltBody = getenv('ALT_BODY');
//    $mail->Body    = getenv('ALT_BODY');
    $content = 'dsadasd';

    ob_start();
    include(__DIR__ . '/../Template/templateEmail.php');
    $html = ob_get_contents();
    ob_get_clean();
    $mail->msgHTML($html);
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
