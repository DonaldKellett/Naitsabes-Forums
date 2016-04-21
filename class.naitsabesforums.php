<?php
require 'encryption/class.caesarcipher.php';
require 'encryption/class.vigenerecipher.php';
require '3rdparty/PHPMailer/PHPMailerAutoload.php';

class NaitsabesForums {
  /* Naitsabes Forums Class Constants - feel free to edit */

  // Name of blog, forum or Service (shown in page title)
  const NAME = "Naitsabes Forums v2.0.0";

  // Database Name, Server Name, Username and Password (IMPORTANT for database connection - edit to your chosen database name upon initialization)
  const DB_SERVER_NAME = "localhost";
  const DB_USERNAME = "root";
  const DB_PASSWORD = "root";
  const DB_NAME = "nsforums2";

  // User Links (links displayed to registered forum users)
  const USER_LINKS = "<li><a href='logout.php'><h3>Logout</h3></a></li>";

  // Content that will be displayed to visitors not logged into the Forum
  const VISITOR_MESSAGE = "<h2>Login Required</h2>
  <p>You must be logged in in order to view the contents of this Forum.  If you do not have an account already, feel free to register for an account.</p>
  <p>
    <a class='button' href='loginform.php'>Log In</a>
    <a class='button' href='register.php'>Register</a>
  </p>";
  const VISITOR_LINKS = "<li><a href='register.php'><h3>Register</h3></a></li>
  <li><a href='loginform.php'><h3>Log In</h3></a></li>";

  // Display this message if the user is already logged into their account but still tries to register
  const NO_REGISTER = "<h2>Already Logged In</h2><p>You are already logged in, no need to register ;)</p>";

  // Register Form (for new users)
  const REGISTER_FORM = "<h2>Register</h2>
  <p>Don't have an account already?  No problem, just fill in the register form below to create your own account.</p>
  <form action='register.php' method='post'>
    <div class='row uniform'>
      <div class='6u 12u(xsmall)'>
        <p>
          <input name='name' type='text' placeholder='Name' />
        </p>
      </div>
      <div class='6u 12u(xsmall)'>
        <p>
          <input name='email' type='email' placeholder='Email Address' />
        </p>
      </div>
    </div>
    <div class='row uniform'>
      <div class='6u 12u(xsmall)'>
        <p>
          <input name='username' type='text' placeholder='Username' />
        </p>
      </div>
      <div class='6u 12u(xsmall)'>
        <p>
          <input name='password' type='password' placeholder='Password' />
        </p>
      </div>
    </div>
    <div class='row uniform'>
      <div class='12u$'>
        <p>
          <input type='submit' value='Register' />
          <input type='reset' value='Reset Form' />
        </p>
      </div>
    </div>
  </form>";

  // Display either of the messages below when a form (e.g. login form or register form) is not properly filled in
  // Short message
  const REQUIRED_FIELDS_NOT_FILLED_IN_INTERNAL = "<p style='color:red'>Sorry, some required fields were not filled in.  Please try filling in them in before re-submitting the form.</p>";
  // Detailed message
  const REQUIRED_FIELDS_NOT_FILLED_IN = "<h2>Required Fields Not Filled In</h2>
  <p>Sorry, you did not fill in all of the required fields.</p>
  <p>Please click on the button below if you are not automatically redirected to the correct page.</p>
  <p><a class='button' href='index.php'>Go Back</a></p>";

  // Invalid Email Message
  // Short
  const INVALID_EMAIL_ADDRESS_INTERNAL = "<p style='color:red'>Sorry, the email address you entered was invalid.  Please enter a valid email address before re-submitting the form.</p>";
  // Detailed

  // Invalid Password message
  // Short
  const INVALID_PASSWORD_INTERNAL = "<p style='color:red'>Sorry, the password you provided is not a valid password.  Valid passwords must meet the following criteria:</p>
  <ul style='color:red'>
    <li>At least <b style='color:red'>8</b> characters in length</li>
    <li>Contains at least 1 digit</li>
    <li>Contains at least 1 <b style='color:red'>lowercase</b> letter</li>
    <li>Contains only alphanumerical characters</li>
  </ul>";
  // Detailed

  // Password Encryption Algorithm Constants
  // WARNING: DO NOT EDIT THE CONSTANTS BELOW AFTER YOU HAVE INITIALIZED YOUR FORUM; OTHERWISE YOU WILL BREAK A LOT OF EXISTING ACCOUNTS
  const CAESAR_SHIFT = 13;
  const VIGENERE_CIPHER_KEY = 'passwd';
  const VIGENERE_CIPHER_ALPHABET = 'abcdefghijklmnopqrstuvwxyz';

  // Duplicate Account message
  // Short
  const DUPLICATE_ACCOUNT_MSG_INTERNAL = "<p style='color:red'>Sorry, some of the account details you entered were already found in our database.  Try entering a different name, email, username or password and re-submit the form.</p>";
  // Detailed

  // Default User Rank (for new Forum users)
  // Key:
  // 0: Banned
  // 1: Member
  // 2: Moderator
  // 3: Super Moderator
  // 4: Admin
  const DEFAULT_RANK = 1;

  // Activation Key Length (used for verifying email acccounts - should be as long as possible)
  const ACTIVATION_KEY_LENGTH = 40;

  // Login Key Length (should ideally be at least 8 characters long)
  const LOGIN_KEY_LENGTH = 10;

  // Allowed Characters in Random Token Generation
  const RANDOM_TOKEN_ALLOWED_CHARS = "abcdefghijklmnopqrstuvwxyz0123456789";

  // Registration Confirmation Email Subject
  const REGISTRATION_CONFIRMATION_EMAIL_SUBJECT = "Naitsabes Forums Account Registration - Email Confirmation";

  // Registration Success Message
  // Short
  const REGISTER_SUCCESS_INTERNAL = "<p style='color:green'>Your account has been successfully created and a confirmation email has been sent to the email address you provided.  Please go to your inbox and click on the link provided to verify your account.</p>";
  // Detailed

  // Unknown Form Submission Error Message
  // Short
  const FORM_SUBMISSION_ERROR_INTERNAL = "<p style='color:red'>Sorry, an unknown error occurred when submitting the form data.  Please try again later.</p>";
  // Detailed

  // Invalid Key Message (displayed to users when they try to verify their email account using a nonexistent key)
  const INVALID_KEY = "<h2>Invalid Activation Key</h2>
  <p>Sorry, the activation key you provided does not exist.  For this reason, we were not able to verify your account.</p>
  <p><a class='button' href='index.php'>Go Back</a></p>";

  // Account Verification Success Message
  const ACCOUNT_VERIFIED = "<h2>Verification Successful</h2>
  <p>Your email address has been successfully verified.  You may now use your account to log in to the Forums.</p>
  <p><a class='button' href='loginform.php'>Log In</a></p>";

  // Account Verification Error Message
  const VERIFICATION_FAILED = "<h2>Verification Failed</h2>
  <p>Sorry, the email verification process failed for some unknown reason.  For this reason, we were unable to verify your email account.  Please try again later.  We sincerely apologise for any inconvenience caused.</p>
  <p><a class='button' href='index.php'>Go Back</a></p>";

  // Already Logged In Message
  const ALREADY_LOGGED_IN = "<h2>Already Logged In</h2><p>You are already logged in; no need to login again ;)</p>";

  // Login Form
  const LOGIN_FORM = "<h2>Log In</h2>
  <p>Fill in the form below to login to your account.  If you do not have an account already feel free to <a href='register.php'>sign up</a> for an account.</p>
  <form action='login.php' method='post'>
    <div class='row uniform'>
      <div class='5u 12u(small)'>
        <p>
          <input type='text' name='username' placeholder='Username' />
        </p>
      </div>
      <div class='5u 12u(small)'>
        <p>
          <input type='password' name='password' placeholder='Password' />
        </p>
      </div>
      <div class='2u 12u(small)'>
        <p>
          <input type='submit' value='Log In' />
        </p>
      </div>
    </div>
  </form>";

  // Display this message to the user if the user enters an incorrect username and/or password when attempting to log in
  const INVALID_LOGIN_CREDENTIALS = "<h2>Invalid Username and/or Password</h2>
  <p>Sorry, the username/password combination you provided does not belong to any account.</p>
  <p><a class='button' href='loginform.php'>Return</a></p>";

  // Account Verification Required Message
  const ACCOUNT_VERIFICATION_REQUIRED = "<h2>Account Verification Required</h2>
  <p>Sorry, our systems have detected that your account is still unverified.  Please go to your inbox and click on the verification link provided to you in the email to verify your account and start using our Services.</p>
  <p><a class='button' href='index.php'>Go Back</a></p>";

  // Login Success Message
  const LOGIN_SUCCESS = "<h2>Logging In</h2>
  <p>You will be redirected in a moment ... </p>";

  // Display this message to the user when a page that requires a form submission has not been accessed properly
  const FORM_NOT_SUBMITTED = "<h2>Form Not Submitted</h2>
  <p>Sorry, the page you were trying to access requires a form submission.</p>
  <p>If you are not immediately redirected to the correct page, please click on the button below to return to the homepage.</p>
  <p><a class='button' href='index.php'>Go Back</a></p>";

  /* Naitsabes Forums Class Methods - DO NOT EDIT unless you are familiar with PHP */

  public static function display_menu() {
    echo ($_COOKIE['logged_in']) ? NaitsabesForums::USER_LINKS : NaitsabesForums::VISITOR_LINKS;
  }
  public static function display_homepage() {
    echo ($_COOKIE['logged_in']) ? NaitsabesForums::get_forum_content() : "<article class='post'>" . NaitsabesForums::VISITOR_MESSAGE . "</article>";
  }
  public static function get_forum_content() {
    // Display all the threads in the Forum homepage
    // NOTE TO SELF: Remeber to **return** the thread contents, NOT echo them to the page
  }
  public static function display_register_form() {
    echo "<article class='post'>";
    echo ($_COOKIE['logged_in']) ? NaitsabesForums::NO_REGISTER : NaitsabesForums::REGISTER_FORM;
    NaitsabesForums::register_check();
    echo "</article>";
  }
  public static function register_check() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password'])) {
        echo NaitsabesForums::REQUIRED_FIELDS_NOT_FILLED_IN_INTERNAL;
      } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
          echo NaitsabesForums::INVALID_EMAIL_ADDRESS_INTERNAL;
        } else {
          if (!NaitsabesForums::is_valid_password($_POST['password'])) {
            echo NaitsabesForums::INVALID_PASSWORD_INTERNAL;
          } else {
            $name = NaitsabesForums::safen_input($_POST['name']);
            $email = NaitsabesForums::safen_input($_POST['email'], FILTER_SANITIZE_EMAIL);
            $username = NaitsabesForums::safen_input($_POST['username']);
            $password = NaitsabesForums::encrypt($_POST['password']);
            if ($GLOBALS['conn']->query("SELECT * FROM accounts WHERE name = '$name' OR email = '$email' OR username = '$username';")->num_rows > 0) {
              echo NaitsabesForums::DUPLICATE_ACCOUNT_MSG_INTERNAL;
            } else {
              $rank = NaitsabesForums::DEFAULT_RANK;
              $activation_key = NaitsabesForums::random_token(NaitsabesForums::ACTIVATION_KEY_LENGTH);
              $activation_status = "confirming";
              $login_token = NaitsabesForums::random_token(NaitsabesForums::LOGIN_KEY_LENGTH);

              $mail = new PHPMailer;
              $mail->addAddress($email);
              $mail->Subject = NaitsabesForums::REGISTRATION_CONFIRMATION_EMAIL_SUBJECT;
              $mail->Body = "Hello $name,

Your email address has just been used to sign up for an account at " . $_SERVER['SERVER_NAME'] . ".  Please click on the link below to verify your email address and complete your registration:

http://" . $_SERVER['SERVER_NAME'] . "/verify.php?key=$activation_key

If you do not recognise this activity, do NOT reply to this email or click on any links provided in this email.";

              echo ($mail->send() && $GLOBALS['conn']->query("INSERT INTO accounts (name, email, username, password, rank, activation_key, activation_status, login_token) VALUES ('$name', '$email', '$username', '$password', $rank, '$activation_key', '$activation_status', '$login_token')")) ? NaitsabesForums::REGISTER_SUCCESS_INTERNAL : NaitsabesForums::FORM_SUBMISSION_ERROR_INTERNAL;
            }
          }
        }
      }
    }
  }
  public static function is_valid_password($password) {
    return strlen($password) >= 8 && preg_match('/\d/', $password) && preg_match('/[a-z]/', $password) && !preg_match('/[^\w\d]/', $password);
  }
  public static function random_token($length = 10) {
    $allowed_chars = str_split(NaitsabesForums::RANDOM_TOKEN_ALLOWED_CHARS);
    $token = "";
    for ($i = 0; $i < $length; $i++) {
      $token .= $allowed_chars[floor(lcg_value() * count($allowed_chars))];
    }
    return $token;
  }
  public static function encrypt($input) {
    return (new VigenèreCipher(NaitsabesForums::VIGENERE_CIPHER_KEY, NaitsabesForums::VIGENERE_CIPHER_ALPHABET))->encrypt((new CaesarCipher(NaitsabesForums::CAESAR_SHIFT))->encrypt($input));
  }
  public static function decrypt($input) {
    return (new CaesarCipher(NaitsabesForums::CAESAR_SHIFT))->decrypt((new VigenèreCipher(NaitsabesForums::VIGENERE_CIPHER_KEY, NaitsabesForums::VIGENERE_CIPHER_ALPHABET))->decrypt($input));
  }
  public static function display_login() {
    echo "<article class='post'>";
    echo ($_COOKIE['logged_in']) ? NaitsabesForums::ALREADY_LOGGED_IN : NaitsabesForums::LOGIN_FORM;
    echo "</article>";
  }
  public static function safen_input($input, $filter = FILTER_SANITIZE_STRING) {
    return filter_var(htmlspecialchars(stripslashes(trim($input))), $filter);
  }
  public static function verify_account($input) {
    echo "<article class='post'>";
    $key = NaitsabesForums::safen_input($input);
    if ($GLOBALS['conn']->query("SELECT * FROM accounts WHERE activation_key = '$key';")->num_rows === 1) {
      echo ($GLOBALS['conn']->query("UPDATE accounts SET activation_status = 'verified' WHERE activation_key = '$key';")) ? NaitsabesForums::ACCOUNT_VERIFIED : NaitsabesForums::VERIFICATION_FAILED;
    } else {
      echo NaitsabesForums::INVALID_KEY;
    }
    echo "</article>";
  }
  public static function login_auth() {
    $GLOBALS['page_content'] = "";
    $GLOBALS['page_content'] .= "<article class='post'>";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (empty($_POST['username']) || empty($_POST['password'])) {
        $GLOBALS['page_content'] .= NaitsabesForums::REQUIRED_FIELDS_NOT_FILLED_IN;
        NaitsabesForums::redirect_to('loginform.php');
      } else {
        $username = NaitsabesForums::safen_input($_POST['username']);
        $password = NaitsabesForums::encrypt(NaitsabesForums::safen_input($_POST['password']));
        if (($account = $GLOBALS['conn']->query("SELECT * FROM accounts WHERE username = '$username' AND password = '$password'"))->num_rows === 1) {
          while ($details = $account->fetch_assoc()) {
            if ($details['activation_status'] === 'verified') {
              setcookie('logged_in', true, time() + 86400, "/");
              setcookie('login_token', $detalis['login_token'], time() + 86400, "/");
              $GLOBALS['page_content'] .= NaitsabesForums::LOGIN_SUCCESS;
              NaitsabesForums::redirect_to('index.php');
            } else {
              $GLOBALS['page_content'] .= NaitsabesForums::ACCOUNT_VERIFICATION_REQUIRED;
            }
          }
        } else {
          $GLOBALS['page_content'] .= NaitsabesForums::INVALID_LOGIN_CREDENTIALS;
        }
      }
    } else {
      $GLOBALS['page_content'] .= NaitsabesForums::FORM_NOT_SUBMITTED;
      NaitsabesForums::redirect_to('loginform.php');
    }
    $GLOBALS['page_content'] .= "</article>";
  }
  public static function logout() {
    setcookie('logged_in', "", time() - 86400, "/");
    setcookie('login_token', "", time() - 86400, "/");
    NaitsabesForums::redirect_to('index.php');
  }
  public static function redirect_to($url, $dur = 1000) {
    echo "<script>
      setTimeout(function () {
        window.location = '$url';
      }, $dur);
    </script>";
  }
  public static function establish_connection() {
    $GLOBALS['conn'] = new mysqli(NaitsabesForums::DB_SERVER_NAME, NaitsabesForums::DB_USERNAME, NaitsabesForums::DB_PASSWORD, NaitsabesForums::DB_NAME);
    if ($GLOBALS['conn']->connect_error) {
      echo "<span style='color:red'>" . strtoupper("<strong style='color:red'>Database Connection Failed</strong>: " . $GLOBALS['conn']->connect_error) . "</span>";
    }
  }
}
NaitsabesForums::establish_connection();
?>
