<?php
date_default_timezone_set('America/Argentina/Catamarca');

require('class/phpmailer/src/Exception.php');
        require('class/phpmailer/src/PHPMailer.php');
        require('class/phpmailer/src/SMTP.php');
        // These must be at the top of your script, not inside a function
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

class Captcha
{

    public function getCaptcha($secretkey)
    {
        $respuesta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LexV8EUAAAAAOXZWFDUK7uHeokr1mHLNM_Cl_Mj&response={$secretkey}");
        $retorno = json_decode($respuesta);
        return $retorno;
    }
}

class Operaciones
{
    public function EnviarEmail($nombres, $apellidos, $email, $asunto)
    {
        // Import PHPMailer classes into the global namespace       

        // Load Composer's autoloader
        //require 'vendor/autoload.php';

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'phsosa@catamarca.gov.ar';                     // SMTP username
            $mail->Password   = 'phs061079soi';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($email, 'Consulta desde el Repositorio');
            $mail->addAddress('soi@catamarca.gov.ar', 'Consulta desde el Repositorio');     // Add a recipient
            // $mail->addAddress('cfrancisci@catamarca.gov.ar', 'Carolina');     // Add a recipient
            /* $mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com'); */

            // Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Consulta desde el Repositorio - ' . date("Y-m-d H:i:s");
            $mail->Body    =    '<h1>Consulta desde el Repositorio - ' . date("Y-m-d H:i:s") . '</h1>
                                <p>Nombres: ' . $nombres . '</p>
                                <p>Apellidos: ' . $apellidos . '</p>
                                <p>Email: ' . $email . '</p>
                                <p>Consulta: ' . $asunto . '</p>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }


    public function LimpiarVariable($variable)
    {
        $variable = (htmlspecialchars(stripslashes(strip_tags(mysqli_real_escape_string($variable)))));
        return $this->variable = $variable;
    }

    public function ValidarDato($variable, $tipodato)
    {
        $variable = trim($variable);
        switch ($tipodato) {
            case "entero":
                $variable = filter_var($variable, FILTER_VALIDATE_INT); //validamos
                break;
            case "fecha":
                $variable = filter_var($variable, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^((0[1-9]|1[0-9]|2[0-9]|3[01])(-|\/)(0[1-9]|1[012])(-|\/)(19[0-9][0-9]|20[0-9][0-9]))$/i")));
                break;
            case "fechamysql":
                $variable = filter_var($variable, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^((19[0-9][0-9]|20[0-9][0-9])(-|\/)(0[1-9]|1[012])(-|\/)(0[1-9]|1[0-9]|2[0-9]|3[01]))$/i")));
                break;
            case "texto-alfabetico":
                $variable = filter_var($variable, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^([a-zA-ZÃ¡-ÃºÃ-ÃšÃ¼ÃœÃ±Ã‘\s]+)$/i")));
                break;
        }
        return $this->variable = $variable;
    }

    public function SanearDato($variable, $tipodato)
    {
        switch ($tipodato) {
            case "texto":
                $variable = filter_var($variable, FILTER_SANITIZE_STRING); //limpiamos
                break;
            case "entero":
                $variable = filter_var($variable, FILTER_SANITIZE_NUMBER_INT); //limpiamos
                break;
        }
        return $this->variable = $variable;
    }
}
