<?php

namespace App\Services;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//gestor de correos, util para enviar diferentes formatos de correo
class Mailer{

    //Genera una instancia para el envio de correos, es necesario pasarle el nombre que aparecera como remitente
    public function config($remitenteName){
        $mail = new PHPMailer(true);
        try {
             //$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
             $mail->isSMTP(); // Set mailer to use SMTP
             $mail->SMTPAuth = true;
             $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
             $mail->Host = env('MAIL_HOST'); // Specify main and backup SMTP servers
             $mail->Port = 587;   // TCP port to connect to
             $mail->Username = env('MAIL_FROM_ADDRESS'); // SMTP username
             $mail->Password = env('MAIL_PASSWORD'); // SMTP password
             $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM'));
             $mail->FromName = $remitenteName;
             $mail->CharSet = 'UTF-8';
             $mail->SMTPSecure = true;
         } catch (\Exception $e) {
             return null;
         }
         return $mail;
    }

    //Envia un email, es importante que fromato del el email se verifique previamente
    public function sentConfirmationEmail($email, $name, $token){
        //Instancia del objeto que permite el envio de email
        $mail =  $this->config();
        if(!$mail) return false;//La instancia no fué exitosa

        //Preparando la url de confirmación
        $token_encoded = urlencode($token);
        $baseURL = env('WEB_URL');
        $url = $baseURL. "/verify/account?token=" .$token_encoded;
        //Renderizamos el view con el template de confirmación
        $name = strtoupper($name);
        $htmlContent = view('email_confirmation_template',['url' => $url])->render();

        
        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de creación de cuenta';
        $mail->Body = $htmlContent;
        $mail->addAddress($email, $name);

        try {
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
            echo "Error al enviar el correo: " . $mail->ErrorInfo;
        }
    }
    
    //Envia un email de confirmación de cuenta a un usuario de tipo consignatario
    public function sendConsignatarioConfirmationEmail($email, $token, $company, $cliente_razon_social){
        //Preparando la url de confirmación
        $token_encoded = urlencode($token);
        $baseURL = env('WEB_URL');
        $url = $baseURL. "/company/verify/account?token=" .$token_encoded;
        
        //Renderizamos el view con el template de confirmación
        $htmlContent = view('email_company_confirmation_template',['url' => $url, 'company' => $company, 'cliente' => $cliente_razon_social])->render();

        $this->send_email_html($email, $company, $htmlContent, 'Confirmación de creación de cuenta', $company);
    }

    //Envia un email con la url de acceso al loguin de consignatarios
    public function sendCompanyURLloginLink($email, $company, $cliente_razon_social){
        //Preparando la url de login de consignatario
        $companyURL = env('FRONT_END_URL')."clientes?company=".urlencode($company); 
        
        //Renderizamos el view con el template de confirmación
        $htmlContent = view('email_url_login_consignatario_template',['url' => strtoupper($companyURL) , 'company' => strtoupper($company) , 'cliente' => strtoupper($cliente_razon_social)])->render();

        $this->send_email_html($email, $company, $htmlContent, "Iniciar sesión en $company", $company);
    }

    public function sentPasswordRecoveryEmail($correo,$nombre, $link){
        $nombre = strtoupper($nombre);
        $htmlContent = view('PasswordEmailTemplate',['token' => $link])->render();
        $mail =  $this->config();
        if(!$mail) return false;
        $mail->isHTML(true);
        $mail->Subject = 'Recuperaión de contraseña';
        $mail->Body = $htmlContent;
        $mail->addAddress($correo, $nombre);

        try {
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
            echo "Error al enviar el correo: " . $mail->ErrorInfo;
        }
    }

    public function send_email_html($email, $name, $htmlContent, $title, $rem = null) {
        $mail =  $this->config($rem);
        if(!$mail) return false;
        $mail->isHTML(true);
        $mail->Subject = $title;
        $mail->Body = $htmlContent;
        $mail->addAddress($email, $name);

        try {
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}