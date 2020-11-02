<?php

namespace App\Services;

use App\Services\Config;
use Twig\Environment;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailerException;

class SendMail
{
    /**
     * @var Config
     */
    private $config;

    private $twig;

    private $projectDir;

    public function __construct(Config $config, Environment $twig, string $projectDir)
    {
        $this->config = $config;
        $this->twig = $twig;
        $this->projectDir = $projectDir;
    }

    public function send(array $mailData, $attach = null){

        $conf = $this->config->getConfig('from_email_contact');
        $from = $conf ? $conf->getValue() : 'contact@funerairefrance.com';
        //$mailer->sendCustomMail($mailData);
        $headers  = "MIME-Version: 1.0 \n";

        $headers .= "Content-type: text/html; charset=utf-8 \n";

        $headers .= "From: $from \n";

        $headers .= "Disposition-Notification-To: $from  \n";

        if(is_array($mailData['to'])){
            $mailData['to'] = implode(',', $mailData['to']);        
        }

        // Message de Priorité haute

        // -------------------------

        $headers .= "X-Priority: 1  \n";

        $headers .= "X-MSMail-Priority: High \n";
        $CR_Mail = TRUE;
        $CR_Mail = @mail($mailData['to'], $mailData['subject'], $this->buildEmail($mailData['body']), $headers);

        return $CR_Mail;
    }

    public function sendWithAttachement(array $mailData, $attachements){
        //die();
        $mail = new PHPMailer();
        $conf = $this->config->getConfig('from_email_contact');
        $from = $conf ? $conf->getValue() : 'contact@funerairefrance.com';

        $mail->setFrom($from, 'FuneraireFrance');
        $mail->addAddress($mailData['to'], '');
        $mail->addReplyTo($from, 'FuneraireFrance');
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $mailData['subject'];
        $mail->Body    = $this->buildEmail($mailData['body']);
        for($i = 0; $i < count($attachements); $i++){
            $mail->addAttachment($this->projectDir.'/public'.$attachements[$i], basename($attachements[$i]));
        }
        
        $mail->send();
       
        /*$file = $this->projectDir.'/public'.$attachements[0];

        $mailto = $mailData['to'];
        $subject = $mailData['subject'];
        $message = $this->buildEmail($mailData['body']);

        $content = file_get_contents($file);
        $content = chunk_split(base64_encode($content));

        // a random hash will be necessary to send mixed content
        $separator = md5(time());

        // carriage return type (RFC)
        $eol = "\r\n";

        // main header (multipart mandatory)
        $headers = "From: $from" . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "X-Priority: 1" . $eol;
        $headers .= "X-MSMail-Priority: High" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol. $eol;
        $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
        //$headers .= "This is a MIME encoded message." . $eol;

        // message
        $body = "--" . $separator . $eol;
        $body .= "Content-Type: text/html; charset=\"utf-8\"" . $eol;
        $body .= "Content-Transfer-Encoding: 8bit" . $eol;
        $body .= $message . $eol;

        // attachment
        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: application/pdf; name=\"" . basename($attachements[0]) . "\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol;
        $body .= $content . $eol;
        $body .= "--" . $separator . "--";

        $CR_Mail = TRUE;
        $CR_Mail = mail($mailto, $subject, $body, $headers);

        return $CR_Mail;*/
    }

    private function buildEmail($body){
        $template = $this->twig->render('includes/template-email.html.twig');
        $style = $this->twig->render('includes/style-pdf.html.twig');
        $html = str_replace([ '::style_bootstrap::', '::body_template::'], 
        [$style, $body], $template);
        
        return $html;
    }

}