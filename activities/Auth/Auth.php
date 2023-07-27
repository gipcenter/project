<?php

namespace Auth;

use database\DataBase;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Auth
{


  protected function redirect($url)
  {
    header('Location: ' . trim(CURRENT_DOMAIN, '/ ') . '/' . trim($url, '/ '));
    exit;
  }


  protected function redirectBack()
  {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }


  private function hash($password)
  {
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);
    return $hashPassword;
  }


  private function random()
  {
    return bin2hex(openssl_random_pseudo_bytes(32));
  }


  public function activationMessage($username, $verifyToken)
  {
    $message = '
  <h1 style="text-align: center; margin-bottom: 20px;">Account Activation</h1>
  <p>Hello, ' . $username . ' </p>
  <p>Thank you for registering with our system. To activate your account, please click on the link below:</p>
  <p><a style="color: #007bff; text-decoration: none;" href="' . url('activation/' . $verifyToken) . '">Activate Account</a></p>
  <p>If the link above does not work, you can use the activation token below:</p>
  <p>' . url('activation/' . $verifyToken) . '</p>
  <p>If you did not sign up and receive this email in error, please disregard it.</p>
  <p>Best regards,</p>
  <p>Our Support Team</p>
    ';
    return $message;
  }


  public function forgotMessage($username, $verifyToken)
  {
    $message = '
  <h1 style="text-align: center; margin-bottom: 20px;">Password Reset</h1>
  <p>Hello, ' . $username . ' </p>
  <p>You are receiving this email because you have requested a password reset for your user account on our website.<br>To reset your password, please click on the link below:</p>
  <p><a style="color: #007bff; text-decoration: none;" href="' . url('reset-password-form/' . $verifyToken) . '">Password Reset Link</a></p>
  <p>If you did not initiate this password reset request, please take necessary actions to secure your account and disregard this email.</p>
  <p>Thank you and regards,</p>
  <p>Our Support Team</p>
    ';
    return $message;
  }

  private function sendMail($emailAddress, $subject, $body)
  {

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
      //Server settings
      $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
      $mail->CharSet = "UTF-8"; //Enable verbose debug output
      $mail->isSMTP();  //Send using SMTP
      $mail->Host = MAIL_HOST; //Set the SMTP server to send through
      $mail->SMTPAuth = SMTP_AUTH; //Enable SMTP authentication
      $mail->Username = MAIL_USERNAME; //SMTP username
      $mail->Password   = MAIL_PASSWORD; //SMTP password
      $mail->SMTPSecure = 'tls';  //Enable PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
      $mail->Port = MAIL_PORT; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

      //Recipients
      $mail->setFrom(SENDER_MAIL, SENDER_NAME);
      $mail->addAddress($emailAddress); //Add a recipient
      // $mail->addAddress('ellen@example.com'); //Name is optional
      // $mail->addReplyTo('info@example.com', 'Information');
      // $mail->addCC('cc@example.com');
      // $mail->addBCC('bcc@example.com');

      //Attachments
      // $mail->addAttachment('/var/tmp/file.tar.gz'); //Add attachments
      // $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional name

      //Content
      $mail->isHTML(true); //Set email format to HTML
      $mail->Subject = $subject;
      $mail->Body = $body;
      // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();
      return true;
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      return false;
    }
  }





  public function register()
  {
    require_once(BASE_PATH . '/template/auth/register.php');
  }



  public function RegisterStore($request)
  {
    if (empty($request['email']) || empty($request['username']) || empty($request['password'])) {
      flash('register_ERROR', 'Please note that all fields are required to be completed.');
      $this->redirectBack();
    } else if (strlen($request['password']) < 8) {
      flash('register_ERROR', 'Please ensure that your password is at least 8 characters long.');
      $this->redirectBack();
    } else if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
      flash('register_ERROR', 'Please enter a valid email address.');
      $this->redirectBack();
    } else {
      $db = new DataBase();
      $user = $db->select('SELECT * FROM users WHERE email = ?', [$request['email']])->fetch();
      if ($user != NULL) {
        flash('register_ERROR', 'This email has already been taken.');
        $this->redirectBack();
      } else {

        $randomToken = $this->random();
        $activationMessage = $this->activationMessage($request['username'], $randomToken);
        $result = $this->sendMail($request['email'], 'User account activation', $activationMessage);

        if ($result) {
          $request['verify_token'] = $randomToken;
          $request['password'] = $this->hash($request['password']);
          $db->insert('users', array_keys($request), $request);

          $this->redirect('login');
        } else {
          flash('register_ERROR', 'The email activation system failed to send. Please try again');
          $this->redirectBack();
        }
      }
    }
  }


  public function activation($verifyToken)
  {
    $db = new DataBase();
    $user = $db->select("SELECT * FROM users WHERE verify_token = ? AND is_active = 0;", [$verifyToken])->fetch();
    if ($user == NULL) {
      $this->redirect('login');
    } else {
      $result = $db->update('users', $user['id'], ['is_active'], [1]);
      $this->redirect('login');
    }
  }



  public function login()
  {
    require_once(BASE_PATH . '/template/auth/login.php');
  }

  public function checkLogin($request)
  {
    if (empty($request['email']) || empty($request['password'])) {
      flash('register_ERROR', 'Please note that all fields are required to be completed.');
      $this->redirectBack();
    } else {
      $db = new DataBase();
      $user = $db->select("SELECT * FROM users WHERE email = ?", [$request['email']])->fetch();

      if ($user != null) {
        if (password_verify($request['password'], $user['password'])) {
          if ($user['is_active'] == 1) {
            $_SESSION['user'] = $user['id'];
            $this->redirect('admin');
          } else {
            flash('register_ERROR', 'Your user account has not been activated yet. Please activate it through the email.');
            $this->redirectBack();
          }
        } else {
          flash('register_ERROR', 'The username or password is incorrect.');
          $this->redirectBack();
        }
      } else {
        flash('register_ERROR', 'No user found with the entered information.');
        $this->redirectBack();
      }
    }
  }

  public function checkAdmin()
  {
    if (isset($_SESSION['user'])) {
      $db = new DataBase();
      $user = $db->select('SELECT * FROM users WHERE id = ?', [$_SESSION['user']])->fetch();
      if ($user != NULL) {
        if ($user['permission'] != 'admin') {
          $this->redirect('home');
        }
      } else {
        $this->redirect('home');
      }
    } else {
      $this->redirect('home');
    }
  }


  public function logOut()
  {
    if (isset($_SESSION['user'])) {
      unset($_SESSION['user']);
      session_destroy();
    }
    $this->redirect('home');
  }

  public function forGot()
  {
    require_once(BASE_PATH . '/template/auth/forgot.php');
  }


  public function forgotRequest($request)
  {
    if ($request['email'] == null) {
      flash('forgot_error', 'Entering an email is mandatory.');
      $this->redirectBack();
    } else if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
      flash('forgot_error', 'Please enter a valid email.');
      $this->redirectBack();
    } else {
      $db = new DataBase();
      $user = $db->select("SELECT * FROM users WHERE email = ?", [$request['email']])->fetch();
      if ($user == null) {
        flash('forgot_error', 'No user exists with this email.');
        $this->redirectBack();
      } else {

        $randomToken = $this->random();
        $forgotMessage = $this->forgotMessage($user['username'], $randomToken);
        $result = $this->sendMail($request['email'], 'Password Reset', $forgotMessage);

        if ($result) {
          $db->update('users', $user['id'], ['forgot_token', 'forgot_token_expire'], [$randomToken, date('Y-m-d H:i:s', strtotime('+15 minutes'))]);
          $this->redirect('login');
        } else {
          flash('forgot_error', 'Email delivery failed. Please try again.');
          $this->redirectBack();
        }
      }
    }
  }


  public function resetPasswordView($forgotToken)
  {
    require_once(BASE_PATH . '/template/auth/reset-password.php');
  }


  public function resetPassword($request, $forgotToken)
  {
    if (!isset($request['password']) || strlen($request['password']) < 8) {
      flash('reset_error', 'Please ensure that your password is at least 8 characters long.');
      $this->redirectBack();
    } else {
      $db = new DataBase();
      $user = $db->select('SELECT * FROM users WHERE forgot_token = ?', [$forgotToken])->fetch();
      if ($user == null) {
        flash('reset_error', 'No user exists.');
        $this->redirectBack();
      } else {
        if ($user['forgot_token_expire'] < date('Y-m-d H:m:s')) {
          flash('reset_error', 'Your token has expired. Please try again.');
          $this->redirectBack();
        }
        if ($user) {
          $db->update('users', $user['id'], ['password'], [$this->hash($request['password'])]);
          $this->redirect('login');
        } else {
          flash('reset_error', 'No user exists.');
          $this->redirectBack();
        }
      }
    }
  }
}
