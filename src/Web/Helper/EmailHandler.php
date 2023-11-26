<?php

namespace Gso\Ws\Web\Helper;

use Dotenv\Dotenv;
use Exception;
use Gso\Ws\Shared\ValuesObjects\Email;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailHandler
{
    private PHPMailer $mail;

    public function __construct()
    {
        $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../../');
        $dotenv->load();
        //Create an instance; passing `true` enables exceptions
        $this->mail = new PHPMailer(true);

        try {
            //Server settings
            $this->mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
            $this->mail->isSMTP();                                            //Send using SMTP
            $this->mail->Host       = getenv('HOST_MAIL');              //Set the SMTP server to send through
            $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $this->mail->Username   = getenv('USER_MAIL');              //SMTP username
            $this->mail->Password   = getenv('PASS_MAIL');              //SMTP password
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
            $this->mail->Port       = getenv('PORT_MAIL');
            //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $this->mail->setFrom(getenv('FROM_EMAIL_MAIL'), getenv('FROM_NAME_MAIL'));

            //Attachments
            //     $this->mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //     $this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';//Set email format to HTML
            $this->mail->Subject = getenv('FROM_NAME_MAIL');
            $this->mail->AltBody = 'teste';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: { $this->mail->ErrorInfo}";
        }
    }

    public function sendMessage(
        Email $emailDestination,
        string $titulo,
        string $message,
        string $link,
        bool $linkEnable = false
    ): bool {
        try {
            $this->mail->addAddress($emailDestination);     //Add a recipient
            $tituloTemplate     = $titulo;
            $contentTemplate    = $message;
            $linkTemplate       = $link;
            $linkEnableTemplate = $linkEnable;
            ob_start();
            include(__DIR__ . '/../Template/templateEmail.php');
            $html = ob_get_contents();
            ob_get_clean();
            $this->mail->msgHTML($html);
            $this->mail->send();



            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: { $this->mail->ErrorInfo}";

            return false;
        }
    }
}
