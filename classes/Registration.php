<?php

require_once("Connection.php");

require_once("Country.php");

require_once("MailMan.php");

class Register extends Connection {

    private $conn;

    private $errors;

	function __construct() {
		$this -> conn = $this -> getConnection();
        $this -> errors = array();
	}

    public function register($user) {
        $this -> validateUser($user);
        if(count($this -> errors) == 0) {
            $key = $this -> _generateKey();
            try {
                $country = new Country();
                $countryId =  $country -> getCountryId($user["country"]);

                // i know i could have set up an insert function and passed in the array but i say NO!

                $stmt = $this -> conn -> prepare("INSERT INTO wor_users
                    (
                        user_firstname,
                        user_lastname,
                        user_email,
                        user_billing_country_id,
                        user_billing_zip,
                        user_billing_address,
                        user_phone,
                        user_billing_town,
                        user_register_date

                    ) VALUES
                    (
                        :user_firstname,
                        :user_lastname,
                        :user_email,
                        :user_billing_country_id,
                        :user_billing_zip,
                        :user_billing_address,
                        :user_phone,
                        :user_billing_town,
                        :user_register_date
                    )");
                $stmt -> execute([
                    "user_firstname" => $user["firstname"],
                    "user_lastname" => $user["lastname"],
                    "user_email" => $user["email"],
                    "user_billing_country_id" => $countryId["country_id"],
                    "user_billing_zip" => $user["zip"],
                    "user_billing_address" => $user["address"],
                    "user_phone" => $user["phone"],
                    "user_billing_town" => $user["town"],
                    "user_register_date" => date("Y-m-d")
                ]);
                if($stmt -> rowCount() == 1) {
                    //creating passport
                    $query = "";
                    $query .="INSERT INTO wor_users_login(user_login_email, user_login_hash, user_confirmation_key, user_id) ";
                    $query .="VALUES(:user_login_email,:user_login_hash,:user_confirmation_key,:user_id)";
                    $stmt = $this -> conn -> prepare($query);
                    $stmt -> execute([
                        "user_login_email" => $user["email"],
                        "user_login_hash" => $this -> hashPassword($user["password"]),
                        "user_confirmation_key" => $key,
                        "user_id" => $this -> getUserId($user["email"])
                    ]);
                    if($stmt -> rowCount() == 1) {
                        $mailMan = new MailMan();
                        return $mailMan -> sendConfirmationEmail($user["email"],$key);
                    } else {
                        return false;
                    }
                }
            } catch (PDOException $e) {
    			$error = new ErrorMaster();
    			$error -> reportError($e);
    		}
        }
    }

    private function validateUser($user) {
        $emailPattern = "/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
        $passwordPattern = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/";
        if(empty($user["email"])) {
            $this -> errors["register"]["email"] = "Please provide an email address";
        } elseif(!preg_match($emailPattern, $user["email"])) {
            $this -> errors["register"]["email"] = "Incorrect email format";
        } elseif ($this -> exists($user["email"])) {
            $this -> errors["register"]["email"] = "User already existst";
        }
        if(empty($user["firstname"])) {
            $this -> errors["register"]["firstname"] = "Firstname Missing";
        }
        if(empty($user["zip"])) {
            $this -> errors["register"]["zip"] = "Please provide a valid zip or postal code";
        }
        if(empty($user["lastname"])) {
            $this -> errors["register"]["lastname"] = "Lastname Missing";
        }
        if(empty($user["password"])) {
            $this -> errors["register"]["password"] = "Password Missing";
        }
        if(empty($user["address"])) {
            $this -> errors["register"]["address"] = "Please provide a billing address";
        }
        if(empty($user["country"])) {
            $this -> errors["register"]["country"] = "Please enter your country's name";
        } elseif(!empty($user["country"])) {
            $country = new Country();
            if($country -> countryExists($user["country"]) == false) {
                $this -> errors["register"]["country"] = "Please provide a valid country";
            }
        }
        if(empty($user["town"])) {
            $this -> errors["register"]["town"] = "Please provide a valid town";
        }
        if(empty($user["phone"]) || !is_numeric($user["phone"])) {
            $this -> errors["register"]["phone"] = "Please provide a valid phone number";
        }
        if(empty($user["passwordRepeat"])) {
            $this -> errors["register"]["passwordRepeat"] = "Please repeat the password";
        }
        if(!empty($user["password"]) && !empty($user["passwordRepeat"])) {
            if($user["password"] != $user["passwordRepeat"]) {
                $this -> errors["register"]["passwordmatch"] = "Passwords do not match";
            } elseif (!preg_match($passwordPattern, $user["password"])) {
                $this -> errors["register"]["password"] = "Password not strong enough, Must be Minimum 8 characters at least 1 Alphabet, 1 Number and 1 Special Character";

            }
        }
    }

    public function getErrors() {
        return $this -> errors;
    }

    private function exists($email) {
        try {

            $stmt = $this -> conn -> prepare("SELECT * FROM wor_users WHERE user_email = :email");
            $stmt -> execute(["email" => $email]);
            if ($stmt -> rowCount() > 0 ) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            $error = new ErrorMaster();
            $error -> reportError($e);
        }
    }

    private function _generateKey() {
        return md5(time() . PASSWORD_SALT . time() .uniqid());
    }

    private function hashPassword($password) {
        $salt = "$2a$" . PASSWORD_BCRYPT_COST . "$" . PASSWORD_SALT;
        $newPassword = crypt($password, $salt);
        return $newPassword;
    }

    private function getUserId($email) {
        try {
            $stmt = $this -> conn -> prepare("SELECT user_id FROM wor_users WHERE user_email = :email");
            $stmt -> execute(["email" => $email]);
            $result = $stmt -> fetch();
            return $result["user_id"];
        } catch (PDOException $e) {
            $error = new ErrorMaster();
            $error -> reportError($e);
        }
    }

    public function confirmUserEmail($key) {
        //edit this to check if user already is confirmed;
        $message = array();
        try {
            $stmt = $this -> conn -> prepare("SELECT user_confirmation_key FROM wor_users_login WHERE user_confirmation_key = :user_confirmation_key AND user_login_email_confirm = :user_login_email_confirm");
            $stmt -> execute(["user_confirmation_key" => $key, "user_login_email_confirm" => 0]);
            if($stmt -> rowCount() == 1) {
                $stmt = $this -> conn -> prepare("UPDATE wor_users_login SET user_login_email_confirm = 1 WHERE user_confirmation_key = :user_confirmation_key");
                $stmt -> execute(["user_confirmation_key" => $key]);
                if($stmt -> rowCount() == 1) {
                    $message["msg"] = "Email confirmation successful, you may now login";
                } else {
                    $message["msg"] = "Ops, something went wrong. Please try again";
                }
            } else {
                $message["msg"] = "Sorry, confirmation link no longer works";
            }
            return $message;
        } catch (PDOException $e) {
            $error = new ErrorMaster();
            $error -> reportError($e);
        }
    }

}
?>
