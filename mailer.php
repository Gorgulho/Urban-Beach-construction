<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'composer/vendor/autoload.php';
$mail = new PHPMailer(TRUE);
// Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
				$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message =trim($_POST["message"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Oops! Ocurreu algum problema com o seu envio. Por favor tente novamente.";
            exit;
        }

        $mail->setFrom('no-reply@urbanbeachrentals.com','No-Reply');
        $mail->addAddress('geral@urbanbeachrentals.com', 'Geral');
        $mail->Subject='Assunto novo';
        $mail->Body='
            nome: ' . $name . '
            email: ' . $email . '
            assunto: ' . $message . '';

        //SMTP settings
        $mail->isSMTP();

        $mail->Host='mail.urbanbeachrentals.com';
        $mail->SMTPSecure='tls';
        $mail->Username='no-reply@urbanbeachrentals.com';
        $mail->Password='UrbanReply1820';
        $mail->Port=25;

        //$mail->SMTPDebug = 3; //Alternative to above constant



        // Send the email.
        if ($mail->send()) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Obrigado! A sua mensagem foi enviada, seremos breves em responder.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Oops! Alguma coisa correu mal com o seu envio, por favor tente novamente.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "Ocorreu um problema com o seu envio, por favor tente novamente.";
    }

?>
