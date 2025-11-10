<?php

ob_start();
include('config.php');
?>

<?php

class connect {

    public $conn;
    
    var $pages;   // Total number of pages required
    var $openPage;  // currently opened page

   function sendmail($detail, $giveorder, $sub = '', $mailHost="", $mailFrom="", $mailFromName="", $mailSMTPSecure="", $mailAddReplyTo="", $mailUsername="", $mailPassword='') {

        $mail = new PHPMailer();

        try {
            $mail->IsSMTP();
            $mail->Host = $mailHost;
            $mail->SMTPAuth = true;
            $mail->Username = $mailUsername;
            $mail->Password = $mailPassword;
            $mail->SMTPSecure = $mailSMTPSecure;                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;

            $mail->From = $mailFrom;
            $mail->FromName = $mailFromName;
            $mail->AddReplyTo($mailAddReplyTo);
            $emailids = explode(',', $giveorder);
            foreach ($emailids as $key => $value) {
                $mail->AddAddress($value);
            }
            //$mail->addBCC();
            $mail->IsHTML(true);
            $mail->Subject = $sub;
            $mail->Body = $detail;
            //echo $detail;
            //exit;
            print_r($mail);

            $res_ofmail = $mail->Send();
            var_dump($res_ofmail);
        } catch (phpmailerException $e) {
            //echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            //echo $e->getMessage(); //Boring error messages from anything else!
        }
    }

}

?>
