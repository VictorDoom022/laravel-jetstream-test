<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// require 'vendor/autoload.php';
class MailController extends Controller
{
    public function sendMail() {

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.mailtrap.io';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = '7fc1a697ab387a';                     //SMTP username
            $mail->Password   = 'd3ea0eed10b986';                               //SMTP password
            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
            $mail->Port       = 2525;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('victordoom022@gmail.com', 'Sender');
            $mail->addAddress('victordoom022@gmail.com');     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'A customer need your help!';
            $mail->Body    = "
                <h3>Here is the customer's detail:</h3>
                <table style='border: 1px solid black'>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                    </tr>
                    <tr>
                        <td>". request('name') ."</td>
                        <td>". request('email') ."</td>
                        <td>". request('subject') ."</td>
                        <td>". request('message') ."</td>
                    </tr>
                </table>
            ";

            $mail->send();
            echo '<script>alert("Message has been sent successfully!"); window.history.go(-1);</script>';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            echo 'window.history.go(-1);</script>';
        }
    }
}
